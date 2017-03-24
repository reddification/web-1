<?php

    class Utils {
        public static function redirect($uri = '') {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: ".$uri, TRUE, 302);
            exit;
        }
    }

    require_once('Common_Constants.php');
    
    /**
      * Вызов функции согласно выбранному хосту и пути к ней.
      * 
      * @param Одна из констант хостов(API, UPLOAD). $HOST
      * @param Путь к функции. $PATH
      * @param Метод(GET,POST,DELETE,PUT). $METHOD
      * @param Параметры передаваемые функции. $PARAM
      */
      function EXECUTE_API_Function($HOST, $PATH, $METHOD, $PARAM){
        if(!empty($HOST) && !empty($PATH)){
           $URL = sprintf('%s/%s/%s.json', $HOST, self:: API_VERSION, $PATH);
        }else{
            
        }
     }
     
     function TWITTER_API_GetULR($API_FUNCTION){
        global $Com_Const;
        return $Com_Const["API_HOST"] . $Com_Const[$API_FUNCTION];
     }
     
     /**
      * Установка передаваемых параметров для сеанса cUrl.
      * 
      * @param $CURLOPT_POSTFIELDS: Параметры в виде URL-кодированной строки запроса. 
      * @param $CURLOPT_SSL: True - сертификат присутствует, иначе False.
      * @param $CURLOPT_URL: URL исполняемого запроса.
      */
      function cUrl_Initialize($CURLOPT_POSTFIELDS, $CURLOPT_SSL, $CURLOPT_URL, $CURLOPT_HTTPHEADER, $RESPONSE_HEADER_INCLUDE){
        $myCurl = curl_init();
                
         
         curl_setopt_array($myCurl, array(
                    CURLOPT_URL => $CURLOPT_URL,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_NOBODY => 1,
                    CURLOPT_SSL_VERIFYPEER => $CURLOPT_SSL, 
                    CURLOPT_SSL_VERIFYHOST => $CURLOPT_SSL,
                    CURLOPT_HEADER => $RESPONSE_HEADER_INCLUDE,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $CURLOPT_POSTFIELDS));
                    
         if(!is_null($CURLOPT_HTTPHEADER))
            curl_setopt($myCurl, CURLOPT_HTTPHEADER, $CURLOPT_HTTPHEADER);
                            
        $response = curl_exec($myCurl);
        curl_close($myCurl);
        
        return $response;
      }
      //P.S. Могут возникнуть проблемы с http_build_query, т.к. если в значениях параметров содержится сочетание "25", 
      //то функция заменяет его символом % (25 в ASCII), либо пропускает его. Поэтому рекомендуется строить строку параметров вручную.
 ?>