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
    
    <div class="generic-content">
        <div class="row group">
            <div class="one-third">
                <?php  the_post_thumbnail('profesorPortrait'); ?>
            </div>
            <div class="two-thirds">
                <?php  the_content(  ); ?>
            </div>
        </div>
    </div>
    <?php 
   $relatedPrograms = get_field('related_programs'); // gets the related program 

   if($relatedPrograms){
    
     echo '<hr class="section-break"/>
   <h2 class="headline headline--medium">Subject(s) Taught</h2>';
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