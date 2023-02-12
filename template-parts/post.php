
<?php

/**Include the component lib*/
include_once( dirname(__DIR__) . "/inc/ui.php");

function PostBarButton (UIBuilder $ui): VDOMObject {
  return $ui->
  create("button")->
  classes("post-bar-button")->e;
}
/**A button bar for posts*/
function PostBar (UIBuilder $ui): VDOMObject {
  return $ui->create("div")->classes("post-bar")->e;
}
function PostBarLink (UIBuilder $ui): VDOMObject {
  return $ui->create("a")->classes("post-bar-link")->e;
}

function Post (UIBuilder $ui): array {
  $post = $ui->
  create("div")->
  classes("post")->e;
  
  $title = $ui->
  create("span")->
  classes("post-title")->
  mount($post)->e;

  $content = $ui->
  create("div")->
  classes("post-content")->
  mount($post)->e;

  $bar = PostBar($ui)->
  mount($post);

  $btnComment = PostBarButton($ui)->
  text("Comment (not impl yet)")->
  mount($bar);

  $btnLike = PostBarButton($ui)->
  text("Like (not impl yet)")->
  mount($bar);

  if ( is_user_logged_in() ) {
    $btnEdit = PostBarLink($ui)->
      attr("href", get_edit_post_link() )->
      text("Edit")->
      mount($bar);
    
    $btnDelete = PostBarLink($ui)->
      attr("href", get_delete_post_link() )->
      text("Delete")->
      mount($bar);
  }

  return [
    "post" => $post,
    "title" => $title,
    "content" => $content,
    "bar" => $bar,
    "comment" => $btnComment,
    "like" => $btnLike
  ];
}
