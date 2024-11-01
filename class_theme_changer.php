<?php
if ( !function_exists( 'add_action' ) ) {
	echo 'Plugin can not be called directly.';
	exit;
}




class theme_changer{
		
					
					
	public $theme_template_ready;								
	public $theme_stylesheet_ready;	
	
	
	public function admin_zone_start(){
		
		// Add admin menu and load admin view
		add_action('admin_menu', array($this,'add_admin_page')) ;		
		
		// Add Jquery script in admin panel		
	    add_action( 'admin_enqueue_scripts',  array($this,'jquery') );	
			
		// save theme filter with ajax	
		add_action('wp_ajax_user_theme_admin', array( $this, 'admin_ajax_action_callback' ));
		
		// save method how user can change theme		
		add_action('wp_ajax_display_method_admin', array( $this, 'admin_ajax_display_method' ));
			
		// set change theme button name	
		add_action('wp_ajax_set_change_theme_button_name', array( $this, 'admin_ajax_set_button_name' ));
		
	
		
	}		
	
	
	public function user_zone_start(){				
		
		$this->setOptions();
				
		// add html and javascript code (buttons and links)
		add_action('wp_footer',array($this, 'user_choose_theme_button'));
		
		// now we can change template	
		$this->changeTemplate();		
		
	}	
	
	public function setOptions(){
		
		// Display "change theme" buttons or links
		// If option is empty set button at right bottom side of page. Option value = 1
		if( empty( get_option( 'display_type' ) ) ){ 	
			
				delete_option('display_type');
				add_option( 'display_type', 1 , '', 'yes' ); 	 
				
		}		
		
		// add "change theme" link to meta widget
		if( get_option( 'display_type' ) == 3 ){ 	
			add_filter( 'widget_meta_poweredby', array($this,'add_meta_widget_link') );	
		}
		
	}
	
	public function changeTemplate(){	
		// When user click "change theme" button script set template name in cookie and refresh page
		// Now we read cookie and load new theme		
		
		
		if(isset($_COOKIE['userfavoritetheme'])){		
			
			$cookie = urldecode(trim($_COOKIE['userfavoritetheme']));
			
			//method "is_theme_available" check is theme name in cookie value exist in database
			//if template exist in database then method "is_theme_available" copy theme name and style name form database and 
			//save it in $this->theme_template_ready and $this->theme_stylesheet_ready fields
			//add_filter function load new theme and new style from $this->theme_template_ready and $this->theme_stylesheet_ready
						
			if( $this->is_theme_available( $cookie ) ){
				
				add_filter('template', array( $this, 'change_template' ));			 
				add_filter('option_template', array( $this, 'change_template' ) );
				add_filter('option_stylesheet', array( $this, 'change_style' ) );
				
			}
		}		
	}	
			 
	public function is_theme_available($cookie_template){
		
		// check is user can use this theme
		
		// list available themes in database
		$themes_list = theme_changer::get_all_themes_not_filtered();	
		
		foreach( $themes_list as $i => $theme){		
			
			// If theme name in cookie is available in database and user can use this   
			if( $cookie_template == $theme['name']  ){	
				
				// copy form database theme_template and theme_stylesheet 				
				$this->theme_template_ready = $theme['theme_template'];
								
				$this->theme_stylesheet_ready = $theme['theme_stylesheet'];
				
				return true;  						
			}
		}
		
		return false;
		
	}	
	
	
	function change_template() {
     // Alternate theme
		return $this->theme_template_ready;
	}
	
	function change_style() {
     // Alternate style
		return $this->theme_stylesheet_ready;
	}
	
	
	public function user_choose_theme_button(){
		// load html and javascript code (buttons)
		include_once('view_user_button.php');
		
	}
	
	
	
	public function add_admin_page(){
		
		// Add new page in admin menu
		add_menu_page( 'Theme Changer Plugin', 'Theme Changer', 'manage_options', 'theme-changer', array($this,'load_admin_page_view') );		
		
	}	
	
	
	public function load_admin_page_view(){
		
		// load admin view - options
		include_once('view_admin.php');
	
	}
	
	public function jquery(){
		
		// load jquery
		  wp_enqueue_script(  'jquery' );
		
	}
	
