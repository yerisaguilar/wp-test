<?php 
 get_header();
?>
<?php
// this block of code is for a single page
//loops if have post 
   while (have_posts()) {
       the_post();
?>
<div class="page-banner">
    <div class="page-banner__bg-image"
        style="background-image: url(<?php echo get_theme_file_uri('images/ocean.jpg')?>)"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title"><?php the_title( )?></h1>
        <div class="page-banner__intro">
            <p>DON'T FORGET TO REPLACEME LATER</p>
        </div>
    </div>
</div>
<div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link( 'program' );?>">
                <i class="fa fa-home" aria-hidden="true"></i> All Programs
            </a>
            <span class="metabox__main">
                <?php 
            the_title(  );
            ?>
            </span>
        </p>
    </div>
    <div class="generic-content">
        <?php the_content(  );?>
    </div>
    <?php 
    $today = date('Ymd');
    //custom query
    $homepageEvents = new WP_Query(array(
        'post_per_page' => 2,// -1returns everyting that meets your query all at onece
        'post_type' => 'event',
        'meta_key'=> 'event_date',//must also be present in the query. This value allows for numerical sorting as noted above in ‘meta_value‘.
        'orderby' => 'meta_value',//rand for random
        'order' => 'ASC',//by default this is DESC desending
        'meta_query' => array(// shows just the post taht are equal or more than the actual date
        array(
            'key' => 'event_date',
            'compare' => '>=',
            'value' => $today,
            'type' => 'numeric'
        ),
        array(
            'key' => 'related_programs',//array(12,120,1250)
            'compare' => 'LIKE',
            'value' => '"'. get_the_ID().'"'
        )
        )
    ));
    ?>
    <?php
    if($homepageEvents->have_posts()){
        echo '<hr class="section-break"/>';
    echo '<h2 class="headline headline--medium">Upcoming '.get_the_title().' Events</h2>';
    while ($homepageEvents -> have_posts()) {
        $homepageEvents->the_post();
        ?>
    <div class="event-summary">
        <a class="event-summary__date t-center" href="#">
            <span class="event-summary__month"><?php 
                $eventDate = new DateTime(get_field('event_date'));
               echo $eventDate -> format('M');// return month
                ?></span>
            <span class="event-summary__day"><?php echo $eventDate -> format('d'); ?></span>
        </a>
        <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a
                    href="<?php the_permalink(); ?>"><?php the_title( ); ?></a></h5>
            <p><?php if(has_excerpt()){
                      the_excerpt();
                  }else{
                    // echo has_excerpt();
                    echo wp_trim_words( get_the_content(), 10 );
                  }?><a href="<?php the_permalink();?>" class="nu gray">Learn more</a></p>
        </div>
    </div>
    <?php
            }
    }
    ?>
</div>

<?php
       
   }
?>
<?php
get_footer();
?>