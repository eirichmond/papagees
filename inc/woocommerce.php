<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package papagees
 */

/**
 * WooCommerce setup function.
 *
 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)
 *
 * @return void
 */
function papagees_theme_woocommerce_setup() {
	add_theme_support( 'woocommerce' );
	//add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	//add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'papagees_theme_woocommerce_setup' );

/**
 * WooCommerce specific scripts & stylesheets.
 *
 * @return void
 */
function papagees_theme_woocommerce_scripts() {
	wp_enqueue_style( 'papagees_theme-woocommerce-style', get_template_directory_uri() . '/woocommerce.css' );

	$font_path   = WC()->plugin_url() . '/assets/fonts/';
	$inline_font = '@font-face {
			font-family: "star";
			src: url("' . $font_path . 'star.eot");
			src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
				url("' . $font_path . 'star.woff") format("woff"),
				url("' . $font_path . 'star.ttf") format("truetype"),
				url("' . $font_path . 'star.svg#star") format("svg");
			font-weight: normal;
			font-style: normal;
		}';

	wp_add_inline_style( 'papagees_theme-woocommerce-style', $inline_font );
}
add_action( 'wp_enqueue_scripts', 'papagees_theme_woocommerce_scripts' );

/**
 * Disable the default WooCommerce stylesheet.
 *
 * Removing the default WooCommerce stylesheet and enqueing your own will
 * protect you during WooCommerce core updates.
 *
 * @link https://docs.woocommerce.com/document/disable-the-default-stylesheet/
 */
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );

/**
 * Add 'woocommerce-active' class to the body tag.
 *
 * @param  array $classes CSS classes applied to the body tag.
 * @return array $classes modified to include 'woocommerce-active' class.
 */
function papagees_theme_woocommerce_active_body_class( $classes ) {
	$classes[] = 'woocommerce-active';

	return $classes;
}
add_filter( 'body_class', 'papagees_theme_woocommerce_active_body_class' );

/**
 * Products per page.
 *
 * @return integer number of products.
 */
function papagees_theme_woocommerce_products_per_page() {
	return 100;
}
add_filter( 'loop_shop_per_page', 'papagees_theme_woocommerce_products_per_page' );

/**
 * Product gallery thumbnail columns.
 *
 * @return integer number of columns.
 */
function papagees_theme_woocommerce_thumbnail_columns() {
	return 3;
}
add_filter( 'woocommerce_product_thumbnails_columns', 'papagees_theme_woocommerce_thumbnail_columns' );

/**
 * Default loop columns on product archives.
 *
 * @return integer products per row.
 */
function papagees_theme_woocommerce_loop_columns() {
	return 1;
}
add_filter( 'loop_shop_columns', 'papagees_theme_woocommerce_loop_columns' );





function papagees_before_shop_loop_item() {
	echo '<div class="papagees-menu-item-thumbnail">';
}
add_action('woocommerce_before_shop_loop_item', 'papagees_before_shop_loop_item', 10);

function papagees_before_shop_loop_item_title() {
	echo '</div>';
}
add_action('woocommerce_before_shop_loop_item_title', 'papagees_before_shop_loop_item_title');

function papagees_shop_loop_item_title() {
	echo '<div class="papagees-menu-item-title-description">';
}
add_action('woocommerce_shop_loop_item_title', 'papagees_shop_loop_item_title', 5);

function papagees_after_shop_loop_item_title() {
	echo '</div>';
}
add_action('woocommerce_after_shop_loop_item_title', 'papagees_after_shop_loop_item_title',10);

/**
 * Insert the opening anchor tag for products in the loop.
 */
function woocommerce_template_loop_product_link_open() {
	global $product;

	$link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );

	echo '<a href="' . esc_url( $link ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link block md:flex items-center">';
}





/**
 * Related Products Args.
 *
 * @param array $args related products args.
 * @return array $args related products args.
 */
