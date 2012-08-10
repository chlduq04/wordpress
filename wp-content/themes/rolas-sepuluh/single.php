<?php get_header(); ?>
	<div class="span-24" id="contentwrap">
		<div class="span-16">
			<div id="content">
			<div class="breadcrumbs"><?php the_breadcrumb(); ?></div>
				<?php if (have_posts()) : ?>
					<?php while (have_posts()) : the_post(); ?>
					<div class="entry">
					<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
						<h1 class="title"><?php the_title(); ?></h1>
							<?php the_content(); ?>
						<div class="clear"></div>
						<div class="postpaging">
							<?php wp_link_pages(array('before' => '<p><span class="postpaging-note">Pages:</span> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
						</div>
						<div class="singlemeta">
							Posted by <?php the_author_posts_link(); ?> at <?php the_date(); ?><br/>
							Filed in category: <?php the_category(', '); ?>, <?php if(get_the_tags()) { ?><?php  the_tags('and tagged with: ', ', '); } ?>
						</div>
						<div class="navigation clearfix">
							<div class="alignleft"><?php previous_post_link('&laquo; %link') ?></div>
							<div class="alignright"><?php next_post_link('%link &raquo;') ?></div>
						</div>
						<?php edit_post_link('Edit this entry','<p>','</p>'); ?>
					</div><!--/post-<?php the_ID(); ?>-->
					</div>
			<div class="entry">
			<?php comments_template(); ?>
			</div>
				<?php endwhile; ?>
				<?php endif; ?>
			</div>
		</div>
		<?php get_sidebar(); ?>
	</div>
<?php get_footer(); ?>