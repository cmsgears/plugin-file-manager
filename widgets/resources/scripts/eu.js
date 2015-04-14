// Public Calls -----------------------------------------------------------------------------

function populateDeviceGallery()
{
		jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: {action:'get_full_devices'},  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
        	var table_data		= "";
        	
        	for( var i=0; i<data.length; i++ ){ 
        		
        		var row		 = data[i];
        		
        		table_data	+= "<li device-id='"+row['did'] + "' device-name='" + row['dn'] + "' active='" + row['active'] +"'>";      
        		  		
        		if( null != row['dfi'] && row['dfi'].length > 10 && 
        			null != row['dbi'] && row['dbi'].length > 10 )
        		{
					table_data	+= "<img title='" + row['dn'] + "' src='" + row['dfi'] + "' width='225px' height='400px' />";
					table_data	+= "<img title='" + row['dn'] + "' src='" + row['dbi'] + "' width='225px' height='400px' />";
				}
				else
				{
					table_data	+= "<img title='" + row['dn'] + "' src='" + row['dfi'] + "' width='450px' height='400px' />";
				}
				
				table_data	+= "</li>";
        	}
        	
        	jQuery( "#device-gallery ul" ).html( table_data );
        	
        	initDeviceGallery();
        	
        },
        error: function( MLHttpRequest, textStatus, errorThrown )
        {  
            // alert(errorThrown);  
        }  
    }); 	
}

function populateTemplateGallery()
{
		jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: {action:'get_all_templates'},  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
        	var table_data		= "";
        	
        	for( var i=0; i<data.length; i++ ){ 
        		
        		var row		 = data[i];
        		
        		table_data	+= "<li template-id='"+row['id'] + "' template-name='"+row['name'] + "' shape1='" + row['s1'] + "' shape2='" + row['s2'] + "' shape3='" + row['s3'] + "' shape4='" + row['s4'] + "' shape5='" + row['s5'] + "'>";      
        		  		
        		if( null != row['url'] )
        		{
					table_data	+= "<img title='" + row['name'] + "' src='" + row['url'] + "' width='225px' height='400px' />";
				}
				
				table_data	+= "</li>";
        	}
        	
        	jQuery( "#template-gallery ul" ).html( table_data );
        	
        	initTemplateGallery();
        	
        },
        error: function( MLHttpRequest, textStatus, errorThrown )
        {  
            // alert(errorThrown);  
        }  
    }); 	
}

function getImageGallery( selected_category, mk )
{
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'get_image_gallery', selectedcategory: selected_category, deviceid: selectedDeviceId, marketplace: mk  },  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
				var options = "";
				
				var length = data.length;
				   
				for(var i = 0; i < length; i++)
				{
					//var ratings = "<div class='rateit' data-rateit-value='" + data[i]['design_ratings'] + "' data-rateit-ispreset='true' data-rateit-readonly='true'></div>";
					
					var ratings = "<div class='rateit'></div>";
					
					options += "<li><img did='" + data[i]['di'] + "' title='" + data[i]['title'] + "' src='" + data[i]['dt'] + "' ratings='" + data[i]['dr'] + "' />" + ratings + "</li>";
				}
				
				jQuery("#cgs-slider-list li").remove();
				jQuery("#cgs-slider-list").html( options );

				if(mk)
				{
					  jQuery("#cgs-slider-list").carouFredSel({
							auto    : {
							        items           : 5,
							        duration        : 7500,
							        easing          : "linear",
							        timeoutDuration : 0,
							        pauseOnHover    : "immediate"
							   }
						    }
					  );
					  
					  initSlider( true );
				}
				else
				{
					initSlider( false );
				}
			}
	});
}

function checkUsername( signUp )
{
	var email = document.getElementById('uemail').value;
	 
	if( null == email || email.length < 3 || !validateEmail(email) )
	{
		clearAndAddClass('#uemail', 'wrong');
		
		return;
	}
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'user_check_username', uemail: email },
        success: function( data, textStatus, XMLHttpRequest ) { 
        		
				if( data == 0 )			// username does not exist
				{
					if( signUp )
					{
						document.getElementById('unique').value = '1';
						clearAndAddClass('#uemail', 'ok');
					}
					else
					{
						document.getElementById('unique').value = '0';
						clearAndAddClass('#uemail', 'wrong');
					}
				}
				else if( data == 1 )	// username already exist
				{
					if( signUp )
					{
						document.getElementById('unique').value = '0';
						clearAndAddClass('#uemail', 'wrong');
						jQuery("#login-error").html("Please choose a user name that is not already in use.");
					}
					else
					{
						clearAndAddClass('#uemail', 'ok');
						document.getElementById('unique').value = '1';
					}
				}
			}
	});
}