function papagees_theme_woocommerce_related_products_args( $args ) {
	$defaults = array(
		'posts_per_page' => 3,
		'columns'        => 1,
	);

	$args = wp_parse_args( $defaults, $args );

	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'papagees_theme_woocommerce_related_products_args' );

if ( ! function_exists( 'papagees_theme_woocommerce_product_columns_wrapper' ) ) {
	/**
	 * Product columns wrapper.
	 *
	 * @return  void
	 */
	function papagees_theme_woocommerce_product_columns_wrapper() {
		$columns = papagees_theme_woocommerce_loop_columns();
		echo '<div class="columns-' . absint( $columns ) . '">';
	}
}
add_action( 'woocommerce_before_shop_loop', 'papagees_theme_woocommerce_product_columns_wrapper', 40 );

if ( ! function_exists( 'papagees_theme_woocommerce_product_columns_wrapper_close' ) ) {
	/**
	 * Product columns wrapper close.
	 *
	 * @return  void
	 */
	function papagees_theme_woocommerce_product_columns_wrapper_close() {
		echo '</div>';
	}
}
add_action( 'woocommerce_after_shop_loop', 'papagees_theme_woocommerce_product_columns_wrapper_close', 40 );

/**
 * Remove default WooCommerce wrapper.
 */
add_action( 'wp_loaded', 'remove_add_woocommerce_functions', 10 );
function remove_add_woocommerce_functions() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	//remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

}

add_filter( 'woocommerce_show_page_title', '__return_false' );

if ( ! function_exists( 'papagees_theme_woocommerce_wrapper_before' ) ) {
	/**
	 * Before Content.
	 *
	 * Wraps all WooCommerce content in wrappers which match the theme markup.
	 *
	 * @return void
	 */
	function papagees_theme_woocommerce_wrapper_before() {
		?>
		<div id="primary" class="content-area">
			<main id="main" class="site-main" role="main">


			<?php
	}
}
add_action( 'woocommerce_before_main_content', 'papagees_theme_woocommerce_wrapper_before' );

if ( ! function_exists( 'papagees_theme_woocommerce_wrapper_after' ) ) {
	/**
	 * After Content.
	 *
	 * Closes the wrapping divs.
	 *
	 * @return void
	 */
	function papagees_theme_woocommerce_wrapper_after() {
		?>


			</main><!-- #main -->
		</div><!-- #primary -->
		<?php
	}
}
add_action( 'woocommerce_after_main_content', 'papagees_theme_woocommerce_wrapper_after' );

/**
 * Sample implementation of the WooCommerce Mini Cart.
 *
 * You can add the WooCommerce Mini Cart to header.php like so ...
 *
	<?php
		if ( function_exists( 'papagees_theme_woocommerce_header_cart' ) ) {
			papagees_theme_woocommerce_header_cart();
		}
	?>
 */

if ( ! function_exists( 'papagees_theme_woocommerce_cart_link_fragment' ) ) {
	/**
	 * Cart Fragments.
	 *
	 * Ensure cart contents update when products are added to the cart via AJAX.
	 *
	 * @param array $fragments Fragments to refresh via AJAX.
	 * @return array Fragments to refresh via AJAX.
	 */
	function papagees_theme_woocommerce_cart_link_fragment( $fragments ) {
		ob_start();
		papagees_theme_woocommerce_cart_link();
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}
}
add_filter( 'woocommerce_add_to_cart_fragments', 'papagees_theme_woocommerce_cart_link_fragment' );

if ( ! function_exists( 'papagees_theme_woocommerce_cart_link' ) ) {
	/**
	 * Cart Link.
	 *
	 * Displayed a link to the cart including the number of items present and the cart total.
	 *
	 * @return void
	 */
	function papagees_theme_woocommerce_cart_link() {
		?>
		<a class="cart-contents inline-block py-3" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'papagees_theme' ); ?>">
			<?php
			$item_count_text = sprintf(
				/* translators: number of items in the mini cart. */
				_n( '%d', WC()->cart->get_cart_contents_count(), 'papagees_theme' ),
				WC()->cart->get_cart_contents_count()
			);
			?>
			<span class="amount flex"><svg xmlns="http://www.w3.org/2000/svg" class="fill-current w-5 h-5 mr-4 text-white" viewBox="0 0 20 20"><path d="M4 2h16l-3 9H4a1 1 0 1 0 0 2h13v2H4a3 3 0 0 1 0-6h.33L3 5 2 2H0V0h3a1 1 0 0 1 1 1v1zm1 18a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm10 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg> <span class="count text-white"><?php echo esc_html( $item_count_text ); ?></span>
		</a>
		<?php
	}
}

