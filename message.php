<?php

include( 'load.php' );

$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';

?>
<html>
<head>
	<title>首页</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
	<link href="contents/css/global.css" rel="stylesheet" type="text/css" >
	<link href="contents/css/message.css" rel="stylesheet" type="text/css" >
</head>
<body>
<div id="wrap">
<?php
show_nav();
?>
<div class="content">
<?php
switch( $action ) {
case 'post':
	$toid = isset( $_REQUEST['toid'] ) ? $_REQUEST['toid'] : null;
?>
<div class="message-post">
	<h1>发送信息</h1>
<?php
	if ( isset( $_POST['do'] ) ) {
		send_message( $_POST['title'], $_POST['content'], $_POST['to']);
		echo '<div class="message-span"><span>发送成功,请等待对方查阅.</span></div>';
	}
	message_form( $toid );
?>
</div>
<?php
	break;
?>
<?php
case 'show':
	show_message( $_REQUEST['id'] );
	break;
case 'delete':
	delete_message( $_REQUEST['id'] );
case 'list':
default:
?>
<div class="message-box">
	<h1>收件箱</h1>
<?php
	get_all_friend_id();
	show_message_list();
?>
</div>
<?php
	break;
}
?>
</div>
<?php show_footer(); ?>
</div>
</body>
</html>