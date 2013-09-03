<?php

require_once( 'load.php' );

$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';

if ( $action == 'addgroup' ) {
	add_friend_group( $_POST['groupname'] );
}

if ( $action == 'updategroup' ) {
	update_group( $_POST['friendid'], $_POST['group'] );
}

if ( $action == 'deletegroup' ) {
	delete_group( $_REQUEST['groupid'] );
}

?>
<html>
<head>
	<title>好友</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link href="contents/css/friend.css" rel="stylesheet" type="text/css">
	<link href="contents/css/global.css" rel="stylesheet" type="text/css">
	<script type="text/javascript" src="contents/js/jquery/jquery-1.6.4.js"></script>
	<script type="text/javascript" src="contents/js/jquery/jquery-1.6.4.min.js"></script>
</head>
<body>
<div id="wrap">
<?php 
show_nav();
?>
<div class="left-sidebar left">
	<div class="left-navigation">
		<ul class="CategoryList">
			<li><a href="friend.php">全部好友</a></li>
			<li><a href="friend.php?action=search">寻找好友</a></li>
		</ul>


		<?php
		show_friend_groups();
		?>
	<div class="clear">
	<h1>创建分组</h1>
	<form method="post" action="?action=addgroup">
		<ul class="create-group">
			<li><input type="text" name="groupname" /></li>
			<li><input type="submit" name="do" value="创建"/></li>
		</ul>
	</form>
	</div>
	</div>
</div>

<div class="content">
<?php
switch( $action ) {
case 'add':
	add_friend_step( $_REQUEST['id'] );
	echo '<p>添加好友信息己发送，请等待对方确认！</p>';
	break;

case 'search':
	search_choose_box();
	break;
case 'result':
	$q = isset( $_POST['q'] ) ? $_POST['q'] : '';
	SearchAndDisplayResult( $q );
	break;
case 'friend_valution':
	if ( isset( $_REQUEST['want'] ) && $_REQUEST['want'] == 'yes' ) {
		add_friend( $_REQUEST['id'] );
		echo '<p>添加好友成功</p>';
		send_message( "好友申请已通过", "你的好友申请已通过", $_REQUEST['id'] );
	}
	if ( isset( $_REQUEST['want'] ) && $_REQUEST['want'] == 'no' ) {
		add_friend( $_REQUEST['id'] );
		echo '<p>添加好友不通过</p>';
		send_message( "好友申请不通过", "你的好友申请不通过", $_REQUEST['id'] );
	}
	break;
case 'delete':
	if ( isset( $_REQUEST['id'] ) ) {
		delet_friend( $_REQUEST['id'] );
	}
default:
	show_friend_list();
	break;
}
?>
</div>
<?php show_footer(); ?>

