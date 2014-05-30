<?php
/**
 * Plugin Name: Tekserve Wordpress Catalog
 * Plugin URI: https://github.com/bangerkuwranger
 * Description: Integrates turn.js into a module that makes a virtual catalog element consisting of dynamically loading pages `(post_type=tekserve_catalog_page)` that can contain links to dynamically loaded products `(post_type=tekserve_catalog_product)`
 * Version: 1.0
 * Author: Chad A. Carino
 * Author URI: http://www.chadacarino.com
 * License: MIT
 *
The MIT License (MIT)
Copyright (c) 2014 Chad A. Carino
 
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
//add support for specific image sizes
add_image_size( 'cpu', 1280, 1280, false );
add_image_size( 'ipadp', 768, 768, false );
add_image_size( 'ipadl', 670, 670, false );
add_image_size( 'iphonep', 320, 320, false );
add_image_size( 'iphonel', 314, 314, false );

add_action( 'wp_enqueue_scripts', 'tekserve_wp_catalog_scripts');

function create_post_type_tekserve_catalog_page() {

	$labels = array(
		'name'                => 'Catalog Pages',
		'singular_name'       => 'Catalog Page',
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
		'label'               => 'tekservecatalogpage',
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
		'menu_icon'           => 'dashicons-book',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'query_var'           => 'tekservecatalogpage',
		'rewrite'             => $rewrite,
		'capability_type'     => 'page',
	);
	register_post_type( 'tekservecatalogpage', $args );
}

add_action( 'init', 'create_post_type_tekserve_catalog_page', 0 );

//retrieve all catalog pages
function get_catalog_pages( $collection = '' ) {
	$args = array(
		'numberposts'	=>	-1,
		'post_type'		=>	'tekservecatalogpage',
		'orderby'		=>	'menu_order',
		'order'			=>	'ASC',
	);
	if( ! empty( $collection ) ) {
		$args["category"] = $collection;
	}
	$catalog_pages = get_posts( $args );
	return $catalog_pages;
}

function retrieve_catalog_page( $position, $catalog_pages ) {
	$catalog_page_content = $catalog_pages[$position]->post_content;
	$catalog_page_content = imgmap_replace_shortcode( $catalog_page_content );
	return $catalog_page_content;
}

//default to 1 page
$howmanypages = 1;

// Add Shortcode
function tekcatalog_shortcode( $atts ) {

	// Attributes
	extract( shortcode_atts(
		array(
			'catalog_collection' => '',
		), $atts )
	);

	// Code
	global $howmanypages;
	tekserve_wp_catalog_scripts();
	if( empty( $catalog_collection ) ) {
		$bookid = 'tekserve-catalog';
	}
	else {
		$bookid = 'tekserve-catalog' . $catalog_collection;
	}
	$catalog_pages = get_catalog_pages( $catalog_collection );
	if( isset( $catalog_pages ) ) {
		$howmanypages = count( $catalog_pages );
	}
	$book = '<div id="zoom-viewport">
		<div class="tekserve-catalog" id="' . $bookid . '">';
	$book .= '<div class="">
	' . retrieve_catalog_page( 0, $catalog_pages ) . '
	</div>';
	// if( $howmanypages > 2 ) {
// 		$book .= '
// 		<div>
// 		' . retrieve_catalog_page( 1, $catalog_pages ) . '
// 		</div>';
// 		$book .= '
// 		<div>
// 		' . retrieve_catalog_page( 2, $catalog_pages ) . '
// 		</div>';
// 	}
	for( $i = 1; $i < $howmanypages; $i++ ) {
		$book .= '<div>';
		$book .= retrieve_catalog_page( $i, $catalog_pages );
		$book .= '</div>';
	}
	$book .= '
	</div>
	</div>';
	$book .= print_r($howmanypages,true);
// 	$book .= '<div>' . print_r( $catalog_pages, true ) . '</div>';
	$catalog_page_ids = array();
	$i=0;
	foreach( $catalog_pages as $catalog_page ) {
		$catalog_page_ids[$i] = $catalog_page->ID;
		$i++;
	}
		$jsdata='<script type="text/javascript">
		/* <![CDATA[ */
		var tekserveCatalogData = \'' . serialize( $catalog_page_ids ) . '\';
		/* ]]> */
		</script>';
		$book .= $jsdata;
// 	add_action( 'genesis_meta', 'tekserve_catalog_js_data' );
	add_action( 'genesis_after_footer', 'tekserve_book_js' );
	return $book;
}
add_shortcode( 'tekcatalog', 'tekcatalog_shortcode' );

