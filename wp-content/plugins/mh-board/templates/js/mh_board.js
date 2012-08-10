jQuery(document).ready(function(){
	jQuery('#mh-board-write form#write_board').submit(function(){
		var write_type = jQuery('#mh-board-write #write_type');
		var guest_name = jQuery('#mh-board-write #guest_name');
		if(jQuery.trim(guest_name.val()) == "" && write_type.val() == 'guest'){
			alert('이름을 입력해주세요.');
			guest_name.focus();
			return false;
		}
		var guest_email = jQuery('#mh-board-write #guest_email');
		if(jQuery.trim(guest_email.val()) == "" && write_type.val() == 'guest'){
			alert('이메일을 입력해주세요.');
			guest_email.focus();
			return false;
		}
		var regEmail = /^[0-9a-zA-z]([-_\.]?[0-9a-zA-z])*@[0-9a-zA-z]([-_\.]?[0-9a-zA-z])*\.[a-zA-Z]{2,3}$/i;
		if(jQuery.trim(guest_email.val()).indexOf("@") == -1 && write_type.val() == 'guest'){
			alert('이메일형식을 확인해주세요.');
			guest_email.focus();
			return false;
		}
		if(!regEmail.test(guest_email.val()) && write_type.val() == 'guest'){
			alert('이메일형식을 확인해주세요.');
			guest_email.focus();
			return false;
		}
		var guest_password = jQuery('#mh-board-write #guest_password');
		if(jQuery.trim(guest_password.val()) == "" && write_type.val() == 'guest'){
			alert('수정 및 삭제를 위한 비밀번호를 입력해주세요.');
			guest_password.focus();
			return false;
		}
		var post_title = jQuery('#mh-board-write .post_title');
		if(jQuery.trim(post_title.val()) == ""){
			alert('제목을 입력해주세요.');
			post_title.focus();
			return false;
		}
		/*var post_content = jQuery('#mh-board-write .post_content');
		if(jQuery.trim(post_content.val()) == ""){
			alert('내용을 입력해주세요.');
			post_content.focus();
			return false;
		}*/
	});
	jQuery('#mh-board-write form#delete_board').submit(function(){
		if(confirm('삭제 하시겠습니까?')){
			return true;
		}else{
			return false;
		}
	});
	jQuery('#mh-board form#delete_board').submit(function(){
		if(confirm('삭제 하시겠습니까?')){
			return true;
		}else{
			return false;
		}
	});
});
