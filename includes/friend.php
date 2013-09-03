<?php

function add_friend_step( $friend_id ) {
	global $db, $user;
	
	if ( is_friend( $friend_id ) ) {
		echo '<p>该用户已是你的好友！！</p>';
		return;
	}
	
	if ( $friend_id == $user->id ) {
		echo '<p>不能添加自己为好友！！</p>';
		return;
	}	
		
	$title = "添加好友";
	$content = "你好,{$user->name}想添加你为好友，是否同意？";
	$content .= '<a href="friend.php?action=friend_valution&want=yes&id=' . $user->id . '">同意</a> - ';
	$content .= '<a href="friend.php?action=friend_valution&want=no&id=' . $user->id . '">不同意</a>';
	send_message( $title, $content, $friend_id );
}

// defalut $group_id is 0
function add_friend( $friend_id, $group_id = 0 ) {
	global $db, $user;
	
	if ( is_friend( $friend_id, $user->id ) || $friend_id == $user->id )
		return;	
		
	if ( ! isset( $friend_id ) || ! isset( $user ) )
		return;

	$data = array();	
	$data['userid'] = $user->id;
	$data['friendid'] = $friend_id;
	$data['groupid'] = $group_id;	
	$db->insert( "friend", $data );
		
	$data['userid'] = $friend_id;
	$data['friendid'] = $user->id;	
	$db->insert( "friend", $data );
}

function delet_friend( $friend_id ) {
	global $db, $user;		
	
	if ( ! isset( $friend_id ) || ! isset( $user ) )
		return;
		
	$db->query( $db->prepare( "DELETE FROM friend WHERE friendid = %d AND userid = %d", $friend_id, $user->id ) );
}

function add_friend_group( $name ) {
	global $db, $user;

	if ( strlen( $name ) < 0 )
		return;	
		
	$data = array();
	$data['userid'] = $user->id;
	$data['name'] = $name;	
	$db->insert( 'friend_group', $data );
}

function show_friend_list() {
	global $db, $user;		
	
	$query = "SELECT * FROM friend WHERE userid = %d";
	if ( isset( $_REQUEST['sort'] ) && $_REQUEST['sort'] == 'group' && isset( $_REQUEST['id'] ) ) {
		$query .= " AND groupid = " . $_REQUEST['id'];
	}
	$friendid = $db->get_results( $db->prepare( $query, $user->id ), ARRAY_A );
	if ( empty( $friendid ) )
		return;	
	
	$results = array();
	foreach ( $friendid as $fid ) {
		$results[] = get_user_by_id( $fid['friendid'] );
	}
	$friendgroup = get_friend_groups();
	$output = '';
	if( isset( $_REQUEST['sort'] ) && $_REQUEST['sort'] == 'group' && $_REQUEST['id'] != 0 ) {
		echo '<div class="group-c"><a href="?action=deletegroup&groupid=' . $_REQUEST['id'] . '">删除分组</a></div>';
	}
	if ( ! empty( $results ) ) {
		$output .= '<table class="talbe-friend-list">';
		foreach ( $results as $result ) {
			$output .= '<tr><td class="td-img"><img src="user/image/men_tiny.gif"></td>';
			$output .= '<td class="td-name">' . $result['name'] . '</td>';
			$output .= '<td class="td-msg"><a href="message.php?action=post&toid=' . $result['id'] . '">发送信息</a></td>';
			
			$output .= '<td class="td-group">';
			$output .= '<form action="?action=updategroup" method="post">';
			$output .= '<select class="changegroup" name="group" onchange="javascript:this.form.submit()">';
			$friend = get_friend( $result['id'] );
			foreach( $friendgroup as $group ) {
				if ( $friend['groupid'] == $group['id'] ) {
					$output .= '<option value="' . $group['id'] . '" selected>' . $group['name'] . '</option>';
				} else {
					$output .= '<option value="' . $group['id'] . '">' . $group['name'] . '</option>';
				}
			}
			$output .= '</select>';
			$output .= '<input type="hidden" name="friendid" value="' . $result['id'] . '">';
			$output .= '</form></td>';
			
			$output .= '<td class="td-link">' . '<a href="?action=delete&id=' . $result['id'] . '">删除好友</a></td></tr>';
		}
		$output .= '</table>';
	}
	echo $output;
}

function get_all_friend_id() {
	global $db, $user;		
	
	$friendid = $db->get_results( $db->prepare( "SELECT friendid,groupid FROM friend WHERE userid = %d", $user->id ), ARRAY_A );
	
	if ( empty( $friendid ) )
		return null;	
	
	$return_val = array();
	foreach( $friendid as $friend ) {
		$return_val[] = $friend['friendid'];
	}

	return $return_val;
}

function get_friends() {
	global $db, $user;	
	
	$result = $db->get_results( $db->prepare( "SELECT * FROM friend WHERE userid = %d", $user->id ), ARRAY_A );
	return $result;
}

function get_friend( $id ) {
	global $db, $user;	
	
	$result = $db->get_results( $db->prepare( "SELECT * FROM friend WHERE userid = %d AND friendid = %d", $user->id, $id ), ARRAY_A );
	return $result[0];
}

function show_friend_groups() {
	global $db, $user;		
	
	$group = array();
	$group[0]['id'] = 0;
	$group[0]['name'] = "默认分类";
	$groups = $db->get_results( $db->prepare( "SELECT * FROM friend_group WHERE userid = %d", $user->id ), ARRAY_A );	
	
	$results = array_merge( $group, $groups );
	$output = '<h1>好友分组</h1><ul id="groupList" class="GroupList">';
	foreach ( $results as $result ) {
		$output .= '<li title="' . $result['name'] . '"><a href="?sort=group&id=' . $result['id'] . '">' . $result['name'] . '</a></li>';
	}
	$output .= '</ul>';
	echo $output;
}

function get_friend_groups() {
	global $db, $user;
	
	$group = array();
	$group[0]['id'] = 0;
	$group[0]['name'] = "默认分类";
	$groups = $db->get_results( $db->prepare( "SELECT * FROM friend_group WHERE userid = %d", $user->id ), ARRAY_A );
	$results = array_merge( $group, $groups );
	return $results;
}

function update_group( $friendid, $groupid ) {
	global $db, $user;
	
	$data = array();
	$data['groupid'] = $groupid;
	$where = array();
	$where['friendid'] = $friendid;
	$where['userid'] = $user->id;
	
	$db->update( 'friend', $data, $where );
}

function delete_group( $groupid ) {
	global $db, $user;
	
	$results = $db->get_results( $db->prepare( "SELECT * FROM friend WHERE userid = %d AND groupid = %d", $user->id, $groupid ), ARRAY_A );

	$db->query( $db->prepare( "UPDATE friend SET groupid = 0 WHERE groupid = %d", $groupid ) );
	$db->query( $db->prepare( "DELETE FROM friend_group WHERE id = %d", $groupid ) );
}

function is_friend( $friend ) {
	global $db, $user;	
	
	$bool = $db->get_var( $db->prepare( "SELECT id FROM friend WHERE userid = %d AND friendid = %d", $user->id, $friend ) );	
	
	if ( empty( $bool ) )
		return false;
	return true;
}

function has_friend() {
	global $db, $user;	
	
	$bool = $db->get_results( $db->prepare( "SELECT * FROM friend WHERE userid = %d", $user->id ), ARRAY_A );	
	
	if ( ! empty( $bool ) )
		return true;
	return false;
}

?>