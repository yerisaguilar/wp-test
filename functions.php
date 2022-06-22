<?php
//get main style css file
function university_files(){
    //load JS file
    wp_enqueue_script('main-university-js', get_theme_file_uri('build/index.js'),array('jquery'), '1.0', true);
     //load google fonts file
    wp_enqueue_style( 'custom-google-fonts', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    //load font awesome library
    wp_enqueue_style( 'font-awesome', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    //calle a wp funtion and point to the css file
    wp_enqueue_style( 'university_main_styles', get_theme_file_uri( 'build/style-index.css' ));
    wp_enqueue_style( 'university_extra_styles', get_theme_file_uri( 'build/index.css' ));
    wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

}
//calls the university_files function when loads header
add_action( 'wp_enqueue_scripts','university_files' );

function university_features(){
    // register_nav_menu( 'headerMenuLocation', 'Header Menu Location' );//register header menu location
    
    // register_nav_menu( 'footerLocationOne', 'Footer Location One' );//explore menu
    // register_nav_menu( 'footerLocationTwo', 'Footer Location Two' );//learn menu

    add_theme_support( 'title-tag');
    add_theme_support( 'post-thumbnails');
    add_image_size( 'professorLandscape', 400, 260, false );
}

add_action('after_setup_theme', 'university_features' );

function university_adjust_queries ($query){
    $today = date('Ymd');
    if(!is_admin(  ) AND is_post_type_archive( 'program' ) AND $query->is_main_query(  )){
        $query->set('order','title');
        $query->set('order','ASC');
        $query->set('posts_per_page',-1);
    }
   if(!is_admin(  ) AND is_post_type_archive( 'event' ) AND $query->is_main_query(  )){//only for event
    $query->set('meta_key','event_date');
    $query->set('orderby','meta_value_num');
    $query->set('order','ASC');
    $query->set('meta_query',array(
        array(
            'key' => 'event_date',
        'compare'=> '>=',
        'value' => $today,
        'type' => 'numeric'
        )
    ));
   }

}
add_action('pre_get_posts', 'university_adjust_queries');
?> 