//book js
function tekserve_book_js( ) {
	global $howmanypages;
	$bookjs = '
	<script type="text/javascript">
		function loadDynPage(page) {
				var newPage = jQuery("<div />", {"class": "p"+page+" dynPage"}).html("<div class=\'pageLoader\'><h1>Loading...</h1><img src=\'' . plugins_url( '/images/ajax-loader.gif', __FILE__ ) . '\' /></div>");
				jQuery(".tekserve-catalog").turn("addPage", newPage);
				var place = "' . base64_encode( plugins_url( '', __FILE__ ) ) . '";
				jQuery.ajax({
				type		: "GET",
				data		: {postid : (page - 1), allposts : tekserveCatalogData},
				dataType	: "html",
				timeout		: 12000,
				url			: "' . plugins_url( '/dyn_page.php', __FILE__ ) . '",
				beforeSend	: function(){
					
				},
				success    : function(data){
					$data = jQuery(data);
					$body = $data.children("div");
					if($data.length) {
						console.log("returned with page: "+page+"- \n "+$body);
// 						$data.hide();
						jQuery(".p"+page+" .pageLoader").remove();
						newPage.append($body);
// 						$data.fadeIn();
					}
				},
				error     : function(jqXHR, textStatus, errorThrown) {
					jQuery(".p"+page+".pageLoader").hide();
					alert(jqXHR + " :: " + textStatus + " :: " + errorThrown);
				}
			});
			}
		jQuery( document ).ready(function( $ ) {
			$(".tekserve-catalog").turn({
			  width: 1280,
			  height: 640,
			  elevation: 1,
			  turnCorners: "bl,br",
			  autoCenter: true,
			  pages: ' . $howmanypages . ',
			});
			$("#zoom-viewport").zoom({
				flipbook: $(".tekserve-catalog"),
				max: 2,
			});
			//$("#zoom-viewport").bind("zoom.doubleTap", function(event) {
			//	if ($(this).zoom("value")==1) {
			//		$(this).zoom("zoomIn", event);
			//	} else {
			//		$(this).zoom("zoomOut");
			//	}
			//});
			$(".tekserve-catalog").bind("turned", function(event, page, view) {
				console.log("turned page: " + page);
				resizeAll();
			});
			$( window ).resize(function() {
			  console.log("Handler for .resize() called.");
			  resizeAll();
			});
			
// 			$(".tekserve-catalog").bind("turning", function(event, page, view) {
// 			  console.log("Turning the page to: "+page);
// 			  for (var i = 0; i < view.length; i++) {
// 				  if(!$(this).turn("hasPage", view[i])) {
// 					loadDynPage(view[i]);
// 				  }
// 				}
// 			});
// 			$(".tekserve-catalog").bind("missing", function(event, pages) {
// 			  for (var i = 0; i < pages.length; i++) {
// 				  if(!$(this).turn("hasPage", pages[i])) {
// 				  	console.log("Loading page: "+pages[i]);
// 					loadDynPage(pages[i]);
// 				  }
// 				}
// 				resizeAll();
// 			});
			
			
			function resizeAll() {
				var resizeWidth;
				var displayMode = "double";
				
				if($( window ).width() < 321) {
					resizeWidth = 320;
					displayMode = "single";
				}
				else if($( window ).width() < 640) {
					resizeWidth = 240;
				}
				else if($( window ).width() < 769) {
					resizeWidth = 768;
					displayMode = "single";
				}
				else if($( window ).width() < 1025) {
					resizeWidth = 516;
				}
				else {
					resizeWidth = 640;
				}
				$(".tekserve-catalog").turn("display", displayMode);
				if( displayMode == "single" ) {
					$(".tekserve-catalog").turn("size", resizeWidth, resizeWidth);
				}
				else {
					$(".tekserve-catalog").turn("size", (resizeWidth*2), resizeWidth);
				}
				$("img[usemap]").mapster("resize", resizeWidth);
			}
			
			resizeAll();
			
			
		});
		</script>
		';
	echo $bookjs;
}

//require templates

function tekserve_wp_catalog_include_templates_function( $template_path ) {
    if ( get_post_type() == 'tekservecatalogpage' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-tekservecatalogpage.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . 'single-tekservecatalogpage.php';
            }
        }
    }
    return $template_path;
}

add_filter( 'template_include', 'tekserve_wp_catalog_include_templates_function', 1 );



//include scripts, (turn.js) and styles
// add_action( 'wp_enqueue_scripts', 'tekserve_wp_catalog_scripts' );
	
function tekserve_wp_catalog_scripts() {
	wp_enqueue_script( 'modernizr', plugins_url( '/lib/modernizr.2.5.3.min.js', __FILE__ ), array(), '2.5.3', true );
	wp_enqueue_script( 'jgestures', plugins_url( '/lib/jgestures.min.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), '0.9.0', true );
	wp_enqueue_script( 'jquery-mousewheel', plugins_url( '/lib/jquery.mousewheel.min.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), '3.0.6', true );
	wp_enqueue_script( 'turn', plugins_url( '/lib/turn.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), '4.1.0', true );
// 	wp_enqueue_script( 'turn-html4', plugins_url( '/lib/turn.html4.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), '4.1.0', true );
// 	wp_enqueue_script( 'turn', plugins_url( '/lib/turn.min.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), '4.1.0', true );
// 	wp_enqueue_script( 'turn-html4', plugins_url( '/lib/turn.html4.min.js', __FILE__ ), array( 'jquery', 'jquery-ui-core' ), '4.1.0', true );
	wp_enqueue_script( 'hash', plugins_url( '/lib/hash.js', __FILE__ ), array( 'turn' ) );
	wp_enqueue_script( 'turn-scissor', plugins_url( '/lib/scissor.js', __FILE__ ), array( 'turn' ) );
// 	wp_enqueue_script( 'turn-scissor', plugins_url( '/lib/scissor.min.js', __FILE__ ), array( 'turn' ) );
	wp_enqueue_script( 'turn-zoom', plugins_url( '/lib/zoom.js', __FILE__ ), array( 'turn' ) );
	wp_enqueue_style( 'tekserve-catalog-imagemap', plugins_url( '/css/imagemap.css', __FILE__ ) );
// 	wp_enqueue_script( 'turn-zoom', plugins_url( '/lib/zoom.min.js', __FILE__ ), array( 'turn' ) );
}