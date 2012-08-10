<?php get_header(); ?>
<div class="span-24" id="contentwrap">
	<div class="span-16">
		<div id="content">
		<div class="breadcrumbs"><?php the_breadcrumb(); ?></div>
			<div class="entry">
				<?php if (have_posts()) : ?>
				<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
				<?php /* If this is a category archive */ if (is_category()) { ?>
					<h1 class="pagetitle">Archive for the &#8216;<?php single_cat_title(); ?>&#8217; Category</h1>
				<?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
					<h1 class="pagetitle">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h1>
				<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
					<h1 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h1>
				<?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
					<h1 class="pagetitle">Archive for <?php the_time('F, Y'); ?></h1>
				<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
					<h1 class="pagetitle">Archive for <?php the_time('Y'); ?></h1>
				<?php /* If this is an author archive */ } elseif (is_author()) { ?>
					<h1 class="pagetitle">Author Archive</h1>
				<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
					<h1 class="pagetitle">Blog Archives</h1>
				<?php } ?>
			</div>
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
		<div class="entry">
		<?php else :
			if ( is_category() ) {
			printf("<h3 class='pagetitle'>Sorry, but there aren't any posts in the %s category yet.</h3>", single_cat_title('',false));
			} else if ( is_date() ) {
			echo("<h3 class='pagetitle'>Sorry, but there aren't any posts with this date.</h3>");
			} else if ( is_author() ) {
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf("<h3 class='pagetitle'>Sorry, but there aren't any posts by %s yet.</h3>", $userdata->display_name);
			} else {
			echo("<h3 class='pagetitle'>No posts found.</h3>");
			}
			get_search_form();
			endif;
		?>
		</div>
		</div>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>