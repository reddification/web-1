 <?php
 require_once('ouath TEST.php');
 require_once('Common_Functions.php');
 
 session_start();
 $C_K = "CONSUMER KEY";
 $SC_K = "SECRET CONSUMER";
  //error_log("[start test]", 3, "my-errors.log");
    if (!empty($_GET['denied']))
            die('Пользователь отменил авторизацию. [ouath_start_test]');
    
    elseif (empty($_GET['oauth_token']) || empty($_GET['oauth_verifier']))
           OAuthTwitter::goToAuth("http://site.local/ouath_start_test.php", $C_K, $SC_K, false);
    
    else {
        if (!OAuthTwitter::get_OAuthToken(trim($_GET['oauth_token']), trim($_GET['oauth_verifier']), "http://site.local/ouath_start_test.php", $C_K, $SC_K, false))
            die('Произошла ошибка в процессе получения разрешения доступа к Access_Token. [ouath_start_test]');
        //elseif(!$conn->get_AcessToken(false, $C_K, $SC_K))
           // die('Произошла ошибка в процессе получения Access_Token. [ouath_start_test]');
        else
        {
            Utils::redirect('http://site.local/Info_Functions.php');//здесь вытащить все переменные из сессии и сохранить их (аксес кей\мб бериар) и завершить сессию
        }
    }
 ?>
