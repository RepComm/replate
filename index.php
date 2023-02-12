<?php

//DEBUGGING - don't flood my UI plz
error_reporting(E_ALL ^ E_DEPRECATED);

/**Include the SSR ui lib*/
include_once("inc/ui.php");

//Import the Post() function, depends on ui.php
include_once("template-parts/post.php");

//Hook into wordpress head element generation
//Any HTML written / echoed here will appear in the <head>
function head_hook () {
  $sBG_COLOR = get_theme_mod("bg_color", "#262630");
  $sCONTENT_COLOR = get_theme_mod("content_color", "#4c505c");

  $sTITLE_TEXT_COLOR = get_theme_mod("title_text_color", "#fbeeac");
  $sGENERIC_TEXT_COLOR = get_theme_mod("generic_text_color", "#f0f1f1");
  $sGENERIC_TEXT_HOVER_COLOR = get_theme_mod("generic_text_hover_color", "#68c7dd");
  $sGENERIC_BUTTON_COLOR = get_theme_mod("generic_button_color", "#222a34");
  $sGENERIC_BUTTON_HOVER_COLOR = get_theme_mod("generic_button_hover_color", "#334760");
  $sGENERIC_BUTTON_TEXT_COLOR = get_theme_mod("generic_button_text_color", "#bee6ea");

  $ui = new UIBuilder();

  //Populate the css data
  $ui->create("style")->text(<<<EOD
  body {
    background-color: $sBG_COLOR;
    margin: 0;
    font-family: courier;
  }
  #main {
    display:flex;
    flex-direction: column;
  }

  #header {
    flex: 1;
    background-color: $sCONTENT_COLOR;
    color: $sTITLE_TEXT_COLOR;
    padding: 1em;
    text-align: center;
    margin-top: 1em;
    font-size: x-large;
  }
  #login {
    position: absolute;
    right: 1em;
  }

  #posts {
    margin-top: 1em;
    flex: 10;
    margin-left: 4em;
    margin-right: 4em;
  }

  .post {
    background-color: $sCONTENT_COLOR;
    border-radius: 0.5em;
    overflow: hidden;
    padding: 1em;
  }
  .post-title {
    color: $sTITLE_TEXT_COLOR;
    text-align: center;
    display: block;
  }
  .post-content {
    color: $sGENERIC_TEXT_COLOR;
  }
  .post-bar {
    text-align: center;
  }
  .post-bar-link, .post-bar-button {
    background-color: $sGENERIC_BUTTON_COLOR;
    border-radius: 0.25em;
    border: solid 1px #726464;
    font-size: small;
    text-decoration: none;
    color: $sGENERIC_BUTTON_TEXT_COLOR;
    margin: 0 0.5em 0 0.5em;
    padding: 0.5em 1em 0.5em 1em;
    cursor: pointer;
  }
  .post-bar-link:hover, .post-bar-button:hover {
    background-color:$sGENERIC_BUTTON_HOVER_COLOR;
    color: $sGENERIC_TEXT_HOVER_COLOR;
    border: solid 1px #e57a3e;
  }
  EOD)->e->writeOutputStream();
  
  //Create a link reference element to import a css file
  $linkCSS = $ui->create("link")->
  attr("href", get_bloginfo("template_directory") . "/style.css")->
  attr("rel", "stylesheet")->
  e->writeOutputStream();
}
//Register the head_hook function so it will be called by wordpress
add_action( 'wp_head', 'head_hook');

//wordpress doesn't have a body hook for some reason, but we're going to pretend like it does
function body_hook () {
  $ui = new UIBuilder();

  $body = $ui->create("body")->e;

  $main = $ui->create("div")->id("main")->mount($body)->e;

  $header = $ui->create("div")->id("header")->mount($main)->e;

  $title = $ui->create("span")->
    id("site-title")->
    text( get_bloginfo("name") )->
    mount($header)->e;

  $desc = $ui->create("span")->
    id("site-description")->
    text( get_bloginfo("description") )->
    mount($header)->e;

  if (!is_user_logged_in()) {
    $login = $ui->create("a")->
      id("login")->
      classes("post-bar-link")->
      attr("href", get_site_url() . "/wp-login.php")->
      text("Login")->
      mount($header)->e;
  }

  $posts = $ui->create("div")->
  id("posts")->
  mount($main)->e;

  //This doesn't execute if there are no posts
  while(have_posts()) {
    the_post(); //consume a post

    //from template-parts/post.php
    $post = Post($ui);
    $post["title"]->text( get_the_title() );
    
    $post["content"]->text ( get_the_content() );

    $post["post"]->mount($posts);

    // while (have_comments()) {
    //   the_comment();

    //   get_the_content()
    // }
  }

  $body->writeOutputStream();
}

//tell wordpress to output the head element
wp_head ();

//output the body element
body_hook();

//tell wordpress to output the footer
wp_footer();

?>