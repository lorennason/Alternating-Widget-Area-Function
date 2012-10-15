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