function checkUsernameShipping()
{
	var email = document.getElementById('prf_primemail').value;
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'user_check_username', uemail: email },
        success: function( data, textStatus, XMLHttpRequest ) { 
        		
				if( data == 1 )	// username already exist
				{
        			jQuery( "#prf_primemail_error" ).css("display", "inline-block" );
					jQuery("#shipping_master_error").show();
					jQuery("#shipping_master_error_list").html( jQuery("#shipping_master_error_list").html() + "</br>" + ERROR_EMAIL_EXIST );
				}
			}
	});
}

function login()
{
	var email 		= document.getElementById('uemail').value;
	var password	= document.getElementById('password').value;
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'user_login', uemail: email, upassword: password },  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
				if( data['result'] == 0 )
				{
					// Clear and add error classes
					if( null != data['uemail'] && data['uemail'] == 1 ) {
						
						jQuery("#login-error").html("User with the provided email does not exist.");
						
						clearAndAddClass( '#uemail', 'wrong' );
					}	
					else
					{
						jQuery("#login-error").html("Please enter correct password.");

						clearAndAddClass('#uemail', 'ok');
						clearAndAddClass( '#password', 'wrong' );
					}
					
					// Hide Spinner and Enable the login button
					hideSpinner('login-spinner');
					jQuery("#log-me-in").prop('disabled', false);
				}
				else if( data['result'] == 1 )	// username already exist
				{
					window.location = jQuery( "#login-form" ).attr( "action" ) + "?action=login";
				}
			}
	});
}

function registerUser()
{
	var email 		= document.getElementById('uemail').value;
	var password	= document.getElementById('password').value;
	var cpassword	= document.getElementById('cpassword').value;
	var flname		= document.getElementById('flname').value;
	var tc			= jQuery('#tandc').parent().hasClass("checked");
	var sup			= jQuery('#subp').parent().hasClass("checked");
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'user_registration', uemail: email, upassword: password, ucpassword: cpassword, uflname: flname, tandc: tc, subp: sup },  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        		
				if( data['result'] == 0 )
				{
					if( null != data['uemailv'] ) {
						clearAndAddClass( '#uemail', 'wrong' );
						
						jQuery("#login-error").html( ERROR_EMAIL_V );
					}
					else if( null != data['upassword'] ) {
						jQuery("#login-error").html( ERROR_PASSWORD_V );
					}
					else if( null != data['uflname'] ) {
						jQuery("#login-error").html( ERROR_NAME_V );
					}	
					else if( null != data['tandc'] ) {
						jQuery("#login-error").html( ERROR_TANDC_V );
					}
					else if( null != data['subp'] ) {
						jQuery("#login-error").html( ERROR_SUBP_V );
					}
					else if( null != data['uemail'] ) {
						clearAndAddClass( '#uemail', 'wrong' );
						
						jQuery("#login-error").html( ERROR_USER_EXIST );
					}
					else if( null != data['ucpassword'] ) {
						jQuery("#login-error").html( ERROR_PASSWORD_CONFIRM_V );
					}	
					else {
						jQuery("#login-error").html( "Your registration request has been failed." );
					}
					
					// Hide Spinner and Enable the login button
					hideSpinner('login-spinner');
					jQuery("#sign-me-up").prop('disabled', false);
				}
				else if( data['result'] == 1 )	// username already exist
				{
					window.location = jQuery( "#login-form" ).attr( "action" ) + "?action=register";
				}
			}
	});
}

function getProvinces( location_id )
{
	// Show Spinner
	showSpinner( "shipping-cnt-spinner" );
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'get_province', locationid: location_id },  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
				var options = "<option> Select Province/State </option>";
				var ulelem	= "<li rel='0'> Select Province/State </li>";
				
				var length = data.length;
				   
				for(var i = 0; i < length; i++)
				{
					options += "<option value='"+ data[i]['id'] +"'> " +  data[i]['local_name'] + " </option>";
					ulelem	+= "<li rel='"+ data[i]['id'] +"'> " +  data[i]['local_name'] + " </li>";
				}

				var select 	= jQuery("#prf_province");
				var ul		= select.parent("div").children( ".options");
				
				select.html( options );
				ul.html( ulelem );
				
				activateListItems( "#prf_province" );
				
				// Show Spinner
				hideSpinner( "shipping-cnt-spinner" );
			}
	});
}

function contactForm( formData )
{
	// Show Spinner
	showSpinner( 'contact-spinner' );
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: formData,  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
				if( data['result'] == 1 )
				{
					window.location = jQuery( "#contact-form" ).attr( "action" );
				}
				else
				{
					// Hide spinner
					hideSpinner( 'contact-spinner' );
						
					if( null != data['name_err'] )
						jQuery("#prf_name_error").show();
						
					if( null != data['email_err'] )
						jQuery("#prf_email_error").show();
						
					if( null != data['subject_err'] )
						jQuery("#prf_subject_error").show();
						
					if( null != data['message_err'] )
						jQuery("#prf_message_error").show();	
						
					if( null != data['captcha_error'] )
					{
						jQuery("#recaptcha_response_field").val('');
						jQuery('#prf_captcha_error').show();
					}																									
				}
			}
	});	
}

