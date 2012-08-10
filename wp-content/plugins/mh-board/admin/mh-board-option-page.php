<?php
add_action( 'admin_menu', 'mh_board_menu' );
function mh_board_menu(){
	add_submenu_page( 'edit.php?post_type=board', 'mh-board-option', 'MH Board Settings', 'manage_options', 'mh-board-setting', 'mh_board_settings' );
	add_submenu_page( 'edit.php?post_type=board', 'mh-board-option', 'MH Board Update', 'manage_options', 'mh-board-update', 'mh_board_update' );
}
add_action('admin_init','mh_board_register_options');
function mh_board_register_options(){
	register_setting( 'mh-board-options', 'mh_board_options' );
}
function mh_board_settings(){
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div><h2>MH Board Settings</h2>
	
	<form method="post" action="options.php">
		<?php settings_fields( 'mh-board-options' ); ?>
		<?php $mh_board_options = get_option('mh_board_options');
		$emailpush = $mh_board_options['emailpush'];
		$mh_comment = $mh_board_options['mh_comment'];
		$mh_link = $mh_board_options['mh_link'];
		$mh_guestwrite = $mh_board_options['mh_guestwrite'];
		
		if($mh_link == 1){
			delete_option('mh_board_write_link');				
			delete_option('mh_board_edit_link');
		}
		?>
		<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="emailpush">Email Push</label></th>
				<td>적용: <input name="mh_board_options[emailpush]" type="checkbox" id="emailpush" value="push" <?php if($emailpush == 'push'){echo " checked";}?>>(* 댓글이 달리면 작성자에게 이메일로 알려줍니다.)</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="emailpush">MH Board 댓글 사용</label></th>
				<td>적용: <input name="mh_board_options[mh_comment]" type="checkbox" id="mh_comment" value="1" <?php if($mh_comment == '1'){echo " checked";}?>>(* 기존 테마 댓글이 아닌 MH Board의 댓글을 사용합니다.)</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="mh_guestwrite">비회원 글쓰기</label></th>
				<td>허용: <input name="mh_board_options[mh_guestwrite]" type="checkbox" id="mh_guestwrite" value="1" <?php if($mh_guestwrite == '1'){echo " checked";}?>>(* 비회원 글쓰기가 가능하도록 해줍니다.)</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="emailpush">MH Board 링크 초기화</label></th>
				<td>초기화: <input name="mh_board_options[mh_link]" type="checkbox" id="mh_link" value="1">(* permalink 변경 시 글쓰기 및 수정 링크를 초기화 시켜줍니다..)</td>
			</tr>
		</tbody>
		</table>
		<?php submit_button();?>
	</form>
</div>
<?php
}
function mh_board_update(){
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div><h2>MH Board Update</h2>
	<?php
	$xml = wp_remote_get(MH_BOARD_UPDATE_URL);
	$data = simplexml_load_string($xml['body']);

	if($data->version != MH_BOARD_VERSION){
		echo "현재 MH Board의 버전은 ".MH_BOARD_VERSION."이며 버전 {$data->version} 가 새로 배포되었습니다.<br>";
		echo "다운로드 받으러 가기: <a href='{$data->download}'>$data->download</a>";
	}else{
		echo "현재 MH Board의 버전은 ".MH_BOARD_VERSION."으로 최신버전입니다.";
	}
	?>
	
</div>
<?php	
}
?>