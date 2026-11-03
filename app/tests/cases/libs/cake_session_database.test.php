<?php
App::import('Core', 'CakeSession');

/**
 * Stand-in session callbacks that record how they were called.
 */
class SessionCallbackRecorder
{
    public static $calls = array();

    public static function record($name, $args, $return)
    {
        self::$calls[] = array($name, $args);
        return $return;
    }

    public static function open() { return self::record('open', func_get_args(), true); }
    public static function close() { return self::record('close', func_get_args(), true); }
    public static function read() { return self::record('read', func_get_args(), 'stored data'); }
    public static function write() { return self::record('write', func_get_args(), false); }
    public static function destroy() { return self::record('destroy', func_get_args(), true); }
    public static function gc() { return self::record('gc', func_get_args(), null); }
}

/**
 * Tests for the database session storage (Session.save = 'database'),
 * which production uses via IPEER_SESSION_SAVE (core.php's 'php' default is
 * overridden there). PHP calls these CakeSession callbacks through
 * the save handler that CakeSession::__initSession() registers:
 * open/read at session start, write/close at the end of the request,
 * destroy on logout (session_destroy, session_regenerate_id(true)) and
 * gc with session.gc_maxlifetime.
 *
 * CakeSession::__initSession() returns early under the CLI, so no save
 * handler is registered and no real session starts here; the tests call
 * the callbacks directly, in the order PHP does.
 *
 * @package app.tests.cases.libs
 */
class CakeSessionDatabaseTestCase extends CakeTestCase
{
    public $fixtures = array('core.session');

    // what a logged-in user's session holds, in PHP's session encoding
    private $loginData = 'Auth|a:1:{s:4:"User";a:2:{s:2:"id";s:1:"1";s:8:"username";s:4:"root";}}';

    function startTest($method)
    {
        // __close() runs gc when mt_rand(1, 150) <= 3; with this seed the next
        // draws are 146 and 90, so it doesn't, and these tests stay deterministic
        mt_srand(1);
        // the callbacks use whatever model is registered as 'Session'
        ClassRegistry::removeObject('Session');
        $this->Session =& ClassRegistry::init(array(
            'class' => 'Session',
            'alias' => 'Session',
            'table' => 'sessions',
            'ds' => 'test_suite',
        ));
    }

    function endTest($method)
    {
        unset($this->Session);
        ClassRegistry::removeObject('Session');
        mt_srand();
    }

    private function row($id)
    {
        return $this->Session->find('first', array(
            'conditions' => array('Session.id' => $id),
        ));
    }

    private function insert($id, $expires)
    {
        $this->Session->create();
        $this->Session->save(array('Session' => array(
            'id' => $id, 'data' => 'x|s:1:"y";', 'expires' => $expires,
        )));
    }

    function testOpenAndClose()
    {
        $this->assertTrue(CakeSession::__open());
        $this->assertTrue(CakeSession::__close());
    }

    function testReadUnknownSessionIsEmpty()
    {
        $this->assertIdentical('', CakeSession::__read('no-such-session'));
    }

    function testLoginPersistsAcrossRequests()
    {
        $id = 'session-login';

        // request 1: new session, user logs in
        CakeSession::__open();
        $this->assertIdentical('', CakeSession::__read($id));
        $this->assertTrue(CakeSession::__write($id, $this->loginData));
        CakeSession::__close();

        $row = $this->row($id);
        $this->assertEqual($id, $row['Session']['id']);
        $this->assertTrue($row['Session']['expires'] > time());

        // request 2: same session id, still logged in
        CakeSession::__open();
        $this->assertIdentical($this->loginData, CakeSession::__read($id));
        CakeSession::__close();
    }

    function testWriteUpdatesExistingSession()
    {
        $id = 'session-update';
        $this->assertTrue(CakeSession::__write($id, 'a|s:1:"1";'));
        $this->assertTrue(CakeSession::__write($id, 'a|s:1:"2";'));

        $this->assertIdentical('a|s:1:"2";', CakeSession::__read($id));
        $this->assertEqual(1, $this->Session->find('count', array(
            'conditions' => array('Session.id' => $id),
        )));
    }

    function testWriteWithoutIdFails()
    {
        $this->assertFalse(CakeSession::__write('', $this->loginData));
    }

    function testLogoutDestroysSession()
    {
        $id = 'session-logout';
        CakeSession::__write($id, $this->loginData);

        $this->assertTrue(CakeSession::__destroy($id));

        $this->assertIdentical('', CakeSession::__read($id));
        $this->assertFalse($this->row($id));
    }

    function testDestroyUnknownSessionStillSucceeds()
    {
        $this->assertTrue(CakeSession::__destroy('no-such-session'));
    }

