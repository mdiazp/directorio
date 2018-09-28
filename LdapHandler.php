<?php

class LdapHandler {
    private $ldapconn;
    private $ldap_error;

    function __construct( $ldap_server , $ldap_user , $ldap_pass , $ldap_dn , $ldap_usr_dom , $ldap_host ){
        $this->ldap_server = $ldap_server;
        $this->ldap_user = $ldap_user;
        $this->ldap_pass = $ldap_pass;

        $this->ldap_dn = $ldap_dn;
        $this->ldap_usr_dom = $ldap_usr_dom;
        $this->ldap_host = $ldap_host;
    }

    function set_limits_and_errors(){
        set_time_limit(30);
        error_reporting(E_ALL);
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors',1);
    }

    function connect_and_bind(){
        $this->set_limits_and_errors();

        $this->ldapconn = ldap_connect($this->ldap_host);
        
        ldap_set_option ($this->ldapconn, LDAP_OPT_REFERRALS, 0) or die("Unable to set LDAP opt referrals");
        ldap_set_option($this->ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version'); 

        $ldapbind = ldap_bind($this->ldapconn, $this->ldap_user. $this->ldap_usr_dom, $this->ldap_pass) 
                or die ("Error trying to bind: ".ldap_error($this->ldapconn));

        if( !$ldapbind ){
            return false;
        }

        return true;
    }

    function setUser( $dn , $attributes ){
        // add data to directory
        return ldap_mod_add($this->ldapconn, $dn, $attributes);
    }

    function close(){
        ldap_close($this->ldapconn);
    }

    function buscar( $filter = "(sn=*)", $attrib = array() ){
        $result = ldap_search($this->ldapconn,$this->ldap_dn,$filter,$attrib) 
                        or die ("Error in search query: ".ldap_error($this->ldap_error));
        $data = ldap_get_entries($this->ldapconn, $result);

        return $data;
    }

    function buscar_by_samaccountname( $samaccountname ){
        $filter = "(&(sn=*)(samaccountname=" . $samaccountname . "))";
        $attributes = array('displayname','mail','samaccountname','dn');
        //$attrib = array('samaccountname', 'mail','displayname', 'telephonenumber', 'physicaldeliveryofficename', 'streetaddress','employeenumber','employeeid','memberof');
        $attrib = array();

        $result = ldap_search($this->ldapconn,$this->ldap_dn,$filter,$attrib) 
                        or die ("Error in search query: ".ldap_error($this->ldap_error));
        $data = ldap_get_entries($this->ldapconn, $result);

        return $data;
    }

    function buscar_pagina( $filter = "(sn=*)", $attrib = array(), $page_size=10, &$cookie_page ){
        ldap_control_paged_result($this->ldapconn, $page_size, false, $cookie_page);
        $result = ldap_search($this->ldapconn, $this->ldap_dn, 
                              $filter, 
                              $attrib);
        $data = ldap_get_entries($this->ldapconn, $result);
        ldap_control_paged_result_response($this->ldapconn, $result, $cookie_page);
        return $data;
    }
}

?>
