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


//create custom post type
add_action( 'init', 'create_post_type_tekserve_catalog_page' );
function create_post_type_case_study() {
	register_post_type( 'tekserve_catalog_page',
		array(
			'labels' => array(
				'name' => __( 'Catalog Pages' ),
				'singular_name' => __( 'Catalog Page' ),
				'add_new' => 'Add New',
            	'add_new_item' => 'Add New Catalog Page',
            	'edit' => 'Edit',
            	'edit_item' => 'Edit Catalog Page',
            	'new_item' => 'New Catalog Page',
            	'view' => 'View',
            	'view_item' => 'View Catalog Page',
            	'search_items' => 'Search Catalog Page',
            	'not_found' => 'No Catalog Pages found',
            	'not_found_in_trash' => 'No Catalog Pages found in Trash',
            	'parent' => 'Parent Catalog Page',
			),
			'public' => true,
			'has_archive' => false,
            'supports' => array( 'editor', 'title' ),
		)
	);
}

add_action( 'init', 'create_post_type_tekserve_catalog_product' );
function create_post_type_case_study() {
	register_post_type( 'tekserve_catalog_product',
		array(
			'labels' => array(
				'name' => __( 'Catalog Products' ),
				'singular_name' => __( 'Catalog Product' ),
				'add_new' => 'Add New',
            	'add_new_item' => 'Add New Catalog Product',
            	'edit' => 'Edit',
            	'edit_item' => 'Edit Catalog Product',
            	'new_item' => 'New Catalog Product',
            	'view' => 'View',
            	'view_item' => 'View Catalog Product',
            	'search_items' => 'Search Catalog Product',
            	'not_found' => 'No Catalog Products found',
            	'not_found_in_trash' => 'No Catalog Products found in Trash',
            	'parent' => 'Parent Catalog Product',
			),
			'public' => true,
			'has_archive' => false,
            'supports' => array( 'editor', 'title', 'thumbnail' ),
		)
	);
}

//include scripts, (turn.js) and styles
add_action( 'wp_enqueue_scripts', 'tekserve_wp_catalog_scripts' );
	
function tekserve_wp_catalog_scripts() {
	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/lib/modernizr.2.5.3.min.js', array(), '2.5.3', true );
	wp_enqueue_script( 'jgestures', get_template_directory_uri() . '/lib/jgestures.min.js', array( 'jquery', 'jquery-ui-core' ), '0.9.0', true );
	wp_enqueue_script( 'jquery-mousewheel', get_template_directory_uri() . '/lib/jquery.mousewheel.min.js', array( 'jquery', 'jquery-ui-core' ), '3.0.6', true );
	wp_enqueue_script( 'turn', get_template_directory_uri() . '/lib/turn.js', array( 'jquery', 'jquery-ui-core' ), '4.1.0', true );
	wp_enqueue_script( 'turn-html4', get_template_directory_uri() . '/lib/turn.html4.js', array( 'jquery', 'jquery-ui-core' ), '4.1.0', true );
// 	wp_enqueue_script( 'turn', get_template_directory_uri() . '/lib/turn.min.js', array( 'jquery', 'jquery-ui-core' ), '4.1.0', true );
// 	wp_enqueue_script( 'turn-html4', get_template_directory_uri() . '/lib/turn.html4.min.js', array( 'jquery', 'jquery-ui-core' ), '4.1.0', true );
	wp_enqueue_script( 'hash', get_template_directory_uri() . '/lib/hash.js', array( 'turn' ) );
	wp_enqueue_script( 'turn-scissor', get_template_directory_uri() . '/lib/scissor.js', array( 'turn' ) );
// 	wp_enqueue_script( 'turn-scissor', get_template_directory_uri() . '/lib/scissor.min.js', array( 'turn' ) );
	wp_enqueue_script( 'turn-zoom', get_template_directory_uri() . '/lib/zoom.js', array( 'turn' ) );
// 	wp_enqueue_script( 'turn-zoom', get_template_directory_uri() . '/lib/zoom.min.js', array( 'turn' ) );
}

