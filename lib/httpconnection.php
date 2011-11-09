<?php

class HttpConnection {

    // @param string $base url to the service, i.e http://example.com:80
    protected $baseUrl;

    // @param array $defaultOptions variable to hold default cURL options.
    protected $defaultOptions;

    /**
     * constructor with some defaults
     * @param string $baseUrl
     */
    public function __construct($baseUrl = '', $params = array())
    {
        $this->baseUrl = $baseUrl;
        $params = array_merge(array('headers' => array(),'basic_auth' => array()),$params);
        if ( ! in_array('Expect:', $params['headers']))
        {
            $params['headers'][] = 'Expect:'; // lighty needs this
        }
        $this->defaultOptions = array(
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HTTPHEADER => $params['headers'],
            CURLOPT_HEADER => 0,
            CURLOPT_USERAGENT => 'curl',
        );
        if (!empty($params['verify_peer']) && $params['verify_peer'] === false) {
            $this->verifyPeer($params['verify_peer']);
        }
        if ( !empty($params['basic_auth'])) {
            $this->setBasicAuth($params['basic_auth']['user'],$params['basic_auth']['password']);
        }

    }

    public function setBasicAuth($user,$password)
    {
        $this->defaultOptions[CURLOPT_USERPWD] = $user.':'.$password;
    }

    public function verifyPeer( $verify = true )
    {
        $this->defaultOptions[CURLOPT_SSL_VERIFYPEER] = $verify;
        $this->defaultOptions[CURLOPT_SSL_VERIFYHOST] = $verify;
    }

    /**
     * @param string $resourceUrl
     * @param array $headers (optional)
     * @return boolean
     */
    public function get($resourceUrl, $opts = array())
    {
        $opts = $this->parseOptions($opts);
        if (!empty($opts['params']))
        {
            $resourceUrl .= (strpos($responseUrl,'?') === false) ? '?' .$opts['params'] : '&'.$opts['params'];
        }

        $response = new HttpResponse();
        $ch = curl_init();
        $h = $this->mergeHeaders($opts['headers']);


        $merged_curlOptions = array(
            CURLOPT_URL =>	$this->baseUrl.$resourceUrl,
            CURLOPT_HTTPHEADER => $h,
            CURLOPT_HEADERFUNCTION => array(&$response,'readHeader')
        );

        // can't use array_merge or the $array + $array since headers is nested array
        $curlOptions = $this->defaultOptions;
        foreach($merged_curlOptions as $k => $v) {
            $curlOptions[$k] = $v;
        }

        curl_setopt_array($ch,$curlOptions);
        $response->body = curl_exec($ch);
        $response->errors = curl_error($ch);
        $response->info = curl_getinfo($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * @param string $resourceUrl
     * @param mixed $post_body array or string.
     * @param array $headers (optional)
     * @return boolean
     */
    public function post($resourceUrl, $opts= array())
    {
        $opts = $this->parseOptions($opts);

        $response = new HttpResponse();
        $ch = curl_init();
        $h = $this->mergeHeaders($opts['headers']);
        $merged_curlOptions = array(
            CURLOPT_URL =>	$this->baseUrl.$resourceUrl,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $opts['params'],
            CURLOPT_HTTPHEADER => $h,
            CURLOPT_HEADERFUNCTION => array(&$response,'readHeader')
        );

        // can't use array_merge or the $array + $array since headers is nested array
        $curlOptions = $this->defaultOptions;
        foreach($merged_curlOptions as $k => $v) {
            $curlOptions[$k] = $v;
        }

        curl_setopt_array($ch,$curlOptions);
        $response->body = curl_exec($ch);
        $response->errors = curl_error($ch);
        $response->info = curl_getinfo($ch);
        curl_close($ch);
        return $response;
    }

    /**
     * merge headers with the defaults from curl
     * @param array $headers
     * @return array $currentHeaders
     */
    protected function mergeHeaders( $headers = array() )
    {
        $currentHeaders = $this->defaultOptions[CURLOPT_HTTPHEADER];
        foreach ($headers as $k => $h) {
            $header = $k . ': ' . $h;
            if ($this->shouldAddRequestHeader($header, $currentHeaders)) {
                $currentHeaders[] = $header;
            }
        }
        return $currentHeaders;
    }

    public function shouldAddRequestHeader($header, &$currentHeaders)
    {
        if (!in_array($header, $currentHeaders)) {
            return true;
        } else {
            return false;
        }
    }


    protected function parseOptions($options) {
        $options = array_merge(array('params' => '', 'headers' => array()), $options);
        if ( is_array($options['params']) )
        {
            $options['params'] = http_build_query($options['params']);
        }
        return $options;
    }
}
