<?php

require_once 'TwitterManager.php';

session_start();

if (isset($_SESSION["current_users_Manager"]))
{

    $rezult = json_encode($_SESSION["current_users_Manager"]->user_data());

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-disposition: attachment; filename=bullshit.json');
    header('Content-Length: '.strlen($rezult));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    header('Pragma: public');

    echo $rezult;
    exit;
}
else echo "FUCK YOU";
