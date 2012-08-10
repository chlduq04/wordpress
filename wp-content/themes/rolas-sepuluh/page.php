<?php get_header(); ?>
<div class="span-24" id="contentwrap">
	<div class="span-16">
		<div id="content">
		<div class="breadcrumbs"><?php the_breadcrumb(); ?></div>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="post" id="post-<?php the_ID(); ?>">
				<div class="entry">
				<h1 class="title"><?php the_title(); ?></h1>
					<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
						<div class="postpaging">
							<?php wp_link_pages(array('before' => '<p><span class="postpaging-note">Pages:</span> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
						</div>
					<div class="clear"></div>
				</div>
					<?php edit_post_link('Edit this entry','<p>','</p>'); ?>
			</div>
				<div class="entry">
				<?php comments_template(); ?>
				</div>	
			<?php endwhile; endif; ?>
		</div>
	</div>
	<?php get_sidebar(); ?>
	<?php get_footer(); ?>