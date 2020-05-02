function uploadOptionFile(id) {
	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
		clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$('#button-upload' + id).button('loading');
				},
				complete: function() {
					$('#button-upload' + id).button('reset');
				},
				success: function(json) {
					$('.text-danger').remove();

					if (json['error']) {
						$('#input-option' + id).after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);
						$('#input-option' + id).val(json['code']);
					} 
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
}

function updatePrice(id) {
	var product = '#product' + id;
	
	if (typeof $('.product-price' + id).data('price') != 'undefined') {
		var price = Number($('.product-price' + id).data('price'));
	} else {
		var price = false;
	}
	
	if (typeof $('.product-special' + id).data('special') != 'undefined') {
		var special = Number($('.product-special' + id).data('special'));
	} else {
		var special = false;
	}
	
	if (typeof $('.product-tax' + id).data('tax') != 'undefined') {
		var tax = Number($('.product-tax' + id).data('tax'));
	} else {
		var tax = false;
	}
	
	if (typeof $('.product-points' + id).data('points') != 'undefined') {
		var points = Number($('.product-points' + id).data('points'));
	} else {
		var points = false;
	}
	
	var quantity = Number($(product + ' input[name=\'quantity\']').val());
	
	$(product + ' input:checked, ' + product + ' option:selected').each(function() {
		
		if (price) {
		
			if ($(this).data('price-prefix') == '+') {
				price = price + Number($(this).data('price'));
				
				if (special) {
					if (typeof $(this).data('special') != 'undefined') {
						special = special + Number($(this).data('special'));
					} else {
						special = special + Number($(this).data('price'));
					}
				}
				
				if (tax && typeof $(this).data('tax') != 'undefined') {
					tax = tax + Number($(this).data('tax'));
				}
			}
			
			if ($(this).data('price-prefix') == '-') {
				price = price - Number($(this).data('price'));
				
				if (special) {
					if (typeof $(this).data('special') != 'undefined') {
						special = special - Number($(this).data('special'));
					} else {
						special = special - Number($(this).data('price'));
					}
				}
				
				if (tax && typeof $(this).data('tax') != 'undefined') {
					tax = tax - Number($(this).data('tax'));
				}
			}
			
			if ($(this).data('price-prefix') == '=') {
				price = Number($(this).data('price'));
				
				if (special) {
					if (typeof $(this).data('special') != 'undefined') {
						special = Number($(this).data('special'));
					} else {
						special = Number($(this).data('price'));
					}
				}
				
				if (tax && typeof $(this).data('tax') != 'undefined') {
					tax = Number($(this).data('tax'));
				}
			}
			
			if ($(this).data('price-prefix') == '*') {
				price = price * Number($(this).data('price'));
				
				if (special) {
					if (typeof $(this).data('special') != 'undefined') {
						special = special * Number($(this).data('special'));
					} else {
						special = special * Number($(this).data('price'));
					}
				}
				
				if (tax && typeof $(this).data('tax') != 'undefined') {
					tax = tax * Number($(this).data('tax'));
				}
			}
			
			if ($(this).data('price-prefix') == '/') {
				price = price / Number($(this).data('price'));
				
				if (special) {
					if (typeof $(this).data('special') != 'undefined') {
						special = special / Number($(this).data('special'));
					} else {
						special = special / Number($(this).data('price'));
					}
				}
				
				if (tax && typeof $(this).data('tax') != 'undefined') {
					tax = tax / Number($(this).data('tax'));
				}
			}
		}
		
		if (points) {
			if ($(this).data('points-prefix') == '+') {
				points = (points + Number($(this).data('points')) * quantity);
			}
			
			if ($(this).data('points-prefix') == '-') {
				points = (points - Number($(this).data('points')) * quantity);
			}
		}
    });
	
	$('.product-price' + id).html(priceFormat(price * quantity));
	$('.product-special' + id).html(priceFormat(special * quantity));
	$('.product-tax' + id).html(priceFormat(tax * quantity));
	
	if (points) {
		$('.product-points' + id).html(points);
	}
}

function priceFormat(price) { 
	format = $('input[name=\'price_format\']');
    decimals = format.data('decimals');
    decimal_point = format.data('decimalPoint');
    thousand_point = format.data('thousandPoint');
	symbol_left = format.data('symbolLeft');
	symbol_right = format.data('symbolRight'); 
	
    price = price * format.data('value');

    i = '' + parseInt(price = Math.abs(price).toFixed(decimals)); 
    j = ((j = i.length) > 3) ? j % 3 : 0; 

    return symbol_left + (j ? i.substr(0, j) + thousand_point : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand_point) + (decimals ? decimal_point + Math.abs(price - i).toFixed(decimals).slice(2) : '') + symbol_right; 
}

function addCartOption(id, minimum) {
	var product = '#product' + id;
	
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $(product + ' input[type=\'text\'], ' + product + ' input[type=\'hidden\'], ' + product + ' input[type=\'radio\']:checked, ' + product + ' input[type=\'checkbox\']:checked, ' + product + ' select, ' + product + ' textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#cart > button').button('loading');
		},
		complete: function() {
			$('#cart > button').button('reset');
		},
		success: function(json) {
			$('.alert, .alert-dismissible, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + id + i.replace('_', '-'));

						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}

				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}
		
			if (json['success']) {
				$('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				// Need to set timeout otherwise it wont update the total
				setTimeout(function () {
					$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
				}, 100);

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				$('#cart > ul').load('index.php?route=common/cart/info ul li');
				
				// Reset quantity
				$(product + ' input[name=\'quantity\']').val(Number($(product + ' input[name=\'quantity\']').data('minimum')));
			}
		}
    });
}

function updateQuantity(prefix, minimum, id) {
	var product = '#product' + id;
	var input = $(product + ' input[name=\'quantity\']');
	
	var quantity = parseInt($(input).val(), 10);

	if (prefix == '+') {
		quantity++;
	} else {
		if (quantity > parseInt(minimum, 10)) {
			quantity--;
		}
	}
	
	$(input).val(quantity);
	updatePrice(id);
}