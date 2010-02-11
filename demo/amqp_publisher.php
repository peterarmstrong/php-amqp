#!/usr/bin/env php
<?php
/**
 * Sends a message to a queue
 *
 * @author Sean Murphy<sean@iamseanmurphy.com>
 */

require_once('../amqp.inc');

$HOST = 'localhost';
$PORT = 5672;
$USER = 'guest';
$PASS = 'guest';
$VHOST = '/';
$EXCHANGE = 'topic_exchange';
$QUEUE = 'msgs';
$KEY = 'random.one';

$conn = new AMQPConnection($HOST, $PORT, $USER, $PASS);
$ch = $conn->channel();
$ch->access_request($VHOST, false, false, true, true);

// $msg_body = implode(' ', array_slice($argv, 1));
// Test round-robin by starting up a few clients and running this:
for ($n = 1; $n < 100; $n++) {
  $msg_body = "msg {$n}";
  $msg = new AMQPMessage($msg_body, array('content_type' => 'text/plain'));
  $ch->basic_publish($msg, $EXCHANGE, $KEY);
}

$ch->close();
$conn->close();
?>