    function testGcRemovesExpiredSessions()
    {
        // __close() calls this (with no argument) on about 2% of requests
        $this->insert('expired', time() - 10);
        $this->insert('live', time() + 3600);

        $this->assertTrue(CakeSession::__gc());

        $this->assertFalse($this->row('expired'));
        $this->assertTrue((bool)$this->row('live'));
    }

    function testGcArgumentIsATimestamp()
    {
        // __gc($expires) deletes rows that expire before an absolute timestamp.
        // The save handler doesn't pass PHP's session.gc_maxlifetime (e.g. 1440)
        // here; see testDatabaseSaveHandlerLoginLogoutAndGc.
        $this->insert('ancient', 1000);
        $this->insert('expired', time() - 10);

        $this->assertTrue(CakeSession::__gc(1440));

        $this->assertFalse($this->row('ancient'));
        $this->assertTrue((bool)$this->row('expired'));
    }

    // -------------------------------------------------------------------------
    // CakeSessionSaveHandler, the object registered with
    // session_set_save_handler()
    // -------------------------------------------------------------------------

    function testSaveHandlerForwardsArgumentsAndResults()
    {
        $callbacks = array();
        foreach (array('open', 'close', 'read', 'write', 'destroy', 'gc') as $name) {
            $callbacks[$name] = array('SessionCallbackRecorder', $name);
        }
        $handler = new CakeSessionSaveHandler($callbacks);
        SessionCallbackRecorder::$calls = array();

        $this->assertIdentical(true, $handler->open('/save/path', 'IPEER'));
        $this->assertIdentical('stored data', $handler->read('sid'));
        $this->assertIdentical(false, $handler->write('sid', 'data'));
        $this->assertIdentical(true, $handler->destroy('sid'));
        $this->assertIdentical(null, $handler->gc(1440));
        $this->assertIdentical(true, $handler->close());

        $this->assertIdentical(array(
            array('open', array('/save/path', 'IPEER')),
            array('read', array('sid')),
            array('write', array('sid', 'data')),
            array('destroy', array('sid')),
            array('gc', array()),  // PHP's max lifetime isn't passed on
            array('close', array()),
        ), SessionCallbackRecorder::$calls);
    }

    function testSaveHandlerTypes()
    {
        $this->assertIsA(CakeSession::_saveHandler('database'), 'SessionHandlerInterface');
        $this->assertIsA(CakeSession::_saveHandler('cache'), 'SessionHandlerInterface');
        $this->assertNull(CakeSession::_saveHandler('php'));
        $this->assertNull(CakeSession::_saveHandler('cake'));
    }

    function testSaveHandlerCallbacks()
    {
        // the same callbacks the 6-argument session_set_save_handler() used
        $database = CakeSession::_saveHandler('database');
        $this->assertIdentical(array(
            'open' => array('CakeSession', '__open'),
            'close' => array('CakeSession', '__close'),
            'read' => array('CakeSession', '__read'),
            'write' => array('CakeSession', '__write'),
            'destroy' => array('CakeSession', '__destroy'),
            'gc' => array('CakeSession', '__gc'),
        ), $database->getCallbacks());

        $cache = CakeSession::_saveHandler('cache');
        $this->assertIdentical(array(
            'open' => array('CakeSession', '__open'),
            'close' => array('CakeSession', '__close'),
            'read' => array('Cache', 'read'),
            'write' => array('Cache', 'write'),
            'destroy' => array('Cache', 'delete'),
            'gc' => array('Cache', 'gc'),
        ), $cache->getCallbacks());
    }

    function testDatabaseSaveHandlerLoginLogoutAndGc()
    {
        $handler = CakeSession::_saveHandler('database');
        $id = 'handler-session';

        // request 1: new session, user logs in
        $this->assertTrue($handler->open('', 'IPEER'));
        $this->assertIdentical('', $handler->read($id));
        $this->assertTrue($handler->write($id, $this->loginData));
        $this->assertTrue($handler->close());

        // request 2: still logged in
        $this->assertTrue($handler->open('', 'IPEER'));
        $this->assertIdentical($this->loginData, $handler->read($id));

        // logout
        $this->assertTrue($handler->destroy($id));
        $this->assertIdentical('', $handler->read($id));
        $this->assertFalse($this->row($id));
        $this->assertTrue($handler->close());

        // PHP calls gc with session.gc_maxlifetime; every expired row goes
        $this->insert('ancient', 1000);
        $this->insert('expired', time() - 10);
        $this->insert('live', time() + 3600);
        $this->assertTrue($handler->gc(1440));
        $this->assertFalse($this->row('ancient'));
        $this->assertFalse($this->row('expired'));
        $this->assertTrue((bool)$this->row('live'));
    }
}
