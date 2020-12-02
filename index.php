<?php

/**Include the component lib*/
include_once("inc/component/component.php");

//Import the postcomponent, depends on component.php
include_once("template-parts/post.php");

//Hook into wordpress head element generation
//Any HTML written / echoed here will appear in the <head>
function head_hook () {
  $sBG_COLOR = get_theme_mod("bg_color", "#b2b1dd");
  $sCONTENT_COLOR = get_theme_mod("content_color", "#717687");

  $sTITLE_TEXT_COLOR = get_theme_mod("title_text_color", "#1b2428");
  $sGENERIC_TEXT_COLOR = get_theme_mod("generic_text_color", "#d9d9d9");
  $sGENERIC_TEXT_HOVER_COLOR = get_theme_mod("generic_text_hover_color", "#bee6ea");
  $sGENERIC_BUTTON_COLOR = get_theme_mod("generic_button_color", "#6781a2");
  $sGENERIC_BUTTON_HOVER_COLOR = get_theme_mod("generic_button_hover_color", "#334760");
  $sGENERIC_BUTTON_TEXT_COLOR = get_theme_mod("generic_button_text_color", "#ffffff");
  
  //Create a dynamic style element
  $styles = new Component("style");
  //Set content type to css
  $styles->
  attr("type", "text/css")->
  //Populate the css data
  textContent(
    //Grab body background color from the wordpress customizer
    "body {" .
      "background-color:" . $sBG_COLOR . ";" .
    "}" .
    
    "#main {" .
      "display:flex;" .
      "flex-direction: column;" .
      "margin-left: 4em;" .
      "margin-right: 4em;" .
    "}" .

    //This header bar shows at the top of the site
    "#header {" .
      "flex: 1;" .
      //grab color from customizer
      "background-color:" . $sCONTENT_COLOR . ";" .
      "color:" . $sTITLE_TEXT_COLOR . ";" .
      "padding-top: 10px;" .
      "padding-bottom: 10px;" .
      "text-align: center;" .
    "}" .

    ".posts {" .
      "margin-top: 1em;" .
      "flex: 10;" .
      "text-align: center;" .
    "}" .

    ".post {" .
      "background-color: $sCONTENT_COLOR;" .
      "border-radius: 0.5em;" .
      "overflow: hidden;" .
      "padding: 1em;" .
      // "text-align: center;" .
    "}" .
    ".post-title {" . 
      "color:$sTITLE_TEXT_COLOR;" .
    "}" .
    ".post-content {" .
      "color:$sGENERIC_TEXT_COLOR;" .
    "}" .
    ".post-bar-link, .post-bar-button {" .
      "background-color: $sGENERIC_BUTTON_COLOR;" .
      "border-radius: 0.25em;" .
      "border: solid 1px white;" .
      "font-size: small;" .
      "text-decoration: none;" .
      "color:$sGENERIC_BUTTON_TEXT_COLOR;" .
      "margin-left: 1em;" .
      "padding-left: 1em;" .
      "padding-right: 1em;" .
      "cursor: pointer;" .
    "}" .
    ".post-bar-link:hover, .post-bar-button:hover {" .
      "background-color:$sGENERIC_BUTTON_HOVER_COLOR;" .
      "color: $sGENERIC_TEXT_HOVER_COLOR;" .
    "}"
  );
  
  //Output the style tag
  writeComponent($styles, false);

  //Create a link reference element to import a css file
  $linkCSS = new Component("link");
  $linkCSS->
  attr("href", get_bloginfo("template_directory") . "/style.css")->
  attr("rel", "stylesheet");

  //Output the link element to head
  writeComponent($linkCSS, false);
}
//Register the head_hook function so it will be called by wordpress
add_action( 'wp_head', 'head_hook');

//Anonymous function to hide our variables from global scope
(function () {
  //tell wordpress to output the head element
  wp_head();

  //Create a body element ( i guess wp doesn't have a wp_body hook, weird )
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

      if (!is_user_logged_in()) {
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
    $posts->id("posts")->
    attr("class", "posts")->
    mount($main);

    //This doesn't execute if there are no posts
    while(have_posts()) {
      the_post(); //consume a post

      //from template-parts/post.php
      $post = new PostComponent();
      // //set the title
      $post->setTitle( get_the_title() )->
      // //set the content
      setContent( get_the_content() )->
      // //append to posts element
      mount($posts);
    }
    writeComponent($body);

  wp_footer();
})();


?>