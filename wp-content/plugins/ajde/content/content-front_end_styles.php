<?php
/*
	this file contain all the front end calendar styles
	assigned to a variable for echo or return on front-end
*/
$content.="<style>".get_option('evcal_styles')."
			.ajde_evcal_calendar a:hover{text-decoration:none}
			.ajde_evcal_calendar ul, #evcal_list{list-style:none; padding:0; margin:0 !important}
			.ajde_evcal_calendar li{margin-left:0px; list-style:none;}
			.ajde_evcal_calendar p{padding:0;margin-bottom:2px !important}
			.ajde_evcal_calendar {width:".$cal_width."px}
			
			.ajde_evcal_calendar .calendar_header p, #evcal_list li .evcal_cblock{font-family:'Helvetica Neue LT','arial narrow';}
			.ajde_evcal_calendar .calendar_header{padding:15px 0 15px; }
			.ajde_evcal_calendar .calendar_header p{height:36px;line-height:36px;margin:0 !important;font-size:36px;font-weight:normal;}
			#evcal_list li{padding-bottom:5px;}
			#evcal_list li .desc_trig{cursor:pointer}
			#evcal_list li a.evcal_list_a{display:block; width:100%; }
			#evcal_list a{text-decoration:none}	
			#evcal_list li .event_description p{float:none;}
			#evcal_list li .event_description .evcal_eventbrite, #evcal_list li .event_description .evcal_desc_top{padding:5px 8px;margin-top:4px;}
			#evcal_list li .event_description{font-style:italic}
			#evcal_list li p{float:left}
			
			.ajde_evcal_calendar .clear{clear:both}
			#evcal_list li .evcal_cblock{line-height:110%;height:30px;font-size:28px;color:#fff; margin:0 6px 0 0;padding:10px 10px 13px;
				-moz-border-radius: 5px; -webkit-border-radius: 5px;border-radius: 5px;}
			#evcal_list li .evcal_cblock span{line-height:100%;font-size:14px;vertical-align:super}
			#evcal_list li .evcal_desc{margin-left:5px; padding-top:3px;}
			#evcal_list li .evcal_desc span{display:block; color:#262626;font-family:arial;}
			#evcal_list li .evcal_desc .evcal_desc_info{font-size:10px;line-height:120%;color:#797979}
			#evcal_list li .evcal_desc .evcal_event_types{font-size:10px;line-height:110%;color:#797979}
			#evcal_list li .evcal_desc .evcal_event_types em{padding-right:3px;color:#9a9a9a;}
			#evcal_list li .evcal_desc em{color:#5b5b5b;font-weight:bold}				
			
			.ajde_evcal_calendar .calendar_header .evcal_arrows{cursor:pointer;}
		
			#evcal_sort_bar ul {list-style:none; margin:0;}
			#evcal_sort_bar ul li{float:left; list-style:none;}
			#evcal_sort_bar ul p{margin:0}
			#evcal_sort_bar ul a{cursor:pointer;display:block;}
			
			#evcal_list li .event_description .evcal_gmaps{width:".$cal_width."px; height:200px; margin-top:4px}
			
			#evcal_loader{background: url(".$evcal_plugin_url."/assets/spinner.gif) top center no-repeat;height:15px; width:15px;position:absolute; margin-top:78px; margin-left:10px}
		</style>";

?>