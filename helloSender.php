<?php

require_once __DIR__ . '/vendor/autoload.php';
 
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
 
/**
 * Inicia a conexão
 */
$connection = new AMQPStreamConnection(
  
  'localhost',  #host - host name where the RabbitMQ server is runing
  5672,         #port - port number of the service, 5672 is the default
  'guest',      #user - username to connect to server
  'guest'       #password

);

$channel = $connection->channel();
 
/**
 * Declara qual a fila que será usada
 */
$channel->queue_declare(

  'hello',  #queue name - Queue names may be up to 255 bytes of UTF-8 characters
  false,    #passive - can use this to check whether an exchange exists without modifying the server state
  false,    #durable - make sure that RabbitMQ will never lose our queue if a crash occurs - the queue will survive a broker restart
  false,    #exclusive - used by only one connection and the queue will be deleted when that connection closes
  false     #autodelete - queue is deleted when last consumer unsubscribes

);
 
/**
 * Cria a nova mensagem
 */
$msg = new AMQPMessage('Ola mundo!');
 
/**
 * Envia para a fila
 */
$channel->basic_publish(
  
  $msg,     #message
  '',       #exchange(troca)
  'hello'   #routing key

);
 
/**
 * Encerra conexão
 */
$channel->close();
$connection->close();