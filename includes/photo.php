<?php

function show_album() {
	global $db, $user;
	
	$albums = $db->get_results( $db->prepare( "SELECT * FROM album WHERE userid = %d", $user->id ), ARRAY_A );
	
	if ( empty( $albums ) ) { ?>
	
<div id="albumsnofound">
<p>目前你没有相册，是否要创建一个呢？</p>
<p><input type="button" onclick="createalbum()" value="创建新相册" /></p>
</div>
<script type="text/javascript">
		function createalbum() {
		var html = '<div class="createalbum"><form action="?action=createalbum" method="POST">';
		html += '相册名称：<input type="text" id="newalbum" name="newalbum" />';
		html += '&nbsp;<input type="submit" id="submit" name="submit" value="创建" />';
		html += '</form></div>';
		window.albumsnofound.innerHTML += html; 
	}
</script>
<?php
	} else {
		$output = '<div class="createalbum"><form action="?action=createalbum" method="POST"><p>创建新相册：<input type="text" id="newalbum" name="newalbum" />&nbsp;<input type="submit" id="submit" name="submit" value="创建" /></form></div>';
		$output .= '<div class="albums">';
		$output .= '<table><tr><th>相册</th><th>操作</th></tr>';
		foreach ( $albums as $album ) {			
			$output .= '<tr><td><a href="?action=showalbum&id=' . $album['id'] . '">' . $album['name'] . '</a></td><td><a href="?action=deleteblum&id=' . $album['id'] . '">删除</a></td></tr>';
		}
		$output .= '</table></div>';
		
		echo $output;
	}
}

function show_single_album( $id ) {
	global $db;

	$photos = get_pices_from_album( $id );

	if ( empty( $photos ) ) {
		echo '<p>还没有上传相片</p>';
		upload_form( $id );
		return;
	} else {
		upload_form( $id );
	}

	$output = '<div class="show_photo">';
	foreach ( $photos as $photo ) {
		$output .= '<dl><dd><dt><img src="' . $photo['url'] . '"><dt>';
		$output .= '<dd><span style="font-size:18px">' . $photo['name'] . '</span></dd>';
		$output .= '<dd><a href="?action=deletepic&pic=' . $photo['id'] . '&id=' . $id . '">删除</a></dd></dl>';
	}
	$output .= '</div>';
	echo $output;
}

function create_album( $name ) {
	global $db, $user;
	
	if ( strlen($name) < 0 ) {
		echo '<span style="color:red">相册名不能为空</span>';
		return;
	}
	
	$data = array( 'name' => $name, 'userid' => $user->id );
	$db->insert( 'album', $data );
}

function delete_album( $album_id ) {
	global $db, $user;
	
	$pictures = get_pices_from_album( $album_id );
	if ( ! empty( $pictures ) ) {
		foreach ( $pictures as $picture ) {
			delete_pic( $picture['id'] );
		}
	}
	
	$db->query( $db->prepare( "DELETE FROM album WHERE id = %d", $album_id ) );
}

function delete_pic( $pic ) {
	global $db;
	$picture = get_pic( $pic );
	
	$file = ABSPATH . $picture['url'];
	if ( file_exists( $file ) ) {
		@unlink( $file );
	}

	$db->query( $db->prepare( "DELETE FROM photo WHERE id = %d", $pic ) );
}

function get_pices_from_album( $album_id ) {
	global $db;
	return $db->get_results( $db->prepare( "SELECT * FROM photo WHERE albumid = %d", $album_id ), ARRAY_A );
}

function get_pic( $pic ) {
	global $db;
	return $db->query( $db->prepare( "SELECT * FROM photo WHERE id = %d", $pic ), ARRAY_A );
}

?>