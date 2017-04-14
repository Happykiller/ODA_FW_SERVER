<?php

namespace Oda;

require '../../../../../../header.php';
require '../../../../../../vendor/autoload.php';
require '../../../../../../config/config.php';

use cebe\markdown\GithubMarkdown;
use Slim\Slim;
use \stdClass, \Oda\SimpleObject\OdaPrepareInterface, \Oda\SimpleObject\OdaPrepareReqSql, \Oda\OdaLibBd, \Oda\InterfaceRest\UserInterface, \Oda\InterfaceRest\SessionInterface;

$slim = new Slim();
//--------------------------------------------------------------------------

$slim->notFound(function () {
    $params = new OdaPrepareInterface();
    $INTERFACE = new OdaRestInterface($params);
    $INTERFACE->dieInError('not found');
});

$slim->get('/', function () {
    $markdown = file_get_contents('./doc.markdown', true);
    $parser = new GithubMarkdown();
    echo $parser->parse($markdown);
});

//----------- USER -------------------------------

$slim->put('/user/pwd/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->arrayInput = array("userCode","pwd","email");
    $INTERFACE = new UserInterface($params);
    $INTERFACE->resetPwd();
});

//----------- SESSION -------------------------------

$slim->get('/session/:key', function ($key) use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $INTERFACE = new SessionInterface($params);
    $INTERFACE->getBykey($key);
});

//----------- AVATAR -------------------------------

$slim->get('/avatar/:userCode', function ($userCode) use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $INTERFACE = new AvatarInterface($params);
    $INTERFACE->getAvatar($userCode);
});

//------------------------------------------

$slim->run();