<?php
/*
	This is the loop of code used for single event
*/
	
	$ed_val=$sd_val=$evb_code= $event_type_html= '';// blank variables
	$p_id = get_the_ID();
	$ev_vals = get_post_custom($p_id);
	
	// Gather Initial event meta data from post meta
	// "es" = event start
	// "ee" = event end
	$es_month_s =	$ev_vals['evcal_start_month_s'][0];	// month name
	$ee_month_s =	$ev_vals['evcal_end_month_s'][0];
	
	$es_month_n =	$ev_vals['evcal_start_month_n'][0];	// month number
	$ee_month_n =	$ev_vals['evcal_end_month_n'][0];	
	
	$es_day_n =	$ev_vals['evcal_start_day_num'][0];	// day number 
	$ee_day_n =	$ev_vals['evcal_end_day_num'][0];		
	
	$row_st_date = $et_row = $ev_vals['evcal_srow'][0];
	$row_et_date = $ee_row = $ev_vals['evcal_erow'][0];
	
	$st_date_val =$ev_vals['evcal_start_date'][0]; // eg 4/12/2012
	$et_date_val =$ev_vals['evcal_end_date'][0];
	$et_date_val =(isset($et_date_val))? $et_date_val :$st_date_val; // if end date is empty
	
		
	
	//filter future events
	$future_event = ($row_et_date > time() )? true:false;	
	if(	( (get_option('evcal_cal_show_past')=='no' ) && $future_event )
		|| ( (get_option('evcal_cal_show_past')=='yes' ) || (get_option('evcal_cal_show_past')=='' ))
	):		
		
		
		// filter events that does not belong to the currently focused month		
		if(
			($row_st_date<=$focus_month_beg_range && $row_et_date>=$focus_month_beg_range) ||
			($row_st_date<=$focus_month_end_range && $row_et_date>=$focus_month_end_range) ||
			($focus_month_beg_range<=$row_st_date && $row_st_date<=$focus_month_end_range && $row_et_date=='') ||		
			($focus_month_beg_range<=$row_st_date && $row_st_date<=$focus_month_end_range && $row_et_date==$row_st_date) 	||
			($focus_month_beg_range<=$row_st_date && $row_st_date<=$focus_month_end_range && $row_et_date!=$row_st_date) 	
		):
		
			// check if the event is all day event
			$alldayevent = $ev_vals['evcal_allday'][0];
			
			if($alldayevent!='yes'){
				//multi day event - show month name or not
				if($et_date_val!='' && $row_st_date< $row_et_date){
					// event with different start and end months
					if(($row_et_date> $focus_month_beg_range && $row_st_date<$focus_month_beg_range) ||
						($row_st_date<$focus_month_beg_range && $row_et_date>$focus_month_end_range))
					{
						$sd_val=$es_month_s.' '.$es_day_n.ajde_evcal_formate_date($st_date_val,'S').' ';
						$ed_val=$ee_month_s.' '.$ee_day_n.ajde_evcal_formate_date($et_date_val,'S').' ';
					
					}elseif($row_st_date<$focus_month_end_range && $row_et_date>$focus_month_end_range){
						$ed_val=$ee_month_s.' '.$ee_day_n.ajde_evcal_formate_date($et_date_val,'S').' ';
					}
					$evdate_html = $es_day_n.'<span> - '.$ee_day_n.'</span>';
				}else{	$evdate_html = $es_day_n;	}
				
			
				//start time
				$st_hr =$ev_vals['evcal_start_time_hour'][0];
				$st_min =$ev_vals['evcal_start_time_min'][0];
				$st_ampm =$ev_vals['evcal_st_ampm'][0];
				
				//end time
				$et_hr =$ev_vals['evcal_end_time_hour'][0];
				$et_min =$ev_vals['evcal_end_time_min'][0];
				$et_ampm =$ev_vals['evcal_et_ampm'][0];	
				
				// get from and to date and time for event
				$from_time = $st_hr.':'.$st_min.$st_ampm;
				$to_time = $et_hr.':'.$et_min.$et_ampm;
				
				if( empty($st_hr)){ $from_time = null;}
				if( empty($et_hr)) { $to_time = null;}
				
				
				$time_mid_connector = ( !empty($from_time) || !empty($sd_val) )?' - ':null;
					
				$from_to_time = $sd_val.$from_time. $time_mid_connector .$ed_val.$to_time;
				
			}else{ //an all day event				
				$start_day = $ev_vals['evcal_start_day'][0];
				$from_to_time = "<em class='evcal_alldayevent_text'>(All Day: ".$evcal_day_is[$start_day].")</em>";
				$evdate_html = $es_day_n;
			}			
			
			//get event location if its event location is availble
			$ev_location = ($ev_vals['evcal_location'][0]!='')?
				'<em class="evcal_location" add_str="'.$ev_vals['evcal_location'][0].'">at '.$ev_vals['evcal_location'][0].'</em>'
				:null;
			
			//descriptoin for the event
			$event_full_description =get_the_content();
			if($event_full_description==''){ $event_full_description =$ev_vals['evcal_description'][0]; }
			if($event_full_description!=''){
				$event_full_description ="<div class='evcal_desc_top'>".apply_filters('the_content',$event_full_description).'</div>';			
			}else{	$event_full_description='';	}			
			
			//eventbrite info
			$evb_id = (isset( $ev_vals['evcal_evb_id'][0] ))? $ev_vals['evcal_evb_id'][0]: null;
			$evb_api = get_option('evcal_evb_api');
			
			if($evb_id!=''): //check eventbrite event id first
			if((get_option('evcal_evb_events')=='yes')  && !empty($evb_api) ){
				
				$xml =simplexml_load_file('http://www.eventbrite.com/xml/event_get?app_key='.$evb_api.'&id='.$evb_id);					
				if($xml->getName()!='error'):						
					$evb_end_time =strtotime($xml->end_date);
					if($evb_end_time>current_time(timestamp)):// if eventbrite registration time hasnt expired
									
						$ticket_obj = $xml->tickets->ticket;	$tix_price =$ticket_obj->price;		$evb_capacity = $xml->capacity;
						
						//event capacity
						$evb_code.= ($evb_capacity!='0')?"<p class='evcal_desc_capacity'>Event Capacity: ".$evb_capacity."</p>":null;
						
						//event is not free
						if($tix_price!='0.00'){
							$evb_code.="<p class='evcal_desc_tix_price'>Ticket Price: ".$ticket_obj->currency.' '.$ticket_obj->price.'</p>';
						}

						//event ticket purchase link
						$buy_tix_win = ($ev_vals['evcal_evb_buy_tix_win'][0]=='yes')?'target="_blank"':null;
						$evb_code.= ($ev_vals["evcal_evb_buy_tix"][0]=='yes')? "<p class='evcal_desc_buy_tix'><a ".$buy_tix_win." href='http://www.eventbrite.com/event/".$evb_id."'>Buy a Ticket</a></p>":null;
					
						$evb_code= "<div class='evcal_eventbrite'>".$evb_code.'</div';
					endif;
				endif;
			}
			endif;
			
			//google maps
			if($ev_vals['evcal_location'][0]!='' && ($ev_vals['evcal_gmap_gen'][0]=='yes')){
				$map_code ="<div class='evcal_gmaps' id='".$p_id."_gmap'></div>";
			}else{$map_code=null;}
				
			if(!empty($event_full_description) || !empty($map_code) || !empty($evb_code)){
				$event_desc_code = "<div class='event_description' style='display:none'>".$event_full_description.$map_code.$evb_code."</div>";
			}else{$event_desc_code=null;}
			
			$event_description_trigger = ($event_desc_code!='')? "desc_trig":null;
			$gmap_trigger = ($ev_vals['evcal_gmap_gen'][0]=='yes')? 'gmap_trip="yes"':null;
			
			//event color			
			$event_color = ($ev_vals['evcal_event_color'][0] !='' )? 
				$ev_vals['evcal_event_color'][0] : $default_event_color;
				
			//event type taxonomies
			$evcal_terms = wp_get_post_terms($p_id,'event_type');
				if($evcal_terms){					
					$event_type_html .="<span class='evcal_event_types'><em>Event Type:</em>";
					foreach($evcal_terms as $termA):
						$event_type_html .="<em>".$termA->name."</em>";
					endforeach; $event_type_html .="</span>";
				}
			
			//Construct actual event HTML code
			$content_li.="<li>
			<a id='".get_the_ID()."' style='border-left:3px solid ".$event_color."' class='evcal_list_a ".$event_description_trigger."' ".$gmap_trigger.">
				<p class='evcal_cblock' style='background-color:".$event_color."' bgcolor='".$event_color."'>".$evdate_html."</p>					
				<p class='evcal_desc'><span class='evcal_desc_info'>".$from_to_time." ".$ev_location."</span>".$event_type_html."					
				<span class='evcal_desc2'>".get_the_title('')."</span></p>
				<div class='clear'></div>
			</a>".$event_desc_code."</li><li class='clear'></li>";			
			
		endif; // this month event filter
	endif; //future event filter
	
?>