<?php
include('php/common.php');

get_header( 'knowledgebase' );

// load the style and script
wp_enqueue_style( 'kbe_theme_style' );
if ( KBE_SEARCH_SETTING == 1 ) {
	wp_enqueue_script( 'kbe_live_search' );
}

init_classes(KBE_SIDEBAR_INNER,$kbe_content_class,$kbe_sidebar_class);

// Query for tags
$kbe_tag_slug = get_queried_object()->slug;
$kbe_tag_name = get_queried_object()->name;

$kbe_tag_post_args = array(
	'post_type'      => KBE_POST_TYPE,
	'posts_per_page' => 999,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'tax_query'      => array(
		array(
			'taxonomy' => KBE_POST_TAGS,
			'field'    => 'slug',
			'terms'    => $kbe_tag_slug
		)
	)
);

?><div id="kbe_container"><?php

	bread_crumbs();
	search_field();

	// Content
	?><div id="kbe_content" <?php echo $kbe_content_class; ?>>
        <!--leftcol-->
        <div class="kbe_leftcol">
            <!--<articles>-->
            <div class="kbe_articles">
                <h2><strong>Tag: </strong><?php echo $kbe_tag_name; ?></h2>

                <?php kbe_articles_list($kbe_tag_post_args); ?>

            </div>
        </div>
        <!--/leftcol-->

    </div>

<?php
    aside($kbe_sidebar_class);
    only_theme();
    echo '</div>';
    get_footer( 'knowledgebase' );
