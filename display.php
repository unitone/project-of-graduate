<?php

include( 'load.php' );

if ( isset ( $_REQUEST['action'] ) && $_REQUEST['action'] == 'delcomment' ) {
	delete_single_comment( $_REQUEST['commentid'] );
}

function post_display( $id ) { 
	global $user;
	$post = get_current_post( $id );

?>

<div class="post-title">
		<h1><?php echo $post['title']?></h1>
		<p><?php echo $post['date']; ?> | 分类 : <?php get_category_by_id( $post['categoryid'] ); ?></p>
		<?php if ( $post['userid'] == $user->id ) { ?><p><a href="post.php?action=update&id=<?php echo $id; ?>">更新</a></p><?php } ?>
	</div>
	<div class="content">
		<p><?php echo $post['content']; ?></p>
	</div>	
<?php
	if ( has_comment( $post ) ) {
		comment_show( $post );
	}

	postl_comment_form( $post, 5 );
}

$id = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : 0;

if ( isset( $_POST['do'] ) ) {
	add_comment( $_POST['key'], $_POST['comment'], $_POST['type'] );
}

?>
<html>
<head>
	<title>首页</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
	<link href="contents/css/global.css" rel="stylesheet" type="text/css" >
	<link href="contents/css/display.css" rel="stylesheet" type="text/css" >
</head>
<body>
<div id="wrap">
<?php 
	if ( ! $id ) {
		die( '<h2>出错啦!</h2>' );
	}
	show_nav();
?>
<div class="contents">
<?php
	post_display( $id );
?>
</div>
<?php show_footer(); ?>