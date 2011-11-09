# Sample code to use the Flattr v2 web API

to get up and running:

the oauth2client uses httpconnection and httpresponse which wrapps the curl php bindings.

if you are using a server with apt you can install it by

    apt-get install php5-curl

point a vhost to the public/ directory.

create an API key at https://flattr.com/apps

copy /config.template.php to /config.php and enter your api credentials

visit http://localhost or whatever domain you assigned to your vhost.

----
documentation at [http://developers.flattr.net/v2](http://developers.flattr.net/v2)
