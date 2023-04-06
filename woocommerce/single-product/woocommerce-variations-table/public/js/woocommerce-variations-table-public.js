if(jQuery.fn.dataTableExt) {
	jQuery.extend( jQuery.fn.dataTableExt.oSort, {
	    "formatted-num-pre": function ( a ) {
	        a = (a === "-" || a === "") ? 0 : a.replace( /[^\d\-\.]/g, "" );
	        return parseFloat( a );
	    },
	 
	    "formatted-num-asc": function ( a, b ) {
	        return a - b;
	    },
	 
	    "formatted-num-desc": function ( a, b ) {
	        return b - a;
	    }
	} );
}

(function( $ ) {
	'use strict';

	$(document).ready(function() {

		if(woocommerce_variations_table_options.lightbox == "1") {
			new LuminousGallery(document.querySelectorAll(".woocommerce-variations-table-lightbox"));
        }


        $(document).on('change', '.variations-table-value-qt input, .variations-table-multiple-quantity-add-to-cart-products input', function(e) {

        	var $this = $(this);
        	var maxExists = parseInt( $this.attr('max') );
        	if(maxExists > 0) {
        		var currentVal = $this.val();
        		if(currentVal >= maxExists) {
        			$this.val( maxExists )
        		}
        	}

        });

		var variationsTables = $('.woocommerce-variations-table.datatables');
		if(variationsTables.length > 0) {

			$.each(variationsTables, function(i, index) {

				var variationsTable = $(this);
				var options = variationsTable.data('options');

				if(options.orderColumn && options.orderColumnType) {
					options.order = [[ options.orderColumn, options.orderColumnType ]];
				}

				if(typeof options.buttons == "undefined" || options.buttons == "") {
					options.buttons = [];
				} else {
					$.each(options.buttons, function(i, index) {
						options.buttons[i].exportOptions = {
		                    format: {
		                        header: function ( data, row, column, node ) {
		                            var newdata = data;
		                            // newdata = newdata.replace(/<[^>]*>/gi, '');
		                              var dummyDiv =  document.createElement( "div" );
									  dummyDiv.innerHTML = newdata;
									  // return Array.from( dummyDiv.childNodes ).filter( s => s.nodeType == 3 ).map( s => s.nodeValue ).join("");
									  // IE 11 Fix
									  return Array.from( dummyDiv.childNodes ).filter( function(s) { return s.nodeType === 3; } ).map( function(s) { return s.nodeValue; } ).join("");
		                            return newdata;
		                        }
		                    }
		                }
		                if(options.buttons[i].extend == "print") {
		                	options.buttons[i].exportOptions.stripHtml = false;
		                }
					});
				}

				// Filtering
				if(options.filtering == "1" && options.columnFilterWidgets !== "1") {
					options.searching = "1";
					options.initComplete = function () {
			            this.api().columns().every( function () {
			                var column = this;
			                var header = $(column.header());
			                if(header[0].id == "variations-table-header-im" 
			                	|| header[0].id == "variations-table-header-ca" 
			                	|| header[0].id == "variations-table-header-mc"
			                	|| header[0].id == "variations-table-header-mq"
			                	|| header[0].id == "variations-table-header-st"
			                	|| header[0].id == "variations-table-header-qt"
			                	|| header[0].id == "variations-table-header-eq"
			                	|| header[0].id == "variations-table-header-pd"
			                	|| header[0].id == "variations-table-header-wi"
		                	) {
			                	return;
			                }

			                var select = $('<select><option value="">' + woocommerce_variations_table_options.show_all + '</option></select>')
			                    .appendTo( $(column.header()) )
			                    .on( 'change', function () {
			                        var val = $.fn.dataTable.util.escapeRegex(
			                            $(this).val()
			                        );

			                        column
			                            .search( val ? '^'+val+'$' : '', true, false )
			                            .draw();
			                    } );

								column.data().unique().sort().each( function ( d, j ) {

									var d = d.replace(/<(?:.|\n)*?>/gm, '');
								    if(column.search() === '^'+d+'$'){
								        select.append( '<option value="' + d + '" selected="selected">'+d+'</option>' )
								    } else {
								        select.append( '<option value="' + d + '">' + d + '</option>' )
								    }
								} );
			            } );

			            var attributeHeader = $('.variations-table-header-at');
			            attributeHeader.each(function(i, index) {
			            	var $this = $(this);
			            	if($this.data('preselect')) {
			            		var preselect = $this.data('preselect');
			            		$this.find('select').val(preselect).trigger('change');
			            	}
			            });
			        };
		        }

		        if(options.columnFilterWidgets == "1") {
		        	var theads = variationsTable.find('th');
		        	var excludeFromFiltering = [];
					
		        	$.each(theads, function(i, index) {
		        		var theadID = $(this).prop('id');
		        		
		        		if(theadID == "variations-table-header-im" 
			                	|| theadID == "variations-table-header-ca" 
			                	|| theadID == "variations-table-header-mc"
			                	|| theadID == "variations-table-header-mq"
			                	// || theadID == "variations-table-header-st"
			                	|| theadID == "variations-table-header-qt"
			                	|| theadID == "variations-table-header-eq"
			                	|| theadID == "variations-table-header-pd"
			                	|| theadID == "variations-table-header-wi"

	                	) { 
		        			excludeFromFiltering.push(i);
		        		}
	        		});

	        		options.oColumnFilterWidgets = {
						aiExclude: excludeFromFiltering
					}
		        }

		        // Column Search
		        if(options.columnSearch == "1") {
				    variationsTable.find('thead tr').clone(true).appendTo( variationsTable.find( 'thead') );
				    variationsTable.find('thead tr:eq(1) th').each( function (i) {

				    	var $this = $(this);
				    	var theadID = $this.prop('id');
						if(theadID == "variations-table-header-im" 
			                	|| theadID == "variations-table-header-ca" 
			                	|| theadID == "variations-table-header-mc"
			                	|| theadID == "variations-table-header-mq"
			                	// || theadID == "variations-table-header-st"
			                	|| theadID == "variations-table-header-qt"
			                	|| theadID == "variations-table-header-eq"
			                	|| theadID == "variations-table-header-pd"
			                	|| theadID == "variations-table-header-wi"
	                	) { 
							$this.html('');
							return;
	            		}

				        var title = $this.text();

				        $this.html( '<input type="text" placeholder="' + woocommerce_variations_table_options.searchText + ' ' + title + '" />' );
				 
				        $( 'input', this ).on( 'keyup change', function () {
				            if ( table.column(i).search() !== this.value ) {
				                table
				                    .column(i)
				                    .search( this.value )
				                    .draw();
				            }
				        } );
				    } );
	        	}

	        	if(options.fixedHeaderA == "1" || options.fixedFooterA == "1") {
	        		options.fixedHeader = {
	        			header: false,
						headerOffset: parseInt(options.fixedHeaderOffset),
						footer: false,
						footerOffset: parseInt(options.fixedFooterOffset),
	        		};

	        		if(options.fixedHeaderA == "1") {
	        			options.fixedHeader.header = true;
	        		}

	        		if(options.fixedFooterA == "1") {
	        			options.fixedHeader.footer = true;
	        		}
	    		}

	    		options.columnDefs = [
					{ "type": "formatted-num", "targets": woocommerce_variations_table_options.numericSortingColumns }
				];

				var table = variationsTable.DataTable(options);
			});
		}

		var variationsTableAddToCart = $('.woocommerce-variations-table-add-to-cart button');
		if ( variationsTableAddToCart.length > 0 ) {
			
			$(document).on('click', '.woocommerce-variations-table-add-to-cart button', function(e) {
				e.preventDefault();

				var $thisbutton = $(this);
				$thisbutton.removeClass( 'added' );
				$thisbutton.addClass( 'loading' );
			
				var product_id = $thisbutton.parent().find('input[name="product_id"]').val();
				var variation_id = $thisbutton.parent().find('input[name="variation_id"]').val();
				var variation = $thisbutton.parent().find('input[name="variation"]').val();
				
				var quantity = 1;
				var quantityField = $('#variations-table-row-' + variation_id + ' input[name="quantity"]');
				if(quantityField.length > 0) {
						quantity = quantityField.val();
				}

				var data = {
					'product_id' : product_id,
					'variation_id' : variation_id,
					'quantity' : quantity,
					'variation' : variation,
					'action' : 'woocommerce_variations_table_add_to_cart',
				};

				$.post( woocommerce_params.ajax_url, data, function( response ) {
					
					if ( ! response ) {
						return;
					}
					
					$thisbutton.text(woocommerce_variations_table_options.addedToCart);

					$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );

					if(woocommerce_variations_table_options.redirectToCart == "yes") {
						window.location.replace(woocommerce_variations_table_options.cartURL);
					}
				});

			});
		}


		var variationsTableMultipleAddToCartButtons = $('.variations-table-multiple-add-to-cart-btn');
		var variationsTableMultipleAddToCartProducts = $('.variations-table-multiple-add-to-cart-products');

		if ( variationsTableMultipleAddToCartButtons.length > 0 && variationsTableMultipleAddToCartProducts.length > 0) {

			variationsTableMultipleAddToCartButtons.each(function(i, index) {

				var variationsTableMultipleAddToCart = $(this);
		
				// Enable / Disable Button when checkbox checked
				$('.woocommerce-variations-table').on('change', variationsTableMultipleAddToCartProducts, function(e) {
					var somethingChecked = false;
					$('.variations-table-multiple-add-to-cart-products').each(function(i, index) {
						if($(this).is(':checked')) {
							somethingChecked = true;
						}
					});
					if(somethingChecked) {
						variationsTableMultipleAddToCart.prop("disabled", false); 
					} else {
						variationsTableMultipleAddToCart.prop("disabled", true); 
					}
				});

				// Multiple Variations add to Cart
				variationsTableMultipleAddToCart.on('click', function(e) {
					e.preventDefault();

					var $thisbutton = $(this);
					$thisbutton.removeClass( 'added' );
					$thisbutton.addClass( 'loading' );

					var variation_ids = [];
					var data = {
						'action' : 'woocommerce_variations_table_add_to_cart',
						'variations' : [],
					};

					$('.variations-table-multiple-add-to-cart-products').each(function(i, index) {
						var checkbox = $(this);
						if(checkbox.is(':checked')) {

							var product_id = $(this).data('product_id');
							var variation_id = $(this).data('variation_id');
							var variation = $(this).data('variation');

							var quantity = 1;
							var quantityField = $('#variations-table-row-' + variation_id + ' input[name="quantity"]');
							if(quantityField.length > 0) {
								quantity = quantityField.val();
							}

							var variation = {
								'product_id' : product_id,
								'variation_id' : variation_id,
								'quantity' : quantity,
								'variation' : variation,
							};
							data.variations.push(variation);

							checkbox.prop('checked', false);
						}
					});

					$.post( woocommerce_params.ajax_url, data, function( response ) {
							// console.log(response);
						if ( ! response ) {
							return;
						}

						$thisbutton.removeClass( 'loading' );
						$thisbutton.text(woocommerce_variations_table_options.addedToCart);

						// alert(woocommerce_variations_table_options.addedToCart);

						setTimeout(function(){ 
							$thisbutton.text(woocommerce_variations_table_options.addAllToCart);
						}, 3000, $thisbutton);

						// Trigger event so themes can refresh other areas.
						$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );

						if(woocommerce_variations_table_options.redirectToCart == "yes") {
							window.location.replace(woocommerce_variations_table_options.cartURL);
						}
					});

				});
			})
		}

		var variationsTableMultipleAddToCart = $('#variations-table-multiple-add-to-cart-btn');
		var variationsTableMultipleQuantityAddToCartProducts = $('.variations-table-multiple-quantity-add-to-cart-products');
		if ( variationsTableMultipleAddToCartButtons.length > 0 && variationsTableMultipleAddToCartProducts.length < 1 && variationsTableMultipleQuantityAddToCartProducts.length > 0) {

			// Enable / Disable Button when checkbox checked
			$('.woocommerce-variations-table').on('keyup, change', 'input[name="quantity"]', function(e) {
				
				var quantity = $(this).val();
				var somethingChecked = false;
				$('.woocommerce-variations-table input[name="quantity"]').each(function(i, index) {
					if($(this).val() > 0) {
						somethingChecked = true;
						return;
					}
				});
				if(somethingChecked) {
					variationsTableMultipleAddToCart.prop("disabled", false); 
				} else {
					variationsTableMultipleAddToCart.prop("disabled", true); 
				}
			});

			// Multiple Variations add to Cart
			variationsTableMultipleAddToCart.on('click', function(e) {
				e.preventDefault();

				var $thisbutton = $(this);
				$thisbutton.removeClass( 'added' );
				$thisbutton.addClass( 'loading' );

				var variation_ids = [];
				var data = {
					'action' : 'woocommerce_variations_table_add_to_cart',
					'variations' : [],
				};

				$('.variations-table-multiple-quantity-add-to-cart-products').each(function(i, index) {
					var divField = $(this);
					var quantityField = divField.find('input[name="quantity"]');
					var quantity = quantityField.val();
					if(quantity > 0) {

						var product_id = divField.data('product_id');
						var variation_id = divField.data('variation_id');
						var variation = divField.data('variation');

						var variation = {
							'product_id' : product_id,
							'variation_id' : variation_id,
							'quantity' : quantity,
							'variation' : variation,
						};
						data.variations.push(variation);

						quantityField.val( quantityField.attr('min') );
					}
				});

				$.post( woocommerce_params.ajax_url, data, function( response ) {

					if ( ! response ) {
						return;
					}

					if (typeof response !== 'object') {
						var response = JSON.parse(response);
					}

					if(response.error != null) {

						if( response.error.length > 0) {
							alert(response.error);
							return;
						}
					}

					$thisbutton.removeClass( 'loading' );
					$thisbutton.text(woocommerce_variations_table_options.addedToCart);

					// alert(woocommerce_variations_table_options.addedToCart);

					setTimeout(function(){ 
						$thisbutton.text(woocommerce_variations_table_options.addAllToCart);
					}, 3000, $thisbutton);

					// Trigger event so themes can refresh other areas.
					$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisbutton ] );

					if(woocommerce_variations_table_options.redirectToCart == "yes") {
						window.location.replace(woocommerce_variations_table_options.cartURL);
					}
				});

			});
		}

	});

})( jQuery );