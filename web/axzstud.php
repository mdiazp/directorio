<?php

if( !isset( $_GET['ci'] ) ) {
    http_response_code(404);
    echo "User not found.";
    die();
}

$ci = $_GET['ci'];


$apisigenu = 'http://apisigenu.upr.edu.cu/api/student?identification=eq.' . $ci . '&select=id_student%2C%20name%2C%20middle_name%2C%20last_name';

//Use file_get_contents to GET the URL in question.
$contents = file_get_contents($apisigenu);

//If $contents is not a boolean FALSE value.
if($contents !== false){
    //Print out the contents.
	echo'<pre>';
    		var_dump($contents);
	echo '</pre>';

}else {
	echo 'User not found';
}