// //create custom fields for details
// add_action( 'admin_init', 'case_study_details' );
// function case_study_details() {
//     add_meta_box( 'tekserve_case_study_details', 'Case Study Details', 'display_tekserve_case_study_details', 'tekserve_case_study', 'side', 'high' );
// }
// 
// // Retrieve current details based on case study ID
// function display_tekserve_case_study_details( $tekserve_case_study ) {
// 	$tekserve_case_study_organization = esc_html( get_post_meta( $tekserve_case_study->ID, 'tekserve_case_study_organization', true ) );
// 	?>
//     <table>
//         <tr>
//             <td style="width: 100%">Organization</td>
//         </tr>
//         <tr>
//             <td><input type="text" size="30" name="tekserve_case_study_organization" value="<?php echo $tekserve_case_study_organization; ?>" /></td>
//         </tr>
//     </table>
//     <?php
// }
// 
// //store custom field data
// add_action( 'save_post', 'add_tekserve_case_study_fields', 5, 2 );
// function add_tekserve_case_study_fields( $tekserve_case_study_id, $tekserve_case_study ) {
//     // Check post type for 'tekserve_case_study'
//     if ( $tekserve_case_study->post_type == 'tekserve_case_study' ) {
//         // Store data in post meta table if present in post data
//         if ( isset( $_POST['tekserve_case_study_organization'] ) && $_POST['tekserve_case_study_organization'] != '' ) {
//             update_post_meta( $tekserve_case_study_id, 'tekserve_case_study_organization', sanitize_text_field( $_REQUEST['tekserve_case_study_organization'] ) );
//     	}
//     }
// }
// 
// //create metabox for case study
// function add_upload_meta_box() {  
// 	// Define the custom attachment for post type 
//     add_meta_box( 'tekserve_case_study_file', 'Case Study File', 'display_tekserve_case_study_file', 'tekserve_case_study', 'side', 'high' );  
// }
// add_action('admin_init', 'add_upload_meta_box');
// 
// // Retrieve meta based on case study ID
// function display_tekserve_case_study_file($tekserve_case_study) {  
//   
//     wp_nonce_field(plugin_basename(__FILE__), 'tekserve_case_study_file_nonce');  
//       
//     $html = '<p class="tekserve-case-study-file-description">';  
//     $html .= 'Upload your PDF here.';  
//     $html .= '</p>';  
//     $html .= '<input type="file" id="tekserve_case_study_file" name="tekserve_case_study_file" value="" size="25">';
//     $pdf = get_post_meta( $tekserve_case_study->ID, 'tekserve_case_study_file', true );
//     if ( strlen(trim($pdf['url'])) > 0 ) {
//     	$html .= '<p class="current-file">Current File: ' . $pdf['name'] . ' <br/>URL: ' . $pdf['url'] . '</p>';
//     }
//     $html .= '<input type="hidden" id="tekserve-case-study-file-url" name="tekserve_case_study_file_url" value=" ' . $pdf['url'] . '" size="30" />';  
//     if(strlen(trim($pdf['url'])) > 0) {  
//         $html .= '<a href="javascript:;" id="tekserve-case-study-file-delete">' . __('Delete File') . '</a>';  
//     } // end if  
//       
//     echo $html;  
// }
// 
// //store custom file form data
// function add_tekserve_case_study_file($id) {  
//   
//     /* --- security verification --- */  
//     if(!wp_verify_nonce($_POST['tekserve_case_study_file_nonce'], plugin_basename(__FILE__))) {  
//       return $id;  
//     } // end if  
//         
//     if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {  
//       return $id;  
//     } // end if  
//         
//     if('page' == $_POST['post_type']) {  
//       if(!current_user_can('edit_page', $id)) {  
//         return $id;  
//       } // end if  
//     } else {  
//         if(!current_user_can('edit_page', $id)) {  
//             return $id;  
//         } // end if  
//     } // end if  
//     /* - end security verification - */  
// 	
// 	// Make sure the file array isn't empty  
//     if(!empty($_FILES['tekserve_case_study_file']['name'])) { 
//          
//         // Setup the array of supported file types. In this case, it's just PDF.  
//         $supported_types = array('application/pdf');  
//           
//         // Get the file type of the upload  
//         $arr_file_type = wp_check_filetype(basename($_FILES['tekserve_case_study_file']['name']));  
//         $uploaded_type = $arr_file_type['type'];  
//           
//         // Check if the type is supported. If not, throw an error.  
//         if(in_array($uploaded_type, $supported_types)) {  
//   
//             // Use the WordPress API to upload the file  
//             $upload = wp_upload_bits($_FILES['tekserve_case_study_file']['name'], null, file_get_contents($_FILES['tekserve_case_study_file']['tmp_name']));  
//       
//             if(isset($upload['error']) && $upload['error'] != 0) {  
//                 wp_die('There was an error uploading your file. The error is: ' . $upload['error']);  
//             } else {  
//                 add_post_meta($id, 'tekserve_case_study_file', $upload);  
//                 update_post_meta($id, 'tekserve_case_study_file', $upload);       
//             } // end if/else  
//   
//         } else {  
//             wp_die("The file type that you've uploaded is not a PDF.");  
//         } // end if/else  
//           
//     }
//     else {  
//         // Grab a reference to the file associated with this post  
//         $doc = get_post_meta($id, 'tekserve_case_study_file', true); 
//          
//         // Grab the value for the URL to the file stored in the text element 
//         $delete_flag = get_post_meta($id, 'tekserve_case_study_file_url', true); 
//          
//         // Determine if a file is associated with this post and if the delete flag has been set (by clearing out the input box) 
//         if(strlen(trim($doc['url'])) > 0 && strlen(trim($delete_flag)) == 0) { 
//          
//             // Attempt to remove the file. If deleting it fails, print a WordPress error. 
//             if(unlink($doc['file'])) { 
//                  
//                 // Delete succeeded so reset the WordPress meta data 
//                 update_post_meta($id, 'tekserve_case_study_file', null); 
//                 update_post_meta($id, 'tekserve_case_study_file_url', ''); 
//                  
//             } 
//             else { 
//             	wp_die('There was an error trying to delete your file.'); 
//             } // end if/else 
//         } // end if 
//     } // end if/else 
// }
// 
// add_action('save_post', 'add_tekserve_case_study_file');  
// 
// //support file upload
// function update_edit_form() {  
//     echo ' enctype="multipart/form-data"';  
// } // end update_edit_form  
// add_action('post_edit_form_tag', 'update_edit_form'); 
// 
// //set title to quote+name+organization+id
// function tekserve_case_study_set_title ($post_id, $post_content) {
//     if ( $post_id == null || empty($_POST) )
//         return;
// 
//     if ( !isset( $_POST['post_type'] ) || $_POST['post_type']!='tekserve_case_study' )  
//         return; 
// 
//     if ( wp_is_post_revision( $post_id ) )
//         $post_id = wp_is_post_revision( $post_id );
// 
//     global $post;  
//     if ( empty( $post ) )
//         $post = get_post($post_id);
// 
//     if ( $_POST['tekserve_case_study_organization']!='' ) {
//         global $wpdb;
//         $title = '"' . $_POST['content'] . '" - ' . $_POST['tekserve_case_study_organization'] . ' Case Study. ID - ' . $post_id;
//         $where = array( 'ID' => $post_id );
//         $wpdb->update( $wpdb->posts, array( 'post_title' => $title ), $where );
//     }
// }
// add_action('save_post', 'tekserve_case_study_set_title', 15, 2 );

