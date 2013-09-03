<?php

function search_friend( $name ) {
	global $db;

	$results = $db->get_results( $db->prepare( "SELECT * FROM user WHERE name LIKE %s", $name ), ARRAY_A );

	search_results_display( $results );
}

function search_results_display( $arrs ) {
	if ( ! is_array( $arrs ) ) {
		return;
	}
	
	if ( is_null( $arrs ) ) {
		echo '没有这个用户';
	}
	
	echo '<table>';
	foreach ( $arrs as $arr ) { 
	
		$group = get_friend_group( $arr['id'] ); ?>
		
		<tr><td><?php echo $arr['name']; ?></td><td><?php echo $group; ?></td><td><a href="?action=add&id=<?php echo $arr['id']; ?>">加为好友</a></td></tr> <?php
	}
	echo '</table>';
}

function SearchAndDisplayResult( $q ) {
	global $db;
	
	if ( is_null( $q ) || empty( $q ) ) {
		echo '<p>查找信息不能为空</p>';
		return;
	}
	
	$q = trim( $q );
	$type = $_POST['type'];
	$query = "SELECT * FROM user WHERE " . $type . " LIKE '%" . $q . "%'";
	$results = $db->get_results( $query, ARRAY_A );

	$output = '';
	if ( empty( $results ) ) {
		$output .= '<div><p>没有此人...</p></div>';
	} else {
		$output .= '<div class="search-result"><table>';
		foreach ( $results as $result ) {
			$output .= '<tr><td class="td-img"><img src="user/image/men_tiny.gif"></td>';
			$output .= '<td class="td-name">' . $result['name'] . '</td>';
			$output .= '<td class="td-do"><a href="friend.php?action=add&id=' . $result['id'] . '">加为好友</a></td></tr>';
		}
		$output .= '</table></div>';
	}
	echo $output;
}

function search_choose_box() { ?>
<div class="choose-box">
<table>
	<tbody>
		<tr>
			<td class="name" style="text-align: right">
			用户名称 :
			</td>
			<td>
				<form method="post" action="friend.php?action=result">
					<input type="text" id="search-rr-name" name="q" class="input-text">
					<input type="submit" name="submit" class="input-submit" value="搜索">
					<input type="hidden" name="type" value="name">
				</form>
			</td>
		</tr>
		<tr>
			<td class="name" style="text-align: right">
			对方Email :
			</td>
			<td>
				<form method="post" action="friend.php?action=result">
					<input type="text" id="search-rr-email" name="q" class="input-text">
					<input type="submit" name="submit" class="input-submit" value="搜索">
					<input type="hidden" name="type" value="email">
				</form>
			</td>
		</tr>
<!--		<tr>
			<td class="name">
			对方ID :
			</td>
			<td>
				<form method="post" action="friend.php?action=result">
					<input type="text" id="search-rr-id" name="q" class="input-text">
					<input type="submit" name="submit" class="input-submit" value="搜索">
					<input type="hidden" name="type" value="id">
				</form>
			</td>
		</tr>-->
	</tbody>
</table>
</div><?php
}

?>