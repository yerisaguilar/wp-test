<?php 
 get_header();
?>
<?php
// this block of code is for a single page
//loops if have page
   while (have_posts()) {
       the_post();
?>
<h1>This is a Page</h1>
<h2><?php the_title();?></h2>
<?php the_content();?>
<?php
       
   }
?>
<?php
get_footer();
?>