function forgotPassword( formData )
{
	// Show Spinner
	showSpinner("forgot-password-spinner");
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: formData,  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
				if( data['result'] == 1 )
				{
					jQuery( "#fpemail_success" ).show();
				}
				else
				{
					if( data['fpemail_error'] == 1 )
					{
						jQuery( "#fpemail_error" ).show();
					}
				}
				
				// Hide Spinner
				hideSpinner("forgot-password-spinner");
			}
	});	
}

function resetPassword( formData )
{
	// Show Spinner
	showSpinner("forgot-password-spinner");
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: formData,  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
				if( data['result'] == 1 )
				{
					window.location = jQuery( "#reset_password_form" ).attr( "action" );
				}
				else
				{
					if( data['rst_pwd_error'] == 1 )
					{
						jQuery( "#rst_pwd_error" ).show(); jQuery( "#rst_pwd_error" ).html( ERROR_PASSWORD_V );
					}
					
					if( data['rst_repwd_error'] == 1 )
					{
						jQuery( "#rst_repwd_error" ).show(); jQuery( "#rst_repwd_error" ).html( ERROR_PASSWORD_CONFIRM_V );
					}

					if( data['fpemail_error'] == 1 )
					{
						jQuery( "#fpemail_error" ).show(); jQuery( "#fpemail_error" ).html( ERROR_PASSWORD_RESET );
					}
				}
				
				// Hide Spinner
				hideSpinner("forgot-password-spinner");
			}
	});	
}

// User Calls ------------------------------------------------------------------------------

// Cart and Order ---------------------------

function getCartItemCount()
{
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'ecart_get_cart_item_count' },  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
        	if( data['result'] == 1 )
        	{
        		jQuery("#cart-items-count").html( data['cart-item-count'] );
        	}
        	else if( data['result'] == 0 )
        	{
        		alertSession( data );
        	}
        },
        error: function(MLHttpRequest, textStatus, errorThrown)
        {  
            // alert(errorThrown);  
        }
    });
}

function addItemToCart()
{
	// Show Spinner
	showSpinner( "dcart-spinner" );
	
	// Redraw to match position
	preDesignUpload();

	var imageData 	= emblmDesigner.designlStage.toDataURL( {

          callback: function( imageData ) {

			var cc			= jQuery('#clearcase').parent().hasClass("checked");
			
			jQuery.ajax({
				type: "POST",
			  	url: EU.ajaxurl + "?action=ecart_save_order_design&dwidth="+deviceTargetWidth+"&dheight="+deviceTargetHeight+"&filename="+designFileName,
			  	data: {image: imageData},
			  	dataType: "JSON"
			}).done(function( respond ) {
				
				if( respond['result'] == 1 )
				{
					// Add item to cart on successful upload of user design
					jQuery.ajax({  
				        type: 'POST',  
				        url: EU.ajaxurl,  
				        data: { action: 'ecart_add_item', clearcase: cc, templateid: selectedTemplateId, deviceid: selectedDeviceId },  
				        dataType: "JSON",
				        success: function( data, textStatus, XMLHttpRequest ) { 
				        	
				        	if( data['result'] == 1 )
				        	{
								location.reload(true);
				        	}
				        	else if( data['result'] == 0 )
				        	{
				        		alertSession( data );
				        		
				        		// Hide Spinner
								hideSpinner( "dcart-spinner" );
								
								if( null != data['message']  && data['message'].length > 0 )
									alert( data['message'] );
								else
				        			alert( "Your item is not added to cart." );
				        	}
				        },
				        error: function(MLHttpRequest, textStatus, errorThrown)
				        {  
				            // alert(errorThrown);  
				        }  
				    });
				}
				else if( respond['result'] == 0 )
				{
					alertSession( respond );
				}
			});
		}
	});
}

function removeItemFromCart( parentRow, dev, cartItemIndex )
{
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'ecart_remove_item', deviceid: dev, cartitemindex: cartItemIndex },  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
        	if( data['result'] == 1 )
        	{
        		// Remove cart item row
        		parentRow.remove();
        		
        		// update item count
        		jQuery("#cart-items-count").html( data['cart-item-count'] );
        		
        		updateCartTotals( data['totals'] );
        		
				if( null != data['newrates']  && data['newrates'] )
				{
					window.location = jQuery( "#update_order_charges_form" ).attr( "action" );
        		}
        		else
        		{
        			hideSpinner( "inc-dec-spinner" );
        		}         		
        	}
        	else if( data['result'] == 0 )
        	{
        		alertSession( data );
        	}        	
        },
        error: function(MLHttpRequest, textStatus, errorThrown)
        {  
            // alert(errorThrown);  
        }  
    });
}

