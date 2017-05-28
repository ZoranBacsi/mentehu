/*
	Javascript code that is associated with the front end of the calendar
*/
jQuery(document).ready(function($){
	
	
	var geocoder;
	var map;
	function initialize(map_canvas_id) {
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(-34.397, 150.644);
		var myOptions = {
		  zoom: 12,
		  center: latlng,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		}
		var map_canvas = document.getElementById(map_canvas_id);
		map = new google.maps.Map(map_canvas, myOptions);
	}

	
  
	function codeAddress(address, map_canvas_id) {
		//var address = document.getElementById("address").value;
		//var address = $("#address").val();
		geocoder.geocode( { 'address': address}, function(results, status) {
		  if (status == google.maps.GeocoderStatus.OK) {
			map.setCenter(results[0].geometry.location);
			var marker = new google.maps.Marker({
				map: map,
				position: results[0].geometry.location
			});
			//$('#'+map_canvas_id).height(200);
		  } else {
				$('#'+map_canvas_id).hide();
			//alert("Geocode was not successful for the following reason: " + status);
		  }
		});
	}
	
	//event full description
	$('.evcal_events_list').delegate('a.desc_trig', 'click', function(){
		var obj = $(this);
		
		obj.siblings('.event_description').slideToggle();
		
		if( obj.attr('gmap_trip')=='yes'){
			var address = obj.find('.evcal_location').attr('add_str');
			var map_canvas_id= obj.siblings('.event_description').find('.evcal_gmaps').attr('id');
			//obj.siblings('.event_description').find('.evcal_gmaps').html(address);
			initialize(map_canvas_id);
			codeAddress(address, map_canvas_id);
		}
	});
	
	
	//event sorting change
	$('.evcal_sort_bar').find('a').click(function(){
		var parent_bar = $(this).closest('div.evcal_sort_bar');
		var cal_head = parent_bar.siblings('.calendar_header');
		cal_id = $(this).closest('.ajde_evcal_calendar').attr('id');
		
		var sort_by = $(this).attr('id');
		var cur_m = parseInt( cal_head.attr('cur_m'));
		var cur_y = parseInt( cal_head.attr('cur_y'));
		
		ajax_post_content(cur_m,cur_y,sort_by,cal_id);
		parent_bar.find('a').removeClass('cur_sort');
		$(this).addClass('cur_sort');
		parent_bar.attr({'sort_by':sort_by});
	});
	
	
	
	// next month
	$('.evcal_btn_next').click(function(){
		var parent_bar = $(this).parent().siblings('div.evcal_sort_bar');
		var cal_head = parent_bar.siblings('.calendar_header');
		
		var sort_by=parent_bar.attr('sort_by');
		var cur_m = parseInt(cal_head.attr('cur_m'));
		var cur_y = parseInt(cal_head.attr('cur_y'));
		var next_m = (cur_m==12)?1:cur_m+1;
		var next_y = (cur_m==12)?cur_y+1:cur_y;
		
		cal_id = $(this).closest('.ajde_evcal_calendar').attr('id');
		
		ajax_post_content(next_m,next_y,sort_by, cal_id);
		
	});
	
	// previous month
	$('.evcal_btn_prev').click(function(){
		var parent_bar = $(this).parent().siblings('div.evcal_sort_bar');
		var cal_head = parent_bar.siblings('.calendar_header');
		
		var sort_by=parent_bar.attr('sort_by');
		var cur_m = parseInt( cal_head.attr('cur_m'));
		var cur_y = parseInt( cal_head.attr('cur_y'));
		var next_m = (cur_m==1)?12:cur_m-1;
		var next_y = (cur_m==1)?cur_y-1:cur_y;
		
		cal_id = $(this).closest('.ajde_evcal_calendar').attr('id');
		
		ajax_post_content(next_m,next_y,sort_by,cal_id);
		
	});
	
	function ajax_post_content(next_m, next_y,sort_by,cal_id){
		var ev_cal = $('#'+cal_id); // find the correct calendar
		ev_cal.find('.evcal_loader').show();
		
		event_type = ev_cal.find('.calendar_header').attr('ev_type');
		
		$.ajax({
			beforeSend: function(){
				ev_cal.find('.evcal_events_list').fadeOut('fast');
			},
			type: 'POST',
			url:the_ajax_script.ajaxurl,
			data: {	action:'the_ajax_hook',	next_m:next_m,	next_y:next_y,	sort_by:sort_by, event_type:event_type},
			dataType:'json',
			success:function(data){
				//alert(data);
				ev_cal.find('.evcal_events_list').html(data.content);
				ev_cal.find('#evcal_cur').html(data.new_month_year+', '+next_y);
				ev_cal.find('#evcal_head').attr({'cur_m':next_m,'cur_y':next_y});
				ev_cal.find('.evcal_loader').hide();
			},complete:function(){
				ev_cal.find('.evcal_events_list').delay(300).fadeIn();
			}
		});
		
		
	}
});