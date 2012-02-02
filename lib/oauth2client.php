<?php

class OAuth2Client
{
    protected $settings = array();
    protected $http = null;

    /**
     * creates the oauth2client object with some defaults
     *
     * @param array $settings overrides default settings.
     * @return OAuth2Client
     */
    public function __construct( $settings = array() )
    {
        $this->settings = array_merge(array(
            'client_id'         => null,
            'client_secret'     => null,
            'base_url'          => null,
            'site_url'          => null,
            'authorize_url'     => null,
            'access_token_url'  => null,
            'scopes'            => null,
            'token_param_name'  => 'Bearer',
            'redirect_uri'      => null, 
            'response_type'     => 'code',
            'grant_type'        => 'authorization_code',
            'access_token'      => null,
            'refresh_token'     => null,
            'developer_mode'    => false,
            'format'            => 'application/json',
        ), $settings);
        $this->http = new HttpConnection();
        if ($this->settings['developer_mode']) {
            $this->http->verifyPeer( false );
        }
    }

    /**
     * assemble the authorize_url link
     *
     * @return string authorize url endpoint
     */
    public function authorizeUrl()
    {
        return $this->settings['authorize_url'].'?'.
            'client_id='.$this->settings['client_id'].'&'.
            'response_type='.$this->settings['response_type'].'&'.
            'redirect_uri='.$this->settings['redirect_uri'].'&'.
            'scope='.$this->settings['scopes'];
    }

    /**
     * exchange $code for an access_token
     *
     * @param string $code
     * @throws exception
     * @return mixed string or false
     */
    public function fetchAccessToken($code, $authInParams = false)
    {
        $params = array(
            'code' => $code,
            'grant_type' => $this->settings['grant_type'],
        );

        // @todo if more params is needed, make a loop...
        if (!empty($this->settings['redirect_uri'])) {
            $params['redirect_uri'] = $this->settings['redirect_uri'];
        }
        $headers = $this->headers();
        if ( $authInParams) {
            $headers = $this->headers(false);
            $params['client_id']     = $this->settings['client_id'];
            $params['client_secret'] = $this->settings['client_secret'];
        }
        $this->logRequest('POST '.$this->settings['access_token_url'],array(
            'params' => $params,
            'headers' => $headers,
        ));
        $response = $this->http->post( $this->settings['access_token_url'],
            array(
                'params' => $params,
                'headers' => $headers,
            )
        );
        $this->logResponse($response);
        if (!empty($response->body)) {
            $prms = static::parseResponse($response);
            if(!empty($prms["error"])) {
                throw new Exception("Authentication error: " . $prms["error"]);
            }
            if ( strpos($response->info['http_code'], '20') !== 0 )
            {
                throw new Exception('failed to create access_token');
            }
            if ( !empty($prms['token_type']) ) {
                $this->settings['token_type'] = $prms['token_type'];
            }
            $this->settings['access_token'] = $prms['access_token'];
            return $prms['access_token'];
        } else {
            throw new Exception('No body received from POST access_token');
        }
        return false;
    }

    /**
     * perform a GET api call
     *
     * @param string $path
     * @return HttpResponse
     */
    public function get($path)
    {
        $this->logRequest(
            'GET '.$this->uriFor($path),
            array(
                'headers' => $this->headers(),
            )
        );
        $response = $this->http->get(
            $this->uriFor($path),
            array(
                'headers' => $this->headers(),
            )
        );
        $this->logResponse($response);
        return $response;
    }

    /**
     * perform a GET api call, wrapped in OAuth2Client::parseResponse
     *
     * @param string $path
     * @return mixed array or string
     */
    public function getParsed($path)
    {
        $response = $this->get($path);
        if (!empty($response->body) ) {
            return static::parseResponse($response);
        } else {
            return array(
                'error' => 'did not get a body from '.$uri
            );
        }
    }

    /**
     * perform a POST api call
     *
     * @param string $path
     * @param array $postarray
     * @return HttpResponse
     */
    public function post($path, $postarray)
    {
        $this->logRequest(
            'POST '.$this->uriFor($path),
            array(
                'params' => http_build_query($postarray),
                'headers' => $this->headers(),
            )
        );

        $response = $this->http->post(
            $this->uriFor($path),
            array(
                'params' => http_build_query($postarray),
                'headers' => $this->headers()
            )
        );

        $this->logResponse($response);
        return $response;
    }

    /**
     * assemble headers for an API clall,
     * adding Accept and potentially Authorization
     *
     * @param boolean $authorizationHeader
     * @return array $headers
     */
    protected function headers($authorizationHeader = true)
    {
        $headers = array(
            'Accept' => $this->settings['format'],
        );
        if ($authorizationHeader) {
            if (! empty($this->settings['access_token'])) {
                $headers['Authorization'] = $this->settings['token_param_name'] . ' '
                                          . $this->settings['access_token'];
            } else if (! empty($this->settings['client_id']) &&
                       ! empty($this->settings['client_secret'])) {
                $headers['Authorization'] = 'Basic ' . base64_encode(
                    $this->settings['client_id'] . ':' . $this->settings['client_secret']
                );
            }
        }

        return $headers;

    }
    /**
     * @todo something to parse yaml, xml, m.m ?
     * parses a response and returns an array if successfull
     *
     * @param HttpResponse $response
     * @return mixed array or string $response->body
     */
    public static function parseResponse($response)
    {
        $return = array();
        if (strpos($response->info['content_type'],'json') !== false) {
            $return = json_decode($response->body,true);
        } else if (false) {
        } else {
        }
        return $return;
    }

    /**
     * adds the base_url before a resource $path
     *
     * @param string $path
     * @return string
     */
    protected function uriFor($path)
    {
        return $this->settings['base_url'] . $path;
    }


    /**
     * log a response
     *
     * @param HttpResponse
     * @return void
     */
    protected function logResponse($response)
    {
        if ($this->settings['developer_mode'] && function_exists('slog')) {
            slog("");
            slog("** LOG RESPONSE **");
            slog("HEADERS =>");
            slog($response->responseHeaders);
            slog("BODY =>");
            slog($response->body);
        }
    }

    /**
     * log a request
     *
     * @param string $uri
     * @param array $params
     */
    protected function logRequest( $uri, $params = array())
    {
        if ($this->settings['developer_mode'] && function_exists('slog')) {
            slog("");
            slog("** LOG REQUEST **");
            slog($uri);
            slog($params);
        }
    }
}
