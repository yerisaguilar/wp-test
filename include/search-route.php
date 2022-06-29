<?php 
add_action( 'rest_api_init', 'universityRegisterSearch' );
function universityRegisterSearch(){
    register_rest_route( 'university/v1', 'search', array(
        'methods' => WP_REST_SERVER::READABLE,
        'callback' => 'universitySearchResults'
    ));
}

function universitySearchResults(){
   $mainQuery= new WP_Query(array(
    'post_type' => array('post','page','profesor','programs','events','campuses'),
    's' => sanitize_text_field( $_GET['term'] ) 
   ));
   $results = array(
    'generalInfo' => array() ,
    'profesors' => array(),
    'program' => array(),
    'event' => array(),
    'campuses' => array()
   );

   while ($mainQuery->have_posts()) {
    $mainQuery->the_post();
    if(get_post_type() == 'post' OR get_post_type() == 'page'){
        array_push($results['generalInfo'],array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'content' => get_the_content()
        ));
    }

    if(get_post_type() == 'profesors'){
        array_push($results['profesors'],array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'content' => get_the_content()
        ));
    }
    
    if(get_post_type() == 'program'){
        array_push($results['programs'],array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'content' => get_the_content()
        ));
    }
    
    if(get_post_type() == 'event'){
        array_push($results['events'],array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'content' => get_the_content()
        ));
    }
    
    if(get_post_type() == 'campuse'){
        array_push($results['campuses'],array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'content' => get_the_content()
        ));
    }
    
   
   }

   return $results;
}
?>