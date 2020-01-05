<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$host = 'localhost';
$porta = 5672;
$usuario = 'guest';
$senha = 'guest';
$connection = new AMQPStreamConnection($host, $porta, $usuario, $senha);
$channel = $connection->channel();

$channel->queue_declare('minha_fila');

$callback = function ($msg) {
    echo "Mensagem recebida '" . $msg->body . "'\n";
};

$queue = 'minha_fila';
$consumer_tag = '';
$no_local = false;
$no_ack = true;
$exclusive = false;
$nowait = false;
$channel->basic_consume($queue, $consumer_tag, $no_local, $no_ack, $exclusive, $nowait, $callback);
while ($channel->is_consuming()) {
    $channel->wait();
};

$channel->close();
$connection->close();

?>