<?php
if ( !function_exists( 'add_action' ) ) {
   echo 'Plugin can not be called directly.';
   exit;
}

?>


<style>
#change_theme_button {
	position: fixed;
    bottom: 5px;
    right: 5px;	
	box-shadow:inset 0px 1px 0px 0px #bee2f9;
	background:linear-gradient(to bottom, #63b8ee 5%, #468ccf 100%);
	background-color:#63b8ee;
	border-radius:6px;
	border:1px solid #3866a3;
	display:inline-block;
	cursor:pointer;
	color:#14396a;
	font-family:Arial;
	font-size:12px;
	font-weight:bold;
	padding:3px 6px;
	text-decoration:none;
	text-shadow:0px 1px 0px #7cacde;
	z-index:1000;
	line-height: 1.2;
}
#change_theme_button:hover {
	background:linear-gradient(to bottom, #468ccf 5%, #63b8ee 100%);
	background-color:#468ccf;
}
#change_theme_button:active {	
	 bottom: 7px;
}

#stopka {
	position:absolute;		
	margin-left:5px;	
	z-index: 1000;
}

.refresh_page{
	cursor:pointer;	
}


</style>


<?php if( get_option( 'display_type' ) == 1){ ?>

<a id="change_theme_button" class = "refresh_page" value = "<?php $theme = theme_changer::getNextTheme(); echo urlencode(esc_attr($theme['name']));  ?>" > 

<?php $btn_name= wp_strip_all_tags(get_option( 'change_theme_button_name')); if(empty($btn_name)){ echo "Change Theme"; }else { echo $btn_name;}  ?>

</a>

<?php } ?>


<?php if( get_option( 'display_type' ) == 2){ ?>

<a class = "refresh_page" value = "<?php $theme = theme_changer::getNextTheme(); echo urlencode(esc_attr($theme['name']));  ?>" id="stopka" >

<?php $btn_name=wp_strip_all_tags(get_option( 'change_theme_button_name')); if(empty($btn_name)){ echo "Change Theme"; }else { echo $btn_name;}  ?>

</a>

<script>
// set change theme button at bottom of page


window.addEventListener('load', 
    function() { 
		let scrollHeight = document.body.scrollHeight<?php if ( !is_user_logged_in()){ echo'-30'; } ?>;
		let stopka = document.getElementById("stopka");
		stopka.style.top = scrollHeight+"px";
  
   
  }, false)


</script>

<?php } ?>

<script>
// refresh page and change template

let themeChangerRefresh_page = document.getElementsByClassName("refresh_page");

function themeChangerSetCookie(cname, cvalue, exdays) {
  let d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  let expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}



Array.from(themeChangerRefresh_page).forEach(element => {

	element.onclick = function(){
		
		let template = encodeURI(this.getAttribute("value"));	
		themeChangerSetCookie('userfavoritetheme',template,7);		
		window.location.reload(true);		
	
	}

});			
				
</script>




