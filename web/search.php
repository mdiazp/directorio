<?php

require "../config.php";
require "../LdapHandler.php";
require "../LdapFilterHandler.php";
require "../ad_description.php";

$rexpr_types = 4;

$op = 0;
$cookie_page = '';
$search_state = 'END';
$search_display_name = '';

if( isset( $_REQUEST['ldap_consulta'] ) ){
    $ldap_consulta = $_REQUEST['ldap_consulta'];

    if( isset($ldap_consulta['search_display_name']) ){
        $search_display_name = $ldap_consulta['search_display_name'];
    }
    if( isset($ldap_consulta['search_state']) ){
        $search_state = $ldap_consulta['search_state'];
    }
    
    if( isset($ldap_consulta['cookie_page']) ){
        $cookie_page = $ldap_consulta['cookie_page'];
    }
    if( isset($ldap_consulta['op']) ){
        $op = $ldap_consulta['op'];
    }
}

$result = array();

$cookie_page = base64_decode( json_decode($cookie_page) );

$ldap = new LdapHandler( $ldap_server , $ldap_user , $ldap_pass , $ldap_dn , $ldap_usr_dom , $ldap_host );
$ldap->connect_and_bind();

$count = 0;
$result = array();

$filterHandler = new LdapFilterHandler();
$filter = '';

for( ; $search_state != $filterHandler->_FINAL_SEARCH_STATE() && $count < $page_size;  ){
    
    $filter = $filterHandler->GetFilter( $search_display_name , $search_state );

    $tmp = $ldap->buscar_pagina( $filter, $attributes, $page_size - $count, $cookie_page );

    
    $tmp_count = $tmp['count'];
    for( $j = 0; $j < $tmp_count; $j++ ){
        
        if( !isset( $tmp[$j]['physicaldeliveryofficename'] ) ){
            $tmp[$j]['physicaldeliveryofficename'] = array( 0 => GetDescription( $tmp[$j]['dn'] ) );
        }
        
        $result[$count++] = $tmp[$j];
    }

    if( $count == $page_size ){
        if( $cookie_page == '' || $cookie_page == null ){
            $search_state = $filterHandler->GetNextSearchState( $search_state );
            $cookie_page == '';
        }
        break;
    }

    $search_state = $filterHandler->GetNextSearchState( $search_state );
    $cookie_page = '';
}

$ldap->close();

$result['more_results'] = ($filterHandler->_FINAL_SEARCH_STATE() == $search_state ? 'No' : 'Yes');
$result['search_state'] = $search_state;
$result['cookie_page'] = json_encode( base64_encode( $cookie_page ) );  
$result['count'] = $count;    


$response = array();
if (!$result) {
    $response = array(
        'status' => false,
        'message' => 'An error occured...',
        'op' => $op
    );
}else {
    $response = array(
        'status' => true,
        'message' => 'Success',
        'data' => $result,
        'op' => $op,

        //'filter' => $filter
    );
}

//header("Access-Control-Allow-Origin: *");
header('Content-type: application/json');
echo json_encode($response);