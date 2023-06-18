<?php

class CurlServer
{
    private $apiName;

    public function __construct(string $apiName)
    {
        $this->apiName = $apiName;
    }

    /**
     * Get request for using Spotify or Deezer Api
     */
    function getRequest(string $url, ?string $accessToken, string $errorMessage): string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $accessToken));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        
        if(array_key_exists("error", json_decode($response, true))) {
            $this->getErrorMessage($response, $errorMessage, $this->apiName);
        }
 
        curl_close($ch);

        return $response;
    }

    /**
     * Post request for using Spotify or Deezer Api
     */
    function postRequest(string $url, array|null|string $postData, array $headerData, string $errorMessage): bool|string
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerData); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
     
        if(is_array(json_decode($response, true)) && array_key_exists("error", json_decode($response, true))) {
        
            $this->getErrorMessage($response, $errorMessage, $this->apiName);
        }

        curl_close($ch);

        return $response;
    }

    /**
     * Function throw an exception corresponding to the request error
     */
    function getErrorMessage(string $response, string $errorMessage, string $apiName): void
    {   
        $response = json_decode($response, true);
        if($apiName == "spotify")
            throw new Exception($errorMessage .= "<br>" . "Code: " . $response["error"]["status"] . "<br>" . $response["error"]["message"] . "<br>");
        else if ($apiName == "deezer")
            throw new Exception($errorMessage .= "<br>" . "Code: " . $response["error"]["code"] . "<br>" . $response["error"]["message"] . "<br>");
    }
}
