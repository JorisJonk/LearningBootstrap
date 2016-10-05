<?php
//namespace MyApp;
require('vendor/autoload.php');
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class Chat implements MessageComponentInterface {
  protected $clients;
  protected $idCounter;

  public function __construct() {
    $this->clients = new \SplObjectStorage;
    $this->idCounter = 0;
    // todo: open mysql connection with pdo
  }

  public function onOpen(ConnectionInterface $conn) {
    // Store the new connection to send messages to later
    $this->clients->attach($conn);
    //todo: send client questioncount

    // todo: broadcast that client is connected

    echo "New connection! ({$conn->resourceId})\n";
  }

  public function onMessage(ConnectionInterface $from, $msg) {
    $numRecv = count($this->clients) - 1;
    echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
      , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

    // todo: save msg in mysql db

    // Type of messages from client:
    // question [the question]
    //
    // reply [id] [the reply]
    //
    // Type of messages from server
    // question [id] [the question]
    //
    // reply [id] [the reply]


    // Interpret client message
      {
        $x = explode(" ", $msg, 2);
        if ($x[0] == "question") {
          $needle = "question ";
          $replace = "";
          $one = 1;
          $question = str_replace($needle, $replace, $msg, $one);
          $serverMsg = "question " . $this->idCounter . " " . $question;
          $this->Broadcast($serverMsg);
          // increment idCounter
          $this->idCounter++;
        }
        else if ($x[0] == "reply") {
          $this->Broadcast($msg);
        }
        else {
          // todo: drop client
        }
      }
  }

  protected function Broadcast($msg) {
    echo "Broadcasting msg: $msg\n";
    foreach ($this->clients as $client) {
      $client->send($msg);
    }
  }

  public function onClose(ConnectionInterface $conn) {
    // The connection is closed, remove it, as we can no longer send it messages
    $this->clients->detach($conn);
    // todo: broadcast that client has left

    echo "Connection {$conn->resourceId} has disconnected\n";
  }

  public function onError(ConnectionInterface $conn, \Exception $e) {
    echo "An error has occurred: {$e->getMessage()}\n";

    $conn->close();
  }
}

$server = IoServer::factory(
  new HttpServer(
    new WsServer(
      new Chat()
    )
  ),
  8080
);
$server->run();
?>
