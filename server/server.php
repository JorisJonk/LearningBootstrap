<?php
//namespace MyApp;
require('vendor/autoload.php');
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "forumthread";

try {
    $dbconn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // set the PDO error mode to exception
    $dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
  echo "error caught";
}


class Chat implements MessageComponentInterface {
  protected $clients;
  protected $asoarrayphp;

  public function __construct() {
    $this->clients = new \SplObjectStorage;
    $this->idCounter = 0;
    $this->asoarrayphp = array();
    // todo: open mysql connection with pdo
  }

  public function onOpen(ConnectionInterface $conn) {
    // Store the new connection to send messages to later
    $this->clients->attach($conn);
    //todo: send client questioncount

    // todo: broadcast that client is connected

    echo "New connection! ({$conn->resourceId})\n";


    for ($i = 0; $i < sizeof($this->asoarrayphp); $i++) {
      $conn->send("question " . $this->asoarrayphp[$i]["sessionid"]. " " . $this->asoarrayphp[$i]["question"]);
      for ($j = 0; $j < sizeof($this->asoarrayphp[$i]["replies"]); $j++) {
        $conn->send("reply " . $i . " " . $this->asoarrayphp[$i]["replies"][$j]);
      }
    }
  }

  public function onMessage(ConnectionInterface $from, $msg) {
    $numRecv = count($this->clients) - 1;
    echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
      , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

    // todo: save msg in mysql db

    // Type of messages from client:
    // question [sessionid] [the question]
    //
    // reply [questionid] [the reply]
    //
    // Type of messages from server
    // question [sessionid] [the question]
    //
    // reply [questionid] [the reply]
    // sessionid mag geen spatiesomvatten

    // Interpret client message
      {
        $x = explode(" ", $msg, 3);
        if ($x[0] == "question") {
          $this->Broadcast($msg);
          $this->Showpostedquestion($from);

          $configarray = array();
          $configarray["question"] = $x[2];
          $configarray["sessionid"] = $x[1];
          $configarray["replies"] = array();
          array_push($this->asoarrayphp, $configarray);
        }
        if ($x[0] == "reply") {
          $x = explode(" ", $msg, 3);
          $id = intval($x[1]);

          // check if id is valid
          if ($id <= sizeof($this->asoarrayphp) - 1) {
            array_push($this->asoarrayphp[$id]["replies"], $x[2]);
            $this->Broadcast($msg);
          }
        }


        // todo: drop client
        }
      }


  protected function Broadcast($msg) {
    foreach ($this->clients as $client) {
      $client->send($msg);
    }
}
  protected function Showpostedquestion($from) {
    $from->send("showquestionposted");
  }



    //geef de verzender een extra bericht genaamd update

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
