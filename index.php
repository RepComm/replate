<?php wp_footer();
function jon_template_customize_css()
{
    ?>
        <style type="text/css">
            #header {
                background-color: <?php echo get_theme_mod('header_color', '#b7b7b7'); ?>;
                color: <?php echo get_theme_mod('header_font_color', '#000'); ?>;
                padding-top: 10px;
                padding-bottom: 10px;
                text-align: center;
            }
        </style>
    <?php
}
add_action( 'wp_head', 'jon_template_customize_css');
wp_head();
?>

<head>
    <link href="<?php echo get_bloginfo("template_directory"); ?>/style.css" rel="stylesheet">
    <!-- <link href="http://192.168.1.17/wordpress/wp-content/themes/jon-template/style.css" rel="stylesheet"> -->
</head>

<body>
    <div id="main">
        <div id="header">
            <span id="site-title">
                <?php echo get_bloginfo("name"); ?>
            </span>
            <br />
            <span id="site-description">
                <?php echo get_bloginfo("description"); ?>
            </span>
            <?php if(!is_user_logged_in()):?>
                <a href="<?php get_site_url(); ?>/wordpress/wp-login.php"><span id="header-login">Login</span></a>
            <?php endif; ?>
        </div>

        <div id="posts">
            <?php
            if (have_posts()) { //If we have any posts
                while (have_posts()) { //Loop until we run out
                    the_post(); //Consume a post data entry, bringing its data into scope

                    //Use template-parts/content.php in context of the loaded post data
                    get_template_part("template-parts/content", get_post_format() );
                }
            }
            
            ?>
        </div>
    </div>
</body>