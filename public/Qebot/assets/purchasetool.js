
/*$("#confirm").click(function() {
	jQuery.validator.setDefaults({
		  debug: true,
		  success: "valid"
		});*/
	
$(window).ready(function() {
	var rules = {
		firstname:{minlength:3, maxlength:15, required:true},
		lastName:{minlength: 3, maxlength: 15, required: true},
		emailAddress:{email: true, required: true},
		'card[address][line1]':{minlength: 3, required: true},
		'card[number]':{required: true},
		'card[address][zip]':{required: true},
		'card[address][city]':{required: true},
		'card[address][country]':{required: true},
		'card[address][state]':{required: true},
		cc:{digits: true, creditcard: true, required: true},
		date:{required:true},
		'card[cvv]':{minlength: 3, digits: true, required: true}
	};

	$('#purchaseform, #confirm').validate({
		rules: rules,
		 messages: {
	        'card[address][line1]': "Please enter Address",
	        'card[address][zip]': "Please enter zip",
	        'card[address][city]': "Please enter city",
	        'card[address][state]': "Please enter state",
	    },
		highlight: function(element) {
			$(element).closest('.form-group').addClass('has-error');
	    },
	    unhighlight: function(element) {
	    	$(element).closest('.form-group').removeClass('has-error');
	    },
	    errorElement: 'span',
	    errorClass: 'help-block',
	    errorPlacement: function(error, element) {
	    	if(element.parent('.input-group').length) {
	    		error.insertAfter(element.parent());
	    	} else {
	    		error.insertAfter(element);
	    	}
	    },
	    success: function(label) {
	    	//alert(label);
	    },
	    submitHandler: function(form) {
	        //
	    	
	    	if( $(form).find('#confirm').length ) {
	    		$('#purchaseModal').modal('hide');
	    		$("#confirmModal").modal('show');
	    	}
	    	else {
		    	$('#purchase-form-message').hide().html('');
		    	var $form = $(form);
		    	$.ajax({
					type: 'post',
					url: '/admin/purchase',
					data: $form.serialize(),
					success: function(msg) {
						var json = JSON.parse(msg);
						var status = false;
						var message = 'Unexpected message';
						
						if(json.status == 'success') {
							$('#purchase-form-message').show().html(json.msg);
						}
						
						if(json.status == 'error') {
							alert(json.msg);
						}
					}
				});
	    	}
	    }
	});
	
	$('.purchase_confirm').click(function() {
		$('#purchase-form-message').hide().html('').attr('class', 'note');

		var address = '';
		if($.trim($('#purchaseform input[name="card\\\[address\\\]\\\[line1\\\]"]').val() ) != '') {
			address += $('#purchaseform input[name="card\\\[address\\\]\\\[line1\\\]"]').val() + '<br />';
		}
		if($.trim($('#purchaseform input[name="card\\\[address\\\]\\\[zip\\\]"]').val() ) != '') {
			address += $('#purchaseform input[name="card\\\[address\\\]\\\[zip\\\]"]').val() + '<br />';
		}
		if($.trim($('#purchaseform input[name="card\\\[address\\\]\\\[city\\\]"]').val() ) != '') {
			address += $('#purchaseform input[name="card\\\[address\\\]\\\[city\\\]"]').val() + '<br />';
		}
		if($.trim($('#purchaseform input[name="card\\\[address\\\]\\\[state\\\]"]').val() ) != '') {
			address += $('#purchaseform input[name="card\\\[address\\\]\\\[state\\\]"]').val() + '<br />';
		}
		if($.trim($('#purchaseform input[name="card\\\[address\\\]\\\[country\\\]"]').val() ) != '') {
			address += $('#purchaseform input[name="card\\\[address\\\]\\\[country\\\]"]').val() + '<br />';
		}
		
		var expiration = '';
		if($.trim($('#purchaseform select[name="expiry-month"]').val() ) != '') {
			expiration += $('#purchaseform select[name="expiry-month"]').val() + '/';
		}
		if($.trim($('#purchaseform select[name="expiry-year"]').val() ) != '') {
			expiration += $('#purchaseform select[name="expiry-year"]').val();
		}
		
		$('#confirmModal tr td:nth-child(1)').html( $('#purchaseform input[name="firstname"]').val() );//first name
		$('#confirmModal tr td:nth-child(2)').html( $('#purchaseform input[name="lastName"]').val() );//last name
		$('#confirmModal tr td:nth-child(3)').html( $('#purchaseform input[name="emailAddress"]').val() );//email
		
		//subdomai
		if ($('#purchaseform input[name="subdomain"]').length) {
			$('#confirmModal .subdomain_row').show();
			$('#confirmModal tr td:nth-child(4)').html( $('#purchaseform input[name="subdomain"]').val() );
		}
		
		$('#confirmModal tr td:nth-child(5)').html( address );//billing address
		$('#confirmModal tr td:nth-child(6)').html( $('#purchaseform input[name="card\\\[number\\\]"]').val() );//card
		$('#confirmModal tr td:nth-child(7)').html(expiration);//exp
		$('#confirmModal tr td:nth-child(8)').html( $('#purchaseform input[name="card\\\[cvv\\\]"]').val() );//cvv
	});
	
	if($('#chooseSubDomain').length) {
		$('#chooseSubDomain')[0].onclick = function() { return false; };
		$('#chooseSubDomain').click(function() {
			$.ajax({
				type:'post',
				url: '/admin/update-subdomain',
				data: {word:$('#purchaseform-UserSetting-sub_domain').val()},
				success: function(msg) {
					var json = JSON.parse(msg);
					var json = JSON.parse(msg);
					if(json.status == 'error') {
						$('#cms-purchase-form-message').show().html(json.msg).attr('class', 'note note-warning');
					}
					else {
						$('#cms-purchase-form-message').show().html(json.msg).attr('class', 'note note-success');
						
						setTimeout(function() {
							$('#cmsPurchaseModal').modal('hide');
						}, 10000);
					}
				}
			});
		});
	}
	
	
	var subdomain_exists;
	$('#cms-purchase-form-message').hide().html('');
	$('#chooseSubDomain').prop('disabled', true);
	$('#purchaseform-UserSetting-sub_domain').keyup(function() {
		var _this = this;
		$('#chooseSubDomain').prop('disabled', true);
		$('#cms-purchase-form-message').hide().html('');
		var text = $('#purchaseform-UserSetting-sub_domain').val();
		if(text.length >= 3) {
			clearTimeout(subdomain_exists);
			subdomain_exists = setTimeout(function() {
				$.ajax({
					type: 'post',
					url: '/admin/subdomain-exists',
					data: {word:text},
					success: function(msg) {
						var json = JSON.parse(msg);
						if(json.status == 'error') {
							$('#chooseSubDomain').prop('disabled', true);
							$('#cms-purchase-form-message').show().html(json.msg).attr('class', 'note note-warning');
						}
						else {
							$('#cms-purchase-form-message').hide();
							$('#chooseSubDomain').prop('disabled', false);
						}
					}
				});
				clearTimeout(subdomain_exists);
			}, 2000);
		}
		else {
			clearTimeout(subdomain_exists);
		}
	});
	
	$('.confirm_final').click(function() {
		$('#purchase-form-message').hide().html('');
    	var $form = $('#purchaseform');
    	$.ajax({
			type: 'post',
			url: '/admin/purchase',
			data: $form.serialize(),
			success: function(msg) {
				var json = JSON.parse(msg);
				
				if(json.status == 'error') {
					$('#purchase-form-message').show().html(json.msg).attr('class', 'note note-warning');
				} else if(json.status == 'success') {
					alert(json.msg);
				} else {
					$('#confirmModal').modal('hide');

					if(json.tools.added[0].id == 1) {
						$('#cmsPurchaseModal').modal('show');
					}
					else {
						$('#thankyouforpurchase').modal('show');
					}
				}
			}
		});
	});
});
	





