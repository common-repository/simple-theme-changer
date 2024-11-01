<?php
if ( !function_exists( 'add_action' ) ) {
	echo 'Plugin can not be called directly.';
	exit;
}
if ( !is_admin()){	
	exit;	
}



?>

<style>
.my_buttons{
	-moz-box-shadow:inset 0px 1px 0px 0px #ffffff;
	-webkit-box-shadow:inset 0px 1px 0px 0px #ffffff;
	box-shadow:inset 0px 1px 0px 0px #ffffff;
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #dfdfdf) );
	background:-moz-linear-gradient( center top, #ededed 5%, #dfdfdf 100% );
	background-color:#ededed;
	-moz-border-radius:6px;
	-webkit-border-radius:6px;
	border-radius:6px;
	border:1px solid #dcdcdc;
	display:inline-block;
	color:#615f5f;
	font-family:arial;
	font-size:15px;
	font-weight:bold;
	padding:6px 24px;
	text-decoration:none;
	text-shadow:1px 1px 0px #ffffff;
}.my_buttons:hover {
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #dfdfdf), color-stop(1, #ededed) );
	background:-moz-linear-gradient( center top, #dfdfdf 5%, #ededed 100% );	
	background-color:#dfdfdf;
	cursor:pointer;
}.my_buttons:active {
	position:relative;
	top:1px;
}

</style>








<h2>Simple Theme Changer - options</h2>
<div style="margin:25px;" >

<h4>1) You can use filter and hidden themes from your users</h4>
<table id="filtr_table" >
	<tr style="font-weight:bold;" > 
		<td>User can use these themes</td>
		<td>User can not use these themes</td>
	</tr>
	<tr>
		<td>
		   <select id="themeChanger_select_all_themes" name="nazwa1" size="10" style="min-height:100px;min-width:200px;" >
			<?PHP
				$all_themes = theme_changer::get_all_themes();

				$themes_with_filtr = get_option( 'filtered_theme_list' ); 
			
	           foreach( $all_themes as $i => $theme){
					if(is_array($themes_with_filtr)){
						if( !in_array( $theme['name'],$themes_with_filtr ) ){
							echo '<option  value = "'.esc_attr($theme['name']).'" >'.wp_strip_all_tags($theme['name']).'</option>';
						}
					}
					else{			
							echo '<option  value = "'.esc_attr($theme['name']).'" >'.wp_strip_all_tags($theme['name']).'</option>';
					}
			   }
			   
			?>
		   </select>
		</td>
		<td>
		   <select id="themeChanger_select_filtr_themes" name="nazwa2" size="10" style="min-height:100px;min-width:200px;" >
	          <?PHP				
			
	          if(is_array($themes_with_filtr)){
					foreach( $themes_with_filtr as $i => $theme_filtr){    
						echo '<option  value = "'.esc_attr($theme_filtr).'" >'.wp_strip_all_tags($theme_filtr).'</option>';     
					}
			  }	
			   
			?>
           </select>
	    </td>
	</tr>	
</table>



<div style="margin-top:10px;">
      <span id="themeChanger_filtr_button" class="my_buttons" > Filter </span>
      <span id="save_filtr" class="my_buttons" style="margin-left:10px;" > Save </span>
</div>
	<h4 style="margin-top:30px;" > 2) How your users can choose theme  </h4>
	<select id="selectdisplaymethod" name="nazwa3" size="10" style="min-height:100px;min-width:250px;" >
		     
			
	       <option <?php if( get_option( 'display_type' ) == 1){ echo 'selected="selected"'; }; ?> value = "1" >Button at right-bottom side of website</option>		   
		   
		   <option <?php if( get_option( 'display_type' ) == 2){ echo 'selected="selected"'; }; ?>value = "2" >Link at left-bottom side of footer</option>

		   <option <?php if( get_option( 'display_type' ) == 3){ echo 'selected="selected"'; }; ?>value = "3" >Link at meta widget (if available)</option>	
		   
		   <option <?php if( get_option( 'display_type' ) == 4){ echo 'selected="selected"'; }; ?>value = "4" >None*</option>		   
		  			   
		  
		  
	</select>
	<div> <span style="margin-top:10px;" id="display_method_button" class="my_buttons" > Save </span></div>
	
	<p>
	* You can add your own links in the template which will allow users to change the theme. Example: 
	</p>
	
	<p style=" font-style: italic;color: #001fff;" >
		&lt;a class=&quot;refresh_page&quot; value=&quot;Twenty Seventeen&quot;&gt; I want to try "Twenty Seventeen" theme &lt;/a&gt;	
	</p>
		

				
	
	
<h4 style="margin-top:30px;" > 3) Text inside link or button  </h4>

<input type="text" id="change_theme_button_input" maxlength="50" value="
<?php $btn_name=wp_strip_all_tags(get_option( 'change_theme_button_name')); if(empty($btn_name)){ echo "Change Theme"; }else { echo $btn_name;}  ?>">
<div> <span style="margin-top:10px;" id="change_theme_button_name" class="my_buttons" > Save </span></div>
	

<hr style="margin-top:20px;">	

<p style="color:#000000; font-size: 16px;" >
You can contact the plugin author. My email address is <strong>daren@darendev.com</strong>
</p>

</div>