function removeClearcaseFromCart( parentRow, dev )
{
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'ecart_remove_item_cc', deviceid: dev },  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
        	if( data['result'] == 1 )
        	{
        		// Remove clearcase row
        		parentRow.remove();
        		
        		// update item count
        		jQuery("#cart-items-count").html( data['cart-item-count'] );
        		        		
        		updateCartTotals( data['totals'] );
        		
				if( null != data['newrates']  && data['newrates'] )
				{
					window.location = jQuery( "#update_order_charges_form" ).attr( "action" );
        		}
        		else
        		{
        			hideSpinner( "inc-dec-spinner" );
        		}        		
        	}
        	else if( data['result'] == 0 )
        	{
        		alertSession( data );
        	}        	
        },
        error: function(MLHttpRequest, textStatus, errorThrown)
        {  
            // alert(errorThrown);  
        }  
    });
}

function updateItemCount( parentRow, elem, dev, cartItemIndex, inc )
{
	disableIncDec();
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'ecart_update_item_count', deviceid: dev, cartitemindex: cartItemIndex, increment: inc },  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
        	if( data['result'] == 1 )
        	{
        		jQuery( "#itemcount_" + cartItemIndex + "_" + dev ).val( data['item-count'] );
        		
        		var unitPrice 	= parseFloat( data['unit-price'] );
        		var itemCount	= parseInt( data['item-count'] );
        		var total		= unitPrice * itemCount;
        		total			= parseFloat(total).toFixed(2);
        		
        		var childhtml	= "$" + total;
				                    	
        		parentRow.children( ".total-price-td").html( childhtml );
        		
        		updateCartTotals( data['totals'] );
        		
				if( null != data['newrates']  && data['newrates'] )
				{
					window.location = jQuery( "#update_order_charges_form" ).attr( "action" );
        		}
	        }
        	else if( data['result'] == 0 )
        	{
        		alertSession( data );

				if( null != data['message']  && data['message'].length > 0 )
					alert( data['message'] );
        	}
        	
    		enableIncDec();
    		
    		hideSpinner( "inc-dec-spinner" );        	
        },
        error: function(MLHttpRequest, textStatus, errorThrown)
        {  
            // alert(errorThrown);  
        }  
    });
}

function updateCcCount( parentRow, elem, dev, inc )
{
	disableIncDec();
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'ecart_update_cc_count', deviceid: dev, increment: inc },  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
        	if( data['result'] == 1 )
        	{
        		jQuery( "#clearcasecount_" + dev ).val( data['item-count'] );
        		
        		var ccUnitPrice	= parseFloat( data['cc-unit-price'] );
        		var itemCount	= parseInt( data['item-count'] );
        		var cctotal		= ccUnitPrice * itemCount;
        		cctotal			= parseFloat(cctotal).toFixed(2);
        		
        		var childhtml	= "$" + cctotal;
				                    	
        		parentRow.children( ".total-price-td").html( childhtml );
        		
        		updateCartTotals( data['totals'] );
        		
				if( null != data['newrates']  && data['newrates'] )
				{
					window.location = jQuery( "#update_order_charges_form" ).attr( "action" );
        		}     		
        	}
        	else if( data['result'] == 0 )
        	{
        		alertSession( data );
        		
				if( null != data['message']  && data['message'].length > 0 )
					alert( data['message'] );
        	}
			
			hideSpinner( "inc-dec-spinner" );

			enableIncDec();
        },
        error: function(MLHttpRequest, textStatus, errorThrown)
        {  
            // alert(errorThrown);  
        }  
    });
}

function updateOrderShipping( dataArr )
{
	// Show Spinner
	showSpinner( "shipping-spinner" );
	 
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: dataArr,  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
        	if( data['result'] == 1 ){
        		
        		window.location = jQuery( "#update_order_shipping" ).attr( "action" );
        	}
        	else if( data['result'] == 0 )
        	{
        		alertSession( data );

        		// No rates found
        		if( data['prf_shipping_q_error'] == 1 )
        			jQuery("#shipping_ser_error").show();
        		
        		// Personal	        	
        		if( data['prf_address_1_error'] == 1 )
        			jQuery( "#prf_address_1_error" ).css("display", "inline-block" );
        		
        		if( data['prf_primemail_error'] == 1 )
        		{
        			jQuery( "#prf_primemail_error" ).css("display", "inline-block" );
        			jQuery( "#prf_primemail_error" ).prop("title", "Please provide a valid email address.");
        		}
        			
        		if( data['prf_primemail_exist'] == 1 )
        		{
        			jQuery( "#prf_primemail_error" ).css("display", "inline-block" );
        			jQuery( "#prf_primemail_error" ).prop("title", "This email is already registered. Please login to continue." );
        		}
        				
        		if( data['prf_password_error'] == 1 )
        			jQuery( "#prf_password_error" ).css("display", "inline-block" );
        			
				if( data['prf_cpassword_error'] == 1 )
        			jQuery( "#prf_cpassword_error" ).css("display", "inline-block" );
        			
	        	// Hide Spinner
				hideSpinner( "shipping-spinner" );
        	}	
		}
	});	
}

