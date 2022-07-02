<?php
get_header( );

pageBanner(array(
    'title' => get_the_title(),
    'subtitle'=>'A recap of our past events.'
  ));
?>
<div class="container container--narrow page-section">
    <?php 
    $today = date('Ymd');
            //custom query
    $pastEvents = new WP_Query(array(
        'paged' => get_query_var('paged', 1),
        // 'post_per_page' => 1,
        // -1returns everyting that meets your query all at onece
        'post_type' => 'event',
        'meta_key'=> 'event_date',//must also be present in the query. This value allows for numerical sorting as noted above in ‘meta_value‘.
        'orderby' => 'meta_value_num',//rand for random
        'order' => 'ASC',//by default this is DESC desending
        'meta_query' => array(// shows just the post taht are equal or more than the actual date
        array(
            'key' => 'event_date',
            'compare' => '<',
            'value' => $today,
            'type' => 'numeric'
        )
        )
    ));

    while ($pastEvents->have_posts()) {
      # code...
      $pastEvents->the_post(  );
      get_template_part('template-parts/content-event');
   
    }
    echo paginate_links( array(
        'total' => $pastEvents->max_num_pages
    ));
    ?>
</div>
<?php 
get_footer(  );
?>