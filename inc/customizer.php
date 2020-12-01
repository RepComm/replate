<?php

/**A theme modifier setting / control wrapper*/
class ThemeMod {
  public $id;
  public $type;
  public $displayname;
  public $default;

  public function __construct ($id, $type, $displayname, $default = 0) {
    $this->id = $id;
    $this->type = $type;
    $this->displayname = $displayname;
    $this->default = $default;
  }
}

/**A theme modifier section wrapper*/
class ThemeModSection {
  protected $id;
  protected $displayname;
  protected $mods;
  
  public function __construct ($id, $displayname) {
    $this->id = $id;
    $this->displayname = $displayname;
    $this->mods = array();
  }
  public function hasMod ($id) {
    //TODO
    return false;
  }
  public function addMod ($mod) {
    if ($this->hasMod($mod->id)) return $this;
    array_push($this->mods, $mod);
    return $this;
  }
  public function createMod ($id, $type, $displayname, $default = 0) {
    $result = new ThemeMod($id, $type, $displayname, $default);
    $this->addMod($result);
    return $result;
  }
  public function build ($wp_customize, $themeName, $priority = 30) {
    $wp_customize->add_section($this->id , array(
      'title'      => __($this->displayname, $themeName),
      'priority'   => $priority,
    ));

    foreach ($this->mods as $mod) {
      $wp_customize->add_setting($mod->id , array(
        'default'   => $mod->default,
        'transport' => 'refresh',
      ));

      if ($mod->type == "color") {
        $wp_customize->add_control(
          new WP_Customize_Color_Control(
            $wp_customize,
            "ctrl-" . $mod->id,//"xheader_font_color",
            array(
              "label" => __($mod->displayname, $themeName),
              "section" => $this->id,
              "settings" => $mod->id
            )
          )
        );
      } else {
        //TODO - throw exception
        //This type isn't supported
      }
    }
  }
}

function customizer_settings_hook ($wp_customize) {
  //Create a set of theme modifiers
  $themeMods = new ThemeModSection("jon-template-colors", "Theme Colors");
  //Create the header color setting
  $themeMods->createMod("header_color", "color", "Header Color", "#b7b7b7");
  //Create the header font color setting
  $themeMods->createMod("header_font_color", "color", "Header Font Color", "#000");

  $themeMods->createMod("body_bg_color", "color", "Body Background Color", "#000");

  //Push to wordpress customizer
  $themeMods->build($wp_customize, "jon-template");
}
add_action('customize_register', 'customizer_settings_hook');

?>