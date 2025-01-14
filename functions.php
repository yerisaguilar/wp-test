<?php

require get_theme_file_path( 'include/search-route.php' );

//add a field to the WP API 
function university_custom_rest(){
  register_rest_field( 'post', 'authorName', array(
    'get_callback' => function(){return get_the_author(  );}
  ) );
  register_rest_field( 'note', 'userNoteCount', array(
    'get_callback' => function(){return count_user_posts( get_current_user_id(), 'note');}
  ) );
}

add_action( 'rest_api_init', 'university_custom_rest' );

function pageBanner($args = NULL) {
  
    if (!$args['title']) {
      $args['title'] = get_the_title();
    }
  
    if (!$args['subtitle']) {
      $args['subtitle'] = get_field('page_banner_subtitle');
    }
  
    if (!$args['photo']) {
      if (get_field('page_banner_background_image') AND !is_archive(  ) AND !is_home(  )) {
        $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
      } else {
        $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
      }
    }
  
    ?>
    <div class="page-banner">
      <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
      <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
        <div class="page-banner__intro">
          <p><?php echo $args['subtitle']; ?></p>
        </div>
      </div>  
    </div>
  <?php }
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
    wp_localize_script( 'main-university-js', 'universityData', array(
      'root_url' => get_site_url(),
      'nonce' => wp_create_nonce( 'wp_rest' )
    ) );
}
//calls the university_files function when loads header
add_action( 'wp_enqueue_scripts','university_files' );

function university_features(){
    // register_nav_menu( 'headerMenuLocation', 'Header Menu Location' );//register header menu location
    
    // register_nav_menu( 'footerLocationOne', 'Footer Location One' );//explore menu
    // register_nav_menu( 'footerLocationTwo', 'Footer Location Two' );//learn menu

    add_theme_support( 'title-tag');
    add_theme_support( 'post-thumbnails');
    //set image sizes for proffesor
    add_image_size( 'professorLandscape', 400, 260, true );//array('left','top')
    add_image_size( 'profesorPortrait', 480, 650, true );//
    //banner
    add_image_size('pageBanner',1500,350,true);
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

// Redirect subscriber accounts out of admin and into home page
add_action( 'admin_init' , 'redirectSubsToFrontEnd');

function redirectSubsToFrontEnd(){
  $ourCurrentUser = wp_get_current_user(  );
  if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber'){
    wp_redirect( site_url('/') );
    exit;
  }
}
///
add_action( 'wp_loaded' , 'noSubsAdminBar');

function noSubsAdminBar(){
  $ourCurrentUser = wp_get_current_user(  );
  if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber'){
    show_admin_bar( false );
  }
}

//Customize login screen
add_filter( 'login_headerurl','ourHeaderURL' );

function ourHeaderURL(){
  return esc_url( site_url( '/' ) );
}

add_action( 'login_enqueue_scripts', 'ourLoginCSS' );
function ourLoginCSS(){
  wp_enqueue_style( 'custom-google-fonts', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  //load font awesome library
  wp_enqueue_style( 'font-awesome', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  //calle a wp funtion and point to the css file
  wp_enqueue_style( 'university_main_styles', get_theme_file_uri( 'build/style-index.css' ));
  wp_enqueue_style( 'university_extra_styles', get_theme_file_uri( 'build/index.css' ));
 
}
add_filter('login_headertitle', 'ourHeaderTitle');
 
function ourHeaderTitle() {
    return get_option('blogname');
}
//force note posts to be private
add_filter( 'wp_insert_post_data', 'makeNotePrivate' , 10,2);

function  makeNotePrivate($data, $postarr) {
  if($data['post_type'] == 'note'){
    if(count_user_posts( get_current_user_id(), 'note') > 4 AND !$postarr['ID'] ){//if the user already created more than 4 post
      die("You have reach your note limit");

    }
    //sanitize fields
    $data['post_content'] = sanitize_textarea_field( $data['post_content']);
    $data['post_content'] = sanitize_text_field( $data['post_title']);
  }
  if($data['post_type'] == 'note' AND $data['post_status'] != 'trash'){
    $data['post_status'] = 'private';

  }
  return $data;

}
// remove "Private: " from titles 
// function removePrivateText() {
//   return "%s";
// }
 
// add_filter( 'private_title_format', 'removePrivateText' );
?> 