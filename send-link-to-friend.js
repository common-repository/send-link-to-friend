(function ($) {
	'use strict';
	
	function prepareFormPostData_sltf(form, formData) {
		jQuery.each((form.serializeArray() || {}), function (i, field) {
			formData['sltf_' + field.name] = field.value;
		});
		return formData;
	}

	function loadResponse_sltf(response, form) {
		var status = response.status;

		var message_class = 'success';
		if(status === 'ERROR') {
			message_class = 'error';
		}

		var responseText = response['message_text'];
		var messageContainer = $(form).next('.sltf_form_message');
		messageContainer.attr('class', 'sltf_form_message ' + message_class);
		messageContainer.html(responseText);
		var esSuccessEvent = { 
			detail: { 
						sltf_response : message_class, 
						msg: responseText
					}, 
			bubbles: true, 
			cancelable: true 
		};

		jQuery(form).trigger('sltf_response', [ esSuccessEvent ]);
	}

	function SendLinkToFriendFun(form){
		var formData = {};
		formData = prepareFormPostData_sltf(form, formData);
		formData['sltf_submit'] = 'submitted';
		formData['action'] = 'send_link_to_friend';
		//alert(formData.toSource());
		var actionUrl = sltf_data.sltf_ajax_url;
		jQuery(form).find('#sltf-loading-image').show();
		$.ajax({
			type: 'POST',
			url: actionUrl,
			data: formData,
			dataType: 'json',
			success: function (response) {
				if( response && typeof response.status !== 'undefined' && response.status === "SUCCESS" ) {
					jQuery(form).slideUp('slow');
					jQuery(form).hide();
				} else {
					jQuery(form).find('#sltf-loading-image').hide();
				}
				jQuery(window).trigger('sltf_submit.send_response', [jQuery(form) , response]);
				loadResponse_sltf(response, form);
			},
			error: function (err) {
				//alert(err.toSource());
				//alert(JSON.stringify(err, null, 4));
				jQuery(form).find('#sltf-loading-image').hide();
				console.log(err, 'error');
			},
		});

		return false;
	}

	$(document).ready(function () {
		$(document).on('submit', '.sltf_form', function (e) {
			e.preventDefault();
			var form = $(this);
			SendLinkToFriendFun(form);
		});

	});

})(jQuery);


