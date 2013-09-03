<?php
	
function send_message( $title, $content, $toid ) {
	global $db, $user;
	
	if ( strlen( $title ) <= 0 || strlen( $content ) <= 0 )
		return false;
	
	$data = array();
	$data['fromid'] = $user->id;
	$data['toid'] = $toid;
	$data['title'] = $title;
	$data['content'] = $content;
	$data['date'] = get_current_time();
	
	$db->insert( 'message', $data );
	return true;
}

function delete_message( $id ) {
	global $db, $user;
	
	$db->query( $db->prepare( "DELETE FROM message WHERE id = %d AND toid = %d", $id, $user->id ) );
}

function message_count() {
	global $db, $user;
	
	$results = $db->get_results( $db->prepare( "SELECT COUNT(*) FROM message WHERE toid = %d", $user->id ), ARRAY_A );
	if ( empty( $results ) )
		return 0;
	
	return count( $results );
}

function show_message_list() {
	global $db, $user;
	
	$results = $db->get_results( $db->prepare( "SELECT * FROM message WHERE toid = %d", $user->id ), ARRAY_A );
	if ( empty( $results ) )
		return;
		
	$output = '<div class="message-list"><table cellspacing="0">';
	foreach ( $results as $result ) {
		$fromuser = get_user_by_id( $result['fromid'] );
		$output .= '<tr><td class="td-title"><a href="?action=show&id=' . $result['id'] . '">' . $result['title'] . '</a></td>';
		$output .= '<td class="td-delete"><a href="?action=delete&id=' . $result['id'] . '">删除</a></td></tr>';
	}
	$output .= '</table></div>';
	echo $output;
}

function show_message( $message_id ) {
	global $db,$user;
	
	$result = $db->get_results( $db->prepare( "SELECT * FROM message WHERE id = %d AND toid = %d LIMIT 1", $message_id, $user->id ), ARRAY_A );

	if ( empty( $result[0] ) )
		return;
	
	$from_user = get_user_by_id( $result[0]['fromid'] );	
	
	$output = '<div class="message-show">';
	$output .= '<div class="message-title"><h1>' . $result[0]['title'] . '</h1></div>';
	
	$output .= '<div class="message-meta"><p>来自: ' . $from_user['name'] . ' - 时间: ' . $result[0]['date'] . ' - <span><a href="?action=delete&id=' . $result[0]['id'] . '">删除</a></span></p></div>';
	$output .= '<div class="message-content">' . $result[0]['content'] . '</div>';
	$output .= '</div>';
	echo $output;
}

function contract_people( $toid ) {
	if ( ! is_null( $toid ) ) {
		$friend = get_user_by_id( $toid );
		echo '<select name="to"><option value="' . $friend['id'] . '">' . $friend['name'] . '</optin></select>';
		return;
	}

	$friend_id = get_all_friend_id();
	if ( empty( $friend_id ) ) {
		echo '<input type="text" name="to" />';
	} else {
		$output = '<select name="to">';
		foreach ( $friend_id as $id ) {
			$friend = get_user_by_id( $id );
			$output .= '<option value="' .$friend['id'] . '">' . $friend['name'] . '</option>';
		}
		$output .= '<select>';
		echo $output;
	}
}

function message_form( $toid = null ) { ?>
<form method="post" action="" onsubmit="return checkform()">
	<table>
		<tr><td><label>收件人</label></td><td><?php contract_people( $toid ); ?></td></tr>
		<tr><td><label>主题</label></td><td><input type="text" name="title" style="width: 380px" /></td></tr>
		<tr><td><label>内容</label></td><td><textarea name="content" cols="45" rows="15"></textarea></td></tr>
		<tr><td></td><td><input type="submit" name="submit" value="发送" /></td></tr>
	</table>
	<input type="hidden" name="do" value="post" />
</form>
<script type="text/javascript">
function checkform() {
	var title = document.getElementById("title").value;
	var content = document.getElementById("content").value;

	if ( title == "" ) {
		alert( "请输入标题" );
		return false;
	}
	if ( content == "" ) {
		alert( "请输入内容" );
		return false;
	}
	return true;
}
</script>
<?php
}
	
?>