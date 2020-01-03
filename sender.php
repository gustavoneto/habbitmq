<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$host = 'localhost';
$porta = 5672;
$usuario = 'guest';
$senha = 'guest';
$connection = new AMQPStreamConnection($host, $porta, $usuario, $senha);
$channel = $connection->channel();

$channel->queue_declare('minha_fila');

$conteudo = 'primeira mensagem';
$msg = new AMQPMessage($conteudo);

$exchange = '';
$routingKey = 'minha_fila';
$channel->basic_publish($msg, $exchange, $routingKey);
echo "Mensagem enviada: '" . $conteudo . "'\n";

$channel->close();
$connection->close();

?>