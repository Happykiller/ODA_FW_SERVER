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
    Oda\InterfaceRest\MessageInterface,
    Oda\InterfaceRest\SystemInterface
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

$slim->post('/session/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->arrayInput = array("userCode","password");
    $params->arrayInputOpt = array("sessionTimeOutMinute" => 720);
    $INTERFACE = new SessionInterface($params);
    $INTERFACE->create();
});

//TODO do not specify key, or do it public
$slim->get('/session/:key', function ($key) use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new SessionInterface($params);
    $INTERFACE->getBykey($key);
})->conditions(array('key' => '^(?!check$)'));

//TODO doublon with /session/:key ?
$slim->get('/session/check', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $params->arrayInput = array("code_user", "key");
    $INTERFACE = new SessionInterface($params);
    $INTERFACE->check();
});

//TODO do not specify key, or do it public
$slim->delete('/session/:key', function ($key) use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new SessionInterface($params);
    $INTERFACE->delete($key);
});

//----------- SYSTEM -------------------------------

$slim->post('/sys/page/trace', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->arrayInput = array("user","page","action");
    $INTERFACE = new SystemInterface($params);
    $INTERFACE->createPageTrace();
});

$slim->get('/sys/report/page/activity', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new SystemInterface($params);
    $INTERFACE->getReportPageActivity();
});

$slim->get('/sys/theme/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new SystemInterface($params);
    $INTERFACE->getAllTheme();
});

$slim->post('/sys/cleanDb/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $params->arrayInput = array("exec",);
    $INTERFACE = new SystemInterface($params);
    $INTERFACE->cleanDb();
});

$slim->get('/sys/report/interfaceMetric', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new SystemInterface($params);
    $INTERFACE->getReportInterfacMetric();
});

//----------- USER -------------------------------

$slim->get('/user/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new UserInterface($params);
    $INTERFACE->getAll();
});

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

$slim->put('/user/current', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->arrayInput = array("password","field","value");
    $params->modePublic = false;
    $INTERFACE = new UserInterface($params);
    $INTERFACE->updateField();
});

$slim->get('/user/:userCode', function ($userCode) use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->modePublic = false;
    $INTERFACE = new UserInterface($params);
    $INTERFACE->getByCode($userCode);
})->conditions(array('userCode' => '^(?!current$)'));

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

$slim->get('/user/mail/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $INTERFACE = new UserInterface($params);
    $INTERFACE->getAllMail();
});

$slim->get('/user/search/mail/', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $params->arrayInput = array("email");
    $INTERFACE = new UserInterface($params);
    $INTERFACE->getByMail();
});

$slim->get('/user/report/activity', function () use ($slim) {
    $params = new OdaPrepareInterface();
    $params->slim = $slim;
    $INTERFACE = new UserInterface($params);
    $params->modePublic = false;
    $INTERFACE->getActivity();
});

//------------------------------------------

$slim->run();