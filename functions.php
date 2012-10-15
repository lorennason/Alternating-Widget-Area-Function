<?php

/** Register and Enqueue Scripts */
add_action('wp_enqueue_scripts', 'custom_enqueue_scripts');
function custom_enqueue_scripts() {
	if ( is_home() ) {
		wp_register_script('masonry', get_stylesheet_directory_uri() . '/js/masonry.js', '2.1.05', true);
			wp_enqueue_script('masonry');

		wp_register_script('masonry-args', get_stylesheet_directory_uri().'/js/masonry-args.js', true  );
			wp_enqueue_script('masonry-args');

		wp_register_script('jquery-infinitescroll', get_stylesheet_directory_uri().'/js/jquery.infinitescroll.min.js', array('jquery'), '2.0b2.120519', true);
			wp_enqueue_script('jquery-infinitescroll');
	}
	wp_register_script('slidedown', get_stylesheet_directory_uri() . '/js/slidedown.js', 'jquery', '1', true);
        wp_enqueue_script('slidedown');

	wp_register_script( 'flexslider', get_stylesheet_directory_uri() . '/js/jquery.flexslider.min.js', array( 'jquery' ) );
		wp_enqueue_script('flexslider');

	wp_deregister_script('jquery');
	wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js', '1.7.2', true);
	wp_enqueue_script('jquery');

}

/** Register widget areas */
genesis_register_sidebar( array(
    'id'            => 'between-posts-box',
    'name'          => __( 'Between Posts Box', 'ylt-pin-it' ),
    'description'   => __( 'This widget area appears after posts on home page and archive pages.', 'ylt-pin-it' ),
) );
genesis_register_sidebar( array(
    'id'            => 'between-posts-box-2',
    'name'          => __( 'Between Posts Box #2', 'ylt-pin-it' ),
    'description'   => __( 'This widget area appears after posts on home page and archive pages.', 'ylt-pin-it' ),
) );

/** Set Cookie for Alternating Widgets */
function set_widget_count_cookie() {
	// reset 'postbox' cookie to 1 on initial page load
	if (substr($_SERVER['REQUEST_URI'], 0, 6) != '/page/')
	{
		$savebox = 2;		// set to 2 on page load, because two widget areas are used
		$postbox = 0;
	}
	else
	{
		// add 1 to existing cookie value or assume a value of 1
		if (isset($_COOKIE['postbox']))
			$postbox = $_COOKIE['postbox'] + 1;
		else
			$postbox = 1;
		$savebox = $postbox;
	}

	// save the cookie
	setcookie('postbox', $savebox, time()+1209600, '/');
	$_COOKIE['postbox'] = $postbox;		// needed later in the dave_between_posts_widget() function
}
add_action( 'init', 'set_widget_count_cookie');


// Add Widget Every 3 Posts.
add_action( 'genesis_after_post', 'daves_between_posts_widget_function', 20 );
function daves_between_posts_function() {
  global $wp_query;
  if ( is_home() && ($wp_query->current_post % 3 == 0)) {
    echo '<div class="post">';

    if (isset($_COOKIE['postbox']))
      $postbox = $_COOKIE['postbox'] + 1;
    else
      $postbox = 1;

	// use cookie value to determine correct
    genesis_widget_area( ($postbox % 2 == 0) ? 'between-posts-box-2' : 'between-posts-box' );
    $_COOKIE['postbox'] = $postbox;		// increment this for next call of the filter

    echo '</div>';
  }
}