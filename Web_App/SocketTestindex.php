
<?php
error_reporting(E_ALL);

$min = 30;
$max = 90;

echo "<h2>TCP/IP Connection</h2>\n";

/* Get the port for the WWW service. */
$service_port = 8080;

/* Get the IP address for the target host. */
$address = '192.168.1.82';

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) 
{
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
} 
else 
{
    echo "Socket Created.\n";
}

echo "\nAttempting to connect to '$address' on port '$service_port'...";
$result = socket_connect($socket, $address, $service_port);

if ($result === false) 
{
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
} 
else 
{
    echo "Connected to Socket.\n";
}

$out = rand(int $min, int $max);

echo "\nSending HTTP HEAD request...";
socket_write($socket, $out, strlen($out));
echo "OK.\n";

echo "\nClosing socket...";
socket_close($socket);
echo "OK.\n\n";
?>
