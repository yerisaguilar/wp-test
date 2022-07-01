<?php 
 get_header();
?>
<?php
// this block of code is for a single page
//loops if have page
   while (have_posts()) {
       the_post();
       pageBanner( );
?>


<div class="container container--narrow page-section">
       <?php 
       $parent = wp_get_post_parent_id( get_the_ID());
       if($parent){
         //have childs
          ?>
         <div class="metabox metabox--position-up metabox--with-home-link"><p>
         <a class="metabox__blog-home-link" href="<?php echo get_permalink( $parent)?>">
            <i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($parent)?>
         </a> 
         <span class="metabox__main"><?php echo the_title()?></span>
            </p>
         </div>
         

         <?php

       }
       ?>
       <?php
       $testArray = get_pages(array(
           'child_of'=> get_the_ID()
       ));
        if($parent or $testArray){
           ?>
       <div class="page-links">
        <h2 class="page-links__title"><a href="<?php echo get_permalink( $parent)?>"><?php echo get_the_title($parent)?></a></h2>
        <ul class="min-list">
           <?php 
           if($parent){
               $findChildrenOf = $parent;

           }else{
            $findChildrenOf = get_the_ID(  );
           }
           wp_list_pages( array(
               'title_li'=> null, /// set to dont se pages text
               'child_of'=> $findChildrenOf,//just displays the menu of the correspoding parent
               'sort_column' => 'menu_order'//get menu order form wp admin 
           ));
           ?>

        </ul>
      </div>
        <?php }?>

    

    <div class="generic-content">
        <form class="search-form" method="get" action="<?php echo esc_url( site_url( '/' ) ); ?>">
        <label class="headline headline--medium" for="s"> Perform a new search: </label>
            <div class="search-form-row">
                <input class="s" type="search" name="s" id="s" placeholder="What are you looking for?">
                <input class="search-submit" type="submit" value="Search">
            </div>
        </form>
    </div>
</div>

<?php
       
   }
?>
<?php
get_footer();
?>