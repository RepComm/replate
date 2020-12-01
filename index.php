<?php

/**Include the component lib*/
include_once("inc/component/component.php");

//Import the postcomponent, depends on component.php
include_once("template-parts/post.php");

//Hook into wordpress head element generation
//This will let us inject dynamic css for customizer support
function head_hook () {
  $styles = new Component("style");
  $styles->
  attr("type", "text/css")->
  textContent(
    "#header {" .
      "background-color:" . get_theme_mod("header_color", "#b7b7b7") . ";" .
      "color:" . get_theme_mod("header_font_color", "#000") . ";" .
      "padding-top: 10px;" .
      "padding-bottom: 10px;" .
      "text-align: center;" .
    "}"
  );
  
  writeComponent($styles, false);

  $linkCSS = new Component("link");
  $linkCSS->
  attr("href", get_bloginfo("template_directory") . "/style.css")->
  attr("rel", "stylesheet");

  writeComponent($linkCSS, false);
}
add_action( 'wp_head', 'head_hook');

(function () {
  //tell wordpress to output the head element
  wp_head();
  //Build using component lib
  //Indention here is just a helper
  //You can safely not indent
  //Just remember to mount() elements properly
  $body = new Component("body");

  $main = new Component("div");
  $main->id("main")->mount($body);

    $header = new Component("div");
    $header->id("header")->mount($main);

      $title = new Component("span");
      $title->id("site-title")->
      textContent(get_bloginfo("name"))->
      mount($header);

      $br = new Component("br");
      $br->mount($header);

      $desc = new Component("span");
      $desc->id("site-description")->
      textContent(get_bloginfo("description"))->
      mount($header);

      if (is_user_logged_in()) {
        $loginLink = new Component("a");
        $loginLink->
        attr("href", get_site_url() . "/wp-login.php")->
        mount($header);

        $loginText = new Component("span");
        $loginText->id("header-login")->
        textContent("Login")->
        mount($loginLink);
      }

    $posts = new Component("div");
    $posts->id("posts")->mount($body);

    //This doesn't execute if there are no posts
    while(have_posts()) {
      the_post(); //consume a post

      $post = new Component("div");
      $post->attr("class", "post")->
      id( get_the_id() )->
      mount($posts);
    
      $postTitle = new Component("span");
      $postTitle->attr("class", "post-title")->
      textContent( get_the_title() )->
      mount($post);
    
      $postBody = new Component("div");
      $postBody->textContent( get_the_content() )->
      attr("class", "post-content")->
      mount($post);
    
      if (is_user_logged_in()) {
        $editLink = new Component("a");
        $editLink->attr("href", get_edit_post_link())->
        mount($post);
    
        $editText = new Component("span");
        $editText->textContent("Edit")->
        attr("class", "post-button")->
        mount($editLink);
    
        $delLink = new Component("a");
        $delLink->attr("href", get_delete_post_link())->
        mount($post);
    
        $delText = new Component("span");
        $delText->textContent("Delete")->
        attr("class", "post-button")->
        mount($delLink);
      }
    }
    writeComponent($body);

  wp_footer();
})();


?>