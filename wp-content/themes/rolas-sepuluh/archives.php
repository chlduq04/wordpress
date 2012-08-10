<?php
/**
 * template page for archive
/*
Template Name: Archives
*/
?>

<?php get_header();?>
<div class="span-24" id="contentwrap">
<div class="breadcrumbs" style="margin:10px 0 10px 10px;"><?php the_breadcrumb(); ?></div>
<h1 class="title" style="margin:5px 0 0 10px;">Archives</h1>
	<div class="span-12">
		<div id="content">
			<div class="entryarsip">
				<h2 class="pagetitle">Pages</h2>
					<ul>
						<?php wp_list_pages('title_li=&sort_column=menu_order&depth=-1'); ?>
					</ul>
			</div>			
			<div class="entryarsip">
				<h2 class="pagetitle">Archives by Month</h2>
					<ul>
						<?php wp_get_archives('type=monthly'); ?>
					</ul>

				<h2 class="pagetitle">Categories</h2>
					<ul>
					<?php wp_list_categories('title_li=&depth=-1');?>
					</ul>
			</div>	
			<p class="comments"></p>
		</div>
	</div>

	<div class="span-12">
	<div id="content">
			<div class="entry">
				<h2 class="pagetitle">Archives by Tags</h2>
				<p>
					<?php wp_tag_cloud(); ?>
				</p>
			</div>
	</div>
	</div>

	<?php get_footer(); ?>