	jQuery(document).ready(function($) {
	    $('.js-example-basic-multiple').select2();
	    width: 'resolve'
	    theme: 'classic'

        
        $('.select_page_tr').hide();
	    $('.select_position_tr').hide();
	    $('.max_enable').click(function(){
	        if($(this).is(":checked")){
	            $('.select_page_tr').show();
	            $('.select_position_tr').show();
	        }
	        else if($(this).is(":not(:checked)")){
	            $('.select_page_tr').hide();
	            $('.select_position_tr').hide();
	        }
        });

        
	    if ($(".max_enable").is(':checked')){
            $('.select_page_tr').show();
	        $('.select_position_tr').show();
        }else{
        	$('.select_page_tr').hide();
	            $('.select_position_tr').hide();
        }


	});