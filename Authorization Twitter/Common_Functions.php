<?php

    class Utils {
        public static function redirect($uri = '') {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$uri, TRUE, 302);
            exit;
        }
    
     /**
      * Установка передаваемых параметров для сеанса cUrl.
      * 
      * @param $CURLOPT_POSTFIELDS: Параметры в виде URL-кодированной строки запроса. 
      * @param $CURLOPT_SSL: True - сертификат присутствует, иначе False.
      * @param $CURLOPT_URL: URL исполняемого запроса.
      * @param $CURLOPT_HTTPHEADER: Заголовок отправляемого запроса, иначе null.
      * @param $RESPONSE_HEADER_INCLUDE: Сохранить заголовок ответа.
      * @param $POST_USE: Отправить запрос методом POST (True\False).
      */
      public function cUrl_SEND_REQUEST($CURLOPT_POSTFIELDS, $CURLOPT_SSL, $CURLOPT_URL, $CURLOPT_HTTPHEADER, $RESPONSE_HEADER_INCLUDE, $POST_USE){
        $myCurl = curl_init();
         
         curl_setopt_array($myCurl, array(
                    CURLOPT_URL => $CURLOPT_URL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => $CURLOPT_SSL, 
                    CURLOPT_SSL_VERIFYHOST => $CURLOPT_SSL,
                    CURLOPT_HEADER => $RESPONSE_HEADER_INCLUDE,
                    CURLOPT_POST => $POST_USE));
                    
         if(!is_null($CURLOPT_HTTPHEADER))
            curl_setopt($myCurl, CURLOPT_HTTPHEADER, $CURLOPT_HTTPHEADER);
            
         if(!is_null($CURLOPT_POSTFIELDS))
            curl_setopt($myCurl, CURLOPT_POSTFIELDS, $CURLOPT_POSTFIELDS);
            
        $response = curl_exec($myCurl);
        $error = curl_error($myCurl);
        curl_close($myCurl);
        
        if($error)
            return $error;
            
        return $response;
      }
      //P.S. Могут возникнуть проблемы с http_build_query, т.к. если в значениях параметров содержится сочетание "25", 
      //то функция заменяет его символом % (25 в ASCII), либо пропускает его. Поэтому рекомендуется строить строку параметров вручную.
      
      
    /** 
       * Исполнение API функций Twitter.
       * 
       * @param $Func_Name - имя вызываемой функции (URL_...).
       * @param $CURLOPT_SSL - наличие сертификата SSL (True/False).
       * @param $CONSUMER["KEY"] - ключ вашего приложения Twitter.
       * @param $CONSUMER["SECRET_KEY"] - секретный ключ вашего приложения Twitter.
       * @param $HTTP_HEAD_INCLUDE - включение HTTP заголовка в ответ (True\False).
      */
      public function GET_API_TWITTER($Func_Name, $CURLOPT_SSL, $CONSUMER, $HTTP_HEAD_INCLUDE) {
        
        $oauth_nonce = md5(uniqid(rand(), true));
        $oauth_timestamp = time();
        $API_FUNCTION = $this->Com_Const["API_HOST"] . $this->Com_Const["API_VERSION"] . $this->Com_Const[$Func_Name];
         
        $oauth_base_text = "GET&";
        $oauth_base_text .= urlencode($API_FUNCTION).'&';
        $oauth_base_text .= urlencode('oauth_consumer_key='.$CONSUMER["KEY"].'&');
        $oauth_base_text .= urlencode('oauth_signature_method=HMAC-SHA1&');
        $oauth_base_text .= urlencode('oauth_timestamp='.$oauth_timestamp.'&');
        $oauth_base_text .= urlencode('oauth_nonce='.$oauth_nonce.'&');
        $oauth_base_text .= urlencode('oauth_version=1.0&');
        $oauth_base_text .= urlencode('oauth_token='.$_SESSION['access_token'].'&');
        $oauth_base_text .= urlencode('screen_name=YebaTu'.'&');
        $oauth_base_text .= urlencode('user_id=2751830970');
        
        $key = $CONSUMER["SECRET_KEY"] . '&' . $_SESSION['access_token_secret'];
        $oauth_signature = $this->encode($oauth_base_text, $key);
        
        $url = $API_FUNCTION;
        $url .= '?oauth_consumer_key=' . $CONSUMER["KEY"];
        $url .= '&oauth_signature_method=HMAC-SHA1';
        $url .= '&oauth_timestamp=' . $oauth_timestamp;
        $url .= '&oauth_nonce=' . $oauth_nonce;
        $url .= '&oauth_version=1.0';
        $url .= '&oauth_token=' . urlencode($_SESSION['access_token']);
        $url .= '&screen_name=YebaTu';
        $url .= '&user_id=2751830970';
        $url .= '&oauth_signature=' . urlencode($oauth_signature);
        
        $response = $this->cUrl_SEND_REQUEST(null, $CURLOPT_SSL, $url, null, $HTTP_HEAD_INCLUDE, false);
        return $response;
    }
    
    public function encode($string, $key) {
        return base64_encode(hash_hmac("sha1", $string, $key, true));
    }   
    
    public $Com_Const = array("UPLOAD_HOST" => 'https://upload.twitter.com',
                           "API_VERSION" => '/1.1',
                           "API_HOST" => 'https://api.twitter.com',
                           "URL_TOKEN" => "/oauth2/token",
                           "URL_REQUEST_TOKEN" => "/oauth/request_token",
                           "URL_AUTHORIZE" => "/oauth/authorize",
                           "URL_ACCESS_TOKEN" => "/oauth/access_token",
                           "URL_USER_TIMELINE" => "/statuses/user_timeline.json",
                           "URL_ACCOUNT_VERIFY" => "/account/verify_credentials.json",
                           "URL_USER_SHOW" => "/users/show.json"); 
}
 ?>
