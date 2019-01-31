
<div class="post" id="<?php the_ID(); ?>">
    <span class="post-title">
        <?php the_title(); ?>
    </span>

    <div class="post-content">
        <?php the_content(); ?>
    </div>

    <?php if(is_user_logged_in()) : ?>
        <?php edit_post_link(
            "<span class='post-edit-button'>Edit</span>"
        );?>
        <a href="<?php echo get_delete_post_link(); ?>"><span class="post-edit-button">Delete</span></a>
    <?php endif; ?>
</div>
