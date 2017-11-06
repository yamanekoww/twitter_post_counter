<?php
// ini_set("display_errors", On);
// error_reporting(E_ALL);

session_start();

require_once 'common/config.php';
require_once 'lib/twitteroauth/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;


$display_html = "";
$err_code_html = "";



if(isset($_POST['tweet'])){
	$tweet = $_POST['tweet'];
}


//セッションに入れておいたさっきの配列
$access_token = $_SESSION['access_token'];

//OAuthトークンとシークレットも使って TwitterOAuth をインスタンス化
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

//ツイート処理
$result = $connection->post('statuses/update', array('status' => $tweet));

// echo "<pre>";
// print_r($result);
// echo "</pre>";


//結果判定
if(array_key_exists("errors", $result)){
	//エラー時
	$err_code = $result->errors[0]->code;
	$err_code_html = "<!--" . $err_code . "-->";
	$display_html = "ツイートの送信に失敗しました。<br>申し訳ありませんがツイートを送信できませんでした。<br>お手数ですが時間をおいて再度お試しください。";

	//http://westplain.sakuraweb.com/translate/twitter/API-Overview/Error-Codes-and-Responses.cgi
	switch ($err_code) {
		case '185':
			//185	User is over daily status update limit
			$display_html = "ツイートの送信に失敗しました。<br>ユーザーの投稿回数が制限を越えたため、投稿できませんでした。";
			break;
		case '187':
			//187	Status is a duplicate
			$display_html = "ツイートの送信に失敗しました。<br>投稿した文章は、このアカウントですでにツイートされています。";
			break;
	}
}else{
	//ツイート成功時
	$user_name = $result->user->name;
	$tweet_id = $result->id;
	$tweet_url = "https://twitter.com/" . $user_name . "/status/" . $tweet_id;
	$display_html = "ツイートが投稿されました<br>ご協力ありがとうございます。 <a href='". $tweet_url ."' target='_blank'>Twitterで確認</a>";



	// auto increment //
	$filename = "count.txt";
	$num = @file_get_contents($filename);

	$file = fopen($filename, "w");
	flock($file, LOCK_EX);

	$nextNum = $num + 1;

	fwrite($file, $nextNum);
	fclose($file);

	// echo "応戦数" . $nextNum;

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
	<meta name="viewport" content="width=1024">
</head>

<body>
	<noscript>JavascriptがOFFのため正しく表示されない可能性があります。</noscript>

	<!-- [ WRAP ] -->
	<div id="l-wrap">
		<!-- [ GLOBAL HEADER ] -->
		<div id="l-header">
		ヘッダー

		</div>
		<!-- [ /GLOBAL HEADER ] -->

		<!-- [ CONTENT ] -->
		<div id="l-content">
			<?php echo $display_html; ?>
			<?php echo $err_code_html; ?>

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
