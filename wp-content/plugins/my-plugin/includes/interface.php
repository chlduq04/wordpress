<?php include_once('db_queries.php'); ?>

<div class="simple-post-preview">
  <p>
    <label for="<?php echo $this->get_field_name('title'); ?>"><?php echo __('Title:') ?></label><br>
    <input id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>"/>
    <small>Make widget name you want</small>
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('item'); ?>"><?php echo __('Select a post or a category:'); ?></label><br>
    <select name="<?php echo $this->get_field_name('item'); ?>" id="<?php echo $this->get_field_id('item'); ?>">
      <option value=""> [Please make your selection] </option>
      <?php foreach(spp_get_dropdown() as $category) : ?>
        <option <?php echo ('c:' . $category['category_id'] == $instance['item']) ? 'selected' : '' ?> value="c:<?php echo $category['category_id']; ?>">
          Category: <?php echo $category['category_name']; ?>
        </option>

        <?php foreach($category['children'] as $post): ?>
          <option <?php echo ('p:' . $post['post_id'] == $instance['item']) ? 'selected' : '' ?> value="p:<?php echo $post['post_id']; ?>">
            - <?php echo $post['post_name']; ?>
          </option>
        <?php endforeach; ?>

      <?php endforeach; ?>
    </select>
  </p>

  <p>
    Use:
    <input id="<?php echo $this->get_field_id('data_to_use'); ?>" name="<?php echo $this->get_field_name('data_to_use'); ?>" type="radio" value="content" <?php echo $data_to_use == 'content' ? 'checked': ''; ?> />
    content
    <input id="<?php echo $this->get_field_id('data_to_use'); ?>" name="<?php echo $this->get_field_name('data_to_use'); ?>" type="radio" value="excerpt" <?php echo $data_to_use == 'excerpt' ? 'checked': ''; ?> />
    excerpt
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('length'); ?>"><?php echo __('Length in characters:'); ?></label><br>
    <input id="<?php echo $this->get_field_id('length'); ?>" name="<?php echo $this->get_field_name('length'); ?>" type="text" value="<?php echo $length; ?>" />
    <small>-1 hides the content and 0 shows all</small>
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('link'); ?>"><?php echo __('Link title:'); ?></label><br>
    <input id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" />
  </p>

  <p>
    <label for="<?php echo $this->get_field_name('link_to'); ?>"><?php echo __('Link to:'); ?></label><br>
    <select name="<?php echo $this->get_field_name('link_to'); ?>" id="<?php echo $this->get_field_id('link_to'); ?>">
    <?php $options = array('Post', 'Category');
      foreach($options as $option) : ?>
        <option value="<?php echo $option; ?>" <?php echo $option == $instance['link_to'] ? 'selected' : '' ?>><?php echo $option; ?></option>
      <?php endforeach; ?>
    </select>
  </p>
</div>
