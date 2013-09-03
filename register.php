<?php

session_start();

$_SESSION['register'] = true;

include( 'load.php' );

function signup( $name, $email, $password, $class, $grade ) {
	global $db;

	if ( ! is_email( $email ) )
		return;

	$args = array();
	$args['name'] = $name;
	$args['email'] = $email;
	$args['password'] = $password;
	$args['class'] = $class;
	$args['grade'] = $grade;

	$db->insert( 'user', $args );
}
$message = '';
if ( is_submit() && submit_action( 'signup' ) ) {

	if ( is_email_existed($_POST['user_email']) ) {
		$message = '<div class="message"><p>该电子邮件已被注册...</p></div>';
	} else {
		signup($_POST['user_name'], $_POST['user_email'], $_POST['user_password'], $_POST['user_class'], $_POST['user_grade']);
		$message = '<div class="message"><p>用户注册成功</p></div>';
	}
}

?>

<style>

.signup-form {
	width: 300px;
	padding: 10px;
	text-align: right;
	border: 1px solid #eee;
}

#wrap {
	width: 50%;
	margin: 0 auto;
}

span {
	color: red;
	/*display: none;*/
}

</style>
<html>
<head>
	<title>首页</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
	<link href="contents/css/global.css" rel="stylesheet" type="text/css" >
	<link href="contents/css/register.css" rel="stylesheet" type="text/css" >
</head>
<body>
<div id="wrap">
<div class="signup-form">
<h2>新用户注册</h2>
<?php
	echo $message;
?>
	<form method="post" action="" onsubmit="return checkform()">
		<p><label for="user_name" >姓名</label><input type="text" id="user_name" name="user_name" maxlength="10"></p>
		<span id="name_input_msg" style="display:none">请输入姓名</span>
		<p><label for="user_email" >电子邮件</label><input type="text" id="user_email" name="user_email" maxlength="70"></p>
		<span id="email_input_msg" style="display:none">请输入邮件</span>
		<p><label for="user_password" >密码</label><input type="password" id="user_password" name="user_password" maxlength="32"></p>
		<span id="password_input_msg" style="display:none">请输入密码</span>
		<p><label for="user_class" >系别</label>
		<select id="user_class" name="user_class">
			<option>理学系</option>
			<option>外语系</option>
			<option>经管系</option>
			<option>艺术系</option>
		</select></p>
		<p><label for="user_grade" >年级</label>
		<select id="user_grade" name="user_grade">
			<option>一年级</option>
			<option>二年级</option>
			<option>三年级</option>
			<option>四年级</option>
		</select></p>
		<p><input type="submit" name="submit" value="注册" /></p>
		<input type="hidden" name="do" value="signup" />
	</form>
</div>
</div>
<script type="text/javascript">

function checkform() {
	var name = document.getElementById("user_name").value;
	var email = document.getElementById("user_email").value;
	var password = document.getElementById("user_password").value;

	if ( name == "" ) {
		alert( "请输入姓名" );
		return false;
	}
	if ( email == "" ) {
		alert( "请输入电子邮件" );
		return false;
	}
	if ( password == "" ) {
		alert( "请输入密码" );
		return false;
	}
	return true;
}

</script>
</body>
</html>