	public static function get_all_themes(){  
	    
			// Load all availble themes 
			$themes = array();		
			$templates = array();		

		
			
			$wp_themes = wp_get_themes( array( 'allowed' => true ) );
			
		
			foreach ( $wp_themes as $i => $theme ) {			
			   
			    $tmp['name'] = $theme->get( 'Name' );			
				$tmp['description'] = $theme->get( 'Description' );	
			    $tmp['theme_root'] = $theme->theme_root;				
				$tmp['theme_template'] = $theme->template;
				$tmp['theme_stylesheet'] = $theme->stylesheet;	
				
		        // check is theme exist
			    if( is_dir($tmp['theme_root'].'/'.$tmp['theme_template']) ){			
			       	      array_push($templates,$tmp);				
			    }
			}				  
			return $templates;
		
	}
	
	
	
	
	public static function get_all_themes_not_filtered(){
		
		// Get all not filtered themes
		
		$all_themes = theme_changer::get_all_themes();		
		$themes_with_filtr = get_option( 'filtered_theme_list' ); 		
		
		$theme_list = array();
		
		if( empty($themes_with_filtr) || !is_array($themes_with_filtr) ){
			$themes_with_filtr  = array();
		}
		
		foreach( $all_themes as $i => $theme){			
			if( !in_array( $theme['name'],$themes_with_filtr ) ){	
				 array_push($theme_list,$theme);	 						
			}
		}
		
		return $theme_list;
		
	}	
	
	
	
	
	
	
	
	
	public static function getNextTheme(){
	
		// return next theme for change button
		$themes_list = theme_changer::get_all_themes_not_filtered();
		$themes_list_lenght = count($themes_list);
		$current_theme = wp_get_theme();

			
				
				
				
		if($themes_list_lenght<1){	
			// If all themes are filtered return current theme
			$all_themes = theme_changer::get_all_themes();	
			foreach ( $all_themes  as $i => $theme ) {				   
			    if( $current_theme->get( 'Name' ) == $theme['name'] ){
					return $theme;
				}				
				
			}		
		}		
				
		foreach( $themes_list as $i => $theme){		
			// return next theme
			if(  $current_theme->get( 'Name' ) == $theme['name']  ){	
				 
				if($i < ($themes_list_lenght-1) ){
					return $themes_list[$i+1];					
				}				
				 
			}
		}
				
		
		// if current theme is last return first theme
		return $themes_list[0];
		
	}
	
	
	function admin_ajax_action_callback() {
		
      // AJAX - admin panel
	  // Admin can hidden some themes from users. This method add list of hidden themes
	  
      $filtered_theme_list = array();	 
	  
	  $all_themes = theme_changer::get_all_themes();
	  $all_themes_array = array();		
	  foreach( $all_themes as $i => $template){	
		 array_push($all_themes_array,$template['name']);	
	  }	  
	 
	  
	  
	  
	  if( !is_array($_POST['filter_list']) ){
			// save empty array
	        delete_option( 'filtered_theme_list' ); 	  
	        add_option( 'filtered_theme_list', $filtered_theme_list, '', 'yes' ); 	  
	        die('No filtered themes');
	  
	  }
	  else{
			// array_flip exchanges all keys with their associated values in an array
			// Example: Array( [0] => My Theme ) convert into  Array( [My Theme] => 0 )
    
			 $all_themes_array_key = array_flip($all_themes_array); 
			
			// If theme in $_POST is in theme list then save list of filtered themes
	        foreach( $_POST['filter_list'] as $i => $theme){	 
				$theme = trim($theme);
				// Check if theme name in $_POST exist in blog database. If exist we can add filter and save option
				if( in_array($theme,$all_themes_array) ){				
					
					// Push theme name to $filtered_theme_list array. Copy value from $all_themes_array (list installed themes based on wp_get_themes)
					// Example:
					// $theme = 'Twenty Twenty' or other theme name;
					// $all_themes_array_key can be array( [Twenty Twenty] => 0 [Twenty Nineteen] => 1)   
					// $all_themes_array can be array( [0] => Twenty Twenty [1] => Twenty Nineteen)
					// $all_themes_array[$all_themes_array_key[$theme]] return 'Twenty Twenty'
					
					array_push($filtered_theme_list, $all_themes_array[$all_themes_array_key[$theme]] );
					
				}
	              
				
	        }
			
			if( count($filtered_theme_list)>0 ){
				// save new list of filtered theme
				delete_option( 'filtered_theme_list' ); 	  
				add_option( 'filtered_theme_list', $filtered_theme_list, '', 'yes' );
			}	
	        	  
	  }	  	   	 	  
		  
			die("Option saved");	
	
    }
	
	
	function admin_ajax_display_method() {
		//AJAX - admin panel
		// This function save method how user can change theme
		
        if(is_numeric($_POST['display_type']) ){
			
			// default display type - button at right bottom side of page
			$display_type = 1;
			
			// admin can set 3 other options
			if( $_POST['display_type'] == 2 ){
				$display_type = 2;				
			}
			elseif( $_POST['display_type'] == 3 ){
				$display_type = 3;				
			}
			elseif( $_POST['display_type'] == 4 ){
				$display_type = 4;				
			}
			
			 delete_option( 'display_type' ); 	  
	         add_option( 'display_type', $display_type , '', 'yes' ); 	 			
		     die("Option saved");	
		}      
		  
		die("error - option not saved");
	
    }
	
	function admin_ajax_set_button_name() {
		
		//AJAX - admin panel
		// Set button or link name
        if( !empty($_POST['change_theme_button_name']) ){
			
			 // remove tags form $_POST and set max 50 chars	
			 $button_name =  substr(  wp_strip_all_tags(trim($_POST['change_theme_button_name'])) ,0,50);			 
			 delete_option( 'change_theme_button_name' ); 				 
	         add_option( 'change_theme_button_name', $button_name  , '', 'yes' ); 	 			
		     die("Button name saved");	
			 
		}      
		  
		die("error - Button name not saved");
	
    }
		
	function add_meta_widget_link( $p1 ) {   
		
		// Add "theme change" link to the meta widget
		$theme = theme_changer::getNextTheme();	
		$btn_name= wp_strip_all_tags(get_option( 'change_theme_button_name')); 
		if(empty($btn_name)){
			$btn_name= "Change Theme";
		}	
	
		$link = '<li> <a class = "refresh_page" value = "'.urlencode(esc_attr($theme['name'])).'" >'.wp_strip_all_tags($btn_name).'</a></li>'; 	
		return $link;
	}
	
	
	
	
	
}









?>