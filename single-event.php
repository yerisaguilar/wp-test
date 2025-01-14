<?php 
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
            <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link( 'event' );?>">
                <i class="fa fa-home" aria-hidden="true"></i> Event Home
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
   $relatedPrograms = get_field('related_programs'); // gets the related program 

   if($relatedPrograms){
    
     echo '<hr class="section-break"/>
   <h2 class="headline headline--medium">Related Program(s)</h2>';
   echo '<ul class="link-list min-list">';
   foreach ($relatedPrograms as $program) {
    # code...
   ?>
    <li>
        <a href="<?php the_permalink( $program) ?>"><?php  echo get_the_title($program); ?></a>
    </li>
    <?php
   }
   echo '</ul>';
   }
  
   ?>
</div>
<?php
       
   }
?>
<?php
get_footer();
?>