<?php
include('constant.php');
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function only_theme(){
	switch(get_template()){
	case 'luxeritas':
		global $luxe;
		$luxe['sns_layout'] = null;
		printf('<div class="sns-msg"><h2>%s</h2></div>',$luxe['sns_bottoms_msg']);
		get_template_part('sns');
	    echo '</main>';
	    echo apply_filters('thk_comments', '');
	    echo my_trackback();
	    echo '</div><!--/#main-->';
		thk_call_sidebar();
		echo '</div><!--/#primary-->';
		break;
	}
}

function my_trackback(){
		echo '<div id="trackback" class="grid">';
		echo '<h3 class="tb"><i class="fa fa-reply-all"></i>';
		echo __( 'TrackBack URL', 'luxeritas' );
		echo '</h3>';
		echo '<input type="text" name="trackback_url" size="60" value="';
		trackback_url();
		echo '" readonly="readonly" class="trackback-url" tabindex="0" accesskey="t" />';
		echo '</div>';
}

function init_classes($place_const,&$kbe_content_class,&$kbe_sidebar_class){
	// Classes For main content div
	if ( $place_const == 0 ) {
		$kbe_content_class = 'class="post kbe_content_full"';
		$kbe_sidebar_class = 'kbe_aside_none';
	} elseif ( $place_const == 1 ) {
		$kbe_content_class = 'class="post kbe_content_right"';
		$kbe_sidebar_class = 'kbe_aside_left';
	} elseif ( $place_const == 2 ) {
		$kbe_content_class = 'class="post kbe_content_left"';
		$kbe_sidebar_class = 'kbe_aside_right';
	}
}

// パンくずリスト
function bread_crumbs(){
	if ( KBE_BREADCRUMBS_SETTING == 1 ) {
		echo '<div class="kbe_breadcrum">';
			kbe_breadcrumbs();
		echo '</div>';
	}
}

// 検索フィールド
function search_field(){
	if ( KBE_SEARCH_SETTING == 1 ) {
		kbe_search_form();
	}
}

// aside
function aside($kbe_sidebar_class){
	$aside_tag = '<div class="kbe_aside %s">';
	echo sprintf($aside_tag, $kbe_sidebar_class);
	if ( (KBE_SIDEBAR_HOME == 2) || (KBE_SIDEBAR_HOME == 1) ) {
		// dynamic_sidebar( 'kbe_cat_widget' );
		dynamic_sidebar( 'kbe_cat_widget' );
	}
	echo '</div>';
}

// xxxArticles
function count_child_article($kbe_child_term){
	$badge = make_badge_tag($kbe_child_term->count);
	echo '<h3>';
		echo sprintf(TAG_A,get_term_link( $kbe_child_term->slug, 'kbe_taxonomy'),"",$kbe_child_term->name.$badge);
	echo '</h3>';
}

// xxxArticles
function count_parent_article($kbe_taxonomy){
	$kbe_count_sum_parent = get_article_count($kbe_taxonomy);
	$badge = make_badge_tag($kbe_count_sum_parent);
	echo '<h2>';
		echo sprintf(TAG_A,get_term_link( $kbe_taxonomy->slug, 'kbe_taxonomy'),"",$kbe_taxonomy->name.$badge);
	echo '</h2>';
}
// ルート記事一覧
function kbe_root_article_list($kbe_taxonomy){
	echo '<ul class="kbe_article_list">';

		$kbe_tax_post_args = array(
			'post_type'      => KBE_POST_TYPE,
			'posts_per_page' => KBE_ARTICLE_QTY,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_parent'    => 0,
			'tax_query'      => array(
				array(
					'taxonomy' => KBE_POST_TAXONOMY,
					'field'    => 'slug',
					'terms'    => $kbe_taxonomy->slug,
					'include_children' => false
				)
			)
		);
		kbe_articles_list($kbe_tax_post_args);

		echo '</ul>';
}

// 子記事一覧
function kbe_child_article_list($kbe_child_term){
	echo '<ul class="kbe_child_article_list">';
	$kbe_child_post_args = array(
		'post_type'      => KBE_POST_TYPE,
		'posts_per_page' => KBE_ARTICLE_QTY,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'tax_query'      => array(
			array(
				'taxonomy' => KBE_POST_TAXONOMY,
				'field'    => 'term_id',
				'terms'    => $kbe_child_term->term_id,
				'include_children' => false
			)
		)
	);

	kbe_articles_list($kbe_child_post_args);
	echo '</ul>';
}

// 記事数取得（子も含む）
function get_article_count($kbe_taxonomy){
	global $wpdb;
	$kbe_taxonomy_parent_count = $kbe_taxonomy->count;
	$children = get_term_children( $kbe_taxonomy->term_id, KBE_POST_TAXONOMY );

	$kbe_count_sum = $wpdb->get_var( "
		SELECT Sum(count)
		FROM {$wpdb->prefix}term_taxonomy
		WHERE taxonomy = '" . KBE_POST_TAXONOMY . "'
		And parent = $kbe_taxonomy->term_id
	" );

	$kbe_count_sum_parent = '';
	if ( $children ) {
		$kbe_count_sum_parent = $kbe_count_sum + $kbe_taxonomy_parent_count;
	} else {
		$kbe_count_sum_parent = $kbe_taxonomy_parent_count;
	}

	return $kbe_count_sum_parent;
}


/**
 * 最上位のカテゴリ一つ分の処理
 *
 * @param obj $kbe_taxonomy
 */
function root_category_proc($kbe_taxonomy){
	echo '<div class="kbe_category">';
	count_parent_article($kbe_taxonomy);
	// 親カテゴリの記事一覧
	kbe_root_article_list($kbe_taxonomy);
	child_category_proc($kbe_taxonomy->term_id);
    echo '</div>';
}

/**
 * 子カテゴリ処理
 *
 * @param obj $kbe_taxonomy
 */
function child_category_proc($term_id){
	// get child categories
	$kbe_child_terms = kbe_get_terms($term_id);
	// 子カテゴリの処理
	if ( $kbe_child_terms ) {
		echo '<div class="kbe_category kbe_child_category" style="display: none;">';
			foreach ( $kbe_child_terms as $kbe_child_term ) {
				count_child_article($kbe_child_term);
				kbe_child_article_list($kbe_child_term);
				child_category_proc($kbe_child_term->term_id);
			}
		echo '</div>';
	}
}

/**
 * 子カテゴリ取得
 *
 * @param int $parent_term_id
 * @return array
 */
function kbe_get_terms($parent_term_id){
	$kbe_child_cat_args = [
		'orderby'    => 'terms_order',
		'order'      => 'ASC',
		'parent'     => $parent_term_id,
		'hide_empty' => true,
	];

	return get_terms( KBE_POST_TAXONOMY, $kbe_child_cat_args );
}

/**
 * 記事取得
 *
 * @param array $post_args
 */
function kbe_articles_list($post_args){
	$kbe_post_qry = new WP_Query( $post_args );
	if ( $kbe_post_qry->have_posts() ) :
		while ( $kbe_post_qry->have_posts() ) :
			$kbe_post_qry->the_post();
			echo '<li>';
			echo sprintf(TAG_A, get_permalink(),"bookmark",get_the_title());
			echo '</li>';
		endwhile;
	else :
		// echo 'No posts';
	endif;
}

/**
 * バッジタグ生成
 *
 * @param string $content
 * @return string
 */
function make_badge_tag($content){
	$badge = '<span class="kbe_badge">';
	$badge .= sprintf( _n( '%d Article', '%d Articles', $content, 'wp-knowledgebase' ), $content );
	$badge .= '</span>';
	return $badge;
}
?>