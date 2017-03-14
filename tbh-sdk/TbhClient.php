<?php

require_once 'Client.php';
require_once 'HmacSha1.php';
//require_once 'SignatureMethod.php';
require_once 'Response.php';
require_once 'Request.php';
require_once 'Util/JsonDecoder.php';

Class TbhClient {

    const API_VERSION = 'V1';
    const API_REST_VERSION = '';
    const API_HOST = 'https://api.translatebyhumans.com/index.php';
    //const API_HOST = 'http://localhost/tbhapi/index.php';
    const API_HOST_SANDBOX = 'http://sandbox.api.translatebyhumans.com/index.php';
    //const API_HOST_OAUTH = 'http://localhost/tbhapi/index.php';
    const API_HOST_OAUTH = 'https://api.translatebyhumans.com/index.php';

    private $method;
    private $resource;
    private $response;
    private $Client;
    private $token;
    private $signatureMethod;
    private $sandbox;
    private $client_id;
    private $Client_secret;
    private $auth;

    public function __construct($client_id, $Client_secret, $Client_public, $auth = null, $sandbox = true) {

        $this->client_id = $client_id;
        $this->Client_secret = $Client_secret;
        $this->auth = $auth;
        $this->sandbox = $sandbox; // if true, it will use sandbox api host

        $this->resetLastResponse();
        $this->signatureMethod = new HmacSha1();
        $this->Client = new Client($client_id, $Client_secret);

        if (empty($auth)) {
            $client_info = array(
                'client_id' => $client_id,
                'client_secret' => $Client_secret,
                'client_public' => $Client_public
            );
            try {
                $this->auth = $this->GenerateToken($client_info);
                if (empty($client_id) || empty($Client_secret) || empty($Client_public)) {
                    throw new InvalidArgumentException($this->auth);
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                die();
            }
        }
    }

    public function GenerateToken(array $params = []) {
        $response = $this->post('token', $params);
        if ($response->success) {
            return $response->data;
        } else {
            die($response->message);
        }
    }

    public function GetSourceLanguage() {

        $data = $this->get('sourcelanguage');
        return $data;
    }

    public function GetDestinationLanguage($lang_code) {
        $data = $this->get('destinationlanguage/' . $lang_code);
        return $data;
    }

    public function GetCategory() {

        $data = $this->get('category');
        return $data;
    }

    public function GetQuote(array $params = []) {

        $data = $this->post('quote', $params);
        return $data;
    }

    public function PlaceOrder(array $params = [], array $doc_files = []) {
        $data = $this->post('order', $params, $doc_files);
        return $data;
    }

    public function GetAllOrders() {
        $data = $this->get('order');
        return $data;
    }

    public function GetOrderDetail($order_no) {
        $data = $this->get('order/' . $order_no);
        return $data;
    }

    public function resetLastResponse() {
        $this->response = new Response();
    }

    public function getLastApiPath() {
        return $this->response->getApiPath();
    }

    public function getLastHttpCode() {
        return $this->response->getHttpCode();
    }

    public function getLastXHeaders() {
        return $this->response->getXHeaders();
    }

    public function getLastBody() {
        return $this->response->getBody();
    }

    public function get($path, array $parameters = []) {
        return $this->http('GET', !$this->sandbox ? self::API_HOST : self::API_HOST_SANDBOX, $path, $parameters);
    }

    public function post($path, array $parameters = [], array $doc_files = [], array $headers = []) {
        return $this->http('POST', !$this->sandbox ? self::API_HOST : self::API_HOST_SANDBOX, $path, $parameters, $doc_files, $headers);
    }

    public function delete($path, array $parameters = []) {
        return $this->http('DELETE', !$this->sandbox ? self::API_HOST : self::API_HOST_SANDBOX, $path, $parameters);
    }

    public function put($path, array $parameters = [], array $headers = []) {
        return $this->http('PUT', !$this->sandbox ? self::API_HOST : self::API_HOST_SANDBOX, $path, $parameters, $headers);
    }

    private function http($method, $host, $path, array $parameters, array $doc_files = [], $headers = []) {
        $this->method = $method;
        $this->resource = $path;
        $this->resetLastResponse();

        $url = sprintf('%s/%s/%s', $host, self::API_VERSION, $path);

        $this->response->setApiPath($path);
        if (empty($doc_files)) {
            $result = $this->oAuthRequest($url, $method, $parameters, $doc_files = [], $headers);
        } else {
            $result = $this->oAuthRequest($url, $method, $parameters, $doc_files, $headers);
        }


        //$response = JsonDecoder::decode($result, $this->decodeJsonAsArray);
        $response = json_decode($result);

//        $this->response->setBody($response);

        if ($this->getLastHttpCode() > 399) {
            $this->manageErrors($response);
        }
        return $this->response = $response;

//        if (empty($this->response->data)) {
//            return $this->response->message;
//        } else {
//            return $this->response->data;
//        }
    }

    public function manageErrors($response) {
        try {
            switch ($this->getLastHttpCode()) {
                case 400:
                    throw new Exception('Error Code: 400, Bad Request Passed');
                case 401:
                    throw new Exception('Error Code: 401, You are not authorized to this section');
                case 403:
                    throw new Exception('Error Code: 403, Forbidden');
                case 404:
                    throw new Exception('Error Code: 404, Page not found');
                case 429:
                    throw new Exception('Error Code: 429, Too Many Requests');
                case 500:
                    throw new Exception('Error Code: 500, Internal Server Error');
                case 503:
                    throw new Exception('Error Code: 503, Service Unavailable');
                default:
                    throw new Exception('Error Code: 500, Internal Server Error');
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }

    private function oAuthRequest($url, $method, array $parameters, $doc_files = [], $headers = []) {
        //print_r($doc_files); exit;
        $request = Request::fromClientAndToken($this->Client, $this->token, $method, $url, $parameters);

        if (array_key_exists('oauth_callback', $parameters)) {
            unset($parameters['oauth_callback']);
        }

        $request->signRequest($this->signatureMethod, $this->Client, $this->token);
        $authorization = $request->toHeader();

        return $this->request($request->getNormalizedHttpUrl(), $method, $authorization, $parameters, $doc_files, $headers);
    }

    private function request($url, $method, $authorization, $postfields, $doc_files = [], $headers = []) {

        $headers = array('Auth: ' . $this->auth, 'Content-Type: multipart/form-data;application/x-www-form-urlencoded');
        /* Curl settings */
        $options = [
            CURLOPT_VERBOSE => false,
            CURLOPT_HEADER => true,
            CURLOPT_HTTPHEADER => array_merge(['Accept: */*', $authorization, 'Expect:'], $headers, ['Connection: close']),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_URL => $url,
            CURLOPT_USERAGENT => 'curl request',
            CURLOPT_ENCODING => 'gzip;q=1.0,deflate;q=0.6,identity;q=0.3',
        ];

        if (!empty($this->proxy)) {
            $options[CURLOPT_PROXY] = $this->proxy['CURLOPT_PROXY'];
            $options[CURLOPT_PROXYUSERPWD] = $this->proxy['CURLOPT_PROXYUSERPWD'];
            $options[CURLOPT_PROXYPORT] = $this->proxy['CURLOPT_PROXYPORT'];
            $options[CURLOPT_PROXYAUTH] = CURLAUTH_BASIC;
            $options[CURLOPT_PROXYTYPE] = CURLPROXY_HTTP;
        }

        switch ($method) {
            case 'GET':
                break;
            case 'POST':
                $options[CURLOPT_POST] = true;
                if (isset($postfields['raw'])) {
                    $options[CURLOPT_POSTFIELDS] = $postfields['raw'];
                } else {
                    if (isset($doc_files)) {
                        foreach ($doc_files as $key => $value) {
                            $filename = $doc_files[$key]['name'];
                            $filepath = realpath('./uploads/' . $filename);
                            $filedata = $doc_files[$key]['tmp_name'];
                            $filetype = $doc_files[$key]['type'];
                            $filesize = $doc_files[$key]['size'];
                            $postfields[$key] = curl_file_create($filepath, $filetype, $filepath);
                        }
                    }

                    $options = [
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_POST => true,
                        CURLOPT_POSTFIELDS => $postfields,
                        CURLOPT_HTTPHEADER => $headers,
                    ];
                }
                //print_r($options); exit;
                break;
            case 'DELETE':
                $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
                break;
            case 'PUT':
                $options[CURLOPT_CUSTOMREQUEST] = 'PUT';
                if (isset($postfields['raw'])) {
                    $options[CURLOPT_POSTFIELDS] = $postfields['raw'];
                }
                break;
        }

        if (in_array($method, ['GET', 'PUT', 'DELETE']) && !empty($postfields) && !isset($postfields['raw'])) {
            $options[CURLOPT_URL] .= '?' . Util::buildHttpQuery($postfields);
        }

        $curlHandle = curl_init();
        curl_setopt_array($curlHandle, $options);

        $response = curl_exec($curlHandle);

        // Throw exceptions on cURL errors.
        try {
            if (curl_errno($curlHandle) > 0) {
                throw new Exception('Curl Errno returned' . curl_errno($curlHandle));
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
        $this->response->setHttpCode(curl_getinfo($curlHandle, CURLINFO_HTTP_CODE));
        $parts = explode("\r\n\r\n", $response);
        $responseBody = array_pop($parts);
        $responseHeader = array_pop($parts);
        $this->response->setHeaders($this->parseHeaders($responseHeader));
        curl_close($curlHandle);

        return $responseBody;
    }

    private function parseHeaders($header) {
        $headers = [];
        foreach (explode("\r\n", $header) as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(': ', $line);
                $key = str_replace('-', '_', strtolower($key));
                $headers[$key] = trim($value);
            }
        }

        return $headers;
    }

    public function getResponse() {
        return $this->response;
    }

    public function getMethod() {
        return $this->method;
    }

    public function getResource() {
        return $this->resource;
    }

}