function updateOrderCharges( dataArr )
{
	// Show Spinner
	showSpinner( "shipping-c-spinner" );
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: dataArr,  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
        	if( data['result'] == 1 ){
        		
        		window.location = jQuery( "#update_order_charges_form" ).attr( "action" );
        	}
        	else if( data['result'] == 0 )
        	{
        		alertSession( data );
        		
				// Hide Spinner
				hideSpinner( "shipping-c-spinner" );	
        	}	
		}
	});	
}

function placeOrder( dataArr )
{
	// Show Spinner
	showSpinner( "cart-spinner" );
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: dataArr,  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
        	if( data['result'] == 1 ){
        		
        		window.location = jQuery( "#place_order_form" ).attr( "action" ) + "?action=completeorder&order_id=" + data['orderid'];
        	}
        	else if( data['result'] == 0 )
        	{
        		alertSession( data );
        		
	        	// Hide Spinner
				hideSpinner( "cart-spinner" );
			}		
		}
	});	
}

function applyPromoCode( promo_code )
{
	// Show Spinner
	showSpinner("promo-spinner");
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'ecart_apply_coupon', promocode: promo_code },  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
        	if( data['result'] == 1 ){

				jQuery("#promo_error_box").hide();

				jQuery("#promo_code").val("");

				jQuery( "#inner-coupons" ).append( "<div class='coupon' id='coupon-" + promo_code + "'><span>" + promo_code + "</span><span class='close' onclick=\"clearPromoCode('" + promo_code + "')\">X</span></div>" );				
        	}
        	else if( data['result'] == 0 )
        	{
        		alertSession( data );

        		jQuery("#promo_error_box").show();
        		
        		jQuery("#promo_error_message").html( ERROR_COUPON_NOT_FOUND );
        		
        		if( null != data['message'] && data['message'].length > 0 ) {
	        		
	        		jQuery("#promo_error_message").html( data['message'] );
	        	}
        	}
        	
        	hideSpinner("promo-spinner");	

        	updateCartTotals( data['totals'] );        
        	
        	if( data['freeshipping'] ) {

        		jQuery("#shipping-free").show();
        	}
        	else {
        		jQuery("#shipping-free").hide();
        	}
		}
	});	
}

function clearPromoCode( promo_code )
{
	// Show Spinner
	showSpinner("promo-spinner");
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'ecart_clear_coupon', promocode: promo_code },  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
        	if( data['result'] == 1 ) {

				jQuery("#promo_error_box").hide();

				jQuery( "#coupon-" + promo_code ).remove();				
        	}
        	else if( data['result'] == 0 )
        	{
        		alertSession( data );
        		
        		jQuery("#promo_error_box").show();
        		
        		jQuery("#promo_error_message").html( ERROR_COUPON_NOT_FOUND );
        	}
			
        	hideSpinner("promo-spinner");	

        	updateCartTotals( data['totals'] );
        	
        	if( data['freeshipping'] ) {

        		jQuery("#shipping-free").show();
        	}
        	else {
        		jQuery("#shipping-free").hide();
        	}        	
		}
	});		
}

// User -------------------------------------

function saveNickname()
{
	var nickname = document.getElementById('user_nickname_t').value;

	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'user_save_nickname', unickname: nickname },  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
			if( data['result'] == 1 )	// username already exist
			{
				//jQuery("#save_nickname_c").toggle( "fast" );
				jQuery("#user_nickname_h").html(nickname);
				toggleNickname();
			}
        	else if( data['result'] == 0 )
        	{
        		alertSession( data );
        	}				
		}
	});
}

function saveUserAvatar(){
	
	if( !loadingAvatar )
	{
		// Show Spinner
		showSpinner("upload-avatar-spinner");
		
		loadingAvatar = true;
		var imageData = activeDesigner.designObjLayer.getCanvas().toDataURL(); 
		
		jQuery.ajax({
			type: "POST",
		  	url: EU.ajaxurl + "?action=user_save_avatar&filename="+avatarFileName,
		  	data: {image: imageData},
		  	dataType: "JSON"
		}).done( function( data ) {
					
				if( data['result'] == 1 )
				{
					// Get the uploaded filename
					var imageUrl = data['avatarUrl'];
					
					jQuery( '#user_avatar_img' ).attr( "src", imageUrl );
					
					hideProfileSelector();
					
					// Show Spinner
					hideSpinner("upload-avatar-spinner");
										
					loadingAvatar 	= false;
					activeDesigner	= mkDesigner;
				}
	        	else if( data['result'] == 0 )
	        	{
	        		alertSession( data );
	        	}					
		});
	}
}

