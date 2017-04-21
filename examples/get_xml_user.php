<?php
require_once 'TwitterManager.php';
require_once '../twitter_classes/TwitterUser.php';
require_once 'Utils.php';
session_start();
//error_reporting(0);

//$xml=new SimpleXMLElement('<data/>');

//function arrayToXml($array,&$axml)
//{
//    foreach ($array as $key => $value)
//        if(is_array($value) || is_object($value))
//            arrayToXml($value, $axml->addChild($key));
//        else $axml->addChild($key, htmlspecialchars($value));
//}


function arrayToXml($array,$_xml)
{
    foreach ($array as $key => $value)
        if(is_array($value) || is_object($value))
            $_xml = arrayToXml($value, $_xml->addChild($key));
        else $_xml->addChild($key, htmlspecialchars($value));
        return $_xml;
}

if (isset($_SESSION["current_User"])&&isset($_SESSION["current_users_Manager"]))
{
//    require_once 'XML_Serializer.php';

//
    $json = $_SESSION["current_users_Manager"]->user_data();


    $xml=new SimpleXMLElement('<data/>');
    arrayToXml(json_decode($json), $xml);
    $rezult = html_entity_decode($xml->asXML(),ENT_QUOTES, 'utf-8');



    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-disposition: attachment; filename=bullshit.xml');
    header('Content-Length: '.strlen($rezult));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    header('Pragma: public');

    echo $rezult;
    exit;
}
else echo "FUCK YOU";
