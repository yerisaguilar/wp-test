<?php 
 get_header();
?>
<?php
// this block of code is for a single page
//loops if have post 
   while (have_posts()) {
       the_post();
?>
<h2><?php the_title();?></h2>
<?php the_content();?>
<?php
       
   }
?>
<?php
get_footer();
?>