if ( ! function_exists( 'papagees_theme_woocommerce_header_cart' ) ) {
	/**
	 * Display Header Cart.
	 *
	 * @return void
	 */
	function papagees_theme_woocommerce_header_cart() {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<ul id="site-header-cart" class="site-header-cart">
			<li class="<?php echo esc_attr( $class ); ?>">
				<?php papagees_theme_woocommerce_cart_link(); ?>
			</li>
			<li>
				<?php
				$instance = array(
					'title' => '',
				);

				the_widget( 'WC_Widget_Cart', $instance );
				?>
			</li>
		</ul>
		<?php
	}
}






/**
 * @TODO move this to the plugin, this area is for testing before adding into the plugin
 */


 // helpers functions
function papagees_too_many_items_in_cart() {
	$slots = papagees_max_slots_per_delivery();
	return wc_add_notice( 'Whoa hold up! Due to limited delivery time slots, you can only order '.$slots.' Pizza per delivery slot.', 'error' );
}

function pagagees_get_order_slots() {
	$order_slots = array(
		'post_type' => 'order_slots',
		'post_status' => 'publish',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'date'
	);
	 
	$order_slots_query = get_posts( $order_slots );
	return $order_slots_query;
}

function papagees_get_qualified_delivery_slots($cart_total) {


	$order_slots_query = pagagees_get_order_slots();
	$qualified = array();

	foreach ($order_slots_query as $order_slot) {
		$timezone = new DateTimeZone( 'Europe/London' );
		$available_slots = intval(get_post_meta($order_slot->ID, 'order_units_available', true));
		$date = get_post_meta($order_slot->ID, 'order_slot_date', true);
		$time = get_post_meta($order_slot->ID, 'order_slot_time', true);
		$time = strtolower($time);
		$date = strtotime(date('d-m-Y h:i a', strtotime($date . ' ' . $time)) . ' +5 minutes');
		$date_now = strtotime(wp_date("d-m-Y H:i:s", null, $timezone));
		
		if($available_slots >= $cart_total) {
			if ($date_now <= $date) {
				$qualified[$order_slot->ID ]= $order_slot->post_title;
			}
		}
	}
	if (empty($qualified)) {
		$qualified[] = 'Deliver Unavailable';
    }
    
	return $qualified;

}

function papagees_max_slots_per_delivery() {

	$order_slots_query = pagagees_get_order_slots();

	$max = array();

	foreach ($order_slots_query as $order_slot) {
		$max[] = get_post_meta($order_slot->ID, 'order_units_available', true);
	}

	$max = intval(max($max));
	return $max;
}


// Add the custom columns to the book post type:
add_filter( 'manage_order_slots_posts_columns', 'set_custom_edit_order_slots_columns' );
function set_custom_edit_order_slots_columns($columns) {
    $columns['order_units_available'] = __( 'Units', 'your_text_domain' );

    return $columns;
}

// Add the data to the custom columns for the book post type:
add_action( 'manage_order_slots_posts_custom_column' , 'custom_order_slots_column', 10, 2 );
function custom_order_slots_column( $column, $post_id ) {
    switch ( $column ) {

        case 'order_units_available' :
            echo get_post_meta( $post_id , 'order_units_available' , true ); 
            break;

    }
}

// To change add to cart text on single product page
add_filter( 'woocommerce_product_single_add_to_cart_text', 'papagees_single_add_to_cart_text' ); 
function papagees_single_add_to_cart_text() {
    return __( 'Order', 'woocommerce' ); 
}

