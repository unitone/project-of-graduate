<?php

require_once( 'load.php' );

function find_friend_by( $action = 'id', $user_id = 0 ) {
	global $db;

	if ( ! $user_id )
		$user_id = $_COOKIE['key'];

	$friends = $db->get_results( $db->prepare( "SELECT friendid FROM friend WHERE userid=%d", $user_id ), ARRAY_N );
	
}

$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';
if ( isset( $_POST['do'] ) )
	$action = $_POST['do'];
if ( ! in_array( $action, array( 'post', 'update', 'delete', 'comment', 'delcomment' ) ) )
	$action = '';

switch( $action ) {
case 'post':
	add_feel( $_POST['feel'] );
	break;
case 'delete':
	delete( (int)$_REQUEST['id'], (int)$_REQUEST['type'] );
	break;
case 'comment':
	add_comment( $_POST['key'], $_POST['comment'], $_POST['type'] );
	break;
case 'delcomment':
	delete_single_comment( (int)$_REQUEST['commentid'] );
	break;
default:
	break;
}

?>
<html>
<head>
	<title>首页</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
	<link href="contents/css/global.css" rel="stylesheet" type="text/css" >
	<link href="contents/css/home.css" rel="stylesheet" type="text/css" >
	<script type="text/javascript" charset="utf-8" src="./contents/js/seemore.js"></script>
</head>
<body>
<div id="wrap">
<?php 
show_nav();
?>

<div class="left-sidebar left">
	<div class="personal-information">
		<a href="personal.php?action=img"><img src="<?php if ( is_null($user->photo) ) { echo 'user/image/men_tiny.gif'; } else { echo $user->photo; } ?>" width="48px" height="48px" /></a>
		<div class="personal"><?php echo $user->name; ?></div>
	</div>
	<div class="left-navigation">
		<ul>
			<li><a href="home.php">最新动态</a></li>
			<li><a href="post.php">添加日志</a></li>
		</ul>
	</div>
</div>

<div class="content left">
	<div class="feel-form">
		<form method="post" action="">
			<textarea name="feel" rows="5" cols="50" maxlength="250"></textarea>
			<input type="submit" name="submit" value="发表" />
			<input type="hidden" name="do" value="post" />
		</form>
	</div>
<div id="feel-content">
<?php
show_feel_items();
?>
</div><div class="more"><input id="more" type="button" onclick="see_more()" value="查看更多" /></div>

</div>
<?php
show_footer();
?>
