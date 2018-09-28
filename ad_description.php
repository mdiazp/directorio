<?php

function GetDescription( $dn ){
    $dn = explode( ',' , $dn );
    $ou = '';
    $sz = count( $dn );

    $ous = array();
    $sz2 = 0;
    $ou2 = '';
    //echo 'sz = ' . $sz;
    $dic2 = array();
    for( $j = 0; $j < $sz; $j++ ){
        //echo $kk . ' --- ' . strlen( $kk ) . '<br/>';

        if( strlen( $dn[$j] ) >= 2 && $dn[$j][0] == 'O' && $dn[$j][1] == 'U' ){
            if( !isset( $dic2[ $dn[$j] ] ) ){
                $dic2[ $dn[$j] ] = 'true';
                $ou .= ( $ou == '' ? '' : ',') . $dn[$j];
                if( $dn[$j] != 'OU=_Usuarios' && 
                    $dn[$j] != 'OU=Trabajadores' && 
                    $dn[$j] != 'OU=Estudiantes' &&
                    $dn[$j] != 'OU=VICERRECTOR PRIMERO' && 
                    $dn[$j] != 'OU=Facultades' && 
                    $dn[$j] != 'OU=Areas Centrales' &&
                    $dn[$j] != 'OU=Gestion' &&
                    $dn[$j] != 'OU=DIRECTOR GENERAL 1' &&
                    $dn[$j] != 'OU=DIRECTOR GENERAL 2' &&
                    $dn[$j] != 'OU=RECTORIA' &&
                    $dn[$j] != 'OU=VICERRECTOR 1' &&
                    $dn[$j] != 'OU=VICERRECTOR 2' &&
                    $dn[$j] != 'OU=VICERRECTOR PRIMERO' ){
                    $ou2 .= ( $ou2 == '' ? '' : ', ') . substr( $dn[$j] , 3 );
                }
                $ous[$sz2++] = $dn[$j];
            }
        }
    }

    return $ou2;
}

?>