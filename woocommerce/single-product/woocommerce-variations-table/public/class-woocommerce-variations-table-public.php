<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://plugins.db-dzine.com
 * @since      1.0.0
 *
 * @package    WooCommerce_Variations_Table
 * @subpackage WooCommerce_Variations_Table/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WooCommerce_Variations_Table
 * @subpackage WooCommerce_Variations_Table/public
 * @author     Daniel Barenkamp <support@db-dzine.com>
 */
class WooCommerce_Variations_Table_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * options of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $options
     */
    private $options;

    /**
     * if true this product will be excluded
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $options
     */
    private $exclude;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) 
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->variationsTableMetaDataTexts = array();
    }

    /**
     * Enqueu Styles
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function enqueue_styles() 
    {
        global $woocommerce_variations_table_options;

        $this->options = $woocommerce_variations_table_options;

        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-variations-table-public.css', array(), $this->version, 'all' );
        if($this->get_option('datatablesEnable')) {
            
            wp_enqueue_style( 'jquery-datatables', plugin_dir_url( __FILE__ ) . 'css/vendor/jquery.dataTables.min.css', array(), '1.10.21', 'all' );

            if($this->get_option('datatablesResponsive')) {
                wp_enqueue_style( 'jquery-datatables-responsive', plugin_dir_url( __FILE__ ) . 'css/vendor/responsive.dataTables.min.css', array(), '2.2.5', 'all' );
            }
            
            if($this->get_option('datatablesPrint') || $this->get_option('datatablesCopyHtml5') || $this->get_option('datatablesExcelHtml5') || $this->get_option('datatablesCsvHtml5') || $this->get_option('datatablesPdfHtml5')) {
                wp_enqueue_style( 'jquery-datatables-buttons', plugin_dir_url( __FILE__ ) . 'css/vendor/buttons.dataTables.min.css', array(), '1.6.2', 'all' );
            }

            if($this->get_option('datatablesFixedHeader')) {
                wp_enqueue_style( 'jquery-datatables-fixedheader', plugin_dir_url( __FILE__ ) . 'css/vendor/fixedHeader.dataTables.min.css', array(), '3.1.7', 'all' );
            }

        }       

        if($this->get_option('lightboxEnable')) {
            wp_enqueue_style( 'luminous-lightbox', plugin_dir_url( __FILE__ ) . 'css/vendor/luminous-basic.min.css', array(), '2.3.2', 'all' );
        }

        $customCSS = $this->get_option('customCSS');
        if(empty($customCSS)) {
            return false;
        }

        file_put_contents( dirname(__FILE__)  . '/css/woocommerce-variations-table-custom.css', $customCSS);

        wp_enqueue_style( $this->plugin_name.'-custom', plugin_dir_url( __FILE__ ) . 'css/woocommerce-variations-table-custom.css', array(), $this->version, 'all');

    }

    /**
     * Enque JS SCripts
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function enqueue_scripts() 
    {
        global $woocommerce_variations_table_options;

        $this->options = $woocommerce_variations_table_options;

        if($this->get_option('datatablesEnable')) {
            wp_enqueue_script( 'jquery-datatables', 'https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js', array( 'jquery' ), '1.10.21', true );

            $jss = array();
            
            if($this->get_option('datatablesResponsive')) {
                $jss['datatables-responsive'] = plugin_dir_url( __FILE__ ) . 'js/vendor/dataTables.responsive.min.js';
            }

            if($this->get_option('datatablesPrint') || $this->get_option('datatablesCopyHtml5') || $this->get_option('datatablesExcelHtml5') || $this->get_option('datatablesCsvHtml5') || $this->get_option('datatablesPdfHtml5')) {
                $jss['datatables-buttons'] = plugin_dir_url( __FILE__ ) . 'js/vendor/dataTables.buttons.min.js';
            }

            if($this->get_option('datatablesPdfHtml5')) {
                $jss['datatables-pdfmake'] = plugin_dir_url( __FILE__ ) . 'js/vendor/pdfmake.min.js';
                $jss['datatables-vfs_fonts'] = plugin_dir_url( __FILE__ ) . 'js/vendor/vfs_fonts.js';
            }

            if($this->get_option('datatablesCopyHtml5') || $this->get_option('datatablesExcelHtml5') || $this->get_option('datatablesCsvHtml5')) {
                $jss['datatables-jszip'] = plugin_dir_url( __FILE__ ) . 'js/vendor/jszip.min.js';
                $jss['datatables-html5'] = plugin_dir_url( __FILE__ ) . 'js/vendor/buttons.html5.min.js';
            }

            if($this->get_option('datatablesPrint')) {
                $jss['datatables-print'] = plugin_dir_url( __FILE__ ) . 'js/vendor/buttons.print.min.js';
            }

            if($this->get_option('datatablesFixedHeader')) {
                $jss['datatables-fixedheader'] = plugin_dir_url( __FILE__ ) . 'js/vendor/dataTables.fixedHeader.min.js';
            }   

            if($this->get_option('datatablesColumnFilterWidgets')) {
                $jss['datatables-column-filter-widgets'] = plugin_dir_url( __FILE__ ) . 'js/ColumnFilterWidgets.js';
            }

            foreach ($jss as $key => $js) {
                wp_enqueue_script( $key, $js, array( 'jquery', 'jquery-datatables' ), '1.10.21', true );
            }
        }

        if($this->get_option('lightboxEnable')) {
            wp_enqueue_script( 'luminous-lightbox', plugin_dir_url( __FILE__ ) . 'js/vendor/luminous.min.js', array( 'jquery' ), '2.3.2', true );
        }
        

        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-variations-table-public.js', array( 'jquery'), $this->version, true );

        $forJS = array();
        $forJS['cart_url'] = wc_get_cart_url();
        $forJS['lightbox'] = $this->get_option('lightboxEnable');
        $forJS['show_all'] = __('Show All', 'woocommerce-variations-table');
        $forJS['searchText'] = __('Search', 'woocommerce-variations-table');
        $forJS['addedToCart'] = __('Added to Cart', 'woocommerce-variations-table');
        $forJS['addedToCart'] = __('Added All to Cart', 'woocommerce-variations-table');
        $forJS['addAllToCart'] = __('Add all To Cart', 'woocommerce-variations-table');
        $forJS['redirectToCart'] = get_option( 'woocommerce_cart_redirect_after_add');
        $forJS['cartURL'] = wc_get_cart_url();

        $numericSortingColumns = array();
        $priceColumnPosition = array_search('pr', array_keys($this->get_option('variationsTableData')['enabled']));
        if(!empty($priceColumnPosition)) {
            $numericSortingColumns[] = $priceColumnPosition;
        }
        $forJS['numericSortingColumns'] = $numericSortingColumns;

        $forJS = apply_filters('woocommerce_variations_table_js_settings', $forJS);
        wp_localize_script($this->plugin_name, 'woocommerce_variations_table_options', $forJS);

        $customJS = $this->get_option('customJS');
        if(empty($customJS))
        {
            return false;
        }

        file_put_contents( dirname(__FILE__)  . '/js/woocommerce-variations-table-custom.js', $customJS);

        wp_enqueue_script( $this->plugin_name.'-custom', plugin_dir_url( __FILE__ ) . 'js/woocommerce-variations-table-custom.js', array( 'jquery' ), $this->version, true );
        
    }

    /**
     * Get Options
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @param   [type]                       $option [description]
     * @return  [type]                               [description]
     */
    private function get_option($option)
    {
        if(!is_array($this->options)) {
            return false;
        }
        
        if(!array_key_exists($option, $this->options))
        {
            return false;
        }
        return $this->options[$option];
    }
    
    /**
     * Inits the Variations Table
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function init()
    {

        global $woocommerce_variations_table_options;
        $this->options = $woocommerce_variations_table_options;
        if (!$this->get_option('enable')) {
            return false;
        }

        $excludeUserRoles = $this->get_option('applyForExcludeUserRoles');
        if(is_array($excludeUserRoles) && !empty($excludeUserRoles)) {

            $currentUserRole = $this->get_user_role();
            if(in_array($currentUserRole, $excludeUserRoles)) {
                return false;
            }
        }

        // Only not logged in
        if($this->get_option('applyForUserGroup') == 2) {
            if(is_user_logged_in()) {
                return false;
            }
        }

        // Only logged in
        if($this->get_option('applyForUserGroup') == 3) {
            if(!is_user_logged_in()) {
                return false;
            }
        }

        if($this->get_option('hardRemove')) {
            add_action('wp_head', array($this, 'hardRemove'), 100);
        }


        $variationsTableMetaDataTexts = $this->get_option('variationsTableMetaDataTexts');
        if(!empty($variationsTableMetaDataTexts)) {

            $temp = array();
            foreach($variationsTableMetaDataTexts as $variationsTableMetaDataText) {
                $variationsTableMetaDataTextSplit = explode('|', $variationsTableMetaDataText);
                if(empty($variationsTableMetaDataTextSplit[0]) || empty($variationsTableMetaDataTextSplit[1])) {
                    continue;
                }
                $temp[$variationsTableMetaDataTextSplit[0]] = $variationsTableMetaDataTextSplit[1];
            }
            $this->variationsTableMetaDataTexts = $temp;
        }

        add_action( 'woocommerce_before_single_product', array($this,  'check_single' ) );
    }

    /**
     * Check if apply on single Product
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function check_single()
    {
        $apply = true;

        $excludeProductCategories = $this->get_option('excludeProductCategories');
        if(!empty($excludeProductCategories)) 
        {
            if($this->excludeProductCategories()) {
                $apply = false;
            }
        }

        $excludeProducts = $this->get_option('excludeProducts');
        if(!empty($excludeProducts)) 
        {
            if($this->excludeProducts()) {
                $apply = false;
            }
        }

        // Not in exclusion list
        if($apply) {

            $this->remove_add_to_cart();
            $variationsTablePosition = $this->get_option('variationsTablePosition');
            $variationsTablePriority = $this->get_option('variationsTablePriority');
            !empty($variationsTablePosition) ? $variationsTablePosition = $variationsTablePosition : $variationsTablePosition = 'woocommerce_after_single_product_summary';
            !empty($variationsTablePriority) ? $variationsTablePriority = $variationsTablePriority : $variationsTablePriority = 5;
            add_action( $variationsTablePosition, array($this,'variationsTable'), $variationsTablePriority );
        }
    }

    public function variations_table_shortcode($atts)
    {
        $args = shortcode_atts( array(
            'product' => '',
            'products' => '',
            'category' => '',
        ), $atts );

        $product_id = intval($args['product']);
        $products = !empty($args['products']) ? explode(',', $args['products']) : '';
        $category = intval($args['category']);

        $product_ids = array();
        if(!empty($category)) {
             $args = array(
                'post_type'             => 'product',
                'post_status'           => 'publish',
                'ignore_sticky_posts'   => 1,
                'posts_per_page'        => -1,
                'tax_query'             => array(
                    array(
                        'taxonomy'      => 'product_cat',
                        'field'         => 'term_id',
                        'terms'         => $category,
                        'operator'      => 'IN'
                    ),
                )
            );
            $products_query = new WP_Query($args);
            if(!empty($products_query->posts)) {
                foreach ($products_query->posts as $product_query) {
                    $product_ids[] = $product_query->ID;
                }
            }
        } elseif (!empty($products)) {
            $product_ids = $products;
        } else {
            if(empty($product_id)) {
                global $post;
                $product_ids[] = $post->ID;
            } else {
                $product_ids[] = $product_id;
            }   
        }

        if(empty($product_ids)) {
            return 'No Products found.';
        }

        ob_start();

        foreach ($product_ids as $product_id) {

            global $product;
            $product = wc_get_product($product_id);
            if(!$product) {
                continue;
            }

            if(!$product->is_type('variable')) {
                continue;
            }

            $this->variationsTable(true);
        }

        $this->getTable();
        
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

   /**
     * Creates the variations Table
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function variationsTable($multiple = false)
    {
        global $product, $woocommerce;

        if(!$product) {
            return false;
        }

        if(!$product->is_type( 'variable' )) {
            return false;
        }

        $enabled = apply_filters('woocommerce_variations_table_enabled', true, $product );
        if(!$enabled) {
            return false;
        }

        $variationsTableTitle = apply_filters('woocommerce_variations_table_title', $this->get_option('variationsTableTitle') );
        $this->variationsTableData = apply_filters('woocommerce_variations_table_data', $this->get_option('variationsTableData') );

        $this->variationsTableData = $this->variationsTableData['enabled'];
        unset($this->variationsTableData['placebo']);

        if(!$this->variationsTableData) {
            return;
        }

        $attributes = $product->get_variation_attributes();
        $attribute_keys = array_keys( $attributes );
        $available_variations = $product->get_available_variations();

        if( version_compare( $woocommerce->version, '3.0.0', ">=" ) ) {
            $selected_attributes = $product->get_default_attributes();
        } else {
            $selected_attributes = $product->get_variation_default_attributes();
        }

        if(!isset($this->th)) {
            $this->th = '';
        }
        if(!isset($this->td)) {
            $this->td = '';
        }
        if(!isset($this->first)) {
            $this->first = true;
        }

        if($this->first && !empty($variationsTableTitle)) {
            echo '<h2>' . $variationsTableTitle . '</h2>';
        }
        if(!isset($this->columnCount)) {
            $this->columnCount = 0;
        }

        $blacklistKeys = array('im', 'qt','ca','mc','mq','wi','pd','eq');

        foreach ($available_variations as $variation) {

            $variation_product = new WC_Product_Variation( $variation['variation_id'] );

            $this->td .= '<tr id="variations-table-row-' . $variation_product->get_id() . '" class="variations-table-row">';
            foreach($this->variationsTableData as $variationsTableDataKey => $variationsTableDataValue) {
                if($variationsTableDataKey == "at") {

                    foreach ($variation_product->get_attributes() as $attr_name => $attr_value) {

                        $attr_name = urldecode($attr_name);
                        $attr_value = urldecode($attr_value);

                        $preselect = '';

                        // Get the correct variation values
                        if (strpos($attr_name, 'pa_') !== FALSE){ // variation is a pre-definted attribute
                            // $attr_name = substr($attr_name, 3);
                            $attr = get_term_by('slug', $attr_value, $attr_name);
                            if(!empty($selected_attributes)) {
                                if(isset($selected_attributes[$attr_name]) && $selected_attributes[$attr_name] == $attr_value){
                                    $preselect = 'data-preselect="' . apply_filters('woocommerce_attribute_value_image', esc_html( $attr->name ), $attr->term_id) . '"';
                                }
                            }

                            $attr_value = apply_filters('woocommerce_attribute_value_image', esc_html( $attr->name ), $attr->term_id);
                            $attr_name = wc_attribute_label($attr_name);

                        } else {
                            $attr = maybe_unserialize( get_post_meta( $product->get_id(), '_product_attributes' ) );
                            $attr_name = $attr[0][$attr_name]['name'];
                            
                        }
                        if($this->first) {
                            $this->th .= '<th id="variations-table-header-' . $variationsTableDataKey . '-' . $attr_name .'" ' . $preselect . ' class="variations-table-header variations-table-header-' . $variationsTableDataKey . '">' . $attr_name . '</th>';
                            $this->columnCount++;
                        }
                        $this->td .= '<td data-order="' . floatval($attr_value) . '" data-th="' . $attr_name . '" class="variations-table-value-' . $variationsTableDataKey . ' variations-table-value">';

                        if(!$this->get_option('datatablesResponsive') && !in_array($variationsTableDataKey, $blacklistKeys)) {
                            $this->td .= '<span class="variations-table-only-mobile">' . $attr_name . ': </span>';
                        }
                        
                        $this->td .= $attr_value . '</td>';
                    }
                    continue;
                }

                $variationsTableDataDataValue = $this->getVariationData($variationsTableDataKey, $variation_product);

                if($this->first) {

                    if($variationsTableDataKey == "mc") {
                        $variationsTableDataValue = "";
                    }
                    if($variationsTableDataKey == "mq") {
                        $variationsTableDataValue = __('Quantity', 'woocommerce-variations-table');
                    }


                    if (strpos($variationsTableDataKey, 'meta_') !== FALSE){ // variation is a pre-definted attribute
                        $meta_key = substr($variationsTableDataKey, 5);
                        if(isset($this->variationsTableMetaDataTexts[$meta_key]) && !empty($this->variationsTableMetaDataTexts[$meta_key])) {
                            $variationsTableDataValue = $this->variationsTableMetaDataTexts[$meta_key];
                        }
                    }


                    $this->th .= '<th id="variations-table-header-' . $variationsTableDataKey . '" class="variations-table-header">' . __( $variationsTableDataValue, 'woocommerce-variations-table' ) . '</th>';
                    $this->columnCount++;
                }

                // data-order="' . (float) filter_var( str_replace(' - ', '', strip_tags($variationsTableDataDataValue) ), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ) . '"
                $this->td .=  '<td data-th="' . __( $variationsTableDataValue, 'woocommerce-variations-table' ) . '" class="variations-table-value-' . $variationsTableDataKey . ' variations-table-value">';

                if(!$this->get_option('datatablesResponsive') && !in_array($variationsTableDataKey, $blacklistKeys)) {
                    $this->td .=  '<span class="variations-table-only-mobile">' . __( $variationsTableDataValue, 'woocommerce-variations-table' ) . ': </span>';
                }

                $this->td .= $variationsTableDataDataValue . '</td>';
            }
            $this->td .= '</tr>';
            
            $this->first = false;
        }

        if(!$multiple) {
            $this->getTable();
        }
    }

    public function getTable()
    {
        if(empty($this->variationsTableData)) {
            return;
        }

        $this->tfooter = "";
        if(array_key_exists('mc', $this->variationsTableData) || array_key_exists('mq', $this->variationsTableData)) {

            $this->tfooter .= '<tfoot>';
                $this->tfooter .= '<tr id="variations-table-multiple-add-to-cart" class="variations-table-multiple-add-to-cart">';
                    $this->tfooter .= '<td colspan="' . $this->columnCount . '">';
                        $this->tfooter .= '<button type="button" disabled="true" href="#" id="variations-table-multiple-add-to-cart-btn" class="variations-table-multiple-add-to-cart-btn btn button btn-primary btn-lg">' . __('Add all To Cart', 'woocommerce-variations-table') . '</button>';
                    $this->tfooter .= '</td>';
                $this->tfooter .= '</tr>';
            $this->tfooter .= '</tfoot>';
        }

        do_action('woocommerce_variations_table_before_table');

        $datatablesEnabled = apply_filters('woocommerce_variations_table_datatables_enabled', $this->get_option('datatablesEnable'));

        if($datatablesEnabled == "1") {

            $datatablesOptions = array(
                'paging' => $this->get_option('datatablesPaging') == "1" ? true : false,
                'pageLength' => (int) $this->get_option('datatablesPageLength') ? (int) $this->get_option('datatablesPageLength') : 10,
                'lengthMenu' => $this->get_option('datatablesPageLengthMenu') ? explode(',', $this->get_option('datatablesPageLengthMenu') ) : array( 10, 25, 50, 60, 75, 100 ),
                'orderColumn' => !empty($this->get_option('datatablesOrderColumn')) ? $this->get_option('datatablesOrderColumn') : '',
                'orderColumnType' => !empty($this->get_option('datatablesOrderColumnType')) ? $this->get_option('datatablesOrderColumnType') : '',
                'ordering' => $this->get_option('datatablesOrdering') == "1" ? true : false,
                'info' => $this->get_option('datatablesInfo') == "1" ? true : false,
                'stateSave' => $this->get_option('datatablesStateSave') == "1" ? true : false,
                'searching' => $this->get_option('datatablesSearching') == "1" ? true : false,
                'fixedHeaderA' => $this->get_option('datatablesFixedHeader'),
                'fixedHeaderOffset' => $this->get_option('datatablesFixedHeaderOffset'),
                'fixedFooterA' => $this->get_option('datatablesFixedFooter'),
                'fixedFooterOffset' => $this->get_option('datatablesFixedFooterOffset'),
                'responsive' => $this->get_option('datatablesResponsive') == "1" ? true : false,
                'filtering' => $this->get_option('datatablesFiltering'),
                'columnSearch' => $this->get_option('datatablesColumnSearch'),
                'orderCellsTop' => $this->get_option('datatablesColumnSearch') == "1" ? true : false,
                'columnFilterWidgets' => $this->get_option('datatablesColumnFilterWidgets'),
                'sDom' => $this->get_option('datatablesSDom'),
                'language' => array(
                    'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/' . $this->get_option('datatablesLanguage') . '.json',
                    // 'url' => '//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/German.json',
                    // 'url' => '//cdn.datatables.net/plug-ins/1.10.16/i18n/' . $this->get_option('datatablesLanguage') . '.json',
                )
            );

            if($this->get_option('datatablesScrollCollapse') == "1") {
                $datatablesOptions['scrollCollapse'] = true;
                $datatablesOptions['scrollY'] = $this->get_option('datatablesScrollY') . 'px';
            }

            $buttons = array();
            if($this->get_option('datatablesPrint') == "1") {
                $buttons[] = array(
                    'extend' => 'print',
                    'exportOptions' => array(
                        'stripHtml' => false,
                    ),
                    'text' => __('Print', 'woocommerce-variations-table'),
                );
            }
            if($this->get_option('datatablesCopyHtml5') == "1") {
                $buttons[] = array(
                    'extend' => 'copyHtml5',
                    'exportOptions' => array(
                        'stripHtml' => false,
                    ),
                    'text' => __('Copy', 'woocommerce-variations-table'),
                );
            }
            if($this->get_option('datatablesExcelHtml5') == "1") {
                $buttons[] = array(
                    'extend' => 'excelHtml5',
                    'exportOptions' => array(
                        'stripHtml' => true,
                    ),
                    'text' => __('Excel', 'woocommerce-variations-table'),
                );
            }
            if($this->get_option('datatablesCsvHtml5') == "1") {
                $buttons[] = array(
                    'extend' => 'csvHtml5',
                    'exportOptions' => array(
                        'stripHtml' => true,
                    )
                );
            }
            if($this->get_option('datatablesPdfHtml5') == "1") {
                $buttons[] = array(
                    'extend' => 'pdfHtml5',
                    'exportOptions' => array(
                        'stripHtml' => true,
                    ),
                    'text' => __('PDF', 'woocommerce-variations-table'),
                );
            }

            if(!empty($buttons)) {
                $datatablesOptions['buttons'] = $buttons;
            }

            $datatablesOptions = apply_filters('woocommerce_variations_table_datatables_options', $datatablesOptions);

            echo '<table class="woocommerce-variations-table datatables nowrap" data-options=' . json_encode($datatablesOptions, true) . ' width="100%">';
        } else {
            echo '<table class="woocommerce-variations-table nowrap rwd-table" width="100%">';
        }
        ?>
            <thead>
                <tr>
                    <?php echo $this->th ?>
                </tr>
            </thead>
            <tbody>
                <?php echo $this->td ?>
            </tbody>
            <?php echo $this->tfooter ?>
        </table>

        <?php
        $this->th = "";
        $this->td = "";
        $this->tfooter = "";
        unset($this->first);
        unset($this->columnCount);

        do_action('woocommerce_variations_table_after_table');
    }

    /**
     * Get Variation data by Key
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @param   [type]                       $key       [description]
     * @param   [type]                       $variation [description]
     * @return  [type]                                  [description]
     */
    public function getVariationData($key, $variation)
    {
        if(empty($key)) {
            return;
        }
        
        $html = "";

        if (strpos($key, 'meta_') !== FALSE){

            $meta_key = substr($key, 5);

            $html = get_post_meta($variation->get_id(), $meta_key, true);
            if(empty($html)) {
                $html = get_post_meta($variation->get_parent_id(), $meta_key, true);
            }
        } else {
            switch ($key) {
                case 'im':
                    $imageId = $variation->get_image_id();
                    $image = wp_get_attachment_image($imageId);
                    if($this->get_option('lightboxEnable')) {
                        $fullImage = wp_get_attachment_image_src($imageId, 'full');

                        $html = '<a href="' . $fullImage[0] . '" class="woocommerce-variations-table-lightbox">' . $image . '</a>';
                    } else {
                        $html = $image; 
                    }

                    if($this->get_option('barcodeYith')) {
                        $html = do_shortcode('[yith_render_barcode product_id= ' . $variation->get_id() . ' ]') . $html;
                    }

                    break;
                case 'sk':
                    $html = $variation->get_sku();
                    break;
                case 'pr':
                    if($this->get_option('datatablesFiltering')) {
                        $html = wc_price( $variation->get_price() );
                    } else {
                        $html = $variation->get_price_html();
                    }
                    break;
                case 'pt':
                    $html = apply_filters('the_title', $variation->get_name(), $variation->get_id());
                    break;
                case 'st':

                    $availability = $variation->get_availability();
                    if($variation->is_on_backorder( 1 )) {
                        $html = __('On Backorder', 'woocommerce-variations-table');
                    } elseif($variation->managing_stock()) {
                        $html = $availability['availability'];
                    } else {
                        if($variation->is_in_stock() == "instock") {
                            $html = __('In Stock', 'woocommerce-variations-table');
                        } elseif($product->get_stock_status() == 'outofstock'){
                            $html = __('Out of Stock', 'woocommerce-variations-table');
                        }
                        elseif($product->get_stock_status() == 'on_demand'){
                            $html = __('On Demand', 'woocommerce-variations-table');
                        }
                        elseif($product->get_stock_status() == 'special'){
                            $html = __('Special', 'woocommerce-variations-table');
                        }
                    }
                    // $html = '<p class="stock '. esc_attr( $class ) . '">'. wp_kses_post( $availability ) . '</p>';
                    break;
                case 'de':
                    $html = $variation->get_description();
                    break;
                case 'wi':
                    if($this->get_option('wishlistYith')) {
                        $html = do_shortcode('[yith_wcwl_add_to_wishlist product_id= ' . $variation->get_id() . ' ]');
                    } else {
                        $html = do_shortcode('[woocommerce_wishlist_button product= ' . $variation->get_id() . ' ]');
                    }
                    break;
                case 'di':
                    $html = wc_format_dimensions( $variation->get_dimensions(false) );
                    break;
                case 'at':
                    $html = $variation->get_variation_attributes();
                    break;
                case 'we':
                    $html = wc_format_weight( $variation->get_weight() );
                    break;
                case 'ca':
                    $html = $this->get_add_to_cart($variation);
                    break;
                case 'eq':
                    $html = $this->get_enquiry_button($variation);
                    break;
                case 'pd':
                    $html = $this->get_pdf_button($variation);
                    break;
                case 'mq':
                global $product;
                    $product_id = $product->get_id();
                    $variation_id = $variation->get_id();

                    if(!$variation->is_in_stock()) {
                        $html = '<small class="woocommerce-variation-add-to-cart-out-of-stock">' . __('Out of Stock', 'woocommerce-variations-table') . '</small>';
                        break;
                    }

                    $variations = $variation->get_attributes();
                    $variation_string = "";
                    if(!empty($variations)) {
                        foreach ($variations as $key => $value) {
                            $variation_string .= $key . '=' . $value . ',';
                        }
                        $variation_string = rtrim($variation_string, ',');
                    }

                    $html = '<div class="variations-table-multiple-quantity-add-to-cart-products" data-product_id="' . $product_id . '" data-variation_id="' . $variation_id . '" data-variation="' . $variation_string . '">';
                        $html .= $this->get_multiple_quantity($variation);
                    $html .= '</div>';
                    break;
                case 'qt':
                    $html = $this->get_quantity($variation);
                    break;
                case 'mc':
                    global $product;
                    $product_id = $product->get_id();
                    $variation_id = $variation->get_id();

                    if(!$variation->is_in_stock()) {
                        $html = '<small class="woocommerce-variation-add-to-cart-out-of-stock">' . __('Out of Stock', 'woocommerce-variations-table') . '</small>';
                        break;
                    }

                    $variations = $variation->get_attributes();
                    $variation_string = "";
                    if(!empty($variations)) {
                        foreach ($variations as $key => $value) {
                            $variation_string .= $key . '=' . $value . ',';
                        }
                        $variation_string = rtrim($variation_string, ',');
                    }

                    $html = '<input type="checkbox" class="variations-table-multiple-add-to-cart-products" name="variations-table-multiple-add-to-cart-products" data-product_id="' . $product_id . '" data-variation_id="' . $variation_id . '" data-variation="' . $variation_string . '">';
                    break;
                default:
                    $variation_id = $variation->get_id();
                    $html = get_post_meta($variation_id, $key, true);
                    break;
            }
        }

        return $html;
    }

    /**
     * Get Variation Add to Cart
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @param   [type]                       $variation [description]
     * @return  [type]                                  [description]
     */
    public function get_add_to_cart($variation)
    {
        global $product;
        ob_start();
        $variations = $variation->get_attributes();
        $variation_string = "";
        if(!empty($variations)) {
            foreach ($variations as $key => $value) {
                $variation_string .= $key . '=' . $value . ',';
            }
            $variation_string = rtrim($variation_string, ',');
        }

        $outOfStock = false;
        if(!$variation->is_on_backorder( 1 ) && !$variation->is_in_stock()) {
            $outOfStock = true;
        }

        ?>
        
        <div class="woocommerce-variation-add-to-cart woocommerce-variations-table-add-to-cart variations_button">
            <?php if($outOfStock) { ?>
            <button type="submit" class=" button alt" disabled="disabled"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
            <small class="woocommerce-variation-add-to-cart-out-of-stock"><?php echo __('Out of Stock', 'woocommerce-variations-table'); ?></small>
            <?php } else { ?>
            <button type="submit" class=" button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
            <?php } ?>
            <input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
            <input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
            <input type="hidden" name="variation_id" class="variation_id" value="<?php echo $variation->get_id() ?>" />
            <input type="hidden" name="variation" class="variation" value="<?php echo $variation_string ?>" />
        </div>
        
        <?php
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function get_enquiry_button($variation)
    {
        if(!class_exists('WooCommerce_Catalog_Mode')) {
            return;
        }

        global $woocommerce_catalog_mode_options;

        $buttonText = apply_filters('woocommerce_catalog_mode_enquiry_button_text', $woocommerce_catalog_mode_options['singleProductButtonText'] );

        $product_data = array(
            'name' => $variation->get_name(),
            'sku' => $variation->get_sku(),
        );
        $btnExtra = "data-products='" . json_encode($product_data). "'";

        ob_start();
        ?>
        <button type="button" class="enquiryLoopButton btn button btn-primary btn-lg" <?php echo $btnExtra ?>>
            <?php echo $buttonText ?>
        </button>
        
        <?php

        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function get_pdf_button($variation)
    {
        if(!class_exists('WooCommerce_Print_Products')) {
            return;
        }

        global $woocommerce_print_products_options;

        $buttonText = $woocommerce_print_products_options['iconTypeButtonPDFText'];

        $parentProductID = $variation->get_parent_id();
        $parentProductURL = get_permalink($parentProductID);
        $parentProductPDFExportURL = $parentProductURL . '?print-products=pdf&variation=' . $variation->get_id();

        ob_start();
        ?>
        <a type="button" href="<?php echo $parentProductPDFExportURL ?>" target="_blank" class="woocommerce-variations-table-pdf-export-button btn button btn-primary btn-lg">
            <?php echo $buttonText ?>
        </a>
        
        <?php

        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    /**
     * Get Variation Add to Cart
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @param   [type]                       $variation [description]
     * @return  [type]                                  [description]
     */
    public function get_quantity($variation)
    {
        global $product;

        if(!$variation->is_in_stock()) {
            return;
        }

        ob_start();
    
        do_action( 'woocommerce_before_add_to_cart_quantity' );

        woocommerce_quantity_input( array(
            'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $variation->get_min_purchase_quantity(), $variation ),
            'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $variation->get_max_purchase_quantity(), $variation ),
            'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : $variation->get_min_purchase_quantity(),
        ) );
        /**
         * @since 3.0.0.
         */
        do_action( 'woocommerce_after_add_to_cart_quantity' );

        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    public function get_multiple_quantity($variation)
    {
        global $product;

        ob_start();

        if(!$variation->is_in_stock()) {
            echo '<small class="woocommerce-variation-add-to-cart-out-of-stock">' . __('Out of Stock', 'woocommerce-variations-table') . '</small>';
            return;
        }       
    
        do_action( 'woocommerce_before_add_to_cart_quantity' );
        
        woocommerce_quantity_input( array(
            'min_value'   => 0,
            'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $variation->get_max_purchase_quantity(), $variation ),
            'input_value' => 0,
        ) );

        /**
         * @since 3.0.0.
         */
        do_action( 'woocommerce_after_add_to_cart_quantity' );

        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }

    /**
     * Exclude Product Categories
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function excludeProductCategories()
    {
        global $post;

        $excludeProductCategories = $this->get_option('excludeProductCategories');
        $excludeProductCategoriesRevert = $this->get_option('excludeProductCategoriesRevert');
        $terms = get_the_terms( $post->ID, 'product_cat' );
        if($terms)
        {
            foreach ($terms as $term)
            {
                if($excludeProductCategoriesRevert) {
                    if(!in_array($term->term_id, $excludeProductCategories)){
                        return TRUE;
                    }
                } else {
                    if(in_array($term->term_id, $excludeProductCategories)){
                        return TRUE;

                    }
                }
            }
        }
    }

    /**
     * Exclude Products
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function excludeProducts()
    {
        global $post;

        $excludeProducts = $this->get_option('excludeProducts');
        $excludeProductsRevert = $this->get_option('excludeProductsRevert');
        if($excludeProductsRevert) {
            if(!in_array($post->ID, $excludeProducts))
            {
                return true;
            }
        } else {
            if(in_array($post->ID, $excludeProducts))
            {
                return true;
            }
        }
    }

    /**
     * Removes the add to cart button
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function remove_add_to_cart()
    {
        global $product;

        if(!$product->is_type( 'variable' )) {
            return false;
        }

        if(!$this->get_option('removeDefaultVariations')) {
            return false;
        }

        remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
        remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
    }

    /**
     * Hard Remove via CSS !important
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    public function hardRemove()
    {
        echo '<style type="text/css">';

        echo 'table.variations .variations_button {
                    display:none !important;
                }';

        $excludeProductCategories = $this->get_option('excludeProductCategories');
        $excludeProducts = $this->get_option('excludeProducts');

        if(!empty($excludeProducts) || !empty($excludeProductCategories)) {

            if(!empty($excludeProductCategories)) 
            {
                $excludeProductCategoriesRevert = $this->get_option('excludeProductCategoriesRevert');
                if($excludeProductCategoriesRevert) {
                    $style = 'none';
                    echo 'table.variations .variations_button {
                                display:block !important;
                            }';
                } else {
                    $style = 'block';
                }

                foreach ($excludeProductCategories as $excludeProductCategory) {
                    $cat = get_term( $excludeProductCategory, 'product_cat' );
                    echo '.term-' . $excludeProductCategory . ' table.variations .variations_button, .term-' . $excludeProductCategory . ' table.variations .variations_button {
                        display: ' . $style . ' !important;
                    }';
                }
            }
            
            if(!empty($excludeProducts)) 
            {
                $excludeProductsRevert = $this->get_option('excludeProductsRevert');
                if($excludeProductsRevert) {
                    $style = 'none';
                    echo 'table.variations .variations_button {
                                display:block !important;
                            }';
                } else {
                    $style = 'block';
                }
                foreach ($excludeProducts as $excludeProduct) {
                    echo '.post-' . $excludeProduct . ' .table.variations .variations_button, .post-' . $excludeProduct . ' .table.variations .variations_button {
                        display: ' . $style . ' !important;
                    }';
                }
            }
        }

        echo '</style>';
    }

    /**
     * Return the current user role
     * @author Daniel Barenkamp
     * @version 1.0.0
     * @since   1.0.0
     * @link    https://plugins.db-dzine.com
     * @return  [type]                       [description]
     */
    private function get_user_role()
    {
        global $current_user;

        $user_roles = $current_user->roles;
        $user_role = array_shift($user_roles);

        return $user_role;
    }

    public function add_to_cart()
    {
        ob_start();

        $cart_item_data = array();
        // $cart_item_data = (array) apply_filters( 'woocommerce_add_cart_item_data', $cart_item_data, $product_id, $variation_id );

        
        $multipleVariations = isset( $_POST['variations'] ) ? $_POST['variations'] : '';
        if(!empty($multipleVariations) && is_array($multipleVariations)) {
            foreach ($multipleVariations as $multipleVariation) {

                $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $multipleVariation['product_id'] ) );
                $quantity          = empty( $multipleVariation['quantity'] ) ? 1 : wc_stock_amount( $multipleVariation['quantity'] );
                $variation_id      = isset( $multipleVariation['variation_id'] ) ? $multipleVariation['variation_id'] : '';
               
                $attributes   = explode(',', $multipleVariation['variation']);
                $variation    = array();
                if(!empty($attributes)) {
                    foreach($attributes as $values){

                        $values = explode('=', $values);
                        if($this->get_option('oldThemeSupport')) {
                            $variation[$values[0]] = $values[1];
                        } else {
                            $variation['attribute_' . $values[0]] = $values[1];
                        }                           
                    }
                }

                $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variation, $cart_item_data );

                if ( $passed_validation) {
                    $added = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
                }
            }
        } else {

            $product_id        = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_POST['product_id'] ) );
            $quantity          = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );

            $variation_id      = isset( $_POST['variation_id'] ) ? $_POST['variation_id'] : '';
           
            $attributes   = explode(',', $_POST['variation']);
            $variation    = array();
            if(!empty($attributes)) {
                foreach($attributes as $values){
                    $values = explode('=', $values);
                    if($this->get_option('oldThemeSupport')) {
                        $variation[ urldecode($values[0]) ] =  urldecode($values[1]) ;
                    } else {
                        $variation['attribute_' . urldecode( $values[0] )] = urldecode( $values[1] );
                    }   
                }
            }

            $passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variation, $cart_item_data );

            if ( $passed_validation) {
                $added = WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variation );
            }
        }
        

        do_action( 'woocommerce_ajax_added_to_cart', $product_id );

        if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
            wc_add_to_cart_message( $product_id );
        }

        // Return fragments
        WC_AJAX::get_refreshed_fragments();

        die();
    }
}