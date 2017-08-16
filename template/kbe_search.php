<?php

include('php/common.php');
init_classes(KBE_SIDEBAR_INNER,$kbe_content_class,$kbe_sidebar_class);


if ( ! empty( $_GET['ajax'] ) ? $_GET['ajax'] : null ) {

	if ( have_posts() ) {

		?><ul id="search-result"><?php

			while ( have_posts() ) : the_post();
				?><li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li><?php
			endwhile;
		?></ul><?php

	} else {
		?><span class="kbe_no_result"><?php _e( 'Search result not found...', 'wp-knowledgebase' ); ?></span><?php
	}

} else {

	get_header( 'knowledgebase' );
	// load the style and script
	wp_enqueue_style( 'kbe_theme_style' );
	if ( KBE_SEARCH_SETTING == 1 ) {
		wp_enqueue_script( 'kbe_live_search' );
	}

	?><div id="kbe_container"><?php

		bread_crumbs();
		search_field();

		// Content
		?><div id="kbe_content" <?php echo $kbe_content_class; ?>><?php

			?><h1><?php echo sprintf( __( 'Search Results for: %s', 'wp-knowledgebase' ), esc_html( $_GET['s'] ) ); ?></h1>

            <!--leftcol-->
            <div class="kbe_leftcol" >
                <!--<articles>-->
                <div class="kbe_articles_search">
                    <ul><?php

						while ( have_posts() ) :
							the_post();
							?><li>
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                <span class="post-meta">Post By <?php the_author(); ?> | Date : <?php the_time( 'j F Y' ); ?></span>
                                <p><?php echo kbe_short_content( 300 ); ?></p>
                                <div class="kbe_read_more">
                                    <a href="<?php the_permalink(); ?>"><?php _e( 'Read more...', 'wp-knowledgebase' ); ?></a>
                                </div>
                            </li><?php
						endwhile;

					?></ul>
                </div>
            </div>

        </div>

        <?php aside(); ?>

    </div><?php

	get_footer( 'knowledgebase' );

}
