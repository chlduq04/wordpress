<?php get_header();?>
	<div id="mh-board">
	<div id="menu">
		<ul>
			<li><a href="<?php echo get_post_type_archive_link('board');?>">전체</a></li>
			<?php
			$categories = @ get_terms('board_cat',array('orderby'=>'id','order'=>'ASC','hide_empty'=>0));
			if(is_array($categories)){
				foreach($categories as $category){
					echo '<li><a href="'.get_term_link($category->slug, 'board_cat').'">'.$category->name.'</a></li>';
				}
			}
			?>			
		</ul>
	</div>
	<table cellpadding="0" cellspacing="0" class="board">
	<?php if ( have_posts() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<?php $category =@ wp_get_object_terms(get_the_ID(),'board_cat');?>
			<?php
				$author = get_the_author();
				if($author){
					$site = $user_data->user_url;
					$email = $_POST['url'];
				}else{
					$guest_info = get_post_meta(get_the_ID(),'guest_info',true);
					$author = $guest_info['guest_name'];
					$action = 'guest';
					$email = $guest_info['guest_email'];
					$site = $guest_info['guest_site'];
				}
				
				
			?>
			<tr>
				<th colspan="6"><h3><b><?php the_title();?></b></h3></th>
			</tr>
			<tr>
				<th>글쓴이</th><td><?php echo $author;?><?php if($site){?>(<a href="http://<?php echo $site;?>"><?php echo "http://".$site;?></a>)<?php }?></td><th>조회</th><td><?php echo $mh_board->get_count(get_the_ID());?></td><th>등록일</th><td><?php echo get_the_date('Y/m/d');?></td>
			</tr>
			<tr>
				<td colspan="6" class='content'><?php the_content();?></td>
			</tr>
		<?php endwhile; ?>
	<?php endif;
	?>
	</table>
	<div class="copyright">
		Powered by <a href="http://ssamture.net">ssamture.net</a>
	</div>
	<div class="action clearfix">
		<?php if(is_admin()):?>
			<a href="<?php echo mh_get_board_write_link();?>" class="button">글쓰기</a>
		<?php endif;?>
		<?php if(is_user_logged_in() && get_current_user_id() == get_the_author_id()):?>
			<form action="<?php echo mh_get_board_edit_link();?>" method="post"><input type="hidden" name="post_id" value="<?php the_ID();?>"/>
				<?php if(function_exists('wp_nonce_field'))	wp_nonce_field('mh_board_nonce','_mh_board_nonce');?>
				<input type="submit" class="button" value="수정"/>
			</form>
			<form action="<?php echo mh_get_board_edit_link();?>" method="post" id="delete_board"><input type="hidden" name="post_id" value="<?php the_ID();?>"/>
				<?php if(function_exists('wp_nonce_field'))	wp_nonce_field('mh_board_nonce','_mh_board_nonce');?>
				<input type="hidden" name="action" value="delete"/>
				<input type="submit" class="button" value="삭제"/>
			</form>
		<?php elseif($action == 'guest'):?>
			<form action="<?php echo mh_get_board_edit_link();?>" method="post"><input type="hidden" name="post_id" value="<?php the_ID();?>"/>
				<?php if(function_exists('wp_nonce_field'))	wp_nonce_field('mh_board_nonce','_mh_board_nonce');?>
				<input type="submit" class="button" value="수정"/>
			</form>
			<form action="<?php echo mh_get_board_edit_link();?>" method="post" id="delete_board"><input type="hidden" name="post_id" value="<?php the_ID();?>"/>
				<?php if(function_exists('wp_nonce_field'))	wp_nonce_field('mh_board_nonce','_mh_board_nonce');?>
				<input type="hidden" name="action" value="delete"/>
				<input type="hidden" name="edit_type" value="guest"/>
				<input type="submit" class="button" value="삭제"/>
			</form>
			<!--<div id="popup" class="clearfix">
				<div id="popupheader">
					<h5>수정 비밀번호</h5>
				</div>
				<div class="popupcontent">
					<input type="hidden" name="post_id" value="<?php the_ID();?>"/>
					<input type="hidden" name="action" value="delete"/>
					<input type="password" name="guest_password"/>
					<input type="submit" class="button" value="삭제"/>
				</div>
			</div>-->
		<?php endif;?>
		<?php if($post->post_parent == 0):?>
			<form action="<?php echo mh_get_board_write_link();?>?post_id=<?php the_ID();?>" method="post">
				<?php if(function_exists('wp_nonce_field'))	wp_nonce_field('mh_board_nonce','_mh_board_nonce');?>
				<input type="submit" class="button" value="답글"/>
			</form>
		<?php endif;?>
	</div>
	<div class="pagenavi">
	<?php
	mh_pagenavi();
	?>
	</div>
	<?php 
	$mh_board_options = get_option('mh_board_options');
	$mh_comment = $mh_board_options['mh_comment'];
	$short_link = get_site_url()."/?p=".$post->ID;
	unset($mh_board_options);
	if($mh_comment){
		require_once(dirname(__FILE__).'/comments.php');	
	}else{
		comments_template('',true);
	}
	?>
	</div>
<?php get_footer();?>
