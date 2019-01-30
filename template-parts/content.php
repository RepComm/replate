
<div class="post" id="<?php the_ID(); ?>">
    <span class="post-title">
        <?php the_title(); ?>
    </span>

    <div class="post-content">
        <?php the_content(); ?>
    </div>

    <?php edit_post_link(
        "<span class='post-edit-button'>Edit</span>"
    ); ?>
</div>
