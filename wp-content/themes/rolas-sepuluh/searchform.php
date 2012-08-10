<?php
/**
 * The template for displaying search forms in Rolas Sepuluh theme
 */
?>
	<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
		<label for="s" class="assistive-text"><?php _e( '', 'rolassepuluh' ); ?></label>
		<input style="width:223px;" type="text" class="field" name="s" id="s"/>
		<input type="submit" class="submit" name="submit" id="searchsubmit" value="<?php esc_attr_e( 'Search', 'rolassepuluh' ); ?>" />
	</form>
