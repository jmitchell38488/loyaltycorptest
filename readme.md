## Laravel API test application

This is a Mail Chimp API v3 API application using Laravel.

### Installation
The vendor path is excluded from the project. You will need to install
the project dependencies before starting the application.

```bash
composer install
```

If composer is not globally installed, you will need to run:

```bash
php /path/to/composer.phar install
```

### Running
This application depends on PHP 5.4.0 for the built-in web server. Alternatively,
you can run this application through a web-server, such as nginx or apache.

To use the php built-in web server, execute the following commands:

```bash
cd loyaltycorptest
php -S localhost:8001 server.php
```

This will start a web server listening on port 8001.


### API Authenticaion
This application has a set-up step before you can use the APIs. You will need to
manually edit `config/mc.php`, changing the following settings using the details
provided by Mail Chimp. If you do not have an account or an API key, you will need
to create one before continuing.

 * `mc_api_user` to your user account name
 * `mc_api_key` to your API key
 * `mc_api_path` to your API path, `https://%s.api.mailchimp.com/3.0/`, where `%s`
  is the final part of your API key, separated by the `-` dash, 
  eg. `abc2123123123-us09` becomes `https://us09.api....`


### API Requests
This application translates API requests to and from Mail Chimp API v3 using RESTful
principles. The authentication is build into the application however, so you won't 
need to sent auth headers with each request.

#### POST/PATCH requests
Make sure that when making requests through Postman, or a web browser, that the request
is sent with the header `Content-Type: application/x-www-form-urlencoded`, otherwise the
request parameters won't be correct intercepted.

### Endpoints
* `/api/lists` \[get, post\] 
* `/api/lists/{listid}` \[get, patch, delete\]
* `/api/lists/{listid}/members` \[get, post\]
* `/api/lists/{listid}/members/{memberid}` \[get, patch, delete\]