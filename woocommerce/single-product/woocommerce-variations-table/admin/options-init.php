<?php

    /**
     * For full documentation, please visit: http://docs.reduxframework.com/
     * For a more extensive sample-config file, you may look at:
     * https://github.com/reduxframework/redux-framework/blob/master/sample/sample-config.php
     */

    if ( ! class_exists( 'weLaunch' ) && ! class_exists( 'Redux' ) ) {
        return;
    }

    if( class_exists( 'weLaunch' ) ) {
        $framework = new weLaunch();
    } else {
        $framework = new Redux();
    }

    // This is your option name where all the Redux data is stored.
    $opt_name = "woocommerce_variations_table_options";

    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */
    // Get Custom Meta Keys for product

    $args = array(
        'opt_name' => 'woocommerce_variations_table_options',
        'use_cdn' => TRUE,
        'dev_mode' => FALSE,
        'display_name' => 'WooCommerce Variations Table',
        'display_version' => '1.3.9',
        'page_title' => 'WooCommerce Variations Table',
        'update_notice' => TRUE,
        'intro_text' => '',
        'footer_text' => '&copy; '.date('Y').' weLaunch',
        'admin_bar' => TRUE,
        'menu_type' => 'submenu',
        'menu_title' => 'Variations Table',
        'allow_sub_menu' => TRUE,
        'page_parent' => 'woocommerce',
        'page_parent_post_type' => 'your_post_type',
        'customizer' => FALSE,
        'default_mark' => '*',
        'hints' => array(
            'icon_position' => 'right',
            'icon_color' => 'lightgray',
            'icon_size' => 'normal',
            'tip_style' => array(
                'color' => 'light',
            ),
            'tip_position' => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect' => array(
                'show' => array(
                    'duration' => '500',
                    'event' => 'mouseover',
                ),
                'hide' => array(
                    'duration' => '500',
                    'event' => 'mouseleave unfocus',
                ),
            ),
        ),
        'output' => TRUE,
        'output_tag' => TRUE,
        'settings_api' => TRUE,
        'cdn_check_time' => '1440',
        'compiler' => TRUE,
        'page_permissions' => 'manage_options',
        'save_defaults' => TRUE,
        'show_import_export' => TRUE,
        'database' => 'options',
        'transient_time' => '3600',
        'network_sites' => TRUE,
    );

    global $weLaunchLicenses;
    if( (isset($weLaunchLicenses['woocommerce-variations-table']) && !empty($weLaunchLicenses['woocommerce-variations-table'])) || (isset($weLaunchLicenses['woocommerce-plugin-bundle']) && !empty($weLaunchLicenses['woocommerce-plugin-bundle'])) ) {
        $args['display_name'] = '<span class="dashicons dashicons-yes-alt" style="color: #9CCC65 !important;"></span> ' . $args['display_name'];
    } else {
        $args['display_name'] = '<span class="dashicons dashicons-dismiss" style="color: #EF5350 !important;"></span> ' . $args['display_name'];
    }

    $tableData = array(
        'enabled' => array(
            'im' => __('Image', 'woocommerce-variations-table'),
            'sk' => __('SKU', 'woocommerce-variations-table'),
            'pr' => __('Price', 'woocommerce-variations-table'),
            'st' => __('Stock', 'woocommerce-variations-table'),
            'at' => __('Attributes', 'woocommerce-variations-table'),
            'qt' => __('Quantity', 'woocommerce-variations-table'),
            'ca' => __('Add to Cart', 'woocommerce-variations-table'),
        ),
        'disabled' => array(
            'pt' => __('Name', 'woocommerce-variations-table'),
            'mc' => __('Multiple CB Add Cart', 'woocommerce-variations-table'),
            'mq' => __('Multiple QT Add Cart', 'woocommerce-variations-table'),
            'de' => __('Description', 'woocommerce-variations-table'),
            'di' => __('Dimensions', 'woocommerce-variations-table'),
            'we' => __('Weight', 'woocommerce-variations-table'),
            'pd' => __('Export PDF *', 'woocommerce-variations-table'),
            'eq' => __('Enquiry **', 'woocommerce-variations-table'),
            'wi' => __('Wishlist ***', 'woocommerce-variations-table'),
        )
    );

    // Get Custom Meta Keys for product
    $transient_name = 'woocommerce_variations_table_meta_keys';
    $woocommerce_variations_table_meta_keys = get_transient( $transient_name );

    $currentOptions = get_option($opt_name);
    if( isset($currentOptions['variationsTableMetaData']) && $currentOptions['variationsTableMetaData'] == "1" ) {
        
        if (false === $woocommerce_variations_table_meta_keys ) { 

            // Get Custom Meta Keys for post
            global $wpdb;
            $sql = "SELECT DISTINCT meta_key
                            FROM " . $wpdb->postmeta . "
                            INNER JOIN  " . $wpdb->posts . " 
                            ON post_id = ID
                            WHERE post_type = 'product' OR post_type ='product_variation'
                            ORDER BY meta_key ASC";

            $meta_keys = $wpdb->get_results( $sql, 'ARRAY_A' );
            $meta_keys_to_exclude = array('_crosssell_ids', '_children', '_default_attributes', '_height', '_length', '_weight', '_width');

            $meta_keys_rearranged = array();
            foreach ($meta_keys as $key => $meta_key) {
                $meta_key = preg_replace('/[^\w-]/', '', $meta_key['meta_key']);

                if(in_array($meta_key, $meta_keys_to_exclude) || (substr( $meta_key, 0, 7 ) === "_oembed")) {
                    continue;
                }

                $meta_keys_rearranged[] = $meta_key;
                $tableData['disabled']['meta_' . $meta_key] = $meta_key;
            }

            set_transient( $transient_name, $meta_keys_rearranged, WEEK_IN_SECONDS);
        } else {

            foreach ($woocommerce_variations_table_meta_keys as $key => $meta_key) {
                $tableData['disabled']['meta_' . $meta_key] = $meta_key;
            }
        }
    }

    $framework::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */

    /*
     * ---> START HELP TABS
     */

    $tabs = array(
        array(
            'id'      => 'help-tab',
            'title'   => __( 'Information', 'woocommerce-variations-table' ),
            'content' => __( '<p>Need support? Please use the comment function on codecanyon.</p>', 'woocommerce-variations-table' )
        ),
    );
    $framework::setHelpTab( $opt_name, $tabs );

    /*
     * <--- END HELP TABS
     */


    /*
     *
     * ---> START SECTIONS
     *
     */

    $framework::setSection( $opt_name, array(
        'title'  => __( 'Variations Table', 'woocommerce-variations-table' ),
        'id'     => 'general',
        'desc'   => __( 'Need support? Please use the comment function on codecanyon.', 'woocommerce-variations-table' ),
        'icon'   => 'el el-home',
    ) );

    $framework::setSection( $opt_name, array(
        'title'      => __( 'General', 'woocommerce-variations-table' ),
        'desc'       => __( 'To get auto updates please <a href="' . admin_url('tools.php?page=welaunch-framework') . '">register your License here</a>.', 'woocommerce-variations-table' ),
        'id'         => 'general-settings',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'enable',
                'type'     => 'switch',
                'title'    => __( 'Enable', 'woocommerce-variations-table' ),
                'subtitle' => __( 'Enable variations table to use the options below', 'woocommerce-variations-table' ),
            ),
            array(
                'id'       => 'removeDefaultVariations',
                'type'     => 'checkbox',
                'title'    => __( 'Remove default variations', 'woocommerce-variations-table' ),
                'subtitle' => __( 'Removes the default variation select fields and add to cart.', 'woocommerce-variations-table' ),
                'default'  => 1
            ),
            array(
                'id'       => 'singleVariationsSupport',
                'type'     => 'checkbox',
                'title'    => __( 'Single Variations Support', 'woocommerce-variations-table' ),
                'subtitle' => __( 'Support our <a href="https://www.welaunch.io/en/product/woocommerce-single-variations/" target="_blank">WooCommerce Single Variations</a> plugin. Will ensure single variations title display.', 'woocommerce-variations-table' ),
                'default'  => 1
            ),

        )
    ));

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Table Settings', 'woocommerce-variations-table' ),
        'desc'       => __( 'Configure the variations table.', 'woocommerce-variations-table' ),
        'id'         => 'variations',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'variationsTableTitle',
                'type'     => 'text',
                'title'    => __('Table Title', 'woocommerce-variations-table'),
                'subtitle' => __('Title before the Varaitons Table.', 'woocommerce-variations-table'),
                'default'  => 'All Variations',
            ),
            array(
                'id'       => 'variationsTablePosition',
                'type'     => 'select',
                'title'    => __('Table Position', 'woocommerce-variations-table'),
                'subtitle' => __('Specify the positon of the Variations Table.', 'woocommerce-variations-table'),
                'default'  => 'woocommerce_after_single_product_summary',
                'options'  => array( 
                    'woocommerce_before_single_product' => __('Before Single Product', 'woocommerce-variations-table'),
                    'woocommerce_before_single_product_summary' => __('Before Single Product Summary', 'woocommerce-variations-table'),
                    'woocommerce_before_add_to_cart_form' => __('Before Add to Cart Form', 'woocommerce-variations-table'),
                    'woocommerce_after_add_to_cart_form' => __('After Add to Cart Form', 'woocommerce-variations-table'),
                    'woocommerce_single_product_summary' => __('In Single Product Summary', 'woocommerce-variations-table'),
                    'woocommerce_product_meta_start' => __('Before Meta Information', 'woocommerce-variations-table'),
                    'woocommerce_product_meta_end' => __('After Meta Information', 'woocommerce-variations-table'),
                    'woocommerce_after_single_product_summary' => __('After Single Product Summary', 'woocommerce-variations-table'),
                    'woocommerce_after_single_product' => __('After Single Product', 'woocommerce-variations-table'),
                    'woocommerce_after_main_content' => __('After Main Product', 'woocommerce-variations-table'),
                ),
            ),
           array(
                'id'       => 'variationsTablePriority',
                'type'     => 'spinner',
                'title'    => __( 'Hook Priority', 'woocommerce-variations-table' ),
                'min'      => '1',
                'step'     => '1',
                'max'      => '999',
                'default'  => '5',
            ),
            array(
                'id'      => 'variationsTableData',
                'type'    => 'sorter',
                'title'   => 'Variation Data',
                'subtitle'    => 'Reorder, enable or disable data fields.<br><br>CB = Checkbox<br>QT = Quantity<br><br>* PDF Export requires our <a href="https://www.welaunch.io/en/product/woocommerce-print-products/" target="_blank">WooCommerce Print Products (PDF) plugin</a>.<br>** Enquiry requires our <a href="https://www.welaunch.io/en/product/woocommerce-catalog-mode/" target="_blank">Catalog Mode Plugin</a> <br>*** Wishlist requires our <a href="https://www.welaunch.io/en/product/woocommerce-wishlist/" target="_blank">Wishlist Plugin</a>.',
                'options' => $tableData,
            ),
            array(
                'id'       => 'variationsTableMetaData',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Meta Data Support ', 'woocommerce-variations-table' ),
                'subtitle'    => __( 'This will list all meta keys in the variation data above. After enabling you have to reload the settings page.', 'woocommerce-variations-table' ),
                'default'  => 0,
            ),
                array(
                    'id'       => 'variationsTableMetaDataTexts',
                    'type'     => 'multi_text',
                    'title'    => __( 'Meta Data Key Texts ', 'woocommerce-variations-table' ),
                    'subtitle'    => __( 'Set custom meta key names for the table header. First is the key, next the name and split the values with |. An example: _regular_price|Regular Price', 'woocommerce-variations-table' ),
                    'default'  => array(
                        '_regular_price|Regular Price'
                    ),
                    'required' => array('variationsTableMetaData','equals','1'),
                ),
            
            array(
                'id'       => 'lightboxEnable',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Lightbox', 'woocommerce-variations-table' ),
                'default'  => 1,
            ),
            array(
                'id'       => 'datatablesEnable',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Datatables', 'woocommerce-variations-table' ),
                'default'  => 1,
            ),
            array(
                'id'       => 'datatablesLanguage',
                'type'     => 'select',
                'title'    => __('Datatables Language', 'woocommerce-variations-table'),
                'subtitle' => __('Set a language for the datatable.', 'woocommerce-variations-table'),
                'default'  => 'English',
                'options'  => array( 
                    'Afrikaans' => __('Afrikaans', 'woocommerce-variations-table'),
                    'Albanian' => __('Albanian', 'woocommerce-variations-table'),
                    'Amharic' => __('Amharic', 'woocommerce-variations-table'),
                    'Arabic' => __('Arabic', 'woocommerce-variations-table'),
                    'Armenian' => __('Armenian', 'woocommerce-variations-table'),
                    'Azerbaijan' => __('Azerbaijan', 'woocommerce-variations-table'),
                    'Bangla' => __('Bangla', 'woocommerce-variations-table'),
                    'Basque' => __('Basque', 'woocommerce-variations-table'),
                    'Belarusian' => __('Belarusian', 'woocommerce-variations-table'),
                    'Bulgarian' => __('Bulgarian', 'woocommerce-variations-table'),
                    'Catalan' => __('Catalan', 'woocommerce-variations-table'),
                    'Chinese-traditional' => __('traditional', 'woocommerce-variations-table'),
                    'Chinese' => __('Chinese', 'woocommerce-variations-table'),
                    'Croatian' => __('Croatian', 'woocommerce-variations-table'),
                    'Czech' => __('Czech', 'woocommerce-variations-table'),
                    'Danish' => __('Danish', 'woocommerce-variations-table'),
                    'Dutch' => __('Dutch', 'woocommerce-variations-table'),
                    'English' => __('English', 'woocommerce-variations-table'),
                    'Estonian' => __('Estonian', 'woocommerce-variations-table'),
                    'Filipino' => __('Filipino', 'woocommerce-variations-table'),
                    'Finnish' => __('Finnish', 'woocommerce-variations-table'),
                    'French' => __('French', 'woocommerce-variations-table'),
                    'Galician' => __('Galician', 'woocommerce-variations-table'),
                    'Georgian' => __('Georgian', 'woocommerce-variations-table'),
                    'German' => __('German', 'woocommerce-variations-table'),
                    'Greek' => __('Greek', 'woocommerce-variations-table'),
                    'Gujarati' => __('Gujarati', 'woocommerce-variations-table'),
                    'Hebrew' => __('Hebrew', 'woocommerce-variations-table'),
                    'Hindi' => __('Hindi', 'woocommerce-variations-table'),
                    'Hungarian' => __('Hungarian', 'woocommerce-variations-table'),
                    'Icelandic' => __('Icelandic', 'woocommerce-variations-table'),
                    'Indonesian-Alternative' => __('Alternative', 'woocommerce-variations-table'),
                    'Indonesian' => __('Indonesian', 'woocommerce-variations-table'),
                    'Irish' => __('Irish', 'woocommerce-variations-table'),
                    'Italian' => __('Italian', 'woocommerce-variations-table'),
                    'Japanese' => __('Japanese', 'woocommerce-variations-table'),
                    'Kazakh' => __('Kazakh', 'woocommerce-variations-table'),
                    'Korean' => __('Korean', 'woocommerce-variations-table'),
                    'Kyrgyz' => __('Kyrgyz', 'woocommerce-variations-table'),
                    'Latvian' => __('Latvian', 'woocommerce-variations-table'),
                    'Lithuanian' => __('Lithuanian', 'woocommerce-variations-table'),
                    'Macedonian' => __('Macedonian', 'woocommerce-variations-table'),
                    'Malay' => __('Malay', 'woocommerce-variations-table'),
                    'Mongolian' => __('Mongolian', 'woocommerce-variations-table'),
                    'Nepali' => __('Nepali', 'woocommerce-variations-table'),
                    'Norwegian-Bokmal' => __('Bokmal', 'woocommerce-variations-table'),
                    'Norwegian-Nynorsk' => __('Nynorsk', 'woocommerce-variations-table'),
                    'Pashto' => __('Pashto', 'woocommerce-variations-table'),
                    'Persian' => __('Persian', 'woocommerce-variations-table'),
                    'Polish' => __('Polish', 'woocommerce-variations-table'),
                    'Portuguese-Brasil' => __('Brasil', 'woocommerce-variations-table'),
                    'Portuguese' => __('Portuguese', 'woocommerce-variations-table'),
                    'Romanian' => __('Romanian', 'woocommerce-variations-table'),
                    'Russian' => __('Russian', 'woocommerce-variations-table'),
                    'Serbian' => __('Serbian', 'woocommerce-variations-table'),
                    'Sinhala' => __('Sinhala', 'woocommerce-variations-table'),
                    'Slovak' => __('Slovak', 'woocommerce-variations-table'),
                    'Slovenian' => __('Slovenian', 'woocommerce-variations-table'),
                    'Spanish' => __('Spanish', 'woocommerce-variations-table'),
                    'Swahili' => __('Swahili', 'woocommerce-variations-table'),
                    'Swedish' => __('Swedish', 'woocommerce-variations-table'),
                    'Tamil' => __('Tamil', 'woocommerce-variations-table'),
                    'telugu' => __('telugu', 'woocommerce-variations-table'),
                    'Thai' => __('Thai', 'woocommerce-variations-table'),
                    'Turkish' => __('Turkish', 'woocommerce-variations-table'),
                    'Ukrainian' => __('Ukrainian', 'woocommerce-variations-table'),
                    'Urdu' => __('Urdu', 'woocommerce-variations-table'),
                    'Uzbek' => __('Uzbek', 'woocommerce-variations-table'),
                    'Vietnamese' => __('Vietnamese', 'woocommerce-variations-table'),
                    'Welsh' => __('Welsh', 'woocommerce-variations-table'),
                ),
            ),
            array(
                'id'       => 'datatablesPaging',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Paging', 'woocommerce-variations-table' ),
                'default'  => 0,
                'required' => array('datatablesEnable','equals','1'),
            ),
           array(
                'id'       => 'datatablesPageLength',
                'type'     => 'spinner',
                'title'    => __( 'Default Paging length', 'woocommerce-variations-table' ),
                'subtitle'    => __( 'Make sure you add this default value to the length menu option below.', 'woocommerce-variations-table' ),
                'min'      => '1',
                'step'     => '1',
                'max'      => '999',
                'default'  => '10',
                'required' => array('datatablesPaging','equals','1'),
            ),
            array(
                'id'       => 'datatablesPageLengthMenu',
                'type'     => 'text',
                'title'    => __( 'Page length menu options', 'woocommerce-variations-table' ),
                'subtitle'    => __( 'No spaced between comma and value allowed here.', 'woocommerce-variations-table' ),
                'default'  => '10,25,50,60,75,100',
                'required' => array('datatablesPaging','equals','1'),
            ),
            array(
                'id'       => 'datatablesOrdering',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Ordering', 'woocommerce-variations-table' ),
                'default'  => 1,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesOrderColumn',
                'type'     => 'text',
                'title'    => __( 'Default Order Column (starts with 0, not 1)', 'woocommerce-variations-table' ),
                'default'  => '1',
                'required' => array('datatablesOrdering','equals','1'),
            ),
            array(
                'id'       => 'datatablesOrderColumnType',
                'type'     => 'text',
                'title'    => __( 'Default Order Column Type (asc or desc)', 'woocommerce-variations-table' ),
                'default'  => 'asc',
                'required' => array('datatablesOrdering','equals','1'),
            ),
            array(
                'id'       => 'datatablesInfo',
                'type'     => 'checkbox',
                'title'    => __( 'Show Info', 'woocommerce-variations-table' ),
                'default'  => 0,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesFiltering',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Filtering', 'woocommerce-variations-table' ),
                'default'  => 1,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesColumnFilterWidgets',
                'type'     => 'checkbox',
                'title'    => __( 'DEPRECATED: Enable Filtering by Column Filter Widgets', 'woocommerce-variations-table' ),
                'default'  => 0,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesStateSave',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Filter & Search Cache (not recommended)', 'woocommerce-variations-table' ),
                'default'  => 0,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesSearching',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Searching', 'woocommerce-variations-table' ),
                'default'  => 1,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesFixedHeader',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Fixed Header', 'woocommerce-variations-table' ),
                'default'  => 1,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesFixedHeaderOffset',
                'type'     => 'spinner',
                'title'    => __( 'Fixed Header Offset', 'woocommerce-variations-table' ),
                'subtitle' => __( 'Leave empty if you have no sticky header e.g. Otherwise specify the height in numbers (without px).', 'woocommerce-variations-table' ),
                'default'  => '0',
                'min'      => '0',
                'step'     => '10',
                'max'      => '500',
                'required' => array('datatablesFixedHeader','equals','1'),
            ),
            array(
                'id'       => 'datatablesFixedFooter',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Fixed Footer', 'woocommerce-variations-table' ),
                'default'  => 1,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesFixedFooterOffset',
                'type'     => 'spinner',
                'title'    => __( 'Fixed Footer Offset', 'woocommerce-variations-table' ),
                'subtitle' => __( 'Leave empty if you have no sticky footer e.g. Otherwise specify the height in numbers (without px).', 'woocommerce-variations-table' ),
                'default'  => '0',
                'min'      => '0',
                'step'     => '10',
                'max'      => '500',
                'required' => array('datatablesFixedFooter','equals','1'),
            ),
            array(
                'id'       => 'datatablesColumnSearch',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Column Search', 'woocommerce-variations-table' ),
                'default'  => 1,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesScrollCollapse',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Scrolling', 'woocommerce-variations-table' ),
                'default'  => 0,
                 'required' => array('datatablesEnable','equals','1'),
            ),
           array(
                'id'       => 'datatablesScrollY',
                'type'     => 'spinner',
                'title'    => __( 'Scroll Max Height', 'woocommerce-variations-table' ),
                'min'      => '1',
                'step'     => '1',
                'max'      => '99999',
                'default'  => '250',
                'required' => array('datatablesScrollCollapse','equals','1'),
            ),
            array(
                'id'       => 'datatablesResponsive',
                'type'     => 'checkbox',
                'title'    => __( 'Enable Responsive', 'woocommerce-variations-table' ),
                'default'  => 1,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesPrint',
                'type'     => 'checkbox',
                'title'    => __( 'Show Print Export-Button', 'woocommerce-variations-table' ),
                'default'  => 1,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesCopyHtml5',
                'type'     => 'checkbox',
                'title'    => __( 'Show Copy Export-Button', 'woocommerce-variations-table' ),
                'default'  => 1,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesExcelHtml5',
                'type'     => 'checkbox',
                'title'    => __( 'Show Excel Export-Button', 'woocommerce-variations-table' ),
                'default'  => 1,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesCsvHtml5',
                'type'     => 'checkbox',
                'title'    => __( 'Show CSV Export-Button', 'woocommerce-variations-table' ),
                'default'  => 1,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesPdfHtml5',
                'type'     => 'checkbox',
                'title'    => __( 'Show PDF Export-Button', 'woocommerce-variations-table' ),
                'default'  => 1,
                'required' => array('datatablesEnable','equals','1'),
            ),
            array(
                'id'       => 'datatablesSDom',
                'type'     => 'text',
                'title'    => __( 'sDOM (leave as it is) Default is: W<"clear">Blfrtip', 'woocommerce-variations-table' ),
                'default'  => 'W<"clear">Blfrtip',
                'required' => array('datatablesEnable','equals','1'),
            ),
        )
    ));

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Exclusions', 'woocommerce-variations-table' ),
        'desc'       => __( 'With the below settings you can exclude products / categories so that the price and add to cart will be shown.', 'woocommerce-variations-table' ),
        'id'         => 'exclusions',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'     =>'excludeProductCategories',
                'type' => 'select',
                'data' => 'categories',
                'args' => array('taxonomy' => array('product_cat')),
                'multi' => true,
                'ajax'  => true,
                'title' => __('Exclude Product Categories', 'woocommerce-variations-table'), 
                'subtitle' => __('Which product categories should be excluded by the variations table.', 'woocommerce-variations-table'),
            ),
            array(
                'id'       => 'excludeProductCategoriesRevert',
                'type'     => 'checkbox',
                'title'    => __( 'Revert Categories Exclusion', 'woocommerce-variations-table' ),
                'subtitle' => __( 'Instead of exclusion it will include.', 'woocommerce-variations-table' ),
            ),
            array(
                'id'     =>'excludeProducts',
                'type' => 'select',
                // 'options' => $woocommerce_variations_table_options_products,
                'data' => 'posts',
                'args' => array('post_type' => array('product'), 'posts_per_page' => -1),
                'multi' => true,
                'ajax'  => true,
                'title' => __('Exclude Products', 'woocommerce-variations-table'), 
                'subtitle' => __('Which products should be excluded by the variations table.', 'woocommerce-variations-table'),
            ),
            array(
                'id'       => 'excludeProductsRevert',
                'type'     => 'checkbox',
                'title'    => __( 'Revert Products Exclusion', 'woocommerce-variations-table' ),
                'subtitle' => __( 'Instead of exclusion it will include.', 'woocommerce-variations-table' ),
            )
         )
    ));   

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Limitations', 'woocommerce-variations-table' ),
        'desc'       => __( 'Apply Variations Table for specific users / roles.', 'woocommerce-variations-table' ),
        'id'         => 'limitations',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'applyForUserGroup',
                'type'     => 'select',
                'title'    => __('Apply for users', 'woocommerce-variations-table'), 
                'subtitle' => __('Which user group should be affected by the variations table.', 'woocommerce-variations-table'),
                'options'  => array(
                    '1' => __('All', 'woocommerce-variations-table' ),
                    '2' => __('Only NOT logged in', 'woocommerce-variations-table' ),
                    '3' => __('Only Logged in', 'woocommerce-variations-table' ),
                ),
                'default'  => '1',
            ),
            array(
                'id'     => 'applyForExcludeUserRoles',
                'type'   => 'select',
                'data'   => 'roles',
                'title'  => __('Exclude User Roles', 'woocommerce-variations-table'),
                'subtitle' => __('Select user roles, where the plugin should NOT apply.', 'woocommerce-variations-table'),
                'multi'    => true,
                'default'  => '',
            ),
        )
    ));

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Advanced settings', 'woocommerce-variations-table' ),
        'desc'       => __( 'Custom stylesheet / javascript.', 'woocommerce-variations-table' ),
        'id'         => 'advanced',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'wishlistYith',
                'type'     => 'checkbox',
                'title'    => __( 'Use Yith Wishlist Instead', 'woocommerce-variations-table' ),
                'subtitle'    => __( 'Instead of using our own <a href="https://www.welaunch.io/en/product/woocommerce-wishlist/" target="_blank">WooCommerce Wishlist plugin</a>, use Yith plugin.', 'woocommerce-variations-table' ),
                'default'  => 0,
            ),
            array(
                'id'       => 'barcodeYith',
                'type'     => 'checkbox',
                'title'    => __( 'Show Yith Barcode below image', 'woocommerce-variations-table' ),
                'subtitle'    => __( 'You need the <a href="https://yithemes.com/themes/plugins/yith-woocommerce-barcodes-and-qr-codes/" target="_blank">Yith Barcode Plugin</a>', 'woocommerce-variations-table' ),
                'default'  => 0,
            ),
            array(
                'id'       => 'customCSS',
                'type'     => 'ace_editor',
                'mode'     => 'css',
                'title'    => __( 'Custom CSS', 'woocommerce-variations-table' ),
                'subtitle' => __( 'Add some stylesheet if you want.', 'woocommerce-variations-table' ),
            ),
            array(
                'id'       => 'customJS',
                'type'     => 'ace_editor',
                'mode'     => 'javascript',
                'title'    => __( 'Custom JS', 'woocommerce-variations-table' ),
                'subtitle' => __( 'Add some javascript if you want.', 'woocommerce-variations-table' ),
            ),
        )
    ));

    $framework::setSection( $opt_name, array(
        'title'      => __( 'Debug', 'woocommerce-variations-table' ),
        'desc'       => __( 'Debug for special themes.', 'woocommerce-variations-table' ),
        'id'         => 'debug',
        'subsection' => true,
        'fields'     => array(
            array(
                'id'       => 'oldThemeSupport',
                'type'     => 'checkbox',
                'title'    => __( 'Old Theme support', 'woocommerce-variations-table' ),
                'subtitle' => __( 'Enable this when attributes do not show in cart.', 'woocommerce-variations-table' ),
                'default'  => 0,
            ),
            array(
                'id'       => 'hardRemove',
                'type'     => 'checkbox',
                'title'    => __( 'Hard Remove Default Variations', 'woocommerce-variations-table' ),
                'subtitle' => __( 'This will add CSS to hide the default variation select fields.', 'woocommerce-variations-table' ),
            ),
        )
    ));

    /*
     * <--- END SECTIONS
     */
