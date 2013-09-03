<?php

session_start();

$_SESSION['login'] = true;

require_once( 'load.php' );

if ( isset( $_COOKIE['key'] ) )
	header( 'Location: home.php' );
	
$message = '';

function signon( $user_email, $user_password ) {
	global $db;

	if ( is_null( $user_email ) || is_null( $user_password ) )
		return;

	$user_id = $db->get_var( $db->prepare( "SELECT id FROM user WHERE email = %s AND password = %s LIMIT 1", $user_email, $user_password ) );
	if ( $user_id ) {
		setcookie( "key", $user_id, time() + 86400 );
		header( 'Location: home.php' );
		exit();
	} else {
		return true;
	}
}

if ( is_submit() && submit_action( 'signon' ) ) {

	$bool = signon( $_POST['user_email'], $_POST['user_password'] );
	
	if ( $bool ) {
		$message = '<div class="message">没有这个用户</div>';
	}
}

?>
<html>
<head>
	<title>好友</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
</head>
<body>
<style>

body {
	background-color: #FBFBFB;
	margin: 0;
	font-size: 12px;
	font-family: Tahoma,Microsoft YaHei,Verdana, Arial, Helvetica, sans-serif;	
	color: #666666;
	text-align: center;
	background-color: #FFFFFF;
}

#wrap {
	width: 978px;
	margin: 0 auto;
}

.login-form {
	background-color: #FFFFFF;
	width: 180px;
	padding: 20px;
	margin: 200px auto;
	border: 2px #eee solid;
	text-align: left;
}

.login {
	width: 160px;
	margin: 0 auto;
	padding: 0 auto;
}

p a {
	font-size: 12px;
	text-decoration: none;
}

p a:hover {
	text-decoration: underline;
}

</style>
<div id="wrap">
<div class="login-form">	
	<form method="post" action="">		
		<div class="login">
		<p>漓江学院交友网登陆</p>
		<p><label for="user_email" >电子邮件</label><br /><input type="text" id="user_email" name="user_email" /></p>
		<p><label for="user_password" >密码</label><br /><input type="password" id="user_password" name="user_password" /></p>
		<p><input type="submit" name="submit" value="登陆" /><a href="register.php">注册</a></p>
		<input type="hidden" name="do" value="signon" />
		<?php echo $message; ?>
		</div>
	</form>
</div>
</div>
</body>
</html>