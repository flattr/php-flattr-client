<?php
/**
 * simple config class that should be extended
 */
abstract class ConfigBase
{
    static $CLIENT_ID;
    static $CLIENT_SECRET;
    static $BASE_URL;
    static $SITE_URL;
    static $AUTHORIZE_URL;
    static $ACCESS_TOKEN_URL;
    static $REDIRECT_URI;
    static $SCOPES;
    static $DEVELOPER_MODE = false;
    static $TOKEN_PARAM_NAME = 'Bearer';
    static $LOGFILE = '/tmp/flattr-web-client.log';
    protected static function up()
    {
        die('need to set config');
    }

    public static function all( $custom = array() )
    {
        static::up();
        return array_merge(array(
            'client_id'         => static::$CLIENT_ID,
            'client_secret'     => static::$CLIENT_SECRET,
            'base_url'          => static::$BASE_URL,
            'site_url'          => static::$SITE_URL,
            'authorize_url'     => static::$AUTHORIZE_URL,
            'access_token_url'  => static::$ACCESS_TOKEN_URL,
            'redirect_uri'      => static::$REDIRECT_URI,
            'scopes'            => static::$SCOPES,
            'token_param_name'  => static::$TOKEN_PARAM_NAME,
            'response_type'     => 'code',
            'grant_type'        => 'authorization_code',
            'access_token'      => null,
            'refresh_token'     => null,
            'code'              => null,
            'developer_mode'    => static::$DEVELOPER_MODE,
        ), $custom);
    }

}
