<?php get_header(); ?>
	<div class="span-24" id="contentwrap">
			<div class="entry">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<div class="post" id="post-<?php the_ID(); ?>">
				<div class="breadcrumbs"><a href="<?php echo home_url() ; ?>">Home</a> &raquo <a href="<?php echo get_permalink($post->post_parent); ?>" rev="attachment"><?php echo get_the_title($post->post_parent); ?></a> &raquo <?php the_title(); ?></div>
					<div class="attcpage">
					<h1><?php the_title(); ?></h1>
						<?php $attachments = array_values(get_children( array('post_parent' => $post->post_parent, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') )); foreach ( $attachments as $k => $attachment ) if ( $attachment->ID == $post->ID )	break; $next_url =  isset($attachments[$k+1]) ? get_permalink($attachments[$k+1]->ID) : get_permalink($attachments[0]->ID);?>
						<a href="<?php echo $next_url; ?>"><?php echo wp_get_attachment_image( $post->ID, 'large' ); ?></a>
						<br />
					<a href="<?php echo wp_get_attachment_url($post->ID); ?>" target="_blank" title="View <?php the_title(); ?> in full size">View <?php the_title(); ?></a>
					</div>
				<div class="picturedetail">	
					<b>Image Description:</b>
					<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
				</div>
				<div class="imagenavigation">
					<b><?php echo get_the_title($post->post_parent); ?> Photo Gallery(s):</b><br/>
					<?php $post_parent = get_post($post->ID, ARRAY_A); $parent = $post_parent['post_parent']; $attachments = get_children("post_parent=$parent&post_type=attachment&post_mime_type=image&orderby=menu_order ASC, ID ASC"); foreach($attachments as $id => $attachment) : echo wp_get_attachment_link($id, 'thumbnail', true); endforeach;?>
				</div>
					<div class="clear"></div>
				</div>
				<?php endwhile; else: ?>
					<p>Sorry, no attachments matched your criteria.</p>
				<?php endif; ?>
			</div>
	<?php get_footer(); ?>