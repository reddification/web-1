<?php
require_once('Common_Functions.php');
session_start();
$Utils = new Utils;
 
    if(isset($_SESSION["access_token"]) || isset($_SESSION["access_token_secret"]))//ПОТОМ СЕССИЮ НА ПОСТ ЗАМЕНИТЬ И ГДЕ ЕЩЕ НУЖНО ПОМЕНЯТЬ, КОГДА ПРИЛОЖЕНИЕ БУДЕТ
    {
        $response = null;
        
        switch('User_Retweet, User_Favorites, User_Mentions') //$POST['func_request']
        {
            case 'User_Retweet, User_Favorites, User_Mentions':
            {
                $CONSUMER = Array("KEY" => "", 
                "SECRET_KEY" => "");
                  //error_log("[info function]", 3, "my-errors.log");
                $result = $Utils->GET_API_TWITTER("URL_USER_SHOW", false, $CONSUMER, true);
                print_r($result);
            }
            break;
            
            case 'User_New_Tweet':
            {
                
            }
            break;
            
            default: die('Требуемой функции запроса не существует. [Info_Functions]');
        }
        
        if(isset($response)) 
        
            switch($POST['func_response'])
            {
                default: die('Требуемой функции ответа не существует. [Info_Functions]');
            }
            
        else
            echo 'Действие [] успешно завершено.'; //echo 'Действие [' .$POST['func_request']. '] успешно завершено.';
    }
    else
        die('Acess_Token отсутствует. [Info_Functions]');

?>
