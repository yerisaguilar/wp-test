
<?php 
 get_header();
?>
<?php
//loops if have post 
   while (have_posts()) {
       the_post();
?>
<h2><a href=<?php the_permalink();?> target="_blank" ><?php the_title();?></a></h2>
<?php the_content();?>
<hr>

<?php
       
   }
?>
<?php
get_footer();
?>

