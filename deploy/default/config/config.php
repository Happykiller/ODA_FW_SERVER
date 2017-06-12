<?php
$config = \Oda\SimpleObject\OdaConfig::getInstance();
$config->urlServer = "http://localhost/server/";
$config->resourcesPath = "resources/";

//for bd engine
$config->BD_ENGINE->base = 'base';
$config->BD_ENGINE->user = 'user';
$config->BD_ENGINE->mdp = 'pass';

//For web socket
$config->websocket->host = "localhost";
$config->websocket->port = 4242;
$config->websocket->instanceName = "app";