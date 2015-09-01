Basic REST client using cURL
========================================

Simple implementation that supports PUT, GET, POST, DELETE and uses cURL.
Based on rest-client-php https://github.com/shurikk/rest-client-php, thanks to him
for his job.

It is made for those like me, when there are some situations in which pecl_http
can not be used.

Supports GET, POST, PUT, DELETE HTTP methods. All options from cURL can be
passed to constructor as an array.

Response from calling curl_getinfo() and curl_exec() are respectively
in *$client->response_info* and *$client->response_object*

Examples
--------

    require 'rest_client.php';
    $c = new RestCurlClient();

*GET request*

    $res = $c->get('http://www.yahoo.com');

*Posting raw POST data*

    $res = $c->post(
      'http://api.example.com/create', json_encode(array('name' => 'foobar'))
    );

*Sending a form using POST*

    $res = $c->post(
      'http://www.example.com/form', array('name' => 'foobar'))
    );

*Sending custom HTTP headers*

    $res = $c->post(
      'http://www.example.com/form', json_encode(array('name' => 'foobar')),
      array(
        CURLOPT_HTTPHEADER => array(
          'X-My-App' => 'foobar/1.0',
          'Content-type' => 'application/json'
        )
      )
    );

*Basic HTTP authentication*

    $res = $c->post(
      'http://www.example.com/form', json_encode(array('name' => 'foobar')),
      array(
        CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        CURLOPT_USERPWD => 'username:password'
      )
    );

Notice that all authentication schemes supported by cURL can be used here.

*PUT request*

    $res = $c->post(
      'http://www.example.com/upload.txt', 'PUT request data'
    );

References
----------

* http://php.net/manual/en/book.curl.php
* https://github.com/shurikk/rest-client-php

Contributors
------------

* [David M. Paz](http://github.com/davidmpaz)

