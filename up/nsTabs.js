// JavaScript Document
/* --------------------------------------
//
// nsTab2.js
//
//
//
-------------------------------------- */
function changeTab(tabid, tab_arr, li_arr) {
	for(i = 0; i < tab_arr.length; i++) {
		if(tab_arr[i] == tabid) {
			document.getElementById(tab_arr[i]).style.display = "block";
			document.getElementById(li_arr[i]).className = "cr";
		} else {
			if(document.getElementById(tab_arr[i])) {
				document.getElementById(tab_arr[i]).style.display = "none";
				document.getElementById(li_arr[i]).className = "";
			}
		}
	}
}
function changeTab2(tabid, tab_arr, li_arr) {
	for(i = 0; i < tab_arr.length; i++) {
		if(tab_arr[i] == tabid) {
			document.getElementById(tab_arr[i]).style.display = "block";
			document.getElementById(li_arr[i]).className = "cr";
		} else {
			if(document.getElementById(tab_arr[i])) {
				document.getElementById(tab_arr[i]).style.display = "none";
				document.getElementById(li_arr[i]).className = "";
			}
		}
	}
}
function changeTab3(tabid, tab_arr, li_arr) {
	for(i = 0; i < tab_arr.length; i++) {
		if(tab_arr[i] == tabid) {
			document.getElementById(tab_arr[i]).style.display = "block";
			document.getElementById(li_arr[i]).className = "cr";
		} else {
			if(document.getElementById(tab_arr[i])) {
				document.getElementById(tab_arr[i]).style.display = "none";
				document.getElementById(li_arr[i]).className = "";
			}
		}
	}
}