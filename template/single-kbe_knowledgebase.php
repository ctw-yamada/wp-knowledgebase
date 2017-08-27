<?php
include('php/common.php');

get_header( 'knowledgebase' );

// load the style and script
wp_enqueue_style( 'kbe_theme_style' );
if ( KBE_SEARCH_SETTING == 1 ) {
	wp_enqueue_script( 'kbe_live_search' );
}

init_classes(KBE_SIDEBAR_INNER,$kbe_content_class,$kbe_sidebar_class)

?>
<div id="kbe_container">
<?php
	bread_crumbs();
	search_field();

	// Content
	?><div id="kbe_content" <?php echo $kbe_content_class; ?>>
        <!--Content Body-->
        <div class="kbe_leftcol" ><?php

			while ( have_posts() ) :
				the_post();

				//  Never ever delete it !!!
				kbe_set_post_views( get_the_ID() );

				?><h1><?php the_title(); ?></h1><?php

				the_content();

				include 'kbe_comments.php';

			endwhile;

		?></div>
        <!--/Content Body-->

    </div>

<?php
    aside($kbe_sidebar_class);
    only_theme();
    echo '</div>';
    get_footer( 'knowledgebase' );
