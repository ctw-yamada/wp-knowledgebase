<?php
include('php/common.php');

get_header( 'knowledgebase' );

// load the style and script
wp_enqueue_style( 'kbe_theme_style' );
if ( KBE_SEARCH_SETTING == 1 ) {
	wp_enqueue_script( 'kbe_live_search' );
}

init_classes(KBE_SIDEBAR_INNER,$kbe_content_class,$kbe_sidebar_class);

// Query for Category
$kbe_cat_slug = get_queried_object()->slug;
$kbe_cat_name = get_queried_object()->name;


?>
<div id="kbe_container">
<?php

	bread_crumbs();
	search_field();

?>
	<!--Content-->
	<div id="kbe_content" <?php echo $kbe_content_class; ?>>
	    <!--leftcol-->
	    <div class="kbe_leftcol">

	        <!--<articles>-->
	        <div class="kbe_articles">
<?php
				$kbe_terms = kbe_get_terms(get_queried_object()->parent);
				foreach ( $kbe_terms as $kbe_taxonomy ) {
					root_category_proc($kbe_taxonomy);
				}
?>
	        </div>
	    </div>
	    <!--/leftcol-->

	</div>
	<!--/content-->
<?php
    aside($kbe_sidebar_class);
    only_theme();
    echo '</div>';
    get_footer( 'knowledgebase' );
