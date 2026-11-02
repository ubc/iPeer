<?php
App::import('Core', 'CakeSession');

/**
 * Tests for the database session storage (Session.save = 'database'),
 * which production uses. PHP calls these CakeSession callbacks through
 * the save handler that CakeSession::__initSession() registers:
 * open/read at session start, write/close at the end of the request,
 * destroy on logout (session_destroy, session_regenerate_id(true)) and
 * gc with session.gc_maxlifetime.
 *
 * The test runner has already sent output, so PHP won't start a real
 * session here; the tests call the callbacks in the order PHP does.
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

    function testGcWithMaxLifetimeComparesItAsATimestamp()
    {
        // Known bug: PHP passes session.gc_maxlifetime (e.g. 1440) here, but __gc()
        // treats it as an absolute timestamp, so PHP-triggered cleanup never removes
        // expired sessions. Update this test when the gc fix lands.
        $this->insert('ancient', 1000);
        $this->insert('expired', time() - 10);

        $this->assertTrue(CakeSession::__gc(1440));

        $this->assertFalse($this->row('ancient'));
        $this->assertTrue((bool)$this->row('expired'));
    }
}
