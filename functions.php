<?php


if ( ! defined( 'B_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'B_VERSION', '1.0.0' );
}

if ( ! function_exists( 'b_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function b_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on _s, use a find and replace
		 * to change 'theopenbook' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'theopenbook', get_template_directory() . '/languages' );

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
				'menu-1' => esc_html__( 'Primary', 'theopenbook' ),
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
				'theopenbook_custom_background_args',
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
add_action( 'after_setup_theme', 'b_setup' );

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
			'name'          => esc_html__( 'Sidebar', 'theopenbook' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'theopenbook' ),
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
	wp_enqueue_style( '_s-style', get_stylesheet_uri(), array(), B_VERSION );
	wp_style_add_data( '_s-style', 'rtl', 'replace' );

	wp_enqueue_script( '_s-navigation', esc_url(get_template_directory_uri()) . '/js/navigation.js', array(), B_VERSION, true );

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
	$themedirectory = esc_url(get_template_directory_uri());
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
    $content = preg_replace_callback( '/(\<h[1-6](.*?))\>(.*)(<\/h[1-6]>)/i', function( $matches) {
   // if ( ! stripos( $matches[0], 'id=' ) ) :
	$cleaned_title = sanitize_title( $matches[3]);
    $heading_link = '<a id="'.$cleaned_title.'" href="#' . 	$cleaned_title. '" class="heading-anchor-link"></a>';
	//consolePrint('0:'.$matches[0].', 1:'.$matches[1].', 2:'.$matches[2].', 3:'.$matches[3]);
    $matches[0] = $heading_link.$matches[1].$matches[2].' id="">'.$matches[3] . $matches[4];// . $matches[1] ./* $matches[2] . */' id="' . sanitize_title( $matches[3] ) . '"><!--<i class="fas fa-link"></i>-->' . $matches[3] . $matches[4];
    //endif;
    return $matches[0];
    }, $content );
    return $content;
    }


function getTopLevelPages(){
	$query_args = array('parent' => 0, // required
	'post_type' => 'page', // required
	'sort_order'   => 'ASC',
	'sort_column'  => 'menu_order',
);
	return get_pages( $query_args );
}


function getRootForPage($thisPost){//gets book for the current page.
	$bookRoot = new stdClass();
	$thisPage = get_post($thisPost->ID);
	if ($thisPage->post_parent)	{
		$ancestors=get_post_ancestors($thisPost->ID);
		$root=count($ancestors)-1;
		$bookRoot = $ancestors[$root];
	} else {
		$bookRoot = $thisPost;
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
	//consolePrint(count($categories));
	foreach ( $categories as $category ) {

		if (stripos($category->name, 'hidden') === false){//if category name doesn't contains hidden
			//check to see if it has a parent.
			if (category_has_parent($category)){
				consolePrint($category->name.' has a parent');
				$parentCat = get_category($category->parent);
				$parentName = $parentCat->cat_name;
				consolePrint($category->name.'s parent is '.$parentName);
				if (($category->name) && (stripos($parentName, 'hidden') === false)){
					array_push($nonHiddenCategories,$category);
				}
			}
			else{
				array_push($nonHiddenCategories,$category);
			}
		}
		else{
			consolePrint($category->name.' is hidden');
		}
	}
	return $nonHiddenCategories;
}
function category_has_parent($catid){
    $category = get_category($catid);
    if ($category->category_parent > 0){
        return true;
    }
    return false;
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
/*
function wpb_change_search_url() {
    if ( is_search() && ! empty( $_GET['s'] ) ) {
        wp_redirect( home_url( "/search/" ) . urlencode( get_query_var( 's' ) ) );
        exit();
    }
}
add_action( 'template_redirect', 'wpb_change_search_url' );
*/
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

/* CUSTOM SCRIPTS */

if(!function_exists('load_my_script')){
    function load_my_script() {
        global $post;
        $deps = array('jquery');
        $version= '1.0';
        $in_footer = true;
       	wp_enqueue_script('my-script', get_stylesheet_directory_uri() . '/js/theopenbook.js', $deps, $version, $in_footer);
        wp_localize_script('my-script', 'my_script_vars', array(
                'postID' => $post->ID
            )
        );
    }
}
add_action('wp_enqueue_scripts', 'load_my_script');



/* ADD SETTINGS PANE */
Function test_custom_settings () {
	add_theme_page("Theme Customization", "Theme Customization", "manage_options", "theme-options", "theme_option_page", null, 99);

?>