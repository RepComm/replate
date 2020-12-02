
<?php

/**Include the component lib*/
include_once( dirname(__DIR__) . "/inc/component/component.php");

/**A button bar for posts*/
class PostBarComponent extends Component {
  public function __construct () {
    parent::__construct("div");
  }
  /**Creates a button component
   * mounts it to the bar
   * and returns it from the function
   */
  public function createButton () {
    $result = new Component("button");
    $result->attr("class", "post-bar-button")->
    mount($this);
    return $result;
  }
  public function createLinkButton () {
    $result = new Component("a");
    $result->attr("class", "post-bar-link")->
    mount($this);
    return $result;
  }
}

class PostComponent extends Component {
  protected string $txtTitle;
  protected Component $title;
  protected string $txtContent;
  protected Component $content;
  protected Component $bar;

  public function __construct () {
    //Call component constructor
    //Make this a <div>
    parent::__construct("div");

    //css class
    $this->attr("class", "post");

    //create a component for the title
    $this->title = new Component("span");
    $this->title->attr("class", "post-title")->
    //attach to post
    mount($this);

    //create a component for the textual content
    $this->content = new Component("div");
    $this->content->attr("class", "post-content")->
    mount($this);

    //create a component for the button bar
    $this->bar = new PostBarComponent();
    $this->bar->attr("class", "post-bar")->
    mount($this);

    //buttons
    $btnComment = $this->bar->createButton();
    $btnComment->textContent("Comment");

    $btnLike = $this->bar->createButton();
    $btnLike->textContent("Like");

    if ( is_user_logged_in() ) {
      $btnEdit = $this->bar->createLinkButton();
      $btnEdit->attr("href", get_edit_post_link() )->
      textContent("Edit");

      $btnEdit = $this->bar->createLinkButton();
      $btnEdit->attr("href", get_delete_post_link() )->
      textContent("Delete");
    }
    
  }
  public function setTitle ($txt) {
    //TODO - sanitize
    $this->title->textContent($txt);
    return $this;
  }
  public function setContent ($txt) {
    //TODO - sanitize
    $this->content->textContent($txt);
    return $this;
  }
}

?>
