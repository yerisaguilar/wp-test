<?php 
//60
 get_header();
?>
<?php
// this block of code is for a single page
//loops if have post 
   while (have_posts()) {
       the_post();
       pageBanner();
?>

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
        <?php  the_field('main_body_content');?>
    </div>
    <?php 

$today = date('Ymd');
//custom query
$relatedProfessor = new WP_Query(array(
    'post_per_page' => -1,// -1returns everyting that meets your query all at onece
    'post_type' => 'profesor',
    'orderby' => 'title',//rand for random
    'order' => 'ASC',//by default this is DESC desending
    'meta_query' => array(// shows just the post taht are equal or more than the actual date
   
    array(
        'key' => 'related_programs',//array(12,120,1250)
        'compare' => 'LIKE',
        'value' => '"'. get_the_ID().'"'
    )
    )
));
?>
<?php
if($relatedProfessor->have_posts()){
    echo '<hr class="section-break"/>';
echo '<h2 class="headline headline--medium">'.get_the_title().' Profesors</h2>';
echo '<ul class="professor-cards">';
while ($relatedProfessor -> have_posts()) {
    $relatedProfessor->the_post();
    //63
    ?>
<li class="professor-card__list-item">
    <a class="professor-card" href="<?php echo the_permalink(); ?>">
    <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape') ?>" alt="">
    <span class="professor-card__name"><?php the_title( ); ?></span>
</a></li>
<?php
        }
        echo '</ul>';
}

wp_reset_postdata();
?>
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
        
        get_template_part('template-parts/content-event');
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