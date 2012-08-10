<?php get_header(); ?>
<div class="span-24" id="contentwrap">
		<div id="content">
			<div class="entry">
				<h1>Error 404 - Page Not Found</h1>
				Sorry, the page you are looking for in no longer exists. Most likely this is due to:<br/>
				<ul>
					<li>An outdated link on another site.</li>
					<li>A wrong type in the address / url.</li>
					<li>The post / page in this site has been moved.</li>
				</ul>
			</div>
			<div class="entryarsip">
				<b>Categories Suggestion</b>
					<ul>
					<?php wp_list_categories('title_li=&depth=-1');?>
					</ul>
			</div>
		</div>
	<div class="clear"></div>
<?php get_footer(); ?>