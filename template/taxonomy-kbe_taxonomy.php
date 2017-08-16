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

$kbe_tax_post_args = array(
	'post_type'      => KBE_POST_TYPE,
	'posts_per_page' => 999,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'tax_query'      => array(
		array(
			'taxonomy' => KBE_POST_TAXONOMY,
			'field'    => 'slug',
			'terms'    => $kbe_cat_slug
		)
	)
);
$kbe_tax_post_qry  = new WP_Query( $kbe_tax_post_args );

?><div id="kbe_container"><?php

	bread_crumbs();
	search_field();

	// Content
	?><div id="kbe_content" <?php echo $kbe_content_class; ?>>
        <!--leftcol-->
        <div class="kbe_leftcol">

            <!--<articles>-->
            <div class="kbe_articles">
                <h2><strong><?php echo $kbe_cat_name; ?></strong></h2>

                <ul><?php
					if ( $kbe_tax_post_qry->have_posts() ) :
						while ( $kbe_tax_post_qry->have_posts() ) :
							$kbe_tax_post_qry->the_post();
							?><li>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </li><?php
						endwhile;
					endif;
				?></ul>

            </div>
        </div>
        <!--/leftcol-->

    </div>
    <!--/content-->

    <?php aside(); ?>

</div><?php
get_footer( 'knowledgebase' );
