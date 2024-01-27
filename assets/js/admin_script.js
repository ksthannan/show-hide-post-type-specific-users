(function($){
jQuery(document).ready(function($){

	$('#visible_for').select2({
		placeholder: 'Select user',
		allowClear: true
	});

	let itemsObj =  shpost_script_object.assigned_users ?  shpost_script_object.assigned_users : [];

	console.log('Existing Object: ' + itemsObj);

	$('#assign_user').on('click', function(e){
		
		e.preventDefault();

		let itemId = $('#visible_for').val();
		let post_id = $('#post_id').val();

		if(itemId){

			if( itemsObj.includes(itemId.toString()) == false ){

				$('<img src="'+shpost_admin_ajax_object.plugin_assets+'/img/ajax_loading.gif" class="ajax_loading_btn" height="30" width="30">').insertAfter(this);

				itemsObj.push(itemId);

				$('#visible_for_users').val(itemsObj);
				
				$.ajax({
					type: "POST",
					dataType: "html",
					url: shpost_admin_ajax_object.ajax_url,
					data: {
							action: 'shpost_user_selection',
							users: itemsObj,
							post_id: post_id,
						},
					success: function(response){
						// console.log(response);
						$('.user_items').html(response);
						$('.ajax_loading_btn').remove();
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log('AJAX Error: ' + textStatus + ' - ' + errorThrown);
					}

				});
			}else{

				$('<p class="small_notice">Already exists</p>').insertAfter(this);

				$('<img src="'+shpost_admin_ajax_object.plugin_assets+'/img/ajax_loading.gif" class="ajax_loading_btn" height="30" width="30">').insertAfter(this);
				
				setTimeout(function(){
					$('.small_notice').remove();
					$('.ajax_loading_btn').remove();
				}, 1000);

			}
			
			
		}
	});

	$('body').on('click', '.item_remove', function(){

		let itemId = $(this).parent().data('userid');
		let post_id = $('#post_id').val();

		if(itemsObj.includes(itemId.toString())){

			$(this).html('<img src="'+shpost_admin_ajax_object.plugin_assets+'/img/ajax_loading.gif" class="ajax_loading_btn" height="10" width="10">');

			let indexItem = itemsObj.indexOf(itemId.toString());

			itemsObj.splice(indexItem, 1);

			$('#visible_for_users').val(itemsObj);
		
			$.ajax({
				type: "POST",
				dataType: "html",
				url: shpost_admin_ajax_object.ajax_url,
				data: {
						action: 'shpost_user_selection',
						users: itemsObj,
						post_id: post_id,
					},
				success: function(response){
					// console.log(response);
					$('.user_items').html(response);
					$('.ajax_loading_btn').remove();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('AJAX Error: ' + textStatus + ' - ' + errorThrown);
				}

			});
		}	

		

	});

});
})(jQuery);