function saveProfile( dataArr )
{
	// Show Spinner
	showSpinner( "save-profile-spinner" );
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: dataArr,  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
        	if( data['result'] == 1 ){
        		
        		// Hide Spinner
        		hideSpinner("save-profile-spinner");
        	}
        	else if( data['result'] == 0 )
        	{
        		alertSession( data );
        	}        		
		}
	});	
}

function verifyPaypal( dataArr )
{
	// Show Spinner
	showSpinner("verify-pp-spinner");
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: dataArr,  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
        	if( data['result'] == 1 )
        	{
        		window.location = jQuery( "#verify_paypal_form" ).attr( "action" );
        	}
        	else if( data['result'] == 0 )
        	{
        		alertSession( data );

        		// Hide Spinner
				hideSpinner("verify-pp-spinner");
				
				// Show errors
				jQuery("#pp_verify_error").show();

				if( null != data['existing'] )
					jQuery("#pp_verify_error").html("This email is already registered with Emblm marketplace.");
				else
					jQuery("#pp_verify_error").html("Please provide the first name, last name and Paypal verified email registered with Paypal.");
        	}
		}
	});	
}

// User Marketplace -------------------------

function checkDesignName( designName )
{
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'user_check_designname', deviceid: selectedDeviceId, udesignname: designName },
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        		
				if( data['result'] == 1 )			// design does not exist
				{
					jQuery( "#frm-design-tags" ).val( data['tags'] );
					
					jQuery( "#frm-design-unique" ).val( 1 );
					
					selectedCategory	= data['category'];
					
					var cat 			= jQuery( "#tool_select_category option[value='"+selectedCategory+"']" ).html();
					
					jQuery( "#tool_select_category" ).next( ".styledSelect").html(cat);
						
					jQuery( "#frm-design-name-error" ).css( "display", "none" );
				}
				else if( data['result'] == 0 )	// design already exist
				{
					jQuery( "#frm-design-name-error" ).css( "display", "inline-block" );
				}
			}
	});
}

function addMarketplaceDesign( formData )
{
	// Show Spinner
	showSpinner("submit-mk-spinner");
	
	// Redraw to match position
	preDesignUpload();

	var imageData = mkDesigner.designlObjLayer.getCanvas().toDataURL(); 

	jQuery.ajax({
		type: "POST",
	  	url: EU.ajaxurl + "?action=user_save_mk_design&deviceid="+selectedDeviceId +"&dwidth="+deviceTargetWidth+"&dheight="+deviceTargetHeight+"&filename="+designFileName,
	  	data: {image: imageData},
	  	dataType: "JSON"
	}).done(function( respond ) {

		if( respond['result'] == 1 )
		{
			formData += "&designurl=" + encodeURIComponent(respond['designurl']) + "&designthumb=" + encodeURIComponent(respond['designthumb']) + "&selecteddevice=" + encodeURIComponent(selectedDeviceId) + "&selectedcategory=" + encodeURIComponent(selectedCategory);
	
			// Add item to cart on successful upload of user design
			jQuery.ajax({  
		        type: 'POST',  
		        url: EU.ajaxurl,  
		        data: formData,  
		        dataType: "JSON",
		        success: function( data, textStatus, XMLHttpRequest ) { 
	
		        	if( data['result'] == 1 )
		        	{
						window.location = jQuery( "#frm-submit-design" ).attr( "action" );
					}
		        	else if( data['result'] == 0 )
		        	{
		        		alertSession( data );

		        		if( null != data['frm-design-name-error'] )
		        			jQuery("#frm-design-name-error").css("display", "inline-block");
	
		        		if( null != data['frm-category-error'] )
		        			jQuery("#frm-category-error").css("display", "inline-block");
		        			
		        		if( null != data['frm-aup-error'] )
		        			jQuery("#frm-aup-error").css("display", "inline-block");
	
		        		if( null != data['frm-design-description-error'] )
		        			jQuery("#frm-design-description-error").css("display", "inline-block");
	
		        		if( null != data['frm-design-tags-error'] )
		        			jQuery("#frm-design-tags-error").css("display", "inline-block");
		        		
		        		if( null != data['frm-ad-error'] )	
		        			alert( "Your design upload is failed. Please try again." );
		        	}
		        	
					// Show Spinner
					hideSpinner("submit-mk-spinner");			        	
		        },
		        error: function(MLHttpRequest, textStatus, errorThrown)
		        {  
		            // alert(errorThrown);  
		        }  
		    });
		}
    	else if( respond['result'] == 0 )
    	{
    		alertSession( respond );
    	}		
	});
}

