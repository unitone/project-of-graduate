<?php

include( 'load.php' );

function SearchAndDisplayResult( $q ) {
	global $db;
	
	if ( is_null( $q ) || empty( $q ) )
		return;
	
	$q = trim( $q );
	$type = $_POST['type'];
	$query = "SELECT * FROM user WHERE " . $type . " LIKE '%" . $q . "%'";
	$results = $db->get_results( $query, ARRAY_A );

	if ( empty( $results ) ) {
		echo '<div><p>没有此人...</p></div>';
	} else {
		echo '<div><table>';
		foreach ( $results as $result ) {
			echo '<tr><td>' . $result['name'] . '</td><td><a href="friend.php?action=add&id=' . $result['id'] . '">添加为好友</a></td></tr>';
		}
		echo '</table></div>';
	}
}

$q = isset( $_POST['q'] ) ? $_POST['q'] : null;
$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';

switch( $action ) {
case 'result':
	SearchAndDisplayResult( $q );
	break;
default:
?>

<div class="choose-box">
<table>
	<tbody>
		<tr>
			<td class="name" style="text-align: right">
			用户名称 :
			</td>
			<td>
				<form method="post" action="search.php?action=result">
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
				<form method="post" action="search.php?action=result">
					<input type="text" id="search-rr-email" name="q" class="input-text">
					<input type="submit" name="submit" class="input-submit" value="搜索">
					<input type="hidden" name="type" value="email">
				</form>
			</td>
		</tr>
		<tr>
			<td class="name">
			对方人人网ID :
			</td>
			<td>
				<form method="post" action="search.php?action=result">
					<input type="text" id="search-rr-id" name="q" class="input-text">
					<input type="submit" name="submit" class="input-submit" value="搜索">
					<input type="hidden" name="type" value="id">
				</form>
			</td>
		</tr>
	</tbody>
</table>
</div>
<?php
break;
}
?>

