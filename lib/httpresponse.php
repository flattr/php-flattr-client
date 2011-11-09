<?php
class HttpResponse
{
    public $body, $responseCode = null;
    public $errors = array();
    public $info = array();
    public $responseHeaders = array();

    public function __construct()
    {
    }

    public function readHeader($ch, $header)
    {
        list($key, $value) = self::parseHeader($header);
        if(empty($value)) {
            return strlen($header);
        }
        if ($key == 0 && strpos($value,'HTTP/1.1 ') === 0) {
            $key = 'Status';
            $value = str_replace('HTTP/1.1 ','',$value);
            $this->responseCode = (int)substr($value,0,3); // cache the response code
        }
        $this->responseHeaders[$key] = $value;
        // !important, return bytes read, curl will fail otherwise
        return strlen($header);
    }

    public static function parseHeader( $header = '' )
    {
        $exploded = explode(':', $header);
        $r = (count($exploded) > 1) ? array() : array(0);
        foreach($exploded as $e) {
            $r[] = trim($e);
        }
        return $r;
    }


}
