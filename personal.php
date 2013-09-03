<?php
require( 'load.php' );

$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';

?>
<html>
<head>
	<title>个人首页</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
	<link href="contents/css/global.css" rel="stylesheet" type="text/css" >
	<link href="contents/css/home.css" rel="stylesheet" type="text/css" >
	<link href="contents/css/personal.css" rel="stylesheet" type="text/css" >
</head>
<body>
<div id="wrap">
<?php 
show_nav();
?>

<div class="left-sidebar left">
	<div class="personal-information">
		<a href="?action=img"><img src="<?php if ( is_null($user->photo) ) { echo 'user/image/men_tiny.gif'; } else { echo $user->photo; } ?>" width="48px" height="48px" /></a>
		<div class="personal"><?php echo $user->name; ?></div>
	</div>
	<div class="left-navigation">
		<ul>
			<li><a href="personal.php">个人信息</a></li>
			<li><a href="?action=contract">联系信息</a></li>
			<li><a href="?action=interest">兴趣爱好</a></li>
			<li><a href="?action=album">相册</a></li>
			<li><a href="?action=changepassword">修改密码</a></li>
		</ul>
	</div>
</div>

<div class="content left">
<?php

switch( $action ) {
case 'createalbum':
	if ( isset( $_POST['newalbum'] ) && $_POST['newalbum'] != '' ) {
		create_album( $_POST['newalbum'] );
	} else {
		echo '<span style="color:red">相册名不能为空</span>';
	}
case 'deleteblum':
	if ( isset( $_REQUEST['id'] ) ) {
		delete_album( $_REQUEST['id'] );
	}
case 'album':
	show_album();
	break;
case 'deletepic':
	if ( isset( $_REQUEST['pic'] ) ) {
		delete_pic( $_REQUEST['pic'] );
	}
	break;
case 'uploadimg':
	upload_img();
case 'showalbum':
	if ( isset( $_REQUEST['id'] ) ) {
		show_single_album( $_REQUEST['id'] );
	}
	break;
case 'contract':
	show_user_contract();
	break;
case 'interest':
	show_user_interest();
	break;
case 'update':
	update_user_information_contract();
	break;
case 'updateinterest':
	update_user_interest();
	break;
case 'img':
	if ( isset( $_POST['do'] ) && $_POST['do'] == 'update' ) {
		upload_img();
	}
	show_user_img();
	break;
case 'changepassword': 
	if ( isset( $_POST['submit'] ) ) {
		change_password($_POST['oldpassword'], $_POST['password1']);
	}
?>
	<form action="?action=changepassword" method="post" onsubmit="return checkform()">
		<p>旧密码：<input type="password" id="oldpassword" name="oldpassword" /></p>
		<p>新密码：<input type="password" id="password1" name="password1" /></p>
		<p>新密码：<input type="password" id="password2" name="password2" /></p>
		<p><input type="submit" id="submit" name="submit" value="修改" /></p>
	</form>
	<script type="text/javascript">
	function checkform() {
		var oldpassword = document.getElementById("oldpassword").value;
		var password = document.getElementById("password1").value;
		var password2 = document.getElementById("password2").value;

		if ( oldpassword == "" ) {
			alert( "请输入旧密码" );
			return false;
		}
		if ( password == "" ) {
			alert( "请输入密码" );
			return false;
		}
		if ( password2 == "" ) {
			alert( "请输入密码" );
			return false;
		}
		if ( password != password2 ) {
			alert( "两次密码输入不一致" );
			return false;
		}
		return true;
	}
	</script>
<?php
	break;
case 'personal':
default:
	show_user_information();
	break;
}

?>
</div>
<?php show_footer(); ?>

