<?php

define( 'USERPATH', ABSPATH . 'user/' );

function get_user_photo() {
	global $db, $user;
	$output = '<img src="';
	if ( is_null( $user->photo ) ) {
		$output .= 'user/image/men_tiny.gif';
	} else {
		$output .= $user->photo;
	}
	
	$output .= '" style="width:100px;height:100px"/>';
	echo $output;
}

function show_user_information() {
	global $user;
	
	$output = '<form action="?action=update" method="POST"><table>';
	$output .= '<tr><td>姓名：</td><td><input id="user_name" name="user_name" type="text" value="' .$user->name . '" /></td></tr>';
	
	$arr = array( '保密', '男', '女' );
	$output .= '<tr><td>性别：</td><td><select id="user_sex" name="user_sex">';
	foreach ( $arr as $a ) {
		$output .= '<option value="' . $a;
		if ( $a == $user->sex ) {
			$output .= '" selected>' . $a . '</option>';
		} else {
			$output .= '" >' . $a . '</option>';
		}
	}			
	$output .= '</select></td></tr>';
	
	$arr1 = array( '理学系', '外语系', '经管系', '艺术系' );
	$output .= '<tr><td>系别：</td><td><select id="user_class" name="user_class">';	
	foreach ( $arr1 as $a ) {
		$output .= '<option value="' . $a;
		if ( $a == $user->class ) {
			$output .= '" selected>' . $a . '</option>';
		} else {
			$output .= '" >' . $a . '</option>';
		}
	}			
	$output .= '</select></td></tr>';
	
	$arr2 = array( '2006', '2007', '2008', '2009', '2010', '2011', '2012', '2013', '2014' );
	$output .= '<tr><td>年级：</td><td><select id="user_grade" name="user_grade">';
				
	foreach ( $arr2 as $a ) {
		$output .= '<option value="' . $a;
		if ( $a == $user->grade ) {
			$output .= '" selected>' . $a . '</option>';
		} else {
			$output .= '" >' . $a . '</option>';
		}
	}
	$output .= '</select></td></tr>';
	
	$output .= '<tr><td>现居地：</td><td><input id="user_place" name="user_place" type="text" value="'.$user->place.'" /></td></tr>';
	$output .= '<tr><td>家乡：</td><td><input id="user_town" name="user_town" type="text" value="'.$user->town.'" /></td></tr>';
	$output .= '<tr><td>自我介绍：</td><td><textarea id="user_detail" name="user_detail" cols="50" rows="5">'.$user->detail.'</textarea></td></tr>';
				
	$output .= '<tr><td></td><td><input id="submit" name="submit" type="submit" value="修改" /></td></tr>';
	$output .= '</table></form>';
	echo $output;	
}

function show_user_contract() {
	global $user;
	
	$output = '<form action="?action=update" method="POST"><table>';
	$output .= '<tr><td>QQ：</td><td><input id="user_qq" name="user_qq" type="text" value="'.$user->qq.'" /></td></tr>';
	$output .= '<tr><td>手机：</td><td><input id="user_phone" name="user_phone" type="text" value="'.$user->phone.'" /></td></tr>';
	$output .= '<tr><td>Email：</td><td><input id="user_email" name="user_email" type="text" readonly value="'.$user->email.'" /></td></tr>';
	$output .= '<tr><td>通信地址：</td><td><input id="user_address" name="user_address" type="text" value="'.$user->address.'" /></td></tr>';
	$output .= '<tr><td>邮编：</td><td><input id="user_zip" name="user_zip" type="text" value="'.$user->zip.'" /></td></tr>';
	$output .= '<tr><td>网站：</td><td><input id="user_website" name="user_website" type="text" value="'.$user->website.'" /></td></tr>';
	$output .= '<tr><td></td><td><input id="submit" name="submit" type="submit" value="保存信息" /></td></tr>';
	$output .= '</table><input type="hidden" name="user_id" value="'. $user->id .'"></form>';
	
	echo $output;	
}

function update_user_information_contract() {
	global $db, $user;
	
	$userArr = array();
	if ( isset( $_POST['user_name'] ) ) {
		$userArr['name'] = $_POST['user_name'];
	} 
	if ( isset( $_POST['user_sex'] ) ) {
		$userArr['sex'] = $_POST['user_sex'];
	} 
	if ( isset( $_POST['user_class'] ) ) {
		$userArr['class'] = $_POST['user_class'];
	} 
	if ( isset( $_POST['user_grade'] ) ) {
		$userArr['grade'] = $_POST['user_grade'];
	} 
	if ( isset( $_POST['user_place'] ) ) {
		$userArr['place'] = $_POST['user_place'];
	} 
	if ( isset( $_POST['user_town'] ) ) {
		$userArr['town'] = $_POST['user_town'];
	} 
	if ( isset( $_POST['user_detail'] ) ) {
		$userArr['detail'] = $_POST['user_detail'];
	} 
	if ( isset( $_POST['user_qq'] ) ) {
		$userArr['qq'] = $_POST['user_qq'];
	} 
	if ( isset( $_POST['user_phone'] ) ) {
		$userArr['phone'] = $_POST['user_phone'];
	}
	if ( isset( $_POST['user_address'] ) ) {
		$userArr['address'] = $_POST['user_address'];
	}
	if ( isset( $_POST['user_zip'] ) ) {
		$userArr['zip'] = $_POST['user_zip'];
	}
	if ( isset( $_POST['user_website'] ) ) {
		$userArr['website'] = $_POST['user_website'];
	}
	
	$db->update( 'user', $userArr, array( 'id' => $user->id ) );
	echo '更新成功';
}