// To change add to cart text on product archives(Collection) page
add_filter( 'woocommerce_product_add_to_cart_text', 'papagees_product_add_to_cart_text' );  
function papagees_product_add_to_cart_text() {
    return __( 'Order', 'woocommerce' );
}

add_filter('single_product_archive_thumbnail_size','papagees_single_product_archive_thumbnail_size',10,1);
function papagees_single_product_archive_thumbnail_size($size) {
	$size = 'shop_catalog';
	return $size;
}

add_filter( 'woocommerce_account_menu_items', 'papagees_remove_downloads_my_account', 10 );
function papagees_remove_downloads_my_account( $items ) {
	unset($items['downloads']);
	return $items;
}

function minimum_order_for_distance() {
    $unqualified = false;
	
	// return early if this is not delivery option 2
	if($_POST['billing_pickup_delivery'] != '2') {
		return $unqualified;
	}
    global $woocommerce;

    $postcode = $_POST['billing_postcode'];

    $total = (int)$woocommerce->cart->get_total('');
    $subtotal = (int)$woocommerce->cart->get_subtotal('');

    $request = 'api.postcodes.io/postcodes/'.$postcode;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $request,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $response = json_decode($response);

    $min_miles = 2;

    $lat1 = 51.95628;
    $lon1 = -2.04092;
    $lat2 = $response->result->latitude;
    $lon2 = $response->result->longitude;

    $public_class = new Papa_Gees_Custom_Woo_Public('public','1.0');

    $distance = $public_class->get_customer_distance($lat1, $lon1, $lat2, $lon2, 'M');
    $min_total = 15.00;
    if ($distance > $min_miles && $subtotal < $min_total) {
        $unqualified = true;
    }

    return $unqualified;
}

function get_total_specific_to_product_category($product_cat_id, $key = false, $quantity = false) {

	$get_cart_items = WC()->cart->get_cart();
	if($key) {
		$get_cart_items[$key]['quantity'] = $quantity;
	}

	$totals = array();
	
	foreach($get_cart_items as $get_cart_item) {
		$terms = wp_get_object_terms( $get_cart_item['product_id'], 'product_cat' );
		foreach ($terms as $term) {
			if ($term->term_id == $product_cat_id) {
				$totals[] = $get_cart_item['quantity'];
			}
		}
	}
	$totals = array_sum($totals);
	return $totals;
}

function if_not_in_cat($product_id, $product_cat_id) {
	$terms = wp_get_object_terms( $product_id, 'product_cat' );
	$bools = array();
	foreach ($terms as $term) {
		if ($term->term_id == $product_cat_id) {
			$bools[] = false;
		} else {
			$bools[] = true;
		}
	}
	if(in_array(true, $bools)) {
		$not_in_cat = true;
	} else {
		$not_in_cat = false;
	}
	return $not_in_cat;

}


// define the woocommerce_add_to_cart_validation callback 
function papagees_add_to_cart_validation( $true, $product_id, $quantity ) { 
	// make filter magic happen here... 
	if(if_not_in_cat($product_id, 18)) {
		return $true;
	}
	
	$cart_totals = get_total_specific_to_product_category(18) + $quantity;

	if($true && $cart_totals > papagees_max_slots_per_delivery()) {
		$true = false;
		papagees_too_many_items_in_cart();
	}
	return $true; 
}; 
			
// add the filter 
add_filter( 'woocommerce_add_to_cart_validation', 'papagees_add_to_cart_validation', 10, 3 ); 

// define the woocommerce_update_cart_validation callback 
function papagees_update_cart_validation( $true, $cart_item_key, $values, $quantity ) { 
	// make filter magic happen here... 
	if(if_not_in_cat($values['product_id'], 18)) {
		return $true;
	}
	
	$cart_totals = get_total_specific_to_product_category(18, $values['key'], $quantity);

	if($true && $cart_totals > papagees_max_slots_per_delivery()) {
		$true = false;
		papagees_too_many_items_in_cart();
	}
	return $true; 
}; 
add_filter( 'woocommerce_update_cart_validation', 'papagees_update_cart_validation', 10, 4 ); 

