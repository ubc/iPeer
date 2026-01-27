<?php
App::import('AuthModule', 'DefaultModule', true, __DIR__, 'default_module.php');
/**
 * LdapModule The LDAP authentication module. It authenticate against a LDAP
 * server.
 *
 * @uses      AuthModule
 * @package   Plugins.Guard
 * @author    Compass <pan.luo@ubc.ca>
 * @copyright 2012 CTLT
 * @license   PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class LdapModule extends AuthModule
{
    /**
     * name the name of the authentication module
     *
     * @var string
     * @access public
     */
    public $name = 'Ldap';

    /**
     * hasLoginForm this module uses internal login page
     *
     * @var mixed
     * @access public
     */
    public $hasLoginForm = true;

    /**
     * authenticate authenticate the user and generate the user session
     *
     * @param mixed $username
     *
     * @access public
     * @return void
     */
    function authenticate($username = null)
    {
        CakeLog::write('debug', 'Using LDAP Authentication module');
        $loggedIn = false;

        $ds = ldap_connect($this->host, $this->port);

        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            ldap_set_option($ds, LDAP_OPT_NETWORK_TIMEOUT, 3);
        }

        try {
            ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3);
            if (!(@ldap_bind($ds, $this->serviceUsername, $this->servicePassword))) {
                $this->guard->error(sprintf('Could not connect to LDAP server: %s with port %d.', $this->host, $this->port));
                throw new Exception(sprintf('Could not connect to LDAP server: %s with port %d.', $this->host, $this->port));
            }

            // search ldap
            if (!($result = ldap_search($ds, $this->baseDn, $this->usernameField.'='.$this->data[$this->guard->fields['username']]))) {
                $this->guard->error(sprintf('Unable to perform LDAP seach with base DN %s and search %s.',
                    $this->baseDn, $this->usernameField.'='.$this->data[$this->guard->fields['username']]));
                throw new Exception(sprintf('Unable to perform LDAP seach with base DN %s and search %s.',
                    $this->baseDn, $this->usernameField.'='.$this->data[$this->guard->fields['username']]));
            }

            $info = ldap_get_entries($ds, $result);

            if (0 != $info['count']) {
                if (@ldap_bind($ds, $info[0]['dn'], $this->data[$this->guard->fields['password']])) {
                    // we need to get attributes
                    if (!empty($this->attributeMap)) {
                        $missingAttrs = array();

                        // check if we already got all attributes from first search
                        foreach ($this->attributeMap as $key => $attr) {
                            // convert to lower case as it is what returned from ldap
                            $attr = strtolower($attr);
                            if (array_key_exists($attr, $info[0]) && $info[0][$attr]['count'] > 0) {
                                if ($info[0][$attr]['count'] == 1) {
                                    $this->data[$key] = $info[0][$attr][0];
                                } else {
                                    $this->data[$key] = $info[0][$attr];
                                    // remove useless 'count' attr from ldap
                                    unset($this->data[$key]['count']);
                                }
                            } else {
                                $missingAttrs[$key] = $attr;
                            }
                        }

                        // if we still missing attributes, try search again with attribute filter
                        if (!empty($missingAttrs)) {
                            // construct filter
                            $filters = array();
                            $entry = ldap_first_entry($ds, $result);
                            // handle the filters that passed by environment vars
                            if (!is_array($this->attributeSearchFilters)) {
                                $this->attributeSearchFilters = explode(',', $this->attributeSearchFilters);

                            }
                            foreach ($this->attributeSearchFilters as $filter) {
                                $values = ldap_get_values($ds, $entry, $filter);
                                $filters[] = $filter.'='.$values[0];
                            }

                            // do the search
                            if (!($result = ldap_search($ds, $info[0]['dn'], implode(',', $filters), array_values($this->attributeMap)))) {
                                $this->guard->error(sprintf('Unable to perform LDAP seach with base DN %s and filter %s.',
                                    $info[0]['dn'], implode(',', $filters)));
                                throw new Exception(sprintf('Unable to perform LDAP seach with base DN %s and filter %s.',
                                    $info[0]['dn'], implode(',', $filters)));
                            }
                            $entry = ldap_first_entry($ds, $result);
                            foreach ($this->attributeMap as $key => $attribute) {
                                $values = ldap_get_values($ds, $entry, $attribute);
                                $this->data[$key] = $values[0];
                            }
                        }
                    }
                    $loggedIn = true;
                }
            }

        } catch (Exception $e) {
            CakeLog::write('error', $e->getMessage());
            CakeLog::write('debug', $e->getMessage());
        }

        ldap_close($ds);

        return $loggedIn;
    }
}
