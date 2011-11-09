<?php
class ConfigFlattr extends ConfigBase
{
    public static function up()
    {
        self::$CLIENT_ID = 'your client id; get one at https://flattr.com/apps';
        self::$CLIENT_SECRET = 'your client secret;';

        self::$LOGFILE = '/tmp/flattr-client.log'; // debug log

        self::$SITE_URL = 'https://flattr.com';
        self::$BASE_URL = 'https://api.flattr.com/rest/v2';
        self::$AUTHORIZE_URL = self::$SITE_URL . '/oauth/authorize';
        self::$ACCESS_TOKEN_URL = self::$SITE_URL . '/oauth/token';

        self::$REDIRECT_URI = 'http://'.$_SERVER['SERVER_NAME'] . dirname($_SERVER['SCRIPT_NAME']) . '/callback.php';
        self::$DEVELOPER_MODE = false; // more extensive logging using slog; turns of SSL check DANGEROUS!!!
        self::$SCOPES = 'thing flattr';
    }
}