/**
 * Check if this cart is only a gift card purchase
 *
 * @return bool
 */
function check_if_cart_is_only_gift_card() {
	
	$bool = false;
	
	$gift_card = 'mwb_wgm_giftcard';
	
	$product_cats = array();
	$get_cart_items = WC()->cart->get_cart();
	foreach($get_cart_items as $get_cart_item) {
		$terms = wp_get_object_terms( $get_cart_item['product_id'], 'product_cat' );
		foreach ($terms as $term) {
			$product_cats[] = $term->slug;
		}
	}
	
	$count = count($product_cats);

	if(count($product_cats) == 1 && in_array($gift_card, $product_cats)) {
		$bool = true;
	}
	return $bool;

}

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'papagees_override_checkout_fields' );
// Our hooked in function - $fields is passed via the filter!
function papagees_override_checkout_fields( $fields ) {

	
	// $cart_total = WC()->cart->get_cart_contents_count();
	$cart_total = get_total_specific_to_product_category(18);
    $qualified = papagees_get_qualified_delivery_slots($cart_total);
    $qualified = array('' => '') + $qualified; 

	unset($fields['billing']['billing_company']);
	unset($fields['billing']['billing_country']);
	unset($fields['billing']['billing_city']);
	unset($fields['billing']['billing_state']);

	// check if this is only a giftcard order
	if(check_if_cart_is_only_gift_card()) {
		return $fields;
	}

	$fields['billing']['billing_pickup_delivery'] = array(
		'type' => 'select',
        'label'       => __('Delivery Options', 'woocommerce'),
        'required'    => true,
        'class'       => array('form-row-wide custom-select'),
		'clear'       => true,
		'options' => array(
            '',
            'Collect',
            'Delivery',
            'Deliver to Bar No.9',
			'Deliver to Kings Head'

        )
    );

    $fields['billing']['billing_delivery_slot'] = array(
		'type' => 'select',
        'label'       => __('Time Slot', 'woocommerce'),
        'required'    => true,
        'class'       => array('form-row-wide custom-select'),
		'clear'       => true,
		'options' => $qualified
    );

    // $fields['billing']['billing_delivery_timestamp'] = array(
	// 	'type' => 'text',
    //     'label'       => __('Timestamp', 'woocommerce'),
    //     'class'       => array('form-row-wide custom-select'),
	// 	'clear'       => true,
	// 	'label_class' => array('hidden')
    // );

	return $fields;
}

// Hook in
add_filter( 'woocommerce_default_address_fields' , 'papagees_override_default_address_fields' );
// Our hooked in function - $address_fields is passed via the filter!
function papagees_override_default_address_fields( $address_fields ) {
	$address_fields['country'] = false;
	$address_fields['city'] = false;
	$address_fields['state'] = false;
	return $address_fields;
}



/**
 * Display field value on the order edit page
 */
 
add_action( 'woocommerce_admin_order_data_after_shipping_address', 'papagees_checkout_field_display_admin_order_meta', 10, 1 );
function papagees_checkout_field_display_admin_order_meta($order){

	$order_slots = array(
		'post_type' => 'order_slots',
		'post_status' => 'publish',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'date'
    );
    $order_slot_posts = get_posts($order_slots);


    $pickup_delivery = array(
        'Nothing Specified',
        'Pick Up',
        'Delivery',
        'Deliver to Bar No.9',
		'Deliver to Kings Head'

    );

    $order_slot_id = get_post_meta( $order->get_id(), '_billing_delivery_slot', true );
    $order_pickup_delivery_key = get_post_meta( $order->get_id(), '_billing_pickup_delivery', true );
    
	$order_date = get_post_meta($order_slot_id, 'order_slot_date', true);
	$order_time = get_post_meta($order_slot_id, 'order_slot_time', true);
    echo '<p><strong>'.__('Order Slot').':</strong> ' . $order_time . ' - ' . date('l jS M', strtotime($order_date)) . '</p>';
	?>
	
	<select name="_billing_delivery_slot">
		<?php foreach ($order_slot_posts as $k => $order_slot_post) { ?>
			<option value="<?php echo esc_attr( $order_slot_post->ID ); ?>" <?php selected( $order_slot_id, $order_slot_post->ID ); ?>><?php echo esc_html( $order_slot_post->post_title ); ?></option>
		<?php } ?>
	</select>

	<?php
    echo '<p><strong>'.__('Type').':</strong> ' . $pickup_delivery[$order_pickup_delivery_key] . '</p>';
}

