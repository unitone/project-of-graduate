<?php

function add_comment( $id, $comment, $type ) {
	global $db, $user;
	
	$data = array();
	$data['content'] = $comment;
	$data['type'] = $type;
	$data['postid'] = $id;
	$data['userid'] = $user->id;
	$data['date'] = get_current_time();
	
	$db->insert( 'comment', $data );
}

function delete_comment( $id ) {
	global $db;
		
	$db->query( $db->prepare( "DELETE FROM comment WHERE postid = %d", $id ) );
}

function comment_show( $post ) {
	global $db;
	
	$results = $db->get_results( $db->prepare( "SELECT * FROM comment WHERE postid = %d ORDER BY 'date' DESC", $post['id'] ), ARRAY_A );
	
	if ( empty( $results ) )
		return;	
	
	$output = '<div class="comment_rely">';
	foreach( $results as $result ) {
		$user = get_user_by_id( $result['userid'] );
		$output .= '<div class="comment clear"><dl>';
		$output .= '<dt><img src="user/image/men_tiny.gif" style="width: 30px;height: 30px;margin-right: 5px"></dt>';
		$output .= '<dd>' . $user['name'] . ' : ' . $result['content'] . '</dd>';
		$output .= '<dd><span>' . $result['date'] . '</span> - <a href="?action=delete&type=' .$result['type']. '&id=' . $result['id'] . '">删除</a></dd>';
		$output .= '</dl></div>';
	}
	$output .= '</div>';
	echo $output;
}

function has_comment( $post ) {
	global $db;
	
	$bool = $db->get_var( $db->prepare( "SELECT id FROM comment WHERE postid = %d", $post['id'] ) );
	if ( $bool )
		return true;
	return false;
}

?>