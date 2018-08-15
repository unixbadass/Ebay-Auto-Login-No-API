<?PHP
require("class/class.php");

$curl = new Curl(md5(1));
$referer = 'http://ebay.com';
$formPage = 'http://signin.ebay.com/aw-cgi/eBayISAPI.dll?SignIn';


$curl->curl_cookie_set($referer);

$data = $curl->get_form_fields($formPage);
$data['userid'] = ""; // Email or Username
$data['pass'] = ""; // Password here
$data['UsingSSL'] = '0';

$formLogin = "https://signin.ebay.com/ws/eBayISAPI.dll?co_partnerId=2&amp;siteid=3&amp;UsingSSL=0";
$curl->curl_post_request($referer, $formLogin, $data);

$result = $curl->show_page('http://my.ebay.com/ws/eBayISAPI.dll?MyeBay');
echo str_replace('<script', '<', $result);
