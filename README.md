# Sample code to use the Flattr v2 web API

to get up and running:

the oauth2client uses httpconnection and httpresponse which wrapps the curl php bindings.

if you are using a server with apt you can install it by

    apt-get install php5-curl

## public api call

    $client = new OAuth2Client(array('base_url' => 'https://api.flattr.com/rest/v2'));
    $thing = $client->getParsed('/things/423405/');
    var_dump($thing); // array

## minimal sample code

create an API key at https://flattr.com/apps
make sure you add a correct callback\_url it should be something like http://url-to-your-vhost/callback.php  
Copy /config.template.php to /config.php and enter your api credentials.  
Point a apache/lighttpd/nginx vhost to the minimal/ directory and restart.  
Open http://url-to-your-vhost

## connect with flattr sample

sample app that implements a connect with flattr (based on sessions, no database, using coltrane framework)

see connect\_with\_flattr/

----
Documentation at [http://developers.flattr.net/](http://developers.flattr.net/)  
Questions: [StackOverflow](http://stackoverflow.com/questions/tagged/flattr)  
Feedback: [twitter](https://twitter.com/#!/flattr)  
