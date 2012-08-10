<?php include_once 'db_query.php';?>
<div class = "ui-preview">
<p >
<label for="<?php echo $this->get_field_name('title'); ?>"><?php echo __('Title:') ?></label><br>
<input id="<?php echo $this->get_field_name('title');?>" type="text" name ="<?php echo $this->get_field_name('title');?>" value="<?php echo $title?>" > </input><br>
</p>
<p>
<label for="<?php echo $this->get_field_name('length'); ?>"><?php echo __('Number of contents:'); ?></label><br>
<small>How many content do you want?</small>
<input id="<?php echo $this->get_field_id('length'); ?>" name="<?php echo $this->get_field_name('length'); ?>" type="text" value="<?php echo $length; ?>" />
</p>
<p>
<label for="<?php echo $this->get_field_name('content_length'); ?>"><?php echo __('Length in character:'); ?></label><br>
<input id="<?php echo $this->get_field_id('content_length'); ?>" name="<?php echo $this->get_field_name('content_length'); ?>" type="text" value="<?php echo $content_length; ?>" />
</p>

</div>