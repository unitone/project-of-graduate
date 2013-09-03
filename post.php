<?php

include( 'load.php' );

if ( is_submit() && submit_action( 'post' ) ) {
	write_post( $_POST['title'], $_POST['content'], $_POST['category'], $_POST['authority'] );
}

if ( is_submit() && submit_action( 'update' ) ) {
	update_post( $_REQUEST['id'] );
}

$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : null;

?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
	<link href="contents/css/global.css" rel="stylesheet" type="text/css" >
	<link href="contents/css/post.css" rel="stylesheet" type="text/css" >
	<script type="text/javascript" charset="utf-8" src="./contents/js/ke/kindeditor.js"></script>
	<script>
		KE.show({
			id : 'content',
			imageUploadJson : '../../php/upload_json.php',
			fileManagerJson : '../../php/file_manager_json.php',
			allowFileManager : true,
			afterCreate : function(id) {
				KE.event.ctrl(document, 13, function() {
					KE.util.setData(id);
					document.forms['post-new'].submit();
				});
				KE.event.ctrl(KE.g[id].iframeDoc, 13, function() {
					KE.util.setData(id);
					document.forms['post-new'].submit();
				});
			}
		});
	</script>
</head>
<body>
<div id="wrap">
<?php

function show_post_form( $do = 'post' ) { 
	global $action;
	
	if ( ! is_null( $action ) )
		$do = $action;
		
	$post = null;
	if ( isset ( $action ) )
		$post = get_current_post( $_REQUEST['id'] );

?>
<form name="post-new" action="" method="post">

	<div class="left">
		<h3>标题</h3>
		<p><?php
			if ( ! isset( $post ) ) { ?>
				<input class="text-title" type="text" name="title" maxlength="250" />
			<?php } else { ?>
				<input class="text-title" type="text" name="title" maxlength="250" value="<?php echo $post['title']; ?>" />
			<?php }
		?></p>
		<p>
			<textarea id="content" name="content" cols="90" rows="25" ><?php
				if ( isset( $post ) ) {
					echo $post['content'];
				}
			?></textarea>
		</p>
	</div>

	<div class="sidebar left">
		<ul>
			<li><p>分类</p></li>
				<?php bind_category( $post['categoryid'] ); ?>
			<li>
				<div id="newcategory">
					<a href="#" onclick="add_category()">添加分类</a>
				</div>
			</li>
		</ul>
		<ul>
			<li><p>权限</p></li>
			<li>
				<select name="authority">
					<option value="2" <?php if ( isset( $post['authority'] ) && $post['authority'] == 2 ) echo 'selected'; ?> >公开</option>
					<option value="1" <?php if ( isset( $post['authority'] ) && $post['authority'] == 1 ) echo 'selected'; ?>>私密</option>
				</select>
			</li>
		</ul>
	</div>

	<div class="clear">
		<input type="submit" name="submit" value="发布" />
		<input type="hidden" name="do" value="<?php echo $do; ?>" />
	</div>

</form><?php
}

show_nav();
?>
<div class="content">
<?php
show_post_form();
?>
</div>
<?php show_footer(); ?>
</div>
<script type="text/javascript">
	function add_category() {
		var html = "";
		html += '<input type="text" id="newcategory" name="newcategory" />';
		window.newcategory.innerHTML += html; 
	}
</script>
</body>
</html>