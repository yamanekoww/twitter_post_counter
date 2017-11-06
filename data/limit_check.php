<?php
ini_set("display_errors", On);
error_reporting(E_ALL);

session_start();

require_once 'common/config.php';
require_once 'lib/twitteroauth/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;


//セッションに入れておいたさっきの配列
$access_token = $_SESSION['access_token'];

//OAuthトークンとシークレットも使って TwitterOAuth をインスタンス化
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

$check = $connection->get("application/rate_limit_status");
echo "<pre>";
print_r ($check);
echo "</pre>";
