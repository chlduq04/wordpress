<?php get_header();?>

<div id="mh-board" class="content" class="clearfix">
	<h2>MH Board</h2>
	<div id="menu" class="clearfix">
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
	<thead>
		<tr class="mh_b_header">		
			<th width="30px">번호</th><th>카테고리</th><th width="40%">제목</th><th>글쓴이</th><th>날짜</th><th width="70px">조회</th>		
		</tr>
	</thead>
	<?php
	$redirect_uri = $_SERVER['REDIRECT_URL'];
	$args= array (
		'post_type' => array('board'),
		'post_status' => array('publish','private'),
		'posts_per_page'=>10,
		'paged'=>1,
		'orderby' =>'post_date',
		'order' => 'DESC',
		'board_cat'=>	$board_cat,
		'meta_key'=>'mh_board_notice',
		'meta_value'=>'1',

	);
	$wp_query = new WP_Query($args);
	if(!$board_cat){
		$total = " class='current current-menu-item selected'";
	}
	?>
	<?php
	$afterdate = strtotime('+2 day',strtotime(get_the_date('Y/m/d')));
	$notime = time();
	$new = '';
	if($notime <= $afterdate){
		$new = " <img src=\"".plugins_url('images/new.png',__FILE__)."\" alt=\"new\" align=\"absmiddle\"/>";
	}
	?>
	<?php if ( $wp_query->have_posts() ) : ?>
		<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
			<?php $category = wp_get_object_terms(get_the_ID(),'board_cat');?>
			<?php
				$author = get_the_author();
				if($author){
					
				}else{
					$author = get_post_meta(get_the_ID(),'guest_info',true);
					$author = $author['guest_name'];
				}
			?>
				<tr>
					<td>공지</td><td><?php echo $category[0]->name;?></td><td class="title"><a href="<?php the_permalink();?>"><?php the_title(); ?>[<?php echo  get_comments_number();?>]</a><?php echo $new;?></td><td><?php echo $author;?></td><td><?php echo get_the_date('Y/m/d');?></td><td><?php echo $mh_board->get_count(get_the_ID());?></td>
				</tr>
		<?php endwhile; ?>
	<?php endif;?>
	<?php
	$redirect_uri = $_SERVER['REDIRECT_URL'];
	
	$args= array (
		'post_type' => array('board'),
		'post_status' => array('publish','private'),
		'paged'=>$paged,
		'orderby' =>'post_date',
		'order' => 'DESC',
		'board_cat'=>	$board_cat,
		'post_parent' => 0
		//'meta_key'=>'mh_board_notice',
		//'meta_value'=>'0',

	);
	global $wp_query;
	$wp_query = new WP_Query($args);
	if(!$board_cat){
		$total = " class='current current-menu-item selected'";
	}
	?>
	<?php if ( $wp_query->have_posts() ) : ?>
		<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
			<?php $category = wp_get_object_terms(get_the_ID(),'board_cat');?>
			<?php
				$author = get_the_author();
				if($author){
					
				}else{
					$author = get_post_meta(get_the_ID(),'guest_info',true);
					$author = $author['guest_name'];
				}
			?>
			<?php
				$afterdate = strtotime('+2 day',strtotime(get_the_date()));
				$notime = time();
				$new = '';
				if($notime <= $afterdate){
					$new = " <img src=\"".plugins_url('images/new.png',__FILE__)."\" alt=\"new\" align=\"absmiddle\"/>";
				}
			?>
				<tr>
					<td><?php the_ID();?></td><td><?php echo $category[0]->name;?></td><td class="title"><a href="<?php the_permalink();?>"><?php the_title(); ?>[<?php echo  get_comments_number();?>]</a><?php echo $new;?></td><td><?php echo $author;?></td><td><?php echo get_the_date('Y/m/d');?></td><td><?php echo $mh_board->get_count(get_the_ID());?></td>
				</tr>
				<?php
				$args= array (
					'post_type' => array('board'),
					'post_status' => array('publish','private'),
					'posts_per_page'=>10,
					'orderby' =>'post_date',
					'order' => 'ASC',
					'board_cat'=>	$board_cat,
					'post_parent' => get_the_ID()
			
				);
				$query = new WP_Query($args);
				if(!$board_cat){
					$total = " class='current current-menu-item selected'";
				}
				?>
				<?php if ( $query->have_posts() ) : ?>
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<?php $category = wp_get_object_terms(get_the_ID(),'board_cat');?>
						<?php
							$author = get_the_author();
							if($author){
								
							}else{
								$author = get_post_meta(get_the_ID(),'guest_info',true);
								$author = $author['guest_name'];
							}
						?>
						<tr>
							<td></td><td></td><td class="title"><a href="<?php the_permalink();?>">└ Re:<?php the_title(); ?>[<?php echo  get_comments_number();?>]</a></td><td><?php echo $author;?></td><td><?php echo get_the_date('Y/m/d');?></td><td><?php echo $mh_board->get_count(get_the_ID());?></td>
						</tr>
						<?php endwhile; ?>
					<?php endif;?>
		<?php endwhile; ?>
	<?php endif;?>
	</table>
	<div class="copyright">
		Powered by <img src="http://ssamture.net/ssamture_logo_21.png" border="0"/><a href="http://ssamture.net">ssamture.net</a>
	</div>
	<?php
	$mh_board_options = get_option('mh_board_options');
	$guestwrite = $mh_board_options['mh_guestwrite'];
	?>
	<?php if($guestwrite == '1' || is_user_logged_in()):?>
		<a href="<?php echo wp_nonce_url(mh_get_board_write_link(),'_mh_board_nonce');?>" class="button">글쓰기</a>
	<?php endif;?>
	<div class="pagenavi">
	<?php
	mh_pagenavi();
	?>
	</div>
</div>
<?php get_footer();?>
