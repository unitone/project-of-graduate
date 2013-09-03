<?php

function has_category( $cate ) {
	global $db;

	return $db->get_var( $db->prepare( "SELECT id FROM category WHERE title = %s", $cate ) );
}

function add_category( $name ) {
	global $db, $user;
	
	$data = array();
	$data['title'] = $name;
	$data['userid'] = $user->id;
	
	$db->insert( "category", $data );
	return $name;
}

function get_current_post( $id ) {
	global $db;
	
	$post = $db->get_results( $db->prepare( "SELECT * FROM post WHERE id = %d", $id ), ARRAY_A );
	
	if ( empty( $post ) )
		return null;
		
	return $post[0];
}

function show_post_table() {
	global $db, $user;
	
	$post = $db->get_results( $db->prepare( 'SELECT * FROM post WHERE type = 1 AND userid = %d', $user->id ), ARRAY_A );
	
	if ( empty( $post ) )
		return;
?>
	<table>
		<tr><th>标题</th><th>分类</th><th>发布时间</th></tr>
<?php
	foreach ( $post as $p ) {
?>
		<tr><td><?php echo $p['title']; ?></td><td><?php echo get_category_by_id( $p['categoryid'] ); ?></td><td><?php echo $p['date']; ?></td></tr>	
<?php
	}
	echo '</table>';
}

// type = 1
function write_post( $title, $content, $categoryid, $limit = 2 ) {
	global $db;

	$userid = isset( $_COOKIE['key'] ) ? (int)$_COOKIE['key'] : 0 ;

	if ( ! isset( $userid ) )
		return;
	
	if ( ! strlen( $title ) || ! strlen( $content ) )
		return;
	
	if ( isset( $_POST['newcategory'] ) && ! is_null( $_POST['newcategory'] ) ) {
		$category = add_category( $_POST['newcategory'] );
		$categoryid = get_category_by_name( $category );
	}
	
	$date = date( 'y-m-d h:i:s', time() );
	
	$db->query( $db->prepare( "INSERT INTO post(title,content,userid,categoryid,type,authority,date) VALUES(%s,%s,%d,%d,%d,%d,NOW())", $title, $content, $userid, $categoryid, 1, $limit ) );
	
}

function update_post( $id ) {
	global $db, $user;

	$data = &$_POST;
	$datas = array();

	if ( isset( $data['title'] ) )
		$datas['title'] = $data['title'];
	if ( isset( $data['content'] ) )
		$datas['content'] = $data['content'];
	if ( isset( $data['category'] ) )
		$datas['categoryid'] = $data['category'];
	if ( isset( $data['authority'] ) )
		$datas['authority'] = $data['authority'];

	$where = array();
	$where['id'] = $id;
	$where['userid'] = $user->id;		

	$db->update( "post", $datas, $where );
}

function delete( $id, $type ) {
	global $db, $user;
	
	$db->query( $db->prepare( "DELETE FROM post WHERE id = %d AND userid = %d AND type = %d", $id, $user->id, $type ) );
	
	delete_comment( $id );
}

// type = 2
function add_feel( $content, $user_id = 0 ) {
	global $db;

	if ( ! strlen( $content ) )
		return;

	if ( ! $user_id )
		$user_id = $_COOKIE['key'];

	$db->query( $db->prepare( "INSERT INTO post(userid,content,type,authority,date) VALUES(%d,%s,%d,%d,NOW())", $user_id, $content, 2, 2 ) );
}

function get_categories() {
	global $db, $user;

	$results = $db->get_results( $db->prepare( "SELECT * FROM category WHERE userid = %d", $user->id ), ARRAY_A );
	
	return $results;
	
}

function bind_category( $cateid = null ) {

	$categories = get_categories();
	
	$output = '<select name="category">';
	
	$output .= '<option value="0">默认分类</option>';
	
	if ( ! empty( $categories ) ) {
		foreach( $categories as $category ) {
			if ( isset( $cateid ) && $cateid == $category['id'] ) {
				$output .= '<option value="' . $category['id'] . '" selected>' . $category['title'] . '</option>';
			} else {
				$output .= '<option value="' . $category['id'] . '">' . $category['title'] . '</option>';
			}
		}
	}
	
	$output .= '</select>';
	echo $output;
}

function get_category_by_id( $id ) {
	global $db;
	
	$cate = $db->get_results( $db->prepare( "SELECT * FROM category WHERE id = %d LIMIT 1", $id ), ARRAY_A );
	if ( empty( $cate ) ) {
		echo '默认分类';
	} else {
		echo $cate[0]['title'];
	}
}

function get_category_by_name( $name ) {
	global $db, $user;
	
	$cate = $db->get_results( $db->prepare( "SELECT * FROM category WHERE title = %s AND userid = %d LIMIT 1", $name, $user->id ), ARRAY_A );
	if ( empty( $cate ) ) {
		return '默认分类';
	} else {
		return $cate[0]['id'];
	}
}

function show_feel_items( $where = 'home' ) {
	global $db, $user;
	$page = isset( $_REQUEST['p'] ) ? $_REQUEST['p'] : 1;
	$total = 5;
	
	$query = 'SELECT * FROM post WHERE userid = ' . $user->id;
	
	$friends = get_friends();

	if ( $where === 'home' && ! empty( $friends ) ) {
		$query .= ' AND authority = 2';
		foreach ( $friends as $friend ) {
			$query .= ' OR userid = ' . $friend['friendid'];
		}
	}
	
	$limit = ' LIMIT ' . $total * ($page - 1) . ', ' . $total * $page;
	$query .= " ORDER BY `date` DESC";
	$query .= $limit;
	
	
	$posts = $db->get_results( $db->prepare( $query ), ARRAY_A );

	if ( ! is_null( $posts ) ) {
		echo '<div class="feel">';
		foreach ( $posts as $post ) {
			show_post_item( $post );
			comment_show( $post );	
			postl_comment_form( $post);
		}
		echo '</div>';
	} else {
		return;
	}
}

function show_post_item( $post ) { 
	global $user;
	
	$post_user = get_user_by_id( $post['userid'] ); ?>

		<p class="feel-user"><a href="user.php?sid=<?php echo $post_user['id']; ?>" target="_blank"><?php echo $post_user['name']; ?></a> - <span><?php echo $post['date']; ?></span>
<?php
		if ( $post['userid'] == $user->id )
			echo '<span> - <a href="?action=delete&type=' . $post['type'] . '&id=' . $post['id'] . '">删除</a></span>';
?>		
		</p>
<?php
	if ( ! empty( $post['title'] ) )
			echo '<p><a href="display.php?id=' . $post['id'] . '">' . $post['title'] . '</a></p>';

?>

	<p>
	<?php
		$str = $post['content'];
		if ( strlen( $str ) > 200 ) {
			$str = strip_tags( $str );
			$str = mb_substr( $str, 0, 100, 'utf-8' );
			$str .= '...';
		}
		echo $str;
	?>
	</p>
<?php

}

function postl_comment_form( $post, $rows = 1, $cols = 50, $maxlength = 200 ) { 
?>
<div class="feel-comment-form">
	<form method="post" action="">
		<textarea name="comment" rows="<?php echo $rows; ?>" cols="<?php echo $cols; ?>" maxlength="<?php echo $maxlength; ?>"></textarea>
		<input type="submit" value="回复" />		
		<input type="hidden" name="type" value="<?php echo $post['type']; ?>" />
		<input type="hidden" name="do" value="comment" />
		<input type="hidden" name="key" value="<?php echo $post['id']; ?>" />
	</form>
</div>
<?php
}

?>