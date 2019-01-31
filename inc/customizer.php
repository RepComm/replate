<?php

function jon_template_new_customizer_settings($wp_customize) {
    $wp_customize->add_section('jon-template-colors' , array(
        'title'      => __('Theme Colors','jon-template'),
        'priority'   => 30,
    ));
    $wp_customize->add_setting('header_color' , array(
        'default'   => '#b7b7b7',
        'transport' => 'refresh',
    ));

    $wp_customize->add_setting("header_font_color", array(
        "default" => "#000",
        "transport" => "refresh"
    ));
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            "xheader_font_color",
            array(
                "label" => __("Header Font Color", "jon-template"),
                "section" => "jon-template-colors",
                "settings" => "header_font_color"
            )
        )
    );

    $wp_customize->add_control( 
        new WP_Customize_Color_Control( 
        $wp_customize, 
        'xheader_color', 
        array(
            'label'      => __( 'Header Color', 'jon-template' ),
            'section'    => 'jon-template-colors',
            'settings'   => 'header_color',
        ) ) 
    );
}
add_action('customize_register', 'jon_template_new_customizer_settings');

?>