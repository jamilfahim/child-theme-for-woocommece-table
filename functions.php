<?php
/**
 * Recommended way to include parent theme styles.
 * Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme
 */

add_action('wp_enqueue_scripts', 'theme_enqueue_styles', 998);
function theme_enqueue_styles() {
	$prefix = function_exists('elessi_prefix_theme') ? elessi_prefix_theme() : 'elessi';
    wp_enqueue_style($prefix . '-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style($prefix . '-child-style', get_stylesheet_uri());
}
/**
 * Your code goes below
 */

 add_action( 'woocommerce_after_main_content', 'single_product_add_to_card' );
 function single_product_add_to_card(){
	global $product;
	if ( $product->is_type( 'variable' ) ) {
		?>


		<div class="single-product-table">
				<table class="product-table">
					<thead>
						<tr>
							<th scope="col"> Name </th>
							<th scope="col"> SKU </th>					
							<th scope="col"> Description </th>
							<th scope="col"> Status </th>
							<th scope="col"> Shiping Date </th>
							<th scope="col">  </th>
						</tr>
					</thead>
					<tbody>
						<?php
								  global $product,$woocommerce;
								  $product_id = $product->get_id();
								  $product = wc_get_product($product_id);
								 
							 ?>
						<tr class="">
							<td data-label="Name " scope="row"><?php  echo $product->get_name();?></td>
							<td data-label="SKU" scope="row"><?php  echo $product->get_sku();?></td>
							
							<td data-label="Description"><?php  echo $product->get_short_description();?></td>
							<td data-label="Status"><?php
				
							if($product->get_stock_status() == 'instock' ){
		
								echo "<p>In Stock</p>";
								
							}else if($product->get_stock_status() == 'outofstock' ){
		
								echo "<p>Out of Stock</p>";
		
							}
							else if($product->get_stock_status() == 'on_demand' ){
		
								echo "<p>On Demand</p>";
								
							}else if($product->get_stock_status() == 'special' ){
		
								echo "<p>Special</p>";
							}
		
							?>
							
						</td>
						<td data-label="Shiping Date">
							<?php
						
						$Today=date('y:m:d');
						// Declare a date 
						$date = "2019-05-10"; 
						
						//if already have time  then use this  =============
						
						if($product->get_stock_status() == 'instock' ){
							
							echo "<span>". date('m/d/Y', strtotime($Today. ' + 3 days'))."</span> to ";
							echo "<span>". date('m/d/Y', strtotime($Today. ' + 4 days'))."</span>";
		
						}else if($product->get_stock_status() == 'outofstock' ){
		
							echo "<p>No Date</p>";
		
						}
						else if($product->get_stock_status() == 'on_demand' ){
							
							echo "<span>". date('m/d/Y', strtotime($Today. ' + 21 days'))."</span> to ";
							echo "<span>". date('m/d/Y', strtotime($Today. ' + 28 days'))."</span>";
							
						}else if($product->get_stock_status() == 'special' ){
						
							echo "<span>". date('m/d/Y', strtotime($Today. ' + 42 days'))."</span> to ";
							echo "<span>". date('m/d/Y', strtotime($Today. ' + 56 days'))."</span>";
		
						}
						
						?></td>
							<td><?php //new
		if ( $product->is_in_stock() ) : ?>
		
			<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
			
				<?php
		
				woocommerce_quantity_input(
					array(
						'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
						'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
						'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
					)
				);
		
				?>
		
				<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
		
			
			</form>
		
		
		<?php endif; ?></td>
						</tr>
						
						
					</tbody>
				</table>
			</div>
			<?php
	}
    

 

 }



 // Add new stock status options
function filter_woocommerce_product_stock_status_options( $status ) {
	// Add new statuses
	$status['on_demand'] = __( 'On Demand', 'woocommerce' );
	$status['special'] = __( 'Special Offer', 'woocommerce' );
	$status['discontinued'] = __( 'Discontinued', 'woocommerce' );

	return $status;
}
add_filter( 'woocommerce_product_stock_status_options', 'filter_woocommerce_product_stock_status_options', 10, 1 );

// Availability text
function filter_woocommerce_get_availability_text( $availability, $product ) {
	// Get stock status
	switch( $product->get_stock_status() ) {
		 case 'on_demand':
			  $availability = __( 'On Demand', 'woocommerce' );
		 break;
		 case 'special':
			  $availability = __( 'special', 'woocommerce' );
		 break;
		 case 'discontinued':
			$availability = __( 'Discontinued', 'woocommerce' );
	  break;
	}

	return $availability; 
}
add_filter( 'woocommerce_get_availability_text', 'filter_woocommerce_get_availability_text', 10, 2 );

// Availability CSS class
function filter_woocommerce_get_availability_class( $class, $product ) {
	// Get stock status
	switch( $product->get_stock_status() ) {
		 case 'on_demand':
			  $class = 'on-demand';
		 break;
		 case 'special':
			  $class = 'special';
		 break;
		 case 'discontinued':
			$class = 'discontinued';
	  break;
	}

	return $class;
}
add_filter( 'woocommerce_get_availability_class', 'filter_woocommerce_get_availability_class', 10, 2 );

// Admin stock html
function filter_woocommerce_admin_stock_html( $stock_html, $product ) {
	// Simple
	if ( $product->is_type( 'simple' ) ) {
		 // Get stock status
		 $product_stock_status = $product->get_stock_status();
	// Variable
	} elseif ( $product->is_type( 'variable' ) ) {
		 foreach( $product->get_visible_children() as $variation_id ) {
			  // Get product
			  $variation = wc_get_product( $variation_id ); 
			  // Get stock status
			  $product_stock_status = $variation->get_stock_status();
		
		 }
	}
	
	// Stock status
	switch( $product_stock_status ) {
		 case 'on_demand':
			  $stock_html = '<mark class="on-demand" style="background:transparent none;color:#33ccff;font-weight:700;line-height:1;">' . __( 'On Demand', 'woocommerce' ) . '</mark>';
		 break;
		 case 'special':
			  $stock_html = '<mark class="special" style="background:transparent none;color:#cc33ff;font-weight:700;line-height:1;">' . __( 'Special Offer', 'woocommerce' ) . '</mark>';
		 break;
		 case 'discontinued':
			$stock_html = '<mark class="special" style="background:transparent none;color:#FF0000;font-weight:700;line-height:1;">' . __( 'Discontinued', 'woocommerce' ) . '</mark>';
	  break;
	}

	return $stock_html;
}
add_filter( 'woocommerce_admin_stock_html', 'filter_woocommerce_admin_stock_html', 10, 2 );


// Show shipping date in order details
function cloudways_display_order_data_in_admin( $order ){  ?>
	<div class="shipping_column form-field form-field-wide">
		 <h4 style="margin-top:4px;">Shipping Date</h4>
		 <div class="shipping-date">
		 <?php
						$order_date = $order->order_date;
						//$Today=date('y:m:d');
						// Declare a date 
						$date = "2019-05-10"; 
		
						
						//$stock_status = $item->get_meta('_stock_status');

						// Get and Loop Over Order Items
foreach ( $order->get_items() as $item_id => $item ) {
   $product_id = $item->get_product_id();
   $item_type = $item->get_type(); // e.g. "line_item"
}

						$stockstatus = get_post_meta(  $product_id, '_stock_status', true );
						//var_dump($stockstatus);
						//var_dump($order_date);
						//if already have time  then use this  =============
						
						if($stockstatus == 1 ){
							
							echo "<span>". date('m/d/Y', strtotime($order_date. ' + 3 days'))."</span> to ";
							echo "<span>". date('m/d/Y', strtotime($order_date. ' + 4 days'))."</span>";
		
						}
						else if($stockstatus == 'on_demand' ){
							
							echo "on demand";
							echo "<span>". date('m/d/Y', strtotime($order_date. ' + 21 days'))."</span> to ";
							echo "<span>". date('m/d/Y', strtotime($order_date. ' + 28 days'))."</span>";
							
						}else if($stockstatus == 'special' ){
						
							echo "<span>". date('m/d/Y', strtotime($order_date. ' + 42 days'))."</span> to ";
							echo "<span>". date('m/d/Y', strtotime($order_date. ' + 56 days'))."</span>";
		
						}else if( $stockstatus == 'discontinued' ) {
		
							echo "<p>No Date</p>";
		
						}
						
						?>
		 </div>
	</div>
<?php }
add_action( 'woocommerce_admin_order_data_after_order_details', 'cloudways_display_order_data_in_admin' );


function save_stock_status_order_item_meta( $item, $cart_item_key, $values, $order ) {
    $item->update_meta_data( '_stock_status', $values['data']->get_stock_status() );

	 $stockstatus = $values['data']->get_stock_status();
	 $order_date = $order->order_date;

	 if($stockstatus == 'instock' ){
			
		$shipping_date =  date('m/d/Y', strtotime($order_date. ' + 3 days'))."to".date('m/d/Y', strtotime($order_date. ' + 4 days'));
		

	}
	else if($stockstatus == 'on_demand' ){

		$shipping_date =   date('m/d/Y', strtotime($order_date. ' + 21 days'))."to".date('m/d/Y', strtotime($order_date. ' + 28 days'));
	
		
	}else if($stockstatus == 'special' ){

		$shipping_date =   date('m/d/Y', strtotime($order_date. ' + 42 days'))."to".date('m/d/Y', strtotime($order_date. ' + 56 days'));
	
	}else {

		echo "<p>No Date</p>";

	}
    $item->update_meta_data( 'Shipping Time', $shipping_date );
}
add_action('woocommerce_checkout_create_order_line_item', 'save_stock_status_order_item_meta', 10, 4 );