function show_user_interest() {
	global $db, $user;
	$interest = get_user_interest();
	$output = '<form action="?action=updateinterest" method="POST"><table>';
	$output .= '<tr><td>明星：</td><td><input id="star" name="star" type="text" value="' . $interest['star'] . '" /></td></tr>';
	$output .= '<tr><td>音乐：</td><td><input id="music" name="music" type="text" value="' . $interest['music'] . '" /></td></tr>';
	$output .= '<tr><td>影视：</td><td><input id="movie" name="movie" type="text" value="' . $interest['movie'] . '" /></td></tr>';
	$output .= '<tr><td>书籍：</td><td><input id="book" name="book" type="text" value="' . $interest['book'] . '" /></td></tr>';
	$output .= '<tr><td>美食：</td><td><input id="food" name="food" type="text" value="' . $interest['food'] . '" /></td></tr>';
	$output .= '<tr><td>旅游：</td><td><input id="travel" name="travel" type="text" value="' . $interest['travel'] . '" /></td></tr>';
	$output .= '<tr><td>运动：</td><td><input id="sport" name="sport" type="text" value="' . $interest['sport'] . '" /></td></tr>';
	$output .= '<tr><td>游戏：</td><td><input id="game" name="game" type="text" value="' . $interest['game'] . '" /></td></tr>';
	$output .= '<tr><td>数码：</td><td><input id="digital" name="digital" type="text" value="' . $interest['digital'] . '" /></td></tr>';
	$output .= '<tr><td></td><td><input id="submit" name="submit" type="submit" value="保存信息" /></td></tr>';
	$output .= '</table></form>';
	echo $output;	
}

function get_user_interest() {
	global $db, $user;
	$interest = $db->get_results( $db->prepare( "SELECT * FROM interest WHERE userid = %d LIMIT 1", $user->id ), ARRAY_A );
	if ( ! empty($interest) )
		return $interest[0];
	else
		return;
}

function is_have_interest() {
	global $db, $user;
	
	return $db->get_var( $db->prepare( "SELECT id FROM interest WHERE userid = %d", $user->id ) );
}

function update_user_interest() {
	global $db, $user;
	
	$userArr = array();
	if ( isset( $_POST['star'] ) ) {
		$userArr['star'] = $_POST['star'];
	}
	if ( isset( $_POST['music'] ) ) {
		$userArr['music'] = $_POST['music'];
	} 
	if ( isset( $_POST['movie'] ) ) {
		$userArr['movie'] = $_POST['movie'];
	} 
	if ( isset( $_POST['book'] ) ) {
		$userArr['book'] = $_POST['book'];
	} 
	if ( isset( $_POST['food'] ) ) {
		$userArr['food'] = $_POST['food'];
	} 
	if ( isset( $_POST['travel'] ) ) {
		$userArr['travel'] = $_POST['travel'];
	} 
	if ( isset( $_POST['sport'] ) ) {
		$userArr['sport'] = $_POST['sport'];
	} 
	if ( isset( $_POST['game'] ) ) {
		$userArr['game'] = $_POST['game'];
	} 
	if ( isset( $_POST['digital'] ) ) {
		$userArr['digital'] = $_POST['digital'];
	} 
	
	if ( is_have_interest() ) {
		$db->update( 'interest', $userArr, array( 'userid' => $user->id ) );		
	} else {
		$userArr['userid'] = $user->id;
		$db->insert( 'interest', $userArr );
	}
	echo '更新成功';
}

function show_user_img() {
	global $db, $user;
	$default = 'user/image/men_tiny.gif';
?>
<div id="defaultimg">	
	<p>用户头像 - <a href="#" onclick="modfiy()">修改</a></p>
	<img src="<?php if ( $user->photo == "" ) { echo $default; } else { echo $user->photo; } ?>" style="width:80px;height:80px">
</div>
<script type="text/javascript">
function modfiy() {
	var html =  '<div class="upload"><form action="" method="POST" enctype="multipart/form-data">';
		html += '<label for="file">文件：</label>';
		html += '<input id="file" name="file" type="file" />';
		html += '<input id="do" name="do" type="hidden" value="update" />';
		html += '<input id="submit" name="submit" type="submit" value="上传" /></form></div>';
	window.defaultimg.innerHTML += html; 
}
</script>
<?php
}

function change_password( $oldpassword, $password ) {
	global $db, $user;
	if ( $oldpassword != $user->password ) {
		echo '<p>原密输不一致</p>';
		return;
	}
	
	$db->update( 'user', array( 'password' => $password ), array( 'id' => $user->id ) );
	echo '<p>密码更新成功</p>';
}


?>