/**
 * Update delivery slot from within the backend
 *
 * @param int $post_id
 * @param object $post
 * @return void
 */
function papagees_process_shop_order_meta_callback($post_id, $post) {
	if ( ! empty( $_POST['_billing_delivery_slot'] ) ) {
		update_post_meta( $post_id, '_billing_delivery_slot', $_POST['_billing_delivery_slot'] );
	}
}
add_action('woocommerce_process_shop_order_meta', 'papagees_process_shop_order_meta_callback', 10, 2);

/**
 * showing order slot in email
 */
add_action('woocommerce_email_after_order_table', 'papagees_email_after_order_table', 10, 4);
function papagees_email_after_order_table($order, $sent_to_admin, $plain_text, $email) {
    $type = array(
        'type not specified',
        'collection',
        'delivery',
        'delivery to Bar No.9',		
        'delivery to Kings Head'		
    );
	$order_slot_id = get_post_meta( $order->get_id(), '_billing_delivery_slot', true );
	$pickup_delivery_key = get_post_meta( $order->get_id(), '_billing_pickup_delivery', true );
	$order_date = get_post_meta($order_slot_id, 'order_slot_date', true);
	$order_time = get_post_meta($order_slot_id, 'order_slot_time', true);

    if ($pickup_delivery_key == 1) {
		echo '<p><strong>'.__('Expected '.$type[$pickup_delivery_key].' time: ').'</strong> approx ' . $order_time . ' - ' . date('l jS M', strtotime($order_date)) . '</p>';
        echo '<p><strong>Collection point:</strong> 84 Millham Road, Bishops Cleeve, GL52 8BG - knock on the side door, then stand back and order will be placed on the table outside.</p>';
	} else {
		echo '<p><strong>'.__('Expected '.$type[$pickup_delivery_key].' time: ').'</strong> ' . get_the_title($order_slot_id) . '</p>';
	}
}


/**
 * Process the checkout
 */
add_action('woocommerce_checkout_process', 'papagees_checkout_field_process');
function papagees_checkout_field_process() {

	// first check if this is just a gift card
	if(check_if_cart_is_only_gift_card()) {
		return;
	}

	// if not just a gift card then check a delivery has been chosen 
    // Check if set, if its not set add an error.
    if ( ! $_POST['billing_delivery_slot'] ) {
		wc_add_notice( __( 'Please select a delivery time slot.' ), 'error' );
	}
        
}

