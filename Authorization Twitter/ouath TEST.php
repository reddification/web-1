<?php
require_once('Common_Functions.php');

class OAuthTwitter {
    
    public function goToAuth($URL_CALLBACK, $CONSUMER_KEY, $CONSUMER_SECRET, $CURLOPT_SSL) {
        $oauth_nonce = md5(uniqid(rand(), true));
        $oauth_timestamp = time();
        $oauth_base_text = "POST&" .
            urlencode(TWITTER_API_GetULR("URL_REQUEST_TOKEN")) . "&" .
            urlencode(
                "oauth_callback=" . urlencode($URL_CALLBACK) . "&" .
                "oauth_consumer_key=" . $CONSUMER_KEY . "&" .
                "oauth_nonce=" . $oauth_nonce . "&" .
                "oauth_signature_method=HMAC-SHA1&" .
                "oauth_timestamp=" . $oauth_timestamp . "&" .
                "oauth_version=1.0"
            );

        $key = $CONSUMER_SECRET . "&";
        $oauth_signature = $this->encode($oauth_base_text, $key);
        
        $postfields = 'oauth_callback=' . urlencode($URL_CALLBACK); 
        $postfields .= '&oauth_consumer_key=' . $CONSUMER_KEY;
        $postfields .= '&oauth_nonce=' . $oauth_nonce;
        $postfields .= '&oauth_signature=' . urlencode($oauth_signature);
        $postfields .= '&oauth_signature_method=HMAC-SHA1';
        $postfields .= '&oauth_timestamp=' . $oauth_timestamp;
        $postfields .= '&oauth_version=1.0';
        
        $response = cUrl_Initialize($postfields,$CURLOPT_SSL,TWITTER_API_GetULR("URL_REQUEST_TOKEN"),null,false);
        
        if (!$response)
            return false;
            
        parse_str($response, $result);
        print_r($result);
        if (empty($result['oauth_token_secret']))
            return false;   

        $_SESSION['oauth_token_secret'] = $result['oauth_token_secret'];
        
        //preg_match('/oauth_token=[^&]*/', $response, $matches);
        //Utils::redirect(TWITTER_API_GetULR("URL_AUTHORIZE") . '?oauth_token=' . str_replace("oauth_token=", "", $matches[0]));
        Utils::redirect(TWITTER_API_GetULR("URL_AUTHORIZE") . '?oauth_token=' . $result['oauth_token']);
        return true;
    }

    private function encode($string, $key) {
        return base64_encode(hash_hmac("sha1", $string, $key, true));
    }
    
    public function get_OAuthToken($OAuth_Token, $OAuth_Verifier, $URL_CALLBACK, $CONSUMER_KEY, $CONSUMER_SECRET, $CURLOPT_SSL) {
        $oauth_nonce = md5(uniqid(rand(), true));
        $oauth_timestamp = time();
        $oauth_token_secret = $_SESSION['oauth_token_secret'];

        $oauth_base_text = "POST&" .
            urlencode(TWITTER_API_GetULR("URL_ACCESS_TOKEN")) . "&" .
            urlencode(
                "oauth_consumer_key=" . $CONSUMER_KEY . "&" .
                "oauth_nonce=" . $oauth_nonce . "&" .
                "oauth_signature_method=HMAC-SHA1&" .
                "oauth_token=" . $OAuth_Token . "&" .
                "oauth_timestamp=" . $oauth_timestamp . "&" .
                "oauth_verifier=" . $OAuth_Verifier . "&" .
                "oauth_version=1.0"
            );

        $key = $CONSUMER_SECRET . "&" . $oauth_token_secret;
        $oauth_signature = $this->encode($oauth_base_text, $key);
        
        $postfields = 'oauth_consumer_key=' . $CONSUMER_KEY ; 
        $postfields .= '&oauth_nonce=' . $oauth_nonce; 
        $postfields .= '&oauth_signature_method=HMAC-SHA1';
        $postfields .= '&oauth_token=' . urlencode($OAuth_Token); 
        $postfields .= '&oauth_timestamp=' . $oauth_timestamp; 
        $postfields .= '&oauth_verifier=' . urlencode($OAuth_Verifier); 
        $postfields .= '&oauth_signature=' . urlencode($oauth_signature); 
        $postfields .= '&oauth_version=1.0'; 
        
        $response = cUrl_Initialize($postfields,$CURLOPT_SSL,TWITTER_API_GetULR("URL_ACCESS_TOKEN"),null,false);
        
        if (!$response)
            return false;

        parse_str($response, $result);
        //preg_match('/oauth_token=[^&]*/', $response, $matches);
        if (empty($result['oauth_token']) || empty($result['user_id'])) 
            return false;

        //$this->USER_ID = $result['user_id'];

        return true;
    }

    public function get_AcessToken($CURLOPT_SSL, $CONSUMER_KEY, $CONSUMER_SECRET) {
        
        $headers = array( 
            "POST /oauth2/token HTTP/1.1", 
            "Host: api.twitter.com", 
            "User-Agent: my Twitter App v.1",
            "Authorization: Basic ".base64_encode($CONSUMER_KEY . ':' . $CONSUMER_SECRET),
            "Content-Type: application/x-www-form-urlencoded;charset=UTF-8", 
            "Content-Length: 29"
        ); 

        $response = cUrl_Initialize("grant_type=client_credentials",$CURLOPT_SSL,TWITTER_API_GetULR("URL_TOKEN"),$headers,false);
    
        if (!$response)
            return false;
            
        preg_match('/"access_token":"[^"}]*/', $response, $matches);
        $result = str_replace('"access_token":"', "", $matches[0]);
        
        if (empty($result)) 
            return false;
        
        $_SESSION["access_token"] = $result;
        return true;
    }
}
?>

