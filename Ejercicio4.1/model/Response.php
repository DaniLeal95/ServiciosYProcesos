<?php

/**
 * Created by PhpStorm.
 * User: dleal
 * Date: 17/11/16
 * Time: 9:40
 */
class Response
{

    private $code;
    private $headers;
    private $body;
    private $format;

    // will receive the response code (200 by default), an associative array with the headers, the data for the body,
    // and the format to output the body (retrieved from the request that the client made)
    public function __construct($code = '200', $headers = null, $body = null, $format = 'json')
    {
        $this->code = $code;
        $this->headers = $headers;
        $this->body = $body;
        $this->format = $format;
    }

    public function generate(){

        switch ($this->format){
            case 'json':

                if (!empty($this->body)) {
                    $this->headers['Content-Type'] = "application/json";
                    $this->body = json_encode($this->body);
                }
                break;

        }
        http_response_code($this->code);
        if (isset($this->headers)) {
            foreach ($this->headers as $key => $value) {
                header($key . ': ' . $value);
            }
        }
        if (!empty($this->body)) {
            echo $this->body;
        }
    }

}