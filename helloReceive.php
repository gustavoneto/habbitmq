<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Inicia a conexão
 */
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
 
/**
 * Declara qual a fila que será usada
 */
$channel->queue_declare(
    
    'hello',    #queue name - Queue names may be up to 255 bytes of UTF-8 characters
    false,      #passive - can use this to check whether an exchange exists without modifying the server state
    false,      #durable - make sure that RabbitMQ will never lose our queue if a crash occurs - the queue will survive a broker restart
    false,      #exclusive - used by only one connection and the queue will be deleted when that connection closes
    false       #autodelete - queue is deleted when last consumer unsubscribes

);
 
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
 
/**
 * Função que vai receber e tratar efetivamente a mensagem
 */
$callback = function($msg) {
  echo " [x] Received ", $msg->body, "\n";
};
 
/**
 * Adiciona esse "callback" para a fila 
 */
$channel->basic_consume(
    
    'hello',    #queue - has to be the same queue name we defined in the Sender
    '',         #consumer tag - an arbitrary name given to the consumer. If this field is empty the server will generate a unique tag
    false,      #no local - This is an obscure parameter, if activated, the server will not deliver its own messages
    true,       #no ack - will automatically acknowledge that the consumer received the message, so we do not have to manually do so
    false,      #exclusive - queues may only be accessed by the current connection
    false,      #no wait - If set, the server will not wait for the process in the consumer to complete
    $callback   #callback - can be a function name, an array containing the object and the method name, or a closure that will receive the queued message

);
 
/**
 * Mantem a função escutando a fila por tempo indeterminado, até que seja encerrada
 */
while(count($channel->callbacks)) {
    $channel->wait();
}
 
$channel->close();
$connection->close();