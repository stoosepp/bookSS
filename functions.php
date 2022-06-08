<?php
/**
 * _s functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package _s
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! function_exists( '_s_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function _s_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on _s, use a find and replace
		 * to change '_s' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( '_s', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Primary', '_s' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'_s_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif;
add_action( 'after_setup_theme', '_s_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function _s_content_width() {
	$GLOBALS['content_width'] = apply_filters( '_s_content_width', 640 );
}
add_action( 'after_setup_theme', '_s_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function _s_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', '_s' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', '_s' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', '_s_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function _s_scripts() {
	wp_enqueue_style( '_s-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( '_s-style', 'rtl', 'replace' );

	wp_enqueue_script( '_s-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', '_s_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}
function ww_load_dashicons(){
    wp_enqueue_style('dashicons');
}
add_action('wp_enqueue_scripts', 'ww_load_dashicons');


/* ---------------- CUSTOM STUFF---------------- */


include 'filterbybook.php';

add_theme_support( 'align-wide' );
add_post_type_support( 'page', 'excerpt' );


function getCustomStylesheet($stylename){
	$themedirectory = get_template_directory_uri();
	return $themedirectory.'/css/'.$stylename.'.css';
}

function getTextBetweenTags($pageHTML, $tagname){
	$domDocument = new DOMDocument();// 1. Create documentt
	libxml_use_internal_errors(true);// 2. Handle errors internally
	$domDocument->loadHTML($pageHTML);// 3. Load your HTML 5
	// 4. Do what you need to do without the warning ...
   // $d = new DOMDocument();
    //$d->loadHTML($pageHTML);
    $return = array();
    foreach($domDocument->getElementsByTagName($tagname) as $item){
        $return[] = $item->textContent;
    }
    return $return;
	// 5. Clear errors
	libxml_clear_errors();
}

function auto_id_headings( $content ) {
    $content = preg_replace_callback( '/(\<h[1-6](.*?))\>(.*)(<\/h[1-6]>)/i', function( $matches ) {
    if ( ! stripos( $matches[0], 'id=' ) ) :
    $heading_link = '<a href="#' . sanitize_title( $matches[3] ) . '" class="heading-anchor-link"></a>';
    $matches[0] = $heading_link . $matches[1] . $matches[2] . ' id="' . sanitize_title( $matches[3] ) . '"><!--<i class="fas fa-link"></i>-->' . $matches[3] . $matches[4];
    endif;
    return $matches[0];
    }, $content );
    return $content;
    }

function preparePageContentForePub($input){

	//$replacedFinalText = preg_replace($pattern,$replacement,$originalcontent);
	//$allowedTags = strip_tags($input, '<p><a><h1><h2><h3><h4><h5><image><iframe>');

	$pattern = "/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user|shorts)\/))([^\?&\"'>]+)/";

	preg_match($pattern, $input, $matches);

		print('shit');
		foreach ($matches as $matchDeets){
			echo $matchDeets[0];
			foreach ($matchDeets as $match){
				echo $match[0];
			}


		}



	//$input = strip_tags($input);
	$input = html_entity_decode($input);

	$final_string  = preg_replace($pattern,"<iframe width=\"100%\" height=\"500\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>",$input);

	return $final_string;

}

function getTopLevelPages(){
	$query_args = array('parent' => 0, // required
	'post_type' => 'page', // required
	'sort_order'   => 'ASC',
	'sort_column'  => 'menu_order',
);
	return get_pages( $query_args );
}


function getRootForPage($post){//gets book for the current page.
	$bookRoot = new stdClass();
	$thisPage = get_post($post->ID);
	if ($thisPage->post_parent)	{
		$ancestors=get_post_ancestors($post->ID);
		$root=count($ancestors)-1;
		$bookRoot = $ancestors[$root];
	} else {
		$bookRoot = $post;
	}
	return $bookRoot;
}

function getKids($forPage){

	$args = array(
		'posts_per_page' => 0,
		'order'          => 'ASC',
		'orderby'		=>'menu_order',
		'post_parent'    => $forPage,
		'post_status'    => null,
		'post_type'      => 'page',
	);
	return get_children( $args );
}
/* CHECK CATEGORIES FOR HIDDEN */
function removeHidden($categories){
	$nonHiddenCategories = array();
	consolePrint(count($categories));
	foreach ( $categories as $category ) {
		$parentCat = get_category($category->parent);
		if (stripos($category->name, 'hidden') !== false){
			consolePrint($category->name.' is hidden');
		}
		else{
			consolePrint($category->name.'s parent is '.$parentCat->name);
			if (($parentCat) && (stripos($parentCat->name, 'hidden') !== false)){
				consolePrint($category->name.'s parent is hidden');
			}
			else{
				array_push($nonHiddenCategories,$category);
			}

		}
	}
	return $nonHiddenCategories;
}


/* ADD PARAMETER FOR CHROMELESS */
function wwp_custom_query_vars_filter($vars) {
    $vars[] .= 'chromeless';
    return $vars;
}
add_filter( 'query_vars', 'wwp_custom_query_vars_filter' );

function consolePrint($string){
	echo '<script>console.log("'.$string.'");</script>';
}

// if (isset($_GET['voteUp']) && isset($_GET['value'])){
// 	return voteUp($_GET['value']);
// }

/* VOTING - JQuery added in header */

add_action('wp_ajax_vote', 'vote');
add_action('wp_ajax_nopriv_vote', 'vote');

function vote() {
   $postid= $_POST['id'];
   $direction = $_POST['direction'];
   consolePrint('Voting '.$direction.' on postID:'.$postid);
   if($direction == 'down') {
		//consolePrint('Adding Down vote');
	  	add_post_meta($postid,'updown_votes','-');
   } else {
		//consolePrint('Adding Up vote');
	  	add_post_meta($postid,'updown_votes','+');
   }
   exit();
}

function getVoteData($post_id){
	$upvotes = 0;
	$downvotes = 0;
	$post_votes = get_post_meta($post_id,'updown_votes',false);
	if ($post_votes){
		//consolePrint('Vote Count: '.count($post_votes));
		foreach($post_votes as $vote){
			if ($vote == '+'){
				$upvotes++;
			}
			else if ($vote == '-'){
				$downvotes++;
			}
		}
		return array($upvotes,$downvotes);
	}
	else{
		return null;
	}
}

function deleteAllPostMeta($post_id){
    delete_post_meta( $post_id, 'updown_votes');
}


/* GENERATE EPUB */

add_action('wp_ajax_makeBook', 'makeBook');
add_action('wp_ajax_nopriv_makeBook', 'makeBook');
function makeBook(){
    $postid= $_POST['id'];
	//echo 'Making book from PageID: '.$postid;
	$URL = get_template_directory_uri().'/template-parts/content-epubgenerator.php';
	//echo $URL;
	header("Location:".$URL);
	//get_template_part('template-parts/content-epubgenerator.php', get_post_type() );
	//test($postid);
}

function test($postid){
	$post = get_post( $postid);
	echo 'This is a test for '.get_the_title($postid);
}

