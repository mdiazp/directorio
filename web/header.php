<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Directorio - UPR </title>
    <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css">
    <link href="/fonts/font-awesome.css" rel="stylesheet" type="text/css">
    <link href="/css/directorio.css" rel="stylesheet" type="text/css">
    <link href="/css/login.css" rel="stylesheet" type="text/css">
    <link rel="icon" type="image/x-icon" href="/img/directorio.svg" />
    <link rel="shortcut icon" type="image/x-icon" href="/img/directorio.svg" />
</head>
<body>

<div id="wrap">
        <!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top navbarstyle">
        <div class="container">
            <div class="navbar-header text-center">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">
                    <img width="40px" height="25px" src='/img/directorio.svg'> </img>
                </a>
            </div>
            <div class="collapse navbar-collapse border-bottom menu-style">
                <ul class="nav navbar-nav">
                    <li class="">
                        <a target="_blank" href="http://intranet.upr.edu.cu">Intranet</a>
                    </li>
                    <li class="">
                        <a target="_blank" href="http://correo.upr.edu.cu">Correo</a>
                    </li>
                    <li class="">
                        <a target="_blank" href="http://telefonos.upr.edu.cu">Tel&eacute;fonos</a>
                    </li>
                    <li class="">
                        <a target="_blank" href="http://elnodo.upr.edu.cu">DI</a>
                    </li>
                </ul>
                <div class="pull-right">
                    <ul class="nav navbar-nav">
                        <li class="pull-right">
                            <a href="/login">Login</a>
                        </li>
                    </ul>
                </div>
            </div><!--/.nav-collapse -->
        </div>
    </div>