function redeemRoyalty( formData )
{
	// Show Spinner
	showSpinner("redeem-spinner");
		
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: formData,
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 

			if( data['result'] == 0 )
			{
				alertSession( data );
				
				jQuery("#frm_royalty_claimed").prop("readonly", false );
				jQuery("#frm_royalty_claimed_s").prop("disabled", false );

				jQuery("#redemption-error").html( ERROR_REDEMPTION_FAILED );
				jQuery("#redemption-error-box").show();
						
				// Hide Spinner
				hideSpinner( "redeem-spinner" );
			}
			else if( data['result'] == 1 )
			{
				window.location = jQuery( "#redeem_royalty_form" ).attr( "action" );
			}
		}
	});
}

function provideRating()
{
	// Show Spinner
	showSpinner("provide-rating-spinner");
	
	formData = {
					action:"user_provide_rating",
					designid: jQuery('#design-rating').attr('did'),
					rating: jQuery('#design-rating').rateit('value')
				};
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: formData,  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
			if( data['result'] == 1 )
			{
				// Success
				jQuery('#design-rating').rateit('value', data['ratings'] );
				
				alert( ALERT_RATING_SUCCESS );
			}
        	else if( data['result'] == 0 )
        	{
				jQuery('#design-rating').rateit('value', selectedDesignRatings );
				
				if( ( null != data['session'] && data['session'] == 1 ) )
				{
					alert( ALERT_SESSION );
					
					window.location = siteUrl;
				}
				else if( null != data['secpass'] && data['secpass'] == 1 )
				{
					showRatingLogin();
				}	
			}
			
			// Hide Spinner
			hideSpinner("provide-rating-spinner");
		}
	});
}

function loginRating()
{
	var email 		= jQuery("#login-lightbox-form input[name='uemail']").val();
	var password	= jQuery("#login-lightbox-form input[name='upassword']").val();
	var error		= jQuery("#login-lightbox-error");
	
	error.html('');
	
	if( null == email || !validateEmail(email) ){
		
		error.html( ERROR_EMAIL_V );
		
		return false;
	}
	
	if( null == password || password.length == 0 ){

		error.html( ERROR_PASSWORD_V );
		
		return false;
	}
	
	showSpinner("login-lightbox-spinner");
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: 'user_login', uemail: email, upassword: password },  
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
				if( data['result'] == 0 )
				{
					// Clear and add error classes
					if( null != data['uemail'] && data['uemail'] == 1 ) {
						
						error.html( ERROR_EMAIL_NOT_FOUND );
					}
					else {
						
						error.html( ERROR_PASSWORD_WRONG );
					}
					
					hideSpinner('login-lightbox-spinner');	
				}
				else if( data['result'] == 1 )	// username already exist
				{
					window.location = document.URL;
				}
			}
	});
	
	return false;
}

// Pagination

