<?php

namespace Oda;

require '../../../../../../header.php';
require '../../../../../../vendor/autoload.php';
require '../../../../../../config/config.php';

use stdClass, 
    Slim\Slim,
    cebe\markdown\GithubMarkdown,
    Oda\SimpleObject\OdaPrepareInterface, 
    Oda\SimpleObject\OdaPrepareReqSql, 
    Oda\OdaLibBd, 
    Oda\InterfaceRest\UserInterface, 
    Oda\InterfaceRest\SessionInterface, 
    Oda\InterfaceRest\AvatarInterface,
    Oda\InterfaceRest\NavigationInterface,
    Oda\InterfaceRest\RankInterface,
    Oda\InterfaceRest\MessageInterface
;

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

//----------- AVATAR -------------------------------

$slim->get('/avatar/:userCode', function ($userCode) use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new AvatarInterface($params);
    $INTERFACE->getAvatar($userCode);
});

//----------- MESSAGE -------------------------------

$slim->post('/message/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->arrayInput = array("userId","message","level","expirationDate","rankId");
    $params->modePublic = false;
    $INTERFACE = new MessageInterface($params);
    $INTERFACE->create();
});

$slim->get('/message/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new MessageInterface($params);
    $INTERFACE->getAll();
});

$slim->get('/message/current', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new MessageInterface($params);
    $INTERFACE->getForCurrentUser();
});

$slim->put('/message/read/:messageId', function ($messageId) use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new MessageInterface($params);
    $INTERFACE->setReadForCurrentUser($messageId);
});

//----------- NAVIGATION -------------------------------

$slim->get('/navigation/page/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new NavigationInterface($params);
    $INTERFACE->getAllPage();
});

$slim->get('/navigation/rank/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new NavigationInterface($params);
    $INTERFACE->getAllRank();
});

$slim->get('/navigation/rights/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new NavigationInterface($params);
    $INTERFACE->getRights();
});

$slim->get('/navigation/right/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new NavigationInterface($params);
    $INTERFACE->getRight();
});

$slim->put('/navigation/right/:id', function ($id) use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->arrayInput = array("value");
    $params->modePublic = false;
    $INTERFACE = new NavigationInterface($params);
    $INTERFACE->updateRight($id);
});

//----------- RANK -------------------------------

$slim->get('/rank/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new RankInterface($params);
    $INTERFACE->getAll();
});

//----------- SESSION -------------------------------

$slim->get('/session/:key', function ($key) use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new SessionInterface($params);
    $INTERFACE->getBykey($key);
});

//----------- USER -------------------------------

$slim->post('/user/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->arrayInput = array("firstName","lastName","mail","password","userCode");
    $INTERFACE = new UserInterface($params);
    $INTERFACE->create();
});

$slim->get('/user/current', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new UserInterface($params);
    $INTERFACE->getCurrent();
});

$slim->get('/user/:userCode', function ($userCode) use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new UserInterface($params);
    $INTERFACE->getByCode($userCode);
});

$slim->put('/user/:userCode', function ($userCode) use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->arrayInput = array("mail","active","rankId","desc");
    $params->modePublic = false;
    $INTERFACE = new UserInterface($params);
    $INTERFACE->updateUser($userCode);
});

$slim->put('/user/pwd/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->arrayInput = array("userCode","pwd","email");
    $params->modePublic = false;
    $INTERFACE = new UserInterface($params);
    $INTERFACE->resetPwd();
});

//------------------------------------------

$slim->run();