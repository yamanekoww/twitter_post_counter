<?php
// ini_set("display_errors", On);
// error_reporting(E_ALL);

session_start();

require_once 'prg/config.php';
require_once 'prg/lib/twitteroauth/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;


$user_icon_html = "";
$twitter_profile_image_url_https = "";



//アクセストークンを取得済みの場合は設定,ない場合は認証画面へ遷移
if(isset($_SESSION['access_token'])){
	$access_token = $_SESSION['access_token'];
	$twitter_user_id = $_SESSION['user_id'];
	$twitter_screen_name = $_SESSION['screen_name'];

	//OAuthトークンとシークレットも使って TwitterOAuth をインスタンス化
	$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

}else{
	oauth();
}


//ユーザーアイコンを取得 + アクセストークンの生存確認 ない場合は認証画面へ遷移
$result = $connection->get('users/show',array("user_id" => $twitter_user_id));
if(!array_key_exists("errors", $result)){
	$twitter_profile_image_url_https = $result->profile_image_url_https;
	$user_icon_html = '<a href="https://twitter.com/"><img src="' . $twitter_profile_image_url_https . '"></a>';

}else{
	oauth();
}

function oauth(){
	header( 'location: oauth.php' );
}
?>
<!DOCTYPE html>
<!--[if IE 8]><html class="lt-ie10 lt-ie9"><![endif]-->
<!--[if IE 9]><html class="lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="ja"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>サイトタイトル</title>
	<meta name="description" content="概要文">
	<meta name="keywords" content="キーワード">
	<meta name="viewport" content="width=1024">
	<!-- レスポンシブ用 必要に応じて利用
	<meta name="viewport" content="width=device-width,initial-scale=1">
	-->

	<meta property="og:type" content="website">
	<meta property="og:title" content="ページタイトル">
	<meta property="og:site_name" content="サイトタイトル">
	<meta property="og:url" content="ページのURL">
	<meta property="og:description" content="概要文">
	<meta property="og:image" content="facebookに表示する画像">

	<meta name="twitter:card" content="カードタイプ">
	<meta name="twitter:site" content="@アカウント名">
	<meta name="twitter:title" content="ページタイトル">
	<meta name="twitter:description" content="概要文">
	<meta name="twitter:image" content="twitterに表示する画像">

	<link rel="canonical" href="ウェブサイトURL（トップページのみでOK）">

	<link rel="stylesheet" href="assets/css/common.css">
	<link rel="stylesheet" href="assets/css/unique/top.css">
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="apple-touch-icon" sizes="152x152" href="apple_touch_icon.png">

	<!--[if lt IE 9]>
	<script src="assets/lib/html5shiv/html5shiv-printshiv.min.js"></script>
	<![endif]-->
</head>

<body>
	<noscript>JavascriptがOFFのため正しく表示されない可能性があります。</noscript>

	<!-- [ WRAP ] -->
	<div id="l-wrap">
		<!-- [ GLOBAL HEADER ] -->
		<div id="l-header">
ヘッダー<br>
<?php echo $user_icon_html; ?>
		</div>
		<!-- [ /GLOBAL HEADER ] -->

		<!-- [ CONTENT ] -->
		<div id="l-content">
			<form action="send.php" method="post">
				<textarea name="tweet">#デフォルトハッシュタグ その他テキスト</textarea>
				<button>ツイートする</button>
			</form>

		</div>
		<!-- [ /CONTENT ] -->

		<!-- [ GLOBAL FOOTER ] -->
		<div id="l-footer">
フッター
		</div>
		<!-- [ /GLOBAL FOOTER ] -->

	</div>
	<!-- [ WRAP ] -->

	<script src="assets/js/library.js"></script>
	<script src="assets/js/common.js"></script>
	<script src="assets/js/analytics.js"></script>
</body>
</html>
