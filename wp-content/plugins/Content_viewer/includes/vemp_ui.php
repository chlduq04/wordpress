<?php include_once 'db_query.php';?>
<div class = "vemp_ui-preview">
<p >
<label for="<?php echo $this->get_field_name('title'); ?>"><?php echo __('Title:') ?></label><br>
<input id="<?php echo $this->get_field_name('title');?>" type="text" name ="<?php echo $this->get_field_name('title');?>" value="<?php echo $title?>" > </input><br>
</p>
<p>
<label for="<?php echo $this->get_field_name('length'); ?>"><?php echo __('Number of contents:'); ?></label><br>
<input id="<?php echo $this->get_field_id('length'); ?>" name="<?php echo $this->get_field_name('length'); ?>" type="text" value="<?php echo $length; ?>" />
<small>If it is empty , it will be filled with 3</small>
</p>
<p>
<label for="<?php echo $this->get_field_name('content_length'); ?>"><?php echo __('Length in character:'); ?></label><br>
<input id="<?php echo $this->get_field_id('content_length'); ?>" name="<?php echo $this->get_field_name('content_length'); ?>" type="text" value="<?php echo $content_length; ?>" />
<small>If it is empty, it will be filled with 25</small>
</p>
<p>
    <label for="<?php echo $this->get_field_name('category'); ?>"><?php echo __('Select a post or a category:'); ?></label><br>
    <select name="<?php echo $this->get_field_name('category'); ?>" id="<?php echo $this->get_field_id('category'); ?>">
      <option value=""> [Choose your category or empty] </option>
      <?php foreach(set_category_dropdown() as $categorys) : ?>
        <option <?php echo ('c:' . $categorys['category_id'] == $instance['category']) ? 'selected' : '' ?> value="c:<?php echo $categorys['category_id']; ?>">
          Category: <?php echo $categorys['category_name']; ?>
        </option>  
      <?php endforeach; ?>
    </select>
    <small>If it is empty, the widget includes all categories</small>
</p>
</div>