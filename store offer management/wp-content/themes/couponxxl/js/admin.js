jQuery(document).ready(function($){
	/* Create meta data in tabs */
	if( $('select[id^="offer_type-sm-field"]').length > 0 ){

		var common = $('select[id^="offer_type-sm-field"]').parents('.postbox');
		var coupon = $('select[id^="coupon_type-sm-field"]').parents('.postbox');
		var deal = $('input[id^="deal_items-sm-field"]').parents('.postbox');
		var requests = $('textarea[id^="offer_new_category-sm"]').parents('.postbox');

		common.find('.inside').append( '<div class="offer_store_wrap">'+$('#offer_deal_location .inside').html()+'</div>' );
		$('#offer_deal_location .inside').html('');

		function hide_options(){
			common.hide().addClass('closed');			
			coupon.hide().addClass('closed');
			deal.hide().addClass('closed');
			requests.hide().addClass('closed');
		}
		hide_options();
		common.show().removeClass('closed');

		var extra_class = '';
		requests.find('textarea').each(function(){
			if( $(this).val() !== '' ){
				extra_class = 'has_new';
			}
		});
  
		common.before(
			'<a href="javascript:;" class="button button-primary button-large offer_toggle_options" data-target="#'+common.attr('id')+'">'+common.find('.hndle span').text()+'</a>'+
			'<a href="javascript:;" class="button button-large offer_toggle_options hide-btn coupon" data-target="#'+coupon.attr('id')+'">'+coupon.find('.hndle span').text()+'</a>'+
			'<a href="javascript:;" class="button button-large offer_toggle_options hide-btn deal" data-target="#'+deal.attr('id')+'">'+deal.find('.hndle span').text()+'</a>'+
			'<a href="javascript:;" class="button button-large offer_toggle_options '+extra_class+'" data-target="#'+requests.attr('id')+'">'+requests.find('.hndle span').text()+'</a>'
		);

		$('select[id^="offer_type"]').change(function(){
			$('.offer_toggle_options.hide-btn').hide();
			$('.offer_toggle_options.hide-btn.'+$(this).val()).css('display', 'inline-block');
		});
		if( $('select[id^="offer_type"]').val() !== '' ){
			$('.offer_toggle_options.hide-btn.'+$('select[id^="offer_type"]').val()).css('display', 'inline-block');
		}

		$(document).on( 'click', '.offer_toggle_options', function(e){
			e.preventDefault();
			hide_options();
			var target = $(this).data('target');
			$('.offer_toggle_options').removeClass('button-primary');
			$(this).addClass('button-primary');
			$(target).removeClass('closed');
			$(target).fadeIn(100);
		});
		/* coupon main select conditional show */
		var code = $('input[id^="coupon_code"]').parents('tr');
		var sale = $('input[id^="coupon_sale"]').parents('tr');
		var image = $('input[name^="coupon_image"]').parents('tr');
		function show_coupon_type_options( val ){
			code.hide();
			sale.hide();
			image.hide();			
			switch( val ){
				case 'code' : code.show(); break;
				case 'sale' : sale.show(); break;
				case 'printable' : image.show(); break;
			}
		}
		$('select[id^="coupon_type"]').change(function(){
			show_coupon_type_options( $(this).val() );
		});

		show_coupon_type_options( $('select[id^="coupon_type"]').val() );
	}

	/* Add credits to user if bank transfer is used */
	$(document).one( 'click', '.process-credits', function(e){
		var $this = $(this);
		$.ajax({
			url: ajaxurl,
			method: 'POST',
			data: {
				action: 'process-credits',
				user_id: $this.data('user_id')
			},
			success: function( response ){
				e.preventDefault();
				$this.remove();
			}
		})
	});

	/* Process payments and refunds */
	var processing = false;
	$(document).on( 'click', '.process-payment', function(){
		if( !processing ){
			processing = true;
			var $this = $(this);
			var $html = $this.html();
			$this.html( '<i class="fa fa-spin fa-spinner"></i>' );
			$.ajax({
				url: ajaxurl,
				method: 'POST',
				data:{
					order_id: $this.hasClass('all') ? '' : $this.data('id'),
					action: 'process_order'
				},
				success: function(){
					window.location.reload();
				},
				complete: function(){
					$this.html( $html );
					processing = false;
				}
			});
		}
	});

	/* Process manuall refund */
	$(document).on( 'click', '.manual-refund', function(){
		if( !processing ){
			processing = true;
			var $this = $(this);
			var $html = $this.html();
			$this.html( '<i class="fa fa-spin fa-spinner"></i>' );		
			$.ajax({
				url: ajaxurl,
				method: 'POST',
				data: {
					item_id: $(this).data('id'),
					action: 'manual_refund'
				},
				success: function(){
					window.location.reload();
				},
				complete: function(){
					$this.html( $html );
					processing = false;
				}
			});
		}
	});

	/* handle image */
	function handle_images( frameArgs, callback ){
		var SM_Frame = wp.media( frameArgs );

		SM_Frame.on( 'select', function() {

			callback( SM_Frame.state().get('selection') );
			SM_Frame.close();
		});

		SM_Frame.open();	
	}	

	function imageExists(url, callback) {
		var img = new Image();
		img.onload = function() { callback(true); };
		img.onerror = function() { callback(false); };
		img.src = url;
	}		

	/* add image on the cat */
	$(document).on( 'click', '.add_cat_image', function(e) {
		e.preventDefault();
		var $this=  $(this);

		var frameArgs = {
			multiple: false,
			title: 'Select Image'
		};

		handle_images( frameArgs, function( selection ){
			model = selection.first();
			$this.parent().find('input').val( model.id );
			var img = model.attributes.url;
			var ext = img.substring(img.lastIndexOf('.'));
			img = img.replace( ext, '-150x150'+ext );
			imageExists( img, function(exists){
				if( exists ){
					$('.image-holder').html( '<img src="'+img+'"><a href="javascript:;" class="remove_cat_image">X</a>' );
				}
				else{
					$('.image-holder').html( '<img src="'+model.attributes.url+'"><a href="javascript:;" class="remove_cat_image">X</a>' );
				}

			} );			
			
		});
	});	

	$(document).on( 'click', '.remove_cat_image', function(){
		$(this).parent().parent().find('input').val( '' );
		$('.image-holder').html('');
	} );

	function check_checked( $this ){
		var val = $this.val();
		if( $this.prop('checked') ){
			$('.'+$this.val()).prop( 'checked', true );
		}
		else{
			$('.'+$this.val()).prop( 'checked', false );
		}
	}

	function update_main_location( id, status ){
		$('#locationchecklist input[value="'+id+'"]').prop('checked', status);
	}

	$(document).on( 'change', '#locationchecklist input', function(){
		var $this = $(this);
		if( $('#offer_type-sm-field-0').val() == 'deal' && $('.store_location input[data-id="'+$this.val()+'"]').length > 0 ){
			$('.store_location input[data-id="'+$this.val()+'"]').prop( 'checked', $this.prop('checked') ).trigger('change');
		}
	});

	$(document).on( 'change', '.checkbox-child', function(){
		var $this = $(this);
		var $parent = $this.parents('.checkbox-group');
		var checkParent = false;
		$parent.find('.checkbox-child').each(function(){
			if( $(this).prop('checked') ){
				checkParent = true;
			}
		});

		var $main = $parent.find('.deal-location-main');
		if( checkParent ){
			update_main_location( $main.data('id'), true );
			$main.prop('checked', true);
		}
		else{
			update_main_location( $main.data('id'), false );
			$main.prop('checked', false);
		}
	});

	$(document).on( 'change', '.deal-location-main', function(){
		var $this = $(this);
		var $parent = $this.parents('.checkbox-group');
		check_checked( $this );
		if( $this.prop('checked') ){
			update_main_location( $this.data('id'), true );
			$parent.find('.checkbox-child').each(function(){
				$(this).prop( 'checked', true );
			});
		}
		else{
			update_main_location( $this.data('id'), false );
			$parent.find('.checkbox-child').each(function(){
				$(this).prop( 'checked', false );
			});			
		}		
	});

	function check_offer_type( val ){
		if( val  == 'coupon' ){
			$('.store_location input').prop( 'checked', false ).attr('disabled', 'disabled');
		}
		else{
			$('.store_location input').removeAttr( 'disabled' );
		}		
	}

	$(document).on( 'change', 'select[id^="offer_type"]', function(){
		check_offer_type( $(this).val() );
	}); 
	$('select[id^="offer_type"]').trigger('change');

	/* CHANGE STORE LOCATION ON STORE CHANGE ON SINGLE OFFER EDIT */
	$(document).on( 'change', 'select[name="offer_store"]',function(){
		$.ajax({
			url: ajaxurl,
			data: {
				store_id: $(this).val(),
				action: 'offer_store_location'
			},
			method: 'GET',
			success: function( response ){
				$('.store_location').html( response );
				$('.store_location input').each(function(){
					check_checked( $(this) );
				});
			}
		})
	});


	/* UPDATE VOUCHER STATUS */
	$(document).on( 'click', '.update_voucher', function(){
		var $this = $(this);
		var new_status = $this.find('.fa-times').length > 0 ? '0' : '1';
		$this.html('<i class="fa fa-spin fa-spinner"></i>');
		$.ajax({
			url: ajaxurl,
			method: 'POST',
			data: {
				status: new_status,
				action: 'update_voucher',
				voucher_id: $this.data('id')
			},
			success: function(response){
				$this.parent().after(response);
				$this.parent().remove();
			}
		})
	});

	/* UDPATE VERSION OF THE THEME */
	$(document).on( 'click', '.cxxl-update-done', function(){
		var $this = $(this);
		$.ajax({
			url: ajaxurl,
			data: {
				action: 'update_version',
				version: $this.data('version')
			},
			success: function(){
				$this.parents('#message').slideUp();
			}
		});
	});

});
