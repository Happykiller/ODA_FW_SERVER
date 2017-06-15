<?php
namespace Oda;

use \stdClass, 
    \SplObjectStorage,
    \Exception,
    \Oda\OdaLib,
    \Oda\SimpleObject\OdaConfig,
    \Oda\OdaLibInterface,
    Ratchet\MessageComponentInterface,
    Ratchet\ConnectionInterface
;

/**
 * Project class
 *
 * OdaWebsockets
 *
 * @author  Fabrice Rosito <rosito.fabrice@gmail.com>
 * @version 0.170609
 */
abstract class OdaWebsockets extends OdaLibInterface implements MessageComponentInterface {
    protected $clients;
    protected $debug = false;

    public function __construct() {
        $config = OdaConfig::getInstance();
        if($this->debug){
            OdaLib::traceLog("Websocket server start for: ws://".$config->websocket->host.':'.$config->websocket->port.'/'.$config->websocket->instanceName);
        }
        $this->clients = new SplObjectStorage;
    }

    // Function that has to be implemented in each child
    abstract public function onOpenPublic(ConnectionInterface $conn);
    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        if($this->debug){
            OdaLib::traceLog("New connection! ({$conn->resourceId})");
        }
        $this->onOpenPublic($conn);
    }

    // Function that has to be implemented in each child
    abstract public function onMessagePublic(ConnectionInterface $from, $msg);
    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        if($this->debug){
            OdaLib::traceLog(sprintf('Connection %d sending message "%s" to %d other connection%s', $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's'));
        }
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }

        $this->onMessagePublic($from, $msg);
    }

    // Function that has to be implemented in each child
    abstract public function onClosePublic(ConnectionInterface $conn);
    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->onClosePublic($conn);
        $this->clients->detach($conn);
        if($this->debug){
            OdaLib::traceLog("Connection {$conn->resourceId} has disconnected");
        }
    }

    // Function that has to be implemented in each child
    abstract public function onErrorPublic(ConnectionInterface $conn, Exception $e);
    public function onError(ConnectionInterface $conn, Exception $e) {
        if($this->debug){
            OdaLib::traceLog("An error has occurred: id={$conn->resourceId}, {$e->getMessage()}");
        }
        $this->onErrorPublic($conn, $e);
        $conn->close();
    }
}