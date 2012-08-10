	<div class="span-24">
		<div id="footer">
		<?php dynamic_sidebar('Footer Widgets'); ?>
		<p>
		<!-- It is completely optional, but if you like the Theme I would appreciate it if you keep the credit link at the bottom. -->
		<a href="<?php echo home_url(); ?>"><b><?php bloginfo('name');?></b></a>.<br/>
		Theme design by <a href="http://www.themesanyar.com/">Themesanyar</a>. Powered by <a href="http://wordpress.org/">WordPress</a>. Copyright &copy; <?php echo date('Y');?> All Rights Reserved.
		</p>
		</div>
	</div>
</div>
<?php wp_footer();?>
</body>
</html>