// define the woocommerce_before_checkout_process callback 
function papagees_before_checkout_process( $array ) { 
	// make action magic happen here... 
	// items in cart
	// $cart_items = WC()->cart->get_cart_item_quantities();
	// $cart_items = intval(array_sum($cart_items));

	if(check_if_cart_is_only_gift_card()) {
		return;
	}

	$cart_items = get_total_specific_to_product_category(18); // number of items from pizza category

	// order slot choosen by customer
	$order_slot_id = $_POST['billing_delivery_slot'];
	// get delivery pickup type
	$pickup_delivery = $_POST['billing_pickup_delivery'];
	// available units in order slot 
    $available_slots = intval(get_post_meta($order_slot_id, 'order_units_available', true));
    
    $unqualified = minimum_order_for_distance();

    if($unqualified) {
        wc_add_notice( __( 'Sorry there is a £15 minimum subtotal order for free delivery to this postcode.' ), 'error' );
		return;
    }

	if($cart_items > $available_slots) {
		wc_add_notice( __( 'You gotta be quicker than that! Looks like you were too slow in placing this order, that time slot is no longer available! Please choose another.' ), 'error' );
		return;
	}

	if($pickup_delivery == 0) {
		wc_add_notice( __( 'Please confirm Collect or Delivery.' ), 'error' );
		return;
	}
	
	if($pickup_delivery == 2 && $cart_items == 0) { // if this is a delivery without any pizza notify we can only deliver with a pizza
		wc_add_notice( __( 'Sorry, delivery is only possible with at least 1 pizza in your order.' ), 'error' );
		return;
	}

	if($pickup_delivery == 2) {
		$response = papagees_postcode_check_on_process();
		if($response === false) {
			wc_add_notice( __( 'Sorry! '.$_POST["billing_postcode"].' is out of our delivery range! Order must be Collected.' ), 'error' );
			return;    
		}
	}

}; 

			
// add the action 
add_action( 'woocommerce_before_checkout_process', 'papagees_before_checkout_process', 10, 1 ); 	


function papagees_postcode_check_on_process() {
    // Handle request then generate response using WP_Ajax_Response
    
    $nonce = $_POST['woocommerce-process-checkout-nonce'];
    if ( ! wp_verify_nonce( $nonce, 'woocommerce-process_checkout' ) ) {
        wp_die ( 'We couldn\'t validate your order, please try again!' );
    }
    $postcode = $_POST['billing_postcode'];
    $request = 'api.postcodes.io/postcodes/'.$postcode;

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $request,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $response = json_decode($response);

    $min_miles = 4;

    $lat1 = 51.95628;
    $lon1 = -2.04092;
    $lat2 = $response->result->latitude;
    $lon2 = $response->result->longitude;

    $public_class = new Papa_Gees_Custom_Woo_Public('public','1.0');

    $distance = $public_class->get_customer_distance($lat1, $lon1, $lat2, $lon2, 'M');

    if ($distance > $min_miles) {
        $response = false;
    }

    return $response;

}


function papagees_before_single_product_summary() {
	echo '<div class="papagees-single">';
}
add_action('woocommerce_before_single_product_summary','papagees_before_single_product_summary',30);

function papagees_after_single_product_summary() {
	echo '</div>';
}
add_action('woocommerce_after_single_product_summary','papagees_after_single_product_summary',11);

remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

add_action( 'woocommerce_after_single_product', 'woocommerce_output_related_products', 20 );

function change_view_cart_link( $params, $handle ) {

	switch ($handle) {
		case 'wc-add-to-cart':
			$params['i18n_view_cart'] = 'Success! View basket...';
		break;
	}
	return $params;
}
add_filter( 'woocommerce_get_script_data', 'change_view_cart_link',10,2 );

function time_ordered_stats() {
    $order_slots = array(
		'post_type' => 'order_slots',
		'post_status' => 'publish',
        'posts_per_page' => -1,
        'order' => 'ASC',
        'orderby' => 'date'
    );
    $order_slot_posts = get_posts($order_slots);

    $orders = array();
    foreach( $order_slot_posts as  $order_slot_post ) {
        
        $orders_args = array(
            'post_type' => 'shop_order',
            'post_status' => 'wc-completed',
            'meta_key' => '_billing_delivery_slot',
            'meta_value' => $order_slot_post->ID,
            'posts_per_page' => -1,
            'order' => 'ASC',
            'orderby' => 'date'
        );
        $orders[$order_slot_post->ID] = get_posts($orders_args);
    }
    
    foreach ($orders as $order_posts) {
        foreach($order_posts as $order_post) {
            $slot_id = get_post_meta( $order_post->ID, '_billing_delivery_slot', true );
            
        }
        // echo '<p>' . get_post_meta( $slot_id, 'order_slot_time', true) . '</p>';

        echo '<p>' . count($order_posts) . '</p>';
    }
    // wp_die();

}
// add_action('init', 'time_ordered_stats');