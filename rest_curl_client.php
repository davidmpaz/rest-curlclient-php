<?php

class HttpServerException extends Exception {
}

class HttpServerException404 extends Exception {
  function __construct($message = 'Not Found') {
    parent::__construct($message, 404);
  }
}

class RestClientException extends Exception {
}

class RestCurlClient {

  public $handle;
  public $http_options;
  public $response_object;
  public $response_info;

  function __construct() {
    $this->http_options = array();
    $this->http_options[CURLOPT_RETURNTRANSFER] = true;
  }

  function get($url, $http_options = array()) {
    $http_options = $http_options + $this->http_options;
    $this->handle = curl_init($url);

    if(! curl_setopt_array($this->handle, $http_options)){
      throw new RestClientException("Error setting cURL request options");
    }

    $this->http_parse_message(curl_exec($this->handle));
    curl_close($this->handle);
    return $this->response_object;
  }

  function post($url, $fields = array(), $http_options = array()) {
    $http_options = $http_options + $this->http_options;
    $http_options[CURLOPT_POST] = true;
    $http_options[CURLOPT_POSTFIELDS] = $fields;
    if(is_array($fields)){
      $http_options[CURLOPT_HTTPHEADER] =
        array('Content-Type: multipart/form-data');
    }
    $this->handle = curl_init($url);

    if(! curl_setopt_array($this->handle, $http_options)){
      throw new RestClientException("Error setting cURL request options.");
    }

    $this->http_parse_message(curl_exec($this->handle));
    curl_close($this->handle);
    return $this->response_object;
  }

  function put($url, $data = '', $http_options = array()) {
    $http_options = $http_options + $this->http_options;
    $http_options[CURLOPT_CUSTOMREQUEST] = 'PUT';
    $http_options[CURLOPT_POSTFIELDS] = $fields;
    $this->handle = curl_init($url);

    if(! curl_setopt_array($this->handle, $http_options)){
      throw new RestClientException("Error setting cURL request options.");
    }

    $this->http_parse_message(curl_exec($this->handle));
    curl_close($this->handle);
    return $this->response_object;
  }

  function delete($url, $http_options = array()) {
    $http_options = $http_options + $this->http_options;
    $http_options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
    $this->handle = curl_init($url);

    if(! curl_setopt_array($this->handle, $http_options)){
      throw new RestClientException("Error setting cURL request options.");
    }

    $this->http_parse_message(curl_exec($this->handle));
    curl_close($this->handle);
    return $this->response_object;
  }

  function http_parse_message($res) {

    if(! $res){
      throw new HttpServerException(curl_error($this->handle), -1);
    }

    $this->response_object = $res;
    $this->response_info = curl_getinfo($this->handle);
    $code = $this->response_info['http_code'];

    if($code == 404) {
      throw new HttpServerException404(curl_error($this->handle));
    }

    if($code >= 400 && $code <=600) {
      throw new HttpServerException(curl_error($this->handle), $code);
    }

    if(!in_array($code, range(200,207))) {
      throw new HttpServerException(curl_error($this->handle), $code);
    }
  }
}
