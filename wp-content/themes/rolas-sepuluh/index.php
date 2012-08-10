
<?php get_header(); ?>
<div class="span-24" id="contentwrap">
	<div class="span-16">
		<div id="content">
		<?php if (have_posts()) : ?>
		<?php while (have_posts()) : the_post(); ?>
			<div class="entry">
			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="front-thumb">
					<?php if (has_post_thumbnail()){the_post_thumbnail(array( 150,150 ), array( 'class' => 'alignleft' ), array('alt' => ''.get_the_title().''));} else { ?>
						<div class="datebox"><div class="months"><?php the_time('M'); ?></div><div class="dates"><?php the_time('d'); ?></div><div class="years"><?php the_time('Y'); ?></div></div> 
					<?php } ?>
				</div>
				<h2 class="title">
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
				</h2>
				<div class="postmeta">
					Posted by <?php the_author_posts_link(); ?>, in category: <?php the_category(', '); ?> <?php the_tags('and tagged in: ', ', ', ''); ?> with <?php comments_popup_link(__('0 Comments','rolassepuluh'), __('1 Comment','rolassepuluh'), __('% Comments','rolassepuluh')); ?>
				</div>
				<?php echo excerpt(40); ?>
				<a href="<?php the_permalink() ?>">Read more</a>
				<div class="postpaging">
					<?php wp_link_pages(array('before' => '<p><span class="postpaging-note">Pages:</span> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				</div>
				<div class="clear"></div>
			</div><!--/post-<?php the_ID(); ?>-->
			</div>
		<?php endwhile; ?>
			<div id="wp-pagenavi">
				<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } else { ?>
				<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
				<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
				<?php } ?>
			</div>
		<?php else : ?>
			<h2 class="center">Not Found</h2>
			<p class="center">Sorry, but you are looking for something that isn't here.</p>
			<?php get_search_form(); ?>
		<?php endif; ?>
		</div>
	</div>
	<?php get_sidebar(); ?>
	<?php get_footer(); ?>