// if ( ! function_exists('tekserve_case_study_type') ) {
// 
// // register case study type taxonomy
// function tekserve_case_study_type()  {
// 
// 	$labels = array(
// 		'name'                       => 'Case Study Types',
// 		'singular_name'              => 'Case Study Type',
// 		'menu_name'                  => 'Case Study Type',
// 		'all_items'                  => 'All Case Study Types',
// 		'parent_item'                => 'Parent Case Study Type',
// 		'parent_item_colon'          => 'Parent Case Study Type:',
// 		'new_item_name'              => 'New Case Study Type',
// 		'add_new_item'               => 'Add New Case Study Type',
// 		'edit_item'                  => 'Edit Case Study Type',
// 		'update_item'                => 'Update Case Study Type',
// 		'separate_items_with_commas' => 'Separate Case Study Types with commas',
// 		'search_items'               => 'Search Case Study Types',
// 		'add_or_remove_items'        => 'Add or remove Case Study Types',
// 		'choose_from_most_used'      => 'Choose from the most used Case Study Types',
// 	);
// 	$args = array(
// 		'labels'                     => $labels,
// 		'hierarchical'               => false,
// 		'public'                     => true,
// 		'show_ui'                    => true,
// 		'show_admin_column'          => true,
// 		'show_in_nav_menus'          => false,
// 		'show_tagcloud'              => false,
// 		'query_var'                  => 'tekserve-case-study-type',
// 		'rewrite'                    => false,
// 	);
// 	register_taxonomy( 'tekserve-case-study-type', 'tekserve_case_study', $args );
// 
// }
// 
// // Hook into the 'init' action
// add_action( 'init', 'tekserve_case_study_type', 0 );
// 
// }
// 
// // add shortcode tekserve-case-study
// //e.g. [tekserve-case-study id="58"] -or- [tekserve-case-study type="b2b"]
// function tekserve_case_study_shortcode( $atts ) {
// 
// 	// attributes
// 	extract( shortcode_atts(
// 		array(
// 			'type' => '',
// 			'id' => '',
// 		), $atts )
// 	);
// 	
// 	//display multiple, i.e. if type is passed in shortcode
//         if ($atts['type'] != ''){
//             $casestudies = NEW WP_Query( array( 'post_type' => 'tekserve_case_study', 'tekserve-case-study-type' => $atts['type'], 'orderby' => 'rand' ) );
//             while($casestudies->have_posts()){
//                 $casestudies->the_post();
//                 if ( genesis_get_custom_field('tekserve_case_study_organization') != '' ) {
//                 	$organization = genesis_get_custom_field('tekserve_case_study_organization');
//                 }
//                 else {
//                 	$organization = '';
//                 }
//                 $pdf = get_post_meta( get_the_ID(), 'tekserve_case_study_file', true );
//                 //looped output
//                 $out .= '<li>
// 							<div class="tekserve-case-study">
// 								<div class="tekserve-case-study-text">
// 									' . apply_filters( 'the_content', get_the_content() ) . '
// 								</div>';
//     			if ( strlen(trim($pdf['url'])) > 0 ) {
//     				$out .= "<div class='tekserve-case-study-cta'>
// 									<a href='" . $pdf['url'] . "' target='_blank'>Read our complete case study on " . $organization . " </a>
// 								</div>";
// 				}
// 				$out .=		"</div>
// 						</li>";
//             }
//             return '<ul class="tekserve-case-study-ul">' . $out . '</ul>';
//         }
// 
//         //display a single case study, i.e. if id is passed in shortcode
//         $casestudy = NEW WP_Query( array( 'post_type' => 'tekserve_case_study','post__in' => array($id) ) );
//             while( $casestudy->have_posts() ) {
//                 $casestudy->the_post();
//                 if ( genesis_get_custom_field('tekserve_case_study_organization') != '' ) {
//                 	$organization = genesis_get_custom_field('tekserve_case_study_organization');
//                 }
//                 else {
//                 	$organization = '';
//                 }
//                 $pdf = get_post_meta( get_the_ID(), 'tekserve_case_study_file', true );
// 
//                 //output single div with case study
//                 $out = '<div class="tekserve-case-study">
// 								<div class="tekserve-case-study-text">
// 									' . apply_filters( 'the_content', get_the_content() ) . '
// 								</div>';
// 				if ( strlen(trim($pdf['url'])) > 0 ) {
//     				$out .= "<div class='tekserve-case-study-cta'>
// 									<a href='" . $pdf['url'] . "' target='_blank'>Read our complete case study on " . $organization . " </a>
// 								</div>";
// 				}
// 				$out .=		"</div>";
//             }
//         return $out;
// }
// add_shortcode( 'tekserve-case-study', 'tekserve_case_study_shortcode' );
// 
// //include css /js
// function include_tekserve_case_study_files() {
// 	wp_enqueue_style ( 'tekserve_case_study', plugins_url().'/tekserve-case-studies/tekserve_case_studies.css' );
// 	wp_register_script('tekserve_case_study_js', plugins_url().'/tekserve-case-studies/tekserve_case_studies.js'); 
// 	wp_enqueue_script('tekserve_case_study_js'); 
// }
// 
// add_action( 'wp_enqueue_scripts', 'include_tekserve_case_study_files' );
// 
// 
// function tekserve_case_study_type_filter() {
// 	global $typenow;
//  
// 	// array of all the taxonomies to display, using taxonomy name or slug
// 	$taxonomies = array('tekserve-case-study-type');
//  
// 	// check for post type before creating menu
// 	if( $typenow == 'tekserve_case_study' ){
//  
// 		foreach ($taxonomies as $tax_slug) {
// 			$tax_obj = get_taxonomy($tax_slug);
// 			$tax_name = $tax_obj->labels->name;
// 			$terms = get_terms($tax_slug);
// 			if(count($terms) > 0) {
// 				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
// 				echo "<option value=''>Show All $tax_name</option>";
// 				foreach ($terms as $term) { 
// 					echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>'; 
// 				}
// 				echo "</select>";
// 			}
// 		}
// 	}
// }
// add_action( 'restrict_manage_posts', 'tekserve_case_study_type_filter' );