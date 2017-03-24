 <?php
 require_once('ouath TEST.php');
 require_once('Common_Functions.php');
 
 session_start();
 $conn = new OAuthTwitter();
 
    if (!empty($_GET['denied']))
            die('Пользователь отменил авторизацию.');
    
    elseif (empty($_GET['oauth_token']) || empty($_GET['oauth_verifier']))
           $conn->goToAuth("http://site.local/ouath_start_test.php","ВАШ КОНСЬЮМЕР КЕЙ","ВАШ СЕКРЕТ КОНСЬЮМЕР КЕЙ",false);
    
    else {
        if (!$conn->get_OAuthToken(trim($_GET['oauth_token']), trim($_GET['oauth_verifier']), "http://site.local/ouath_start_test.php","ВАШ КОНСЬЮМЕР КЕЙ","ВАШ СЕКРЕТ КОНСЬЮМЕР КЕЙ",false))
            die('Произошла ошибка в процессе получения разрешения доступа к Access_Token.');
        
        elseif(!$conn->get_AcessToken(false, "ВАШ КОНСЬЮМЕР КЕЙ","ВАШ СЕКРЕТ КОНСЬЮМЕР КЕЙ"))
            die('Произошла ошибка в процессе получения Access_Token.');
        
        else
            echo 'ENGLISH MAFAKA DO YOU SPEAK IT';
    }
 ?>