function getDesignPage( page_num )
{
	// Get all Pages
	var pageExist	= false;
	
	jQuery(".design-page").each( function(){
		
		if( jQuery(this).attr('page-num') == page_num )
		{
			pageExist = true;
		}
	});
	
	// Do not load same page
	if( pageExist )
	{
		jQuery(".design-page").fadeOut("fast");
		jQuery(".design-page[page-num="+page_num+"]").fadeIn("slow");
		
		return;
	}
	
	showSpinner("design-page-spinner");
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: "user_design_page", pagenum: page_num },   
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
				if( data['result'] == 1 )
				{
					// Form the page
					var pageData 			= data['designs'];
					var pageLimit			= data['pagelimit'];
					var processedPageData 	= "<div class='design-page' page-num='" + page_num + "'>";
					
					var count	= pageData.length;
					var counter	= (page_num-1) * pageLimit;
					
					for( i = 0; i < count; i++ )
					{
						var designData 	= pageData[i];
						var description	= "";
						var tags		= "";
						
						counter++;
						
						if( null != designData['design_desc'] )
						{
							description	= "<p class='design-description'>" + designData['design_desc'] + "</p>";
						}
						
						if( null != designData['design_tags'] )
						{
							tags	= "<div>Tags: <strong>" + designData['design_tags'] + "</strong></div>";
						}
												
		                processedPageData += "<div class='inner fleft container'> <div class='sn fleft'>" + counter + "</div>" +
					                            " <div class='fleft img'><img src='" + designData['design_thumb_url'] + "' width='125' height='125'></div> " +
					                            " <div class='heading fleft'> " +
					                            	" <h6> " + designData['design_title'] + " </h6>" +
					                            	" <div> <sup></sup> " +
					                            		" <div class='rateit' data-rateit-value='" + designData['ratings'] + "' data-rateit-ispreset='true' data-rateit-readonly='true'></div> " +
					                            		" <span>(" + designData['ratings'] + " ratings)</span>" +
					                            	" </div>" +	
					                            	" <div class='social-container'> " +
						                            	"<span class='social-btn facebook' onclick=\"Share.facebook('"+siteUrl+"/designer?deviceid=" + designData['device_id'] + "&designid=" + designData['design_id'] + "','" + designData['device_name'] + " - " + designData['design_title'] + "','" + designData['design_image_url'] + "','" + designData['design_desc'] + "')\"></span>" +
						                            	"<span class='social-btn twitter' onclick=\"Share.twitter('"+siteUrl+"', '"+siteUrl+"/designer?deviceid=" + designData['device_id'] + "&designid=" + designData['design_id'] + "','" + designData['device_name'] + " - " + designData['design_title'] + " - " + designData['design_desc'] + "')\"></span>" +
						                            	"<span class='social-btn pintrest' onclick=\"Share.pintrest('"+siteUrl+"/designer?deviceid=" + designData['device_id'] + "&designid=" + designData['design_id'] + "','" + designData['device_name'] + " - " + designData['design_title'] + "','" + designData['design_image_url'] + "','" + designData['design_desc'] + "')\"></span>" +
						                            " </div> " +                             	
					                            " </div>" +
					                            " <div class='img_info fleft'>" +
					                            	" <div>Submission Status: <span class='" + designData['status_css'] + "'><strong>" + designData['design_status'] + "</strong></span></div>" +
					                                " <div>Device Model: <strong>" + designData['device_name'] + "</strong></div>" +
					                                " <div>Total Quantity Sold: <strong>" + designData['quantity_sold'] + " </strong></div>" +
					                                " <div>Total Royalties Earned: <strong>$" + designData['royalties'] + "</strong></div>" +
					                                tags + "</div></div>";
					}
					
					processedPageData += "</div>";
					
					jQuery(".design-page").fadeOut("fast");
					
					jQuery(".m_place_row").append( processedPageData );
					
					jQuery(".design-page[page-num="+page_num+"]").fadeIn("slow");
					
					// RE - Rate all
					jQuery(".design-page[page-num="+page_num+"] .rateit").each(function(){
						
						var pr 		= jQuery(this);
						var ratings	= pr.attr('data-rateit-value')
						
						pr.rateit();
    					pr.rateit( 'value', ratings );	
						
					});	
				}
	        	else if( data['result'] == 0 )
	        	{
	        		alertSession( data );				
				}
				
				// Hide Spinner
				hideSpinner("design-page-spinner");
			}
	});	
}

function sumbitForZ10()
{
	var temail 		= jQuery("#email_submit_z10").val();
	var timerStatus	= jQuery("#timer-status");

	timerStatus.html("");

	if( null == temail || !validateEmail(temail) )
	{
		timerStatus.html("Please provide a valid email address.");
		
		return;
	}
	
	showSpinner("timer-spinner");
	
	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: "submit_for_z10", email: temail},   
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
				if( data == 1 )
				{
					timerStatus.html("Thanks for submitting your request.");
				}
				else if( data == 2 )
				{
					timerStatus.html("You have already submitted your request.");
				}
				
				// Hide Spinner
				hideSpinner("timer-spinner");
			}
	});	
}

// Utilities ---------------------------------------------------------------------------------

function updateCartTotals( totals ) {

	jQuery("#cart_total").html( totals['total'].toFixed(2) );	
	jQuery("#cart_discount").html( totals['coupon_discount'].toFixed(2) );	
	jQuery("#cart_sub_total").html( totals['sub_total'].toFixed(2) );	
	jQuery("#cart_shipping_charge").html( totals['shipping_charge'].toFixed(2) );	
	jQuery("#cart_tax").html( totals['tax'].toFixed(2) );	
	jQuery("#cart_grand_total").html( totals['grand_total'].toFixed(2) );
	
	if( totals['grand_total'] == 0 )
	{
		jQuery("#confirm_order").html("PLACE ORDER");
	}	
	else
	{
		jQuery("#confirm_order").html("PAY NOW");
	}
}

function alertSession( data )
{
	if( ( null != data['session'] && data['session'] == 1 ) || data['secpass'] == 1 )
	{
		alert( ALERT_SESSION );
		
		window.location = siteUrl;
	}
}

function generateQrCode( codeUrl ) {
	
	showSpinner("qr-code-spinner");

	jQuery.ajax({  
        type: 'POST',  
        url: EU.ajaxurl,  
        data: { action: "generate_qr_code", url: codeUrl },   
        dataType: "JSON",
        success: function( data, textStatus, XMLHttpRequest ) { 
        	
				if( data['result'] == 1 )
				{
					// success
					var url = data['url'];
					
					if( null != emblmDesigner ){
						
						emblmDesigner.loadQrCode( url );
					}
				}
				else
				{
					alert( ERROR_QR_CODE );
				}
				
				hideSpinner("qr-code-spinner");
			}
	});	
}
