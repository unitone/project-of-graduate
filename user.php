<?php

require_once( 'load.php' );

//if ( ! isset( $_REQUEST['sid'] ) )
//	header( 'Location: home.php' );

$myuser = get_user_by_id( $_REQUEST['sid'] );

?>
<html>
<head>
	<title>个人首页</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
	<link href="contents/css/global.css" rel="stylesheet" type="text/css" >
	<link href="contents/css/user.css" rel="stylesheet" type="text/css" >
</head>
<body>
<div id="wrap">
<?php 
show_nav();
?>
<div class="content left">
	<div class="img"><img src="<?php if ( is_null( $myuser['photo'] ) ) { echo 'user/image/men_tiny.gif'; } else { echo $myuser['photo']; } ?>" width="48px" height="48px" /></div>
	<div class="information">
		<table>
			<tr><td>姓名：</td><td><?php echo $myuser['name']; ?></td></tr>
			<tr><td>系别：</td><td><?php echo $myuser['class']; ?></td></tr>
			<tr><td>年级：</td><td><?php echo $myuser['grade']; ?></td></tr>
		</table>
	</div>
</div>
<?php show_footer(); ?>