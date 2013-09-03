<?php

function show_header( $title = '', $css = '') {

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" >
<link href="contents/css/<?php echo $css; ?>.css" rel="stylesheet" type="text/css" >
<link href="contents/css/global.css" rel="stylesheet" type="text/css" >
</head>
<body>
<div id="wrap">
<?php
}

function show_nav() { ?>
<div class="nav">
	<ul>
		<li><a href="home.php">首页</a></li>
		<li><a href="personal.php">个人主页</a></li>
		<li><a href="friend.php">好友</a></li>
		<li><a href="message.php">信息</a></li>
	</ul>
</div><?php
}

?>