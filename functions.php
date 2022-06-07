<?php
//get main style css file
function university_files(){
    //called a wp funtion and point to the css file
    wp_enqueue_style( 'university_main_styles', get_stylesheet_uri());
}
//calls the university_files function when loads header
add_action( 'wp_enqueue_scripts','university_files' );




?>