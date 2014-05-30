<?php

// include wp-load
define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');

//GET vars
$poststring = (isset($_GET['allposts'])) ? $_GET['allposts'] : 0;
$position = (isset($_GET['postid'])) ? $_GET['postid'] : 0;
$url = (isset($_GET['place'])) ? $_GET['place'] : 0;
$url = base64_decode( $url );

$catalog_pages = unserialize( $poststring );
$catalog_page = get_post( $catalog_pages[$position] );
$catalog_page_content = imgmap_replace_shortcode( $catalog_page->post_content );
echo '<head>
<link rel="stylesheet" id="imgmap_style-css" href="http://holiday.tekserve.com/wp-content/plugins/imagemapper/imgmap_style.css?ver=3.9.1" type="text/css" media="all">
<link rel="stylesheet" id="tekserve-catalog-imagemap-css" href="http://holiday.tekserve.com/wp-content/plugins/tekserve_wp_catalog/css/imagemap.css?ver=3.9.1" type="text/css" media="all">
<script type="text/javascript" src="' . $url . '/js/jquery.imagemapster.min.js"></script>
<script type="text/javascript" src="' . $url . '/js/imagemapper_script.js?ver=3.9.1"></script>
</head>';
echo $catalog_page_content;
?>