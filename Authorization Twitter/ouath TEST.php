<?php
require_once('Common_Functions.php');

class OAuthTwitter {
    
    public function goToAuth($URL_CALLBACK, $CONSUMER_KEY, $CONSUMER_SECRET, $CURLOPT_SSL) {
        $oauth_nonce = md5(uniqid(rand(), true));
        $oauth_timestamp = time();
        $Utils = new Utils;
        
        $oauth_base_text = "POST&" .
            urlencode($Utils->Com_Const["API_HOST"].$Utils->Com_Const["URL_REQUEST_TOKEN"]) . "&" .
            urlencode(
                "oauth_callback=" . urlencode($URL_CALLBACK) . "&" .
                "oauth_consumer_key=" . $CONSUMER_KEY . "&" .
                "oauth_nonce=" . $oauth_nonce . "&" .
                "oauth_signature_method=HMAC-SHA1&" .
                "oauth_timestamp=" . $oauth_timestamp . "&" .
                "oauth_version=1.0"
            );

        $key = $CONSUMER_SECRET . "&";
        $oauth_signature = $Utils->encode($oauth_base_text, $key);
        
        $postfields = 'oauth_callback=' . urlencode($URL_CALLBACK); 
        $postfields .= '&oauth_consumer_key=' . $CONSUMER_KEY;
        $postfields .= '&oauth_nonce=' . $oauth_nonce;
        $postfields .= '&oauth_signature=' . urlencode($oauth_signature);
        $postfields .= '&oauth_signature_method=HMAC-SHA1';
        $postfields .= '&oauth_timestamp=' . $oauth_timestamp;
        $postfields .= '&oauth_version=1.0';
        
        $response = $Utils->cUrl_SEND_REQUEST($postfields,$CURLOPT_SSL,$Utils->Com_Const["API_HOST"].$Utils->Com_Const["URL_REQUEST_TOKEN"],null,false,true);
        
        if (!$response)
            return false;
        
        parse_str($response, $result);
        print_r($result);
        if (empty($result['oauth_token_secret']))
            return false;   

        $_SESSION['oauth_token_secret'] = $result['oauth_token_secret'];
        Utils::redirect($Utils->Com_Const["API_HOST"].$Utils->Com_Const["URL_AUTHORIZE"] . '?oauth_token=' . $result['oauth_token']);
        return true;
    }
    
    public function get_OAuthToken($OAuth_Token, $OAuth_Verifier, $URL_CALLBACK, $CONSUMER_KEY, $CONSUMER_SECRET, $CURLOPT_SSL) {
        $oauth_nonce = md5(uniqid(rand(), true));
        $oauth_timestamp = time();
        $Utils = new Utils;
        
        $oauth_base_text = "POST&" .
            urlencode($Utils->Com_Const["API_HOST"].$Utils->Com_Const["URL_ACCESS_TOKEN"]). "&" .
            urlencode(
                "oauth_consumer_key=" . $CONSUMER_KEY . "&" .
                "oauth_nonce=" . $oauth_nonce . "&" .
                "oauth_signature_method=HMAC-SHA1&" .
                "oauth_token=" . $OAuth_Token . "&" .
                "oauth_timestamp=" . $oauth_timestamp . "&" .
                "oauth_verifier=" . $OAuth_Verifier . "&" .
                "oauth_version=1.0"
            );

        $key = $CONSUMER_SECRET . "&" . $_SESSION['oauth_token_secret'];
        $oauth_signature = $Utils->encode($oauth_base_text, $key);
        
        $postfields = 'oauth_consumer_key=' . $CONSUMER_KEY ; 
        $postfields .= '&oauth_nonce=' . $oauth_nonce; 
        $postfields .= '&oauth_signature_method=HMAC-SHA1';
        $postfields .= '&oauth_token=' . urlencode($OAuth_Token); 
        $postfields .= '&oauth_timestamp=' . $oauth_timestamp; 
        $postfields .= '&oauth_verifier=' . urlencode($OAuth_Verifier); 
        $postfields .= '&oauth_signature=' . urlencode($oauth_signature); 
        $postfields .= '&oauth_version=1.0'; 
        
        $response = $Utils->cUrl_SEND_REQUEST($postfields, $CURLOPT_SSL,$Utils->Com_Const["API_HOST"].$Utils->Com_Const["URL_ACCESS_TOKEN"],null,false,true);
        
        if (!$response)
            return false;
            
        parse_str($response, $result);
        
        if (empty($result['oauth_token']) || empty($result['user_id'])) 
            return false;
               
        $_SESSION['access_token'] = $result['oauth_token'];
        $_SESSION['access_token_secret'] = $result['oauth_token_secret'];
        return true;
    }

    public function get_AcessToken($CURLOPT_SSL, $CONSUMER_KEY, $CONSUMER_SECRET) {
        $Utils = new Utils;
        
        $headers = array( 
            "POST /oauth2/token HTTP/1.1", 
            "Host: api.twitter.com", 
            "User-Agent: my Twitter App v.1",
            "Authorization: Basic ".base64_encode($CONSUMER_KEY . ':' . $CONSUMER_SECRET),
            "Content-Type: application/x-www-form-urlencoded;charset=UTF-8", 
            "Content-Length: 29"
        ); 
        
        $response = $Utils->cUrl_SEND_REQUEST("grant_type=client_credentials",$CURLOPT_SSL,$Utils->Com_Const["API_HOST"].$Utils->Com_Const["URL_TOKEN"],$headers,false,true);
    
        if (!$response)
            return false;
            
        preg_match('/"access_token":"[^"}]*/', $response, $matches);
        $result = str_replace('"access_token":"', "", $matches[0]);
        
        if (empty($result)) 
            return false;
        
        $_SESSION["bearer_access_token"] = $result;
        return true;
    }
}
?>

