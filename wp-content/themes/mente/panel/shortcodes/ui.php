<?php
$page = htmlentities($_GET['page']);

?>
<!DOCTYPE html>
<head>
	<script type="text/javascript" src="../../../../../wp-admin/load-scripts.php?c=1&load=jquery"></script>
	<script type="text/javascript" src="../../../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<link rel='stylesheet' href='shortcodes.css' type='text/css' media='all' />
<?php
if( $page == 'box' ){
?>
	<script type="text/javascript">
		var AddBoxes = {
			e: '',
			init: function(e) {
				AddBoxes.e = e;
				tinyMCEPopup.resizeToInnerSize();
			},
			insert: function createGalleryShortcode(e) {

				var BoxType = jQuery('#BoxType').val();
				var Boxalign = jQuery('#Boxalign').val();
				var BoxClass = jQuery('#BoxClass').val();
				var BoxWidth = jQuery('#BoxWidth').val();
				var BoxContent = jQuery('#BoxContent').val();

				var output = '[box ';
		
				if(BoxType) {
					output += 'type="'+BoxType+'" ';
				}
				if(Boxalign) {
					output += 'align="'+Boxalign+'" ';
				}
				if(BoxClass) {
					output += 'class="'+BoxClass+'" ';
				}
				if(BoxWidth) {
					output += 'width="'+BoxWidth+'" ';
				}

				output += ']'+BoxContent+'[/box]';
				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
				
			}
		}
		tinyMCEPopup.onInit.add(AddBoxes.init, AddBoxes);

	</script>
	<title>Add Box</title>

</head>
<body>
<form id="GalleryShortcode">
	<p>
		<label for="BoxType">Type of the box :</label>
		<select id="BoxType" name="BoxType">
			<option value="shadow">Shadow</option>
			<option value="info">Info</option>
			<option value="success">Success</option>
			<option value="warning">Warning</option>
			<option value="error">Error</option>
			<option value="download">Download</option>
			<option value="note">Note</option>
		</select>
	</p>
	<p>
		<label for="Boxalign">Box alignment :</label>
		<select id="Boxalign" name="Boxalign">
			<option value=""></option>
			<option value="alignright">right</option>
			<option value="alignleft">left</option>
			<option value="aligncenter">Center</option>
		</select>
	</p>
	<p>
		<label for="BoxClass">Custom Class :</label>
		<input id="BoxClass" name="BoxClass" type="text" value="" />
	</p>
	</p>
	<p>
		<label for="BoxWidth">Box Width :</label>
		<input id="BoxWidth" name="BoxWidth" type="text" value="" />
	</p>
	<p>
		<label for="BoxContent">Content : </label>
		<textarea id="BoxContent" name="BoxContent" col="20"></textarea>
	</p>
	<p><a class="add" href="javascript:AddBoxes.insert(AddBoxes.e)">insert into post</a></p>
</form>

<?php
} elseif( $page == 'buttons' ){
 ?>
 	<script type="text/javascript">
		var AddButtons = {
			e: '',
			init: function(e) {
				AddButtons.e = e;
				tinyMCEPopup.resizeToInnerSize();
			},
			insert: function createGalleryShortcode(e) {

				var ButtonColor = jQuery('#ButtonColor').val();
				var Buttonsize = jQuery('#Buttonsize').val();
				var Buttonlink = jQuery('#Buttonlink').val();
				var Buttontext = jQuery('#Buttontext').val();
				var Buttontarget = jQuery('#Buttontarget').val();

				var output = '[button ';
				
				if(ButtonColor) {
					output += 'color="'+ButtonColor+'" ';
				}
				if(Buttonsize) {
					output += 'size="'+Buttonsize+'" ';
				}
				if(Buttonlink) {
					output += 'link="'+Buttonlink+'" ';
				}
				if(Buttontarget) {
					output += 'target="blank" ';
				}


				output += ']'+Buttontext+'[/button]';
				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
				
			}
		}
		tinyMCEPopup.onInit.add(AddButtons.init, AddButtons);

	</script>
	<title>Add Buttons</title>

</head>
<body>
<form id="GalleryShortcode">
	<p>
		<label for="ButtonColor">Type of the box :</label>
		<select id="ButtonColor" name="ButtonColor">
			<option value="red">Red</option>
			<option value="orange">Orange</option>
			<option value="blue">Blue</option>
			<option value="green">Green</option>
			<option value="black">Black</option>
			<option value="gray">Gray</option>
			<option value="white">White</option>
			<option value="pink">Pink</option>
			<option value="purple ">Purple</option>
		</select>
	</p>
	<p>
		<label for="Buttonsize">Button Size :</label>
		<select id="Buttonsize" name="Buttonsize">
			<option value="small">Small</option>
			<option value="medium">Medium</option>
			<option value="big">Big</option>
		</select>
	</p>
	<p>
		<label for="Buttonlink">Button Link :</label>
		<input id="Buttonlink" name="Buttonlink" type="text" value="http://" />
	</p>
	<p>
		<label for="Buttontarget">Open Link in a new window : </label>
		<input id="Buttontarget" name="Buttontarget" type="checkbox" value="true" />
	</p>
	</p>
	<p>
		<label for="Buttontext">Button Text :</label>
		<input id="Buttontext" name="Buttontext" type="text" value="" />
	</p>

	<p><a class="add" href="javascript:AddButtons.insert(AddButtons.e)">insert into post</a></p>
</form>
  
<?php } elseif( $page == 'author' ){ ?>
	<script type="text/javascript">
		var Author = {
			e: '',
			init: function(e) {
				Author.e = e;
				tinyMCEPopup.resizeToInnerSize();
			},
			insert: function createGalleryShortcode(e) {

				var image = jQuery('#image').val();
				var Content = jQuery('#Content').val();


				var output = '[author ';
				
				if(image) {
					output += 'image="'+image+'" ';
				}



				output += ']'+Content+'[/author]';
				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
				
			}
		}
		tinyMCEPopup.onInit.add(Author.init, Author);

	</script>
	<title>Author Bio</title>

</head>
<body>
<form id="GalleryShortcode">
	<p>
		<label for="image">Author Image URL</label>
		<input id="image" name="image" type="text" value="" />
	</p>
	<p>
		<label for="Content">Content : </label>
		<textarea id="Content" name="Content" col="20"></textarea>
	</p>
	
	<p><a class="add" href="javascript:Author.insert(Author.e)">insert into post</a></p>
</form>

<?php } elseif( $page == 'flickr' ){ ?>

	<script type="text/javascript">
		var AddFlickr = {
			e: '',
			init: function(e) {
				AddFlickr.e = e;
				tinyMCEPopup.resizeToInnerSize();
			},
			insert: function createGalleryShortcode(e) {

				var FlickrID = jQuery('#FlickrID').val();
				var Photos = jQuery('#Photos').val();
				var PhotosOrder = jQuery('#PhotosOrder').val();


				var output = '[flickr ';
				
				if(FlickrID) {
					output += 'id="'+FlickrID+'" ';
				}
				if(Photos) {
					output += 'number="'+Photos+'" ';
				}
				if(PhotosOrder) {
					output += 'orderby="'+PhotosOrder+'" ';
				}

				output += ']';
				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
				
			}
		}
		tinyMCEPopup.onInit.add(AddFlickr.init, AddFlickr);

	</script>
	<title>Add Photos From Flickr</title>

</head>
<body>
<form id="GalleryShortcode">
	<p>
		<label for="FlickrID">Your account Id</label>
		<input id="FlickrID" name="FlickrID" type="text" value="" />
		<small>(get it from <a href="http://www.idgettr.com">idGettr</a>)</small>

	</p>
	<p>
		<label for="Photos">Number of photos :</label>
		<input id="Photos" name="Photos" type="text" value="" />

	</p>
	<p>
		<label for="PhotosOrder">Sorting : </label>
		<select id="PhotosOrder" name="PhotosOrder">
			<option value="latest">Most recent</option>
			<option value="random">Random selection</option>
		</select>
	</p>
	<p><a class="add" href="javascript:AddFlickr.insert(AddFlickr.e)">insert into post</a></p>
	</form>

<?php } elseif( $page == 'audio' ){ ?>

	<script type="text/javascript">
		var AddAudio = {
			e: '',
			init: function(e) {
				AddAudio.e = e;
				tinyMCEPopup.resizeToInnerSize();
			},
			insert: function createGalleryShortcode(e) {

				var mp3Url = jQuery('#mp3Url').val();
				var m4aUrl = jQuery('#m4aUrl').val();
				var oggUrl = jQuery('#oggUrl').val();

				var output = '[audio ';
				
				if(mp3Url) {
					output += 'mp3="'+mp3Url+'" ';
				}				
				if(m4aUrl) {
					output += 'm4a="'+m4aUrl+'" ';
				}				
				if(oggUrl) {
					output += 'ogg="'+oggUrl+'" ';
				}

				output += ']';
				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
				
			}
		}
		tinyMCEPopup.onInit.add(AddAudio.init, AddAudio);

	</script>
	<title>Add Mp3 Player</title>

</head>
<body>
<form id="GalleryShortcode">
	<p>
		<label for="mp3Url">Mp3 file Url : </label>
		<input id="mp3Url" name="mp3Url" type="text" value="" />
	</p>
	<p>
		<label for="m4aUrl">M4A file Url : </label>
		<input id="m4aUrl" name="m4aUrl" type="text" value="" />
	</p>
	<p>
		<label for="oggUrl">OGG file Url : </label>
		<input id="oggUrl" name="oggUrl" type="text" value="" />
	</p>

	
	<p><a class="add" href="javascript:AddAudio.insert(AddAudio.e)">insert into post</a></p>
</form>

<?php } elseif( $page == 'tabs' ){ ?>

	<script type="text/javascript">
		var Author = {
			e: '',
			init: function(e) {
				Author.e = e;
				tinyMCEPopup.resizeToInnerSize();
			},
			insert: function createGalleryShortcode(e) {


			
				var output = "[tabs ";
				var Type = jQuery('#Type').val();
				
				if(Type) {
					output += 'type="'+Type+'"';
				}
				
				output += "][tabs_head]";
				
				 jQuery("input[id^=tab_title]").each(function() {
					output +='[tab_title]'+jQuery(this).val()+'[/tab_title]';
				});
				
   				output += "[/tabs_head]";

				 jQuery("textarea[id^=Content]").each(function() {
					output +='[tab]'+jQuery(this).val()+'[/tab]';
				});
				


				output += '[/tabs]';
				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
				
			}
		}
		tinyMCEPopup.onInit.add(Author.init, Author);

		jQuery(document).ready(function() {
			jQuery("#add-tab").click(function() {
				jQuery('#TabsShortcodeContent').append('<p><label for="tab_title[]">Tab Title</label><input id="tab_title[]" name="tab_title[]" type="text" value="" /></p><p><label for="Content[]">Tab Content</label><textarea  style="height:100px;  width:400px;" id="Content[]" name="Content[]" type="text" value=""></textarea></p>	<hr style="border-bottom: 1px solid #FFF;border-top: 1px solid #ccc; border-left:0; border-right:0;" />');
			});
		});
		
	</script>
	<title>Add Tabbed Content</title>

</head>
<body>
<form id="TabsShortcode">
<div id="TabsShortcodeContent">
	<p>
		<label for="Type">Type : </label>
		<select id="Type" name="Type">
			<option value="horizontal">Horizontal</option>
			<option value="vertical">Vertical</option>
		</select>
	</p>
	<hr style="border-bottom: 1px solid #FFF;border-top: 1px solid #ccc; border-left:0; border-right:0;" />
	<p>
		<label for="tab_title[]">Tab Title</label>
		<input id="tab_title[]" name="tab_title[]" type="text" value="" />
	</p>
	<p>
		<label for="Content[]">Tab Content</label>
		<textarea style="height:100px; width:400px;" id="Content[]" name="Content[]" type="text" value="" ></textarea>
	</p>
	<hr style="border-bottom: 1px solid #FFF;border-top: 1px solid #ccc; border-left:0; border-right:0;" />
</div>
	<strong><a style="cursor: pointer;" id="add-tab">+ Add Tab</a></strong>
	<p><a class="add" href="javascript:Author.insert(Author.e)">insert into post</a></p>
</form>

<?php } elseif( $page == 'toggle' ){ ?>

	<script type="text/javascript">
		var toggle = {
			e: '',
			init: function(e) {
				toggle.e = e;
				tinyMCEPopup.resizeToInnerSize();
			},
			insert: function createGalleryShortcode(e) {

				var Title = jQuery('#Title').val();
				var state = jQuery('#state').val();
				var Content = jQuery('#Content').val();


				var output = '[toggle ';
				
				if(Title) {
					output += 'title="'+Title+'" ';
				}
				if(state) {
					output += 'state="'+state+'" ';
				}


				output += ']'+Content+'[/toggle]';
				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
				
			}
		}
		tinyMCEPopup.onInit.add(toggle.init, toggle);

	</script>
	<title>Add Toggle</title>

</head>
<body>
<form id="GalleryShortcode">
	<p>
		<label for="Title">Title</label>
		<input id="Title" name="Title" type="text" value="" />

	</p>
	<p>
		<label for="state">State : </label>
		<select id="state" name="state">
			<option value="open">open</option>
			<option value="close">close</option>
		</select>
	</p>
	<p>
		<label for="Content">Content : </label>
		<textarea id="Content" name="Content" col="20"></textarea>
	</p>
	
	
	<p><a class="add" href="javascript:toggle.insert(toggle.e)">insert into post</a></p>
</form>

<?php } elseif( $page == 'twitter' ){ ?>

	<script type="text/javascript">
		var AddTwitter = {
			e: '',
			init: function(e) {
				AddTwitter.e = e;
				tinyMCEPopup.resizeToInnerSize();
			},
			insert: function createGalleryShortcode(e) {

				var TwitterID = jQuery('#TwitterID').val();
				var tweets = jQuery('#tweets').val();
				var avatar = jQuery('#avatar').val();


				var output = '[twitter ';
				
				if(TwitterID) {
					output += 'id="'+TwitterID+'" ';
				}
				if(tweets) {
					output += 'number="'+tweets+'" ';
				}
				if(avatar) {
					output += 'avatar="true" ';
				}

				output += ']';
				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
				
			}
		}
		tinyMCEPopup.onInit.add(AddTwitter.init, AddTwitter);

	</script>
	<title>Add your Latest Tweets</title>

</head>
<body>
<form id="GalleryShortcode">
	<p>
		<label for="TwitterID">Username</label>
		<input id="TwitterID" name="TwitterID" type="text" value="" />

	</p>
	<p>
		<label for="tweets">Number of tweets :</label>
		<input id="tweets" name="tweets" type="text" value="" />

	</p>
	<p>
		<label for="avatar">Display avatar : </label>
		<input id="avatar" name="avatar" type="checkbox" value="true" />
	</p>
	
	<p><a class="add" href="javascript:AddTwitter.insert(AddTwitter.e)">insert into post</a></p>
</form>

<?php } elseif( $page == 'feeds' ){ ?>

	<script type="text/javascript">
		var AddTwitter = {
			e: '',
			init: function(e) {
				AddTwitter.e = e;
				tinyMCEPopup.resizeToInnerSize();
			},
			insert: function createGalleryShortcode(e) {

				var feedurl = jQuery('#feedurl').val();
				var numberFeeds = jQuery('#numberFedds').val();

				var output = '[feed ';
				
				if(feedurl) {
					output += 'url="'+feedurl+'" ';
				}
				if(numberFeeds) {
					output += 'number="'+numberFeeds+'" ';
				}

				output += ']';
				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
				
			}
		}
		tinyMCEPopup.onInit.add(AddTwitter.init, AddTwitter);

	</script>
	<title>Display Feeds</title>

</head>
<body>
<form id="GalleryShortcode">
	<p>
		<label for="feedurl">URI of the RSS feed</label>
		<input id="feedurl" name="feedurl" type="text" value="" />

	</p>
	<p>
		<label for="numberFedds">Number of Feeds :</label>
		<select id="numberFedds" name="numberFedds" style="width:50px;">
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
			<option value="11">11</option>
			<option value="12">12</option>
			<option value="13">13</option>
			<option value="14">14</option>
			<option value="15">15</option>
			<option value="16">16</option>
			<option value="17">17</option>
			<option value="18">18</option>
			<option value="19">19</option>
			<option value="20">20</option>
			<option value="21">21</option>
			<option value="22">22</option>
			<option value="23">23</option>
			<option value="24">24</option>
			<option value="25">25</option>
			<option value="26">26</option>
			<option value="27">27</option>
			<option value="28">28</option>
			<option value="29">29</option>
			<option value="30">30</option>
		</select>
	</p>
	
	<p><a class="add" href="javascript:AddTwitter.insert(AddTwitter.e)">insert into post</a></p>
</form>

<?php } elseif( $page == 'lightbox' ){ ?>

	<script type="text/javascript">
		var Addlightbox = {
			e: '',
			init: function(e) {
				Addlightbox.e = e;
				tinyMCEPopup.resizeToInnerSize();
			},
			insert: function createGalleryShortcode(e) {

				var ImgUrl = jQuery('#ImgUrl').val();
				var ImgTitle = jQuery('#ImgTitle').val();
				var ImgContent = jQuery('#ImgContent').val();


				var output = '[lightbox';
				
				if(ImgUrl) {
					output += ' full="'+ImgUrl+'"';
				}
				if(ImgTitle) {
					output += ' title="'+ImgTitle+'"';
				}

				output += ']'+ ImgContent + '[/lightbox]';
				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
				
			}
		}
		tinyMCEPopup.onInit.add(Addlightbox.init, Addlightbox);

	</script>
	<title>Add LightBox</title>

</head>
<body>
<form id="GalleryShortcode">
	<p>
		<label for="ImgUrl">Full Image or Youtube / Vimeo Video URL : </label>
		<input id="ImgUrl" name="ImgUrl" type="text" value="http://" />

	</p>
	<p>
		<label for="ImgTitle">Title : </label>
		<input id="ImgTitle" name="ImgTitle" type="text" value="" />

	</p>
	<p>
		<label for="ImgContent">Content : </label>
		<textarea id="ImgContent" name="ImgContent" cols="45" rows="5" ></textarea>
	</p>
	
	<p><a class="add" href="javascript:Addlightbox.insert(Addlightbox.e)">insert into post</a></p>
</form>

<?php } elseif( $page == 'video' ){ ?>

	<script type="text/javascript">
		var AddVideo = {
			e: '',
			init: function(e) {
				AddVideo.e = e;
				tinyMCEPopup.resizeToInnerSize();
			},
			insert: function createGalleryShortcode(e) {

				var VideoUrl = jQuery('#VideoUrl').val();
				var width = jQuery('#width').val();
				var height = jQuery('#height').val();


				var output = '[video ';
				
				if(width) {
					output += 'width="'+width+'" ';
				}
				if(height) {
					output += 'height="'+height+'" ';
				}

				output += ']'+ VideoUrl + '[/video]';
				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
				
			}
		}
		tinyMCEPopup.onInit.add(AddVideo.init, AddVideo);

	</script>
	<title>Add Video</title>

</head>
<body>
<form id="GalleryShortcode">
	<p>
		<label for="VideoUrl" style="width:230px">Youtube / Vimeo / Dailymotion Video Url : </label>
		<input id="VideoUrl" name="VideoUrl" type="text" value="http://" />

	</p>
	<p>
		<label for="width">Width :</label>
		<input style="width:40px;" id="width" name="width" type="text" value="" />
	</p>
	<p>
		<label for="height">Height :</label>
		<input style="width:40px;"  id="height" name="height" type="text" value="" />
	</p>

	
	<p><a class="add" href="javascript:AddVideo.insert(AddVideo.e)">insert into post</a></p>
</form>


<?php } elseif( $page == 'googlemap' ){ ?>

	<script type="text/javascript">
		var googlemap = {
			e: '',
			init: function(e) {
				googlemap.e = e;
				tinyMCEPopup.resizeToInnerSize();
			},
			insert: function createGalleryShortcode(e) {

				var MapUrl = jQuery('#MapUrl').val();
				var width = jQuery('#width').val();
				var height = jQuery('#height').val();
				var align = jQuery('#Mapalign').val();


				var output = '[googlemap ';
				
				if(MapUrl) {
					output += 'src="'+MapUrl+'" ';
				}
				if(width) {
					output += 'width="'+width+'" ';
				}
				if(height) {
					output += 'height="'+height+'" ';
				}
				if(align) {
					output += 'align="'+align+'" ';
				}

				output += ']';
				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
				
			}
		}
		tinyMCEPopup.onInit.add(googlemap.init, googlemap);

	</script>
	<title>Insert Google Map</title>

</head>
<body>
<form id="GalleryShortcode">
	<p>
		<label for="MapUrl">Map Url : </label>
		<input id="MapUrl" name="MapUrl" type="text" value="http://" />

	</p>
	<p>
		<label for="Mapalign">Map alignment :</label>
		<select id="Mapalign" name="Mapalign">
			<option value=""></option>
			<option value="alignright">right</option>
			<option value="alignleft">left</option>
			<option value="aligncenter">Center</option>
		</select>
	</p>
	<p>
		<label for="width">Width :</label>
		<input style="width:40px;" id="width" name="width" type="text" value="" />
	</p>
	<p>
		<label for="height">Height :</label>
		<input style="width:40px;"  id="height" name="height" type="text" value="" />
	</p>

	
	<p><a class="add" href="javascript:googlemap.insert(googlemap.e)">insert into post</a></p>
</form>

<?php } elseif( $page == 'tooltip' ){ ?>

	<script type="text/javascript">
		var toggle = {
			e: '',
			init: function(e) {
				toggle.e = e;
				tinyMCEPopup.resizeToInnerSize();
			},
			insert: function createGalleryShortcode(e) {

				var Text = jQuery('#Text').val();
				var gravity = jQuery('#Gravities').val();
				var Content = jQuery('#Content').val();


				var output = '[tooltip ';
				
				if(Text) {
					output += 'text="'+Text+'"';
				}
				if(gravity) {
					output += ' gravity="'+gravity+'"';
				}



				output += ']'+Content+'[/tooltip]';
				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
				
			}
		}
		tinyMCEPopup.onInit.add(toggle.init, toggle);

	</script>
	<title>Add Tooltip</title>

</head>
<body>
<form id="GalleryShortcode">
	<p>
		<label for="Text">Text</label>
		<input id="Text" name="Text" type="Text" value="" />
	</p>
	<p>
		<label for="Gravities">Gravities :</label>
		<select id="Gravities" name="Gravities">
			<option value="nw">Northwest</option>
			<option value="n">North</option>
			<option value="ne">Northeast</option>
			<option value="w">West</option>
			<option value="e">East</option>
			<option value="sw">Southwest</option>
			<option value="s">South</option>
			<option value="se">Southeast</option>
		</select>
	</p>
	<p>
		<label for="Content">Content : </label>
		<textarea id="Content" name="Content" col="20"></textarea>
	</p>
	
	
	<p><a class="add" href="javascript:toggle.insert(toggle.e)">insert into post</a></p>
</form>

<?php } elseif( $page == 'share' ){ ?>


	<script type="text/javascript">
		var toggle = {
			e: '',
			init: function(e) {
				toggle.e = e;
				tinyMCEPopup.resizeToInnerSize();
			},
			insert: function createGalleryShortcode(e) {
				var output = '';

				var Facebook = jQuery('#Facebook');
				if(Facebook.is(":checked")) {
					output += '[facebook]';
				}
				var tweet = jQuery('#tweet');
				if(tweet.is(":checked")) {
					output += '[tweet]';
				}
				var digg = jQuery('#digg');
				if(digg.is(":checked")) {
					output += '[digg]';
				}
				var stumble = jQuery('#stumble');
				if(stumble.is(":checked")) {
					output += '[stumble]';
				}
				var Google = jQuery('#Google');
				if(Google.is(":checked")) {
					output += '[Google]';
				}
				var feedburner = jQuery('#feedburner');
				var feedburnerName = jQuery('#feedburnerName').val();
				if(feedburner.is(":checked")) {
					output += '[feedburner name="'+feedburnerName+'"]';
				}
				var pinterest = jQuery('#pinterest');
				if(pinterest.is(":checked")) {
					output += '[pinterest]';
				}

				var twitterFollow = jQuery('#twitterFollow');
				var TwitterID = jQuery('#TwitterID').val();
				var Large = jQuery('#Large');
				var count = jQuery('#count');
				
				if(twitterFollow.is(":checked")) {
					output += '[follow ';
					if(TwitterID) {
						output += 'id="'+TwitterID+'" ';
					}
					if(Large.is(":checked")) {
						output += 'size="large" ';
					}
					if(count.is(":checked")) {
						output += 'count="true" ';
					}
					output += ']';

				}
				


				tinyMCEPopup.execCommand('mceReplaceContent', false, output);
				tinyMCEPopup.close();
				
			}
		}
		tinyMCEPopup.onInit.add(toggle.init, toggle);

	</script>
	<title>Add Share Buttons</title>

</head>
<body>
<form id="GalleryShortcode">

	<p>
		<label for="Facebook"><strong>Facebook Like Button</strong></label>
		<input id="Facebook" name="Facebook" type="checkbox" value="true" />
	</p>
	<hr style="border-bottom: 1px solid #FFF;border-top: 1px solid #ccc; border-left:0; border-right:0;" />

	<p>
		<label for="tweet"><strong>Tweet Button</strong></label>
		<input id="tweet" name="tweet" type="checkbox" value="true" />
	</p>
	<hr style="border-bottom: 1px solid #FFF;border-top: 1px solid #ccc; border-left:0; border-right:0;" />
	<p>
		<label for="digg"><strong>Digg Button</strong></label>
		<input id="digg" name="digg" type="checkbox" value="true" />
	</p>
	<hr style="border-bottom: 1px solid #FFF;border-top: 1px solid #ccc; border-left:0; border-right:0;" />
	<p>
		<label for="stumble"><strong>Stumble Button</strong></label>
		<input id="stumble" name="stumble" type="checkbox" value="true" />
	</p>
	<hr style="border-bottom: 1px solid #FFF;border-top: 1px solid #ccc; border-left:0; border-right:0;" />
	<p>
		<label for="Google"><strong>Google+ Button</strong></label>
		<input id="Google" name="Google" type="checkbox" value="true" />
	</p>
	<hr style="border-bottom: 1px solid #FFF;border-top: 1px solid #ccc; border-left:0; border-right:0;" />
	<p>
		<label for="feedburner"><strong>Feedburner Counter</strong></label>
		<input id="feedburner" name="feedburner" type="checkbox" value="true" />
		<input id="feedburnerName" name="feedburnerName" type="Text" value="Feed Name" />
	</p>
	<hr style="border-bottom: 1px solid #FFF;border-top: 1px solid #ccc; border-left:0; border-right:0;" />
	<p>
		<label for="pinterest"><strong>Pinterest Button</strong></label>
		<input id="pinterest" name="pinterest" type="checkbox" value="true" />
	</p>
	<hr style="border-bottom: 1px solid #FFF;border-top: 1px solid #ccc; border-left:0; border-right:0;" />
	<p>
		<label for="TwitterID"><strong>Twitter Follow Button</strong></label>
		<input id="twitterFollow" name="twitterFollow" type="checkbox" value="true" />
		<input id="TwitterID" name="TwitterID" type="text" value="Username" />
	</p>
	<p>
		<label for="Large">Large button : </label>
		<input id="Large" name="Large" type="checkbox" value="true" />
	</p>
	<p>
		<label for="count">Show count : </label>
		<input id="count" name="count" type="checkbox" value="true" />
	</p>
	<hr style="border-bottom: 1px solid #FFF;border-top: 1px solid #ccc; border-left:0; border-right:0;" />

	
	
	<p><a class="add" href="javascript:toggle.insert(toggle.e)">insert into post</a></p>
</form>

<?php } ?>

</body>
</html>