<script type="text/javascript">

     var themeChanger_filtr_button = document.getElementById("themeChanger_filtr_button");
	 var themeChanger_select_all_themes = document.getElementById("themeChanger_select_all_themes");
	 var themeChanger_select_filtr_themes = document.getElementById("themeChanger_select_filtr_themes");	 
	 
	 
	 function themeChangerUnselectAllThemes(){	 
	 
	          for(var i=0;i<themeChanger_select_all_themes.getElementsByTagName("option").length;i++){
			  
			       themeChanger_select_all_themes.getElementsByTagName("option")[i].selected = false;
			  
			  }
	 
	 }
	 
	 function themeChangerUnselectFiltrThemes(){	 
	 
	          
	         for(var i=0;i<themeChanger_select_filtr_themes.getElementsByTagName("option").length;i++){
			  
			       themeChanger_select_filtr_themes.getElementsByTagName("option")[i].selected = false;
			  
			 }
	 
	 }
	 
	 
		
	 function themeChangerSetEvents(){
	 
	          for(var i=0;i<themeChanger_select_all_themes.getElementsByTagName("option").length;i++){
			  
			          themeChanger_select_all_themes.getElementsByTagName("option")[i].onclick=function(){
					  
					      
					        themeChanger_filtr_button.innerHTML="Filter";
							themeChangerUnselectFiltrThemes();
					  
					  } 
			  
			  }
			  
			  for(var i=0;i<themeChanger_select_filtr_themes.getElementsByTagName("option").length;i++){
			  
			          themeChanger_select_filtr_themes.getElementsByTagName("option")[i].onclick=function(){
					  
					      
					        themeChanger_filtr_button.innerHTML="Unfilter";
							themeChangerUnselectAllThemes();
					  
					  }
			  
			  }
	 
	 }	
	 
	 themeChangerSetEvents();
	 
	 themeChanger_filtr_button.onclick = function(){


	 
	      var is_unchecked = true; 
	 
	      for(var i=0;i<themeChanger_select_all_themes.getElementsByTagName("option").length;i++){
			  
			  if( themeChanger_select_all_themes.getElementsByTagName("option")[i].selected ){
			      
				  
			      var copyOption = themeChanger_select_all_themes.getElementsByTagName("option")[i].cloneNode(true);				  
                  themeChanger_select_filtr_themes.appendChild(copyOption);	  			
			      themeChanger_select_all_themes.getElementsByTagName("option")[i].parentNode.removeChild(themeChanger_select_all_themes.getElementsByTagName("option")[i]);
			      is_unchecked = false;		
				  
			  }
			          			  
		  }
		  
		  for(var i=0;i<themeChanger_select_filtr_themes.getElementsByTagName("option").length;i++){
			  
			  if( themeChanger_select_filtr_themes.getElementsByTagName("option")[i].selected ){
			  
			      var copyOption = themeChanger_select_filtr_themes.getElementsByTagName("option")[i].cloneNode(true);				  
                  themeChanger_select_all_themes.appendChild(copyOption);	  			
			      themeChanger_select_filtr_themes.getElementsByTagName("option")[i].parentNode.removeChild(themeChanger_select_filtr_themes.getElementsByTagName("option")[i]);
			      is_unchecked = false;	
			  }
			          			  
		  }
		  
		  if(  is_unchecked ){
		  
		        alert("Please select theme");
		  
		  }
		  
		  themeChangerUnselectFiltrThemes(); 
		  themeChangerUnselectAllThemes();		  
		  themeChangerSetEvents();
	 
	 	     	   
		  

     } 	 
	

</script>



<script type="text/javascript" >


function themeChangerSendMessage(action_value, value_name, value, is_response   ){		
   
		var data = {};		
		data["action"] = action_value;
		data[value_name] = value;
				
		var url = "<?php echo admin_url('admin-ajax.php'); ?>";
	    jQuery.post(url, data, function(response) {
			
			if( is_response == true ){	
				alert("Got this from the server: " + response);
			}
			
	    });	  		
	
}


function themeChangerSetThemeFilter(){  
 jQuery(document).ready(function($) {     
    $("#save_filtr").click(function(){	
  
	    var filtr_themes = document.getElementById("themeChanger_select_filtr_themes").getElementsByTagName("option");	
	    var filtr_themes_for_save = new Array(); 
		
        for(var i=0; i<filtr_themes.length;i++){
	        
			 filtr_themes_for_save.push(filtr_themes[i].getAttribute("value"));
	
        }			
		 
		 themeChangerSendMessage("user_theme_admin", "filter_list", filtr_themes_for_save , true   );
		 
    });
});   
}


function themeChangerChooseDisplayMethod(){  
 
jQuery(document).ready(function($) {  
   
    $("#display_method_button").click(function(){		  

	  
	    var selectedOption = $( "#selectdisplaymethod" ).val();	  	
		 
		themeChangerSendMessage("display_method_admin", "display_type", selectedOption , true   );
		 
    });
   
});
}


function themeChangerSetButtonName(){  
 
jQuery(document).ready(function($) {  
   
    $("#change_theme_button_name").click(function(){		  

	  
	    var selectedOption = $( "#change_theme_button_input" ).val();	  	
				
		themeChangerSendMessage("set_change_theme_button_name", "change_theme_button_name", selectedOption , true   );
		 
    });
   
});
}




themeChangerSetThemeFilter();  
themeChangerChooseDisplayMethod();
themeChangerSetButtonName();



</script>





