<?php

function keyCount ($o) {
  return count ( get_object_vars($o) );
}

class VDOMObject {
  
  protected VDOMObject $parentElement;
  protected string $type;

  protected string $_id;

  protected array $children;
  protected array $classList;

  protected array $attributes;
  
  protected $style;

  protected string $textContent;

  public function __construct ($type) {
    $this->type = $type;
    $this->children = array();
    $this->attributes = array();
    $this->style = array();
    $this->classList = array();
  }
  
  public function mountChild ($child) {
    array_push( $this->children, $child );
  }
  public function mount ($parent): VDOMObject {
    $parent->mountChild($this);
    return $this;
  }

  public function id ($_id) {
    $this->_id = $_id;
  }

  public function outputStream ($cb) {
    $type = $this->type;

    $cb("<$type ");

    if (isset( $this->_id) ) $cb("id=\"{$this->_id}\" ");

    if (count($this->classList) > 0) {
      $cb("class=\"");
      foreach ($this->classList as $c) {
        $cb("{$c} ");
      }
      $cb("\" ");
    }

    if (count($this->attributes) > 0) {
      foreach ($this->attributes as $key => $value) {
        $cb("$key=\"$value\" ");
      }
    }

    if (count($this->style) > 0) {
      $cb("style=\"");
      foreach ($this->style as $key => $value) {
        $cb("$key=\"$value\";");
      }
      $cb("\" ");
    }

    $cb(">");

    if (isset($this->textContent)) {
      $cb($this->textContent);
    }

    if (count($this->children) > 0) {
      foreach ($this->children as $child) {
        $child->outputStream($cb);
      }
    }

    $cb("</{$type}>");

  }

  public function writeOutputStream () {
    $this->outputStream( function ($txt) { echo $txt ; } );
  }

  public function classes (...$cs) {
    $this->classList = array_unique(
      array_merge($this->classList, $cs), SORT_REGULAR
    );
    // foreach ($cs as $c) {
    //   if (isset($this->classList[$c])) continue;
    //   array_push($c);
    // }
    return $this;
  }

  public function attr ($key, $value) {
    $this->attributes[$key] = $value;
    return $this;
  }
  public function text ($textContent) {
    $this->textContent = $textContent;
    return $this;
  }
}

class UIBuilder {
  
  public VDOMObject $e;

  public function __construct () {
    
  }

  function create ($type) {
    $this->e = new VDOMObject($type);
    return $this;
  }

  function mount ($parent) {
    $parent->mountChild($this->e);
    return $this;
  }

  function id ($_id) {
    $this->e->id($_id);
    return $this;
  }

  function ref ($e) {
    $this->e = $e;
    return $this;
  }

  function classes (...$cs) {
    $this->e->classes(...$cs);
    return $this;
  }

  function attr ($key, $value) {
    $this->e->attr($key, $value);
    return $this;
  }

  function text ($textContent) {
    $this->e->text($textContent);
    return $this;
  }
}

?>