//
//
//	var login= false;
//	checkpurchase();
//	function checkpurchase{
//		login?($('#login').hide(),$('#logout').show()):($('#login').show(),$('#logout').hide()
//	}
//	$('#form').submit(function(){
//		login= true;
//		checkpurchase();
//		$('#purchaseModal').model('hide');
//
//		var formdatatest=$("#form").serializeArray();
//		  $.ajax({
//			   type: "POST",
//			   url: "/your/url",
//			    data: formdatatest,
//			   success: function(html){    
//
//
//			   },
//			   error:function()
//			   {
//
//			   }
//			  });
//			return false;
//			});

//		 var url = $("#Video").attr('src');
//		    
//		    /* Assign empty url value to the iframe src attribute when
//		    modal hide, which stop the video playing */
//		    $("#myModal").on('hide.bs.modal', function(){
//		        $("#Video").attr('src', '');
//		    });
//		    
//		    /* Assign the initially stored url back to the iframe src
//		    attribute when modal is displayed again */
//		    $("#myModal").on('show.bs.modal', function(){
//		        $("#Video").attr('src', url);
//		    });
 
//	    $('#form')
//	        .bootstrapValidator({
//	            framework: 'bootstrap',
//	            icon: {
//	                valid: 'fa fa-check',
//	                invalid: 'fa fa-times',
//	                validating: 'fa fa-refresh'
//	            },
//	            fields: {
//	                cardType: {
//	                    validators: {
//	                        notEmpty: {
//	                            message: 'The type is required'
//	                        }
//	                    }
//	                },
//	                cc: {
//	                    validators: {
//	                        notEmpty: {
//	                            message: 'The credit card number is required'
//	                        },
//	                        creditCard: {
//	                            message: 'The credit card number is not valid'
//	                        }
//	                    }
//	                }
//	            }
//	        })
//	        .on('success.validator.fv', function(e, data) {
//	            // data.field     ---> The field name
//	            // data.validator ---> The validator name
//	            // data.fv        ---> The plugin instance
//
//	            // Whenever user changes the card type,
//	            // we need to revalidate the credit card number
//	            if (data.field === 'cardType') {
//	                data.fv.revalidateField('cc');
//	            }
//
//	            if (data.field === 'cc' && data.validator === 'creditCard') {
//	                // data.result.type can be one of
//	                // AMERICAN_EXPRESS, DINERS_CLUB, DINERS_CLUB_US, DISCOVER, JCB, LASER,
//	                // MAESTRO, MASTERCARD, SOLO, UNIONPAY, VISA
//
//	                var currentType = null;
//	                switch (data.result.type) {
//	                    case 'AMERICAN_EXPRESS':
//	                        currentType = 'Ae';         // Ae is the value of American Express card in the select box
//	                        break;
//
//	                    case 'MASTERCARD':
//	                    case 'DINERS_CLUB_US':
//	                        currentType = 'Master';     // Master is the value of Master card in the select box
//	                        break;
//
//	                    case 'VISA':
//	                        currentType = 'Visa';       // Visa is the value of Visa card in the select box
//	                        break;
//
//	                    default:
//	                        break;
//	                }
//
//	                // Get the selected type
//	                var selectedType = data.fv.getFieldElements('cardType').val();
//	                if (selectedType && currentType !== selectedType) {
//	                    // The credit card type doesn't match with the selected one
//	                    // Mark the field as not valid
//	                    data.fv.updateStatus('cc', data.fv.STATUS_INVALID, 'creditCard');
//	                }
//	            }
//	        });

	