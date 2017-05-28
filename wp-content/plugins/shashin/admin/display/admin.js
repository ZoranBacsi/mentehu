jQuery(document).ready(function($) {
    $( "#shashinTabs" ).tabs();
	
	$("#regioLista").change(function(){
				
					$("#userUrl").val($("#regioLista option:selected").val())
				
	});
	
});


