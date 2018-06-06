# Router
Route the URL variables to your project

## Install
Add the following to your .htaccess file in order to redirect everything to the index.php file. 
```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-s
RewriteRule ^(.*)$ index.php
```
##Build the URL
Define `PARSE_URL` with your domain name:
```
define('PARSE_URL','https://www.example.gr')
```
Use the `url()` function with as many attributes you want.
```
$url = Parse::url('class', 'method', ['var'=>'foo','foo'=>'boo']);
```
the above example will output
```
https://www.example.gr/class/method/var=foo&foo=boo
```
##Parse variables from a URL
Contract the `Parse` object passing the URL and use the `get` method to get an array of variables.
```
$array = (new Parse($url))->get();
```
If we use the previous URL, this example will output
```
Array
(
    [0] => class
    [1] => method
    [2] => Array
        (
            [var] => foo
            [foo] => boo
        )

)
```
You may also pass which key your want get
```
$var = (new Parse($url))->get(1);
```
If we use the previous URL, this example will output a string `method` or `false` if there is no value