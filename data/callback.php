<?php
// ini_set("display_errors", On);
// error_reporting(E_ALL);

session_start();

require_once 'common/config.php';
require_once 'lib/twitteroauth/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

//login.phpでセットしたセッション
$request_token = [];  // [] は array() の短縮記法。詳しくは以下の「追々記」参照
$request_token['oauth_token'] = $_SESSION['oauth_token'];
$request_token['oauth_token_secret'] = $_SESSION['oauth_token_secret'];

//Twitterから返されたOAuthトークンと、あらかじめlogin.phpで入れておいたセッション上のものと一致するかをチェック
if (isset($_REQUEST['oauth_token']) && $request_token['oauth_token'] !== $_REQUEST['oauth_token']) {
    die( 'Error!' );
}

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $request_token['oauth_token'], $request_token['oauth_token_secret']);

//アクセストークン所得
// $access_token = $tw->getAccessToken($_REQUEST['oauth_verifier']);
$_SESSION['access_token'] = $connection->oauth("oauth/access_token", array("oauth_verifier" => $_REQUEST['oauth_verifier']));

$listbody = $connection->getLastBody();
$_SESSION['user_id'] = $listbody['user_id'];
$_SESSION['screen_name'] = $listbody['screen_name'];
// echo "<pre>";
// print_r($connection);
// echo "</pre>";
// print_r($connection->getLastBody());
// echo $listbody['user_id'];


//マイページへリダイレクト
header( 'location: tweet.php' );