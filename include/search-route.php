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
    'post_type' => array('post','page','profesor','program','event','campus'),
    's' => sanitize_text_field( $_GET['term'] ) //$_GET['term']
   ));
   $results = array(
    'generalInfo' => array() ,
    'profesors' => array(),
    'programs' => array(),
    'events' => array(),
    'campuses' => array()
   );

   while ($mainQuery->have_posts()) {
    $mainQuery->the_post();
    if(get_post_type() == 'post' OR get_post_type() == 'page'){
        array_push($results['generalInfo'],array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'postType' => get_post_type( ),
            'author' => get_author_name(  )
        ));
    }

    if(get_post_type() == 'profesor'){
        array_push($results['profesors'],array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'thumbnail' =>  get_the_post_thumbnail_url(0,'professorLandscape')

        ));
    }
    
    if(get_post_type() == 'program'){
        array_push($results['programs'],array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'content' => get_the_content(),
            'id' => get_the_ID(  )
        ));
    }
    
    if(get_post_type() == 'event'){
        
        $eventDate = new DateTime(get_field('event_date'));
        $description = null;
        if(has_excerpt()){
            $description = get_the_excerpt();
        }else{
            $description = wp_trim_words( get_the_content(), 18 );
        }
        array_push($results['events'],array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'month' => $eventDate -> format('M'),
            'day' => $eventDate -> format('d'),
            'excerpt' => $description,
            'content' => wp_trim_words( get_the_content(), 10 )
        ));
    }
    
    if(get_post_type() == 'campus'){
        array_push($results['campuses'],array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'content' => get_the_content()
        ));
    }
    
   
   }
   if($results['programs']){
    $programsMetaQuery = array('relation' => 'OR');

    foreach($results['programs'] as $item){
     array_push($programsMetaQuery, array(
         'key' => 'related_programs',
         'compare' => 'LIKE',
         'value' => '"'.$item['id'].'"'
     ));
    }
 // Releated programs and professors
    $programRelationshipQuery = new WP_Query(array(
     'post_type' => array('profesor','event'),
     'meta_query' => $programsMetaQuery
         ));
     while ($programRelationshipQuery -> have_posts()) {
         # code...
         $programRelationshipQuery->the_post();
         if(get_post_type() == 'profesor'){
             array_push($results['profesors'],array(
                 'title' => get_the_title(),
                 'permalink' => get_the_permalink(),
                 'thumbnail' =>  get_the_post_thumbnail_url(0,'professorLandscape')
     
             ));
         }
         if(get_post_type() == 'event'){
        
            $eventDate = new DateTime(get_field('event_date'));
            $description = null;
            if(has_excerpt()){
                $description = get_the_excerpt();
            }else{
                $description = wp_trim_words( get_the_content(), 18 );
            }
            array_push($results['events'],array(
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'month' => $eventDate -> format('M'),
                'day' => $eventDate -> format('d'),
                'excerpt' => $description,
                'content' => wp_trim_words( get_the_content(), 10 )
            ));
        }
         
 
     }
     //delete duplicated porfesors and events
     $results['profesors'] = array_values(array_unique($results['profesors'],SORT_REGULAR));
     $results['events'] = array_values(array_unique($results['events'],SORT_REGULAR));
  
   }
    return $results;
}
?>