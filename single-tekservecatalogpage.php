<?php

/*
Template Name: Tekserve Wordpress Catalog Page - Single
*/

// FORCE FULL WIDTH LAYOUT
add_filter ( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_footer', 'genesis_do_footer' );



genesis();