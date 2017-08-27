<?php
include('php/common.php');

/*=========
Template Name: KBE
=========*/

global $wpdb;

const ROOT_TERM_ID = 0;

get_header( 'knowledgebase' );

// load the style and script
wp_enqueue_style( 'kbe_theme_style' );
if ( KBE_SEARCH_SETTING == 1 ) {
	wp_enqueue_script( 'kbe_live_search' );
}

init_classes(KBE_SIDEBAR_HOME,$kbe_content_class,$kbe_sidebar_class);

?>
<div id="kbe_container">
    <div>
<?php
	bread_crumbs();
	search_field();
?>
	<!--Content-->
	<div id="kbe_content" <?php echo $kbe_content_class; ?>>
        <h1>
        	<?php echo get_the_title( KBE_PAGE_TITLE ); ?>
        </h1>
        <!--leftcol-->
        <div class="kbe_leftcol">
            <div class="kbe_categories">
<?php
			// get all root category
			$kbe_terms = kbe_get_terms(ROOT_TERM_ID);

			foreach ( $kbe_terms as $kbe_taxonomy ) {
				root_category_proc($kbe_taxonomy);
			}
?>
			</div>
        </div>
        <!--/leftcol-->

    </div>
    <!--content-->
<?php
    aside($kbe_sidebar_class);
    only_theme();
    echo '</div>';
    get_footer( 'knowledgebase' );
