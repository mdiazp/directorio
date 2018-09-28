<?php

require "../config.php";
require "../LdapHandler.php";

if( !isset( $_GET['username'] ) ) {
    http_response_code(404);
    echo "User not found.";
    die();
}

$username = $_GET['username'];

$ldap = new LdapHandler( $ldap_server , $ldap_user , $ldap_pass , $ldap_dn , $ldap_usr_dom , $ldap_host );
$ldap->connect_and_bind();

$result = $ldap->buscar_by_samaccountname($username);

//echo "<pre>";
//var_dump($result);
//echo "</pre>";

if (!$result || $result['count']==0) {
    http_response_code(404);
    echo "User not found.";
    die();
}

//header("Access-Control-Allow-Origin: *");
//header('Content-type: application/json');
//echo json_encode($result);


echo "--------------------------------------  AD Info ---------------------------------------<br/>";
$fields_to_show = array('samaccountname'=>'Usuario',
			'displayname'=>'Nombre',
			'employeeid'=>'Carnet de Identidad',
			'mail'=>'Correo',
			'telephonenumber'=>'Telefono',
			'physicaldeliveryofficename'=>'Oficina',
			'streetaddress'=>'Direccion Particular',
			'employeenumber'=>'Numero de empleado');

foreach ($fields_to_show as $key => $value) {
	if( isset( $result[0][$key] ) ) {
		echo $value . ":<br/>" . $result[0][$key][0] . "<br/><br/>";
	}
}

echo "Servicios:" . "<br/>";
$services = array(	'CN=UPR-Ras,OU=_Gestion,DC=upr,DC=edu,DC=cu'=>'Acceso Remoto',
			'CN=UPR-Correo-Internacional,OU=Correo,OU=Gestion,OU=_Usuarios,DC=upr,DC=edu,DC=cu'=>'Correo Internacional',
			'CN=UPR-Internet-Profes,OU=_Gestion,DC=upr,DC=edu,DC=cu'=>'Internet para profesores',
			'CN=UPR-Internet-Est,OU=_Gestion,DC=upr,DC=edu,DC=cu'=>'Internet para estudiantes',
			'CN=UPR-Internet-AlumnoAyudante,OU=_Gestion,DC=upr,DC=edu,DC=cu'=>'Internet para alumnos ayudantes',
			'CN=UPR-Jabber,OU=_Gestion,DC=upr,DC=edu,DC=cu' => 'Servicio de mensajeria Jabber' );

if( isset( $result[0]['memberof'] ) ){
	$sz = $result[0]['memberof']['count'];
	for( $i = 0; $i < $sz; $i++ ){
		if( isset( $services[$result[0]['memberof'][$i]] ) ){
			echo "- " . $services[$result[0]['memberof'][$i]] . "<br/>";
		}
	}
}

//echo'<pre>';
//    var_dump($result);
//echo '</pre>';

echo "<br/>--------------------------------------  SIGENU Info  ---------------------------------<br/><br/>";

if( isset( $result[0]['employeeid'][0] ) ){
	$ci = str_replace(' ', '', $result[0]['employeeid'][0]);
	$apisigenu_error = '';

	$service_url = 'http://apisigenu.upr.edu.cu/api/student?identification=eq.'.$ci.'&select=id_student';

	$curl = curl_init($service_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
		$info = curl_getinfo($curl);
		curl_close($curl);
		$apisigenu_error = 'error occured during curl exec. Additioanl info: ' . var_export($info);
	}
	curl_close($curl);

	if( $apisigenu_error === '' ){
		$decoded = json_decode($curl_response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			die('error occured: ' . $decoded->response->errormessage);
		}

		if( $decoded === NULL || !$decoded ){
			echo "No existe ningun registro.<br/>";
		}
		else{
			echo "id_student:<br/>" . $decoded[0]->id_student . "<br/>";
		}

//		echo'<pre>';
//			var_dump($decoded);
//		echo '</pre>';

	}
	else {
		echo "La conexion con apisigenu.upr.edu.cu fallo";
	}
}
else {
	echo "El carnet de identidad no existe en el AD";
}


echo "<br/>--------------------------------------  ASSETS Info  ---------------------------------<br/><br/>";

if( isset( $result[0]['employeeid'][0] ) ){
	$ci = str_replace(' ', '', $result[0]['employeeid'][0]);
	$apisigenu_error = '';

	$service_url = 'http://apiassets.upr.edu.cu/empleados_gras?_format=json&noCi=' . $ci;

	$curl = curl_init($service_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
		$info = curl_getinfo($curl);
		curl_close($curl);
		$apisigenu_error = 'error occured during curl exec. Additioanl info: ' . var_export($info);
	}
	curl_close($curl);

	if( $apisigenu_error === '' ){
		$decoded = json_decode($curl_response, true);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			die('error occured: ' . $decoded->response->errormessage);
		}

//		echo'<pre>';
//			var_dump($decoded);
//		echo '</pre>';


		if( $decoded === NULL || !$decoded || !isset($decoded["hydra:member"][0]) ){
			echo "No existe ningun registro.<br/>";
		}
		else{
			echo "Numero de expediente:<br/>" . $decoded["hydra:member"][0]["idExpediente"] . "<br/><br/>";
			echo "Telefono Particular:<br/>" . $decoded["hydra:member"][0]["telefonoParticular"] . "<br/><br/>";
		}

	}
	else {
		echo "La conexion con apiassets.upr.edu.cu fallo";
	}
}
else {
	echo "El carnet de identidad no existe en el AD";
}
