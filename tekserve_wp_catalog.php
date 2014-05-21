<?php
/**
 * Plugin Name: Tekserve Wordpress Catalog
 * Plugin URI: https://github.com/bangerkuwranger
 * Description: Integrates turn.js into a module that makes a virtual catalog element consisting of dynamically loading pages `(post_type=tekserve_catalog_page)` that can contain links to dynamically loaded products `(post_type=tekserve_catalog_product)`
 * Version: 1.0
 * Author: Chad A. Carino
 * Author URI: http://www.chadacarino.com
 * License: MIT
 */
/*
The MIT License (MIT)
Copyright (c) 2014 Chad A. Carino
 
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
function create_post_type_tekserve_catalog_page() {

	$labels = array(
		'name'                => _x( 'Catalog Pages', 'Post Type General Name', 'tekserve_catalog_page' ),
		'singular_name'       => _x( 'Catalog Page', 'Post Type Singular Name', 'tekserve_catalog_page' ),
		'menu_name'           => 'Catalog Page',
		'parent_item_colon'   => 'Parent Catalog Page:',
		'all_items'           => 'All Catalog Pages',
		'view_item'           => 'View Catalog Page',
		'add_new_item'        => 'Add New Catalog Page',
		'add_new'             => 'Add New',
		'edit_item'           => 'Edit Catalog Page',
		'update_item'         => 'Update Catalog Page',
		'search_items'        => 'Search Catalog Page',
		'not_found'           => 'Not found',
		'not_found_in_trash'  => 'Not found in Trash',
	);
	$rewrite = array(
		'slug'                => 'catalogpage',
		'with_front'          => true,
		'pages'               => true,
		'feeds'               => true,
	);
	$args = array(
		'label'               => 'tekserve_catalog_page',
		'description'         => 'Pages that populate the catalog',
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'page-attributes', 'post-formats', ),
		'taxonomies'          => array( 'category', 'post_tag' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicok-book',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'query_var'           => 'tekserve_catalog_page',
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
	);
	register_post_type( 'tekserve_catalog_page', $args );

}

add_action( 'init', 'create_post_type_tekserve_catalog_page', 0 );


//include scripts, (turn.js) and styles
add_action( 'wp_enqueue_scripts', 'tekserve_wp_catalog_scripts' );
	
function tekserve_wp_catalog_scripts() {
	wp_enqueue_script( 'modernizr', plugins_url( '/lib/modernizr.2.5.3.min.js', __FILE__ ), array(), '2.5.3', true );
	wp_enqueue_script( 'jgestures', plugins_url( '/lib/jgestures.min.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), '0.9.0', true );
	wp_enqueue_script( 'jquery-mousewheel', plugins_url( '/lib/jquery.mousewheel.min.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), '3.0.6', true );
	wp_enqueue_script( 'turn', plugins_url( '/lib/turn.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), '4.1.0', true );
	wp_enqueue_script( 'turn-html4', plugins_url( '/lib/turn.html4.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), '4.1.0', true );
// 	wp_enqueue_script( 'turn', plugins_url( '/lib/turn.min.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), '4.1.0', true );
// 	wp_enqueue_script( 'turn-html4', plugins_url( '/lib/turn.html4.min.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), '4.1.0', true );
	wp_enqueue_script( 'hash', plugins_url( '/lib/hash.js', __FILE__ ), array( 'turn' ) );
	wp_enqueue_script( 'turn-scissor', plugins_url( '/lib/scissor.js', __FILE__ ), array( 'turn' ) );
// 	wp_enqueue_script( 'turn-scissor', plugins_url( '/lib/scissor.min.js', __FILE__ ), array( 'turn' ) );
	wp_enqueue_script( 'turn-zoom', plugins_url( '/lib/zoom.js', __FILE__ ), array( 'turn' ) );
// 	wp_enqueue_script( 'turn-zoom', plugins_url( '/lib/zoom.min.js', __FILE__ ), array( 'turn' ) );
}