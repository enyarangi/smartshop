<?php
   /* Plugin Name: WP Admin Formatter - Customize Admin Panel
    * Description: This Plugin provides multiple features to Customize Admin Panel. 
    * Version: 1.0  
	* Author: Ayesha Idrees
	* Author URI: http://www.discretelogix.com/
    */
   define( 'WP_DEBUG', true );
   
   function wpaf_picker_css( $hook_suffix ) {
       // first check that $hook_suffix is appropriate for your admin page
       wp_enqueue_style( 'wp-color-picker' );
       wp_enqueue_script( 'my-script-handle', plugins_url('js/colorpicker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
       wp_register_style( 'style-css', plugins_url() . '/featurePlugin/css/style.css' );
       wp_enqueue_style( 'style-css');
       
   }
   add_action( 'admin_enqueue_scripts', 'wpaf_picker_css' );
   
   include('save.php');
   class wpaf_featured_plugin {
   	
   	/*
   	 * For easier overriding we declared the keys
   	 * here as well as our tabs array which is populated
   	 * when registering settings
   	 */
   	private $wpaf_general_settings_key = 'wpaf_general_settings';
   	private $wpaf_advanced_settings_key = 'wpaf_advanced_settings';
   	private $wpaf_plugin_options_key = 'wpaf_formatter_plugin_options';
   	private $wpaf_plugin_settings_tabs = array();
   	
   	/*
   	 * Fired during plugins_loaded (very very early),
   	 * so don't miss-use this, only actions and filters,
   	 * current ones speak for themselves.
   	 */
   	function __construct() {
   		add_action( 'init', array( &$this, 'wpaf_load_settings' ) );
   		add_action( 'admin_init', array( &$this, 'wpaf_register_general_settings' ) );
   		add_action( 'admin_init', array( &$this, 'wpaf_register_advanced_settings' ) );
   		add_action( 'admin_menu', array( &$this, 'wpaf_add_admin_menus' ) );
                                  }
   
   	
   	/*
   	 * Loads both the general and advanced settings from
   	 * the database into their respective arrays. Uses
   	 * array_merge to merge with default values if they're
   	 * missing.
   	 */
   	function wpaf_load_settings() {
   		$this->wpaf_general_settings = (array) get_option( $this->wpaf_general_settings_key );
   		$this->wpaf_advanced_settings = (array) get_option( $this->wpaf_advanced_settings_key );
   		
   		// Merge with defaults
   		$this->wpaf_general_settings = array_merge( array(
   			'wpaf_general_option' => 'General value'
   		), $this->wpaf_general_settings );
   		
   		$this->wpaf_advanced_settings = array_merge( array(
   			'wpaf_advanced_option' => 'Advanced value'
   		), $this->wpaf_advanced_settings );
   	}
   	/*
   	 * Registers the general settings via the Settings API,
   	 * appends the setting to the tabs array of the object.
   	 */
   	function wpaf_register_general_settings() {
   		$this->wpaf_plugin_settings_tabs[$this->wpaf_general_settings_key] = 'General';
   		
   		register_setting( $this->wpaf_general_settings_key, $this->wpaf_general_settings_key );
   		add_settings_section( 'wpaf_section_general', '', array( &$this, 'wpaf_section_general_desc' ), $this->wpaf_general_settings_key );
   		
   	}
   	
   	/*
   	 * Registers the advanced settings and appends the
   	 * key to the plugin settings tabs array.
   	 */
   	function wpaf_register_advanced_settings() {
   		$this->wpaf_plugin_settings_tabs[$this->wpaf_advanced_settings_key] = 'Advanced';
   		
   		register_setting( $this->wpaf_advanced_settings_key, $this->wpaf_advanced_settings_key );
   		add_settings_section( 'wpaf_section_advanced', '', array( &$this, 'wpaf_section_advanced_desc' ), $this->wpaf_advanced_settings_key );
   		
   	}
   	/*
   	 * The following methods provide descriptions
   	 * for their respective sections, used as callbacks
   	 * with wpaf_add_settings_section
   	 */
   	function wpaf_section_general_desc() {
           echo '<form novalidate="novalidate" action="#" method="post" enctype="multipart/form-data">'; 
           echo '<table class="form-table"><tbody>';
           echo '<tr>';
           echo '<th scope="row"><label class="wpaf-width-login-label" for=""><h3>Login Page</h3></label></th>';
           echo '<td></td>';
           echo '</tr>';
           echo '<tr>'; 
           echo '<th scope="row"><label for="wpaf_loginlogo">Login Page logo</label></th>';
           echo '<td><input type="file" name="wpaf_loginlogo"/></td>'; 
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_loginpagebackgroundcolor">Login Page Background Color</label></th>';
           echo '<td><input class="regular-text wpaf-color-field" type="text" value="'; echo get_option( 'wp_formatter_loginpagebackgroundcolor' ); echo'" name="wpaf_loginpagebackgroundcolor" placeholder="#f7f7f7"></td>';
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_loginpagebackgroundimage">Login Page Background Image</label></th>';
           echo '<td><input type="file" name="wpaf_loginpagebackgroundimage"/></td>'; 
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_loginpageformbackgroundcolor">Login Page Form Background Color</label></th>';
           echo '<td><input class="regular-text wpaf-color-field" type="text" value="'; echo get_option( 'wp_formatter_loginpageformbackgroundcolor' ); echo'" name="wpaf_loginpageformbackgroundcolor" placeholder="#f7f7f7"></td>'; 
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_loginpageformfieldbackgroundcolor">Login Page Form Field Background Color</label></th>';
           echo '<td><input class="regular-text wpaf-color-field" type="text" value="'; echo get_option( 'wp_formatter_loginpageformfieldbackgroundcolor' ); echo'" name="wpaf_loginpageformfieldbackgroundcolor" placeholder="#f7f7f7"></td>'; 
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_loginpageformfontcolor">Login Page Form Text Color</label></th>';
           echo '<td><input  class="regular-text wpaf-color-field" type="text" value="'; echo get_option( 'wp_formatter_loginpageformfontcolor' ); echo'" name="wpaf_loginpageformfontcolor" placeholder="#f7f7f7"></td>'; 
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label class="wpaf-width-login-label" for=""><h3>Media</h3></label></th>';
           echo '<td></td>';
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_uploadfilesize">Upload Media File Size</label></th>';
           echo '<td><fieldset><p><label><input class="regular-text wpaf-width-uploadfile" type="number" value="'; echo get_option( 'wp_formatter_uploadfilesize' ); echo'" name="wpaf_uploadfilesize" placeholder="Enter in MB" > Min 1MB , Max 300MB</label></p><p class="wpaf-field-description">Increase Upload Media File Size upto 300MB </p></fieldset></td>'; 
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label class="wpaf-width-login-label" for=""><h3>Favicon</h3></label></th>';
           echo '<td></td>';
           echo '</tr>';
           echo '<tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_faviconimage">Website Favicon Image</label></th>';
           echo '<td><input type="file" name="wpaf_faviconimage"/></td>'; 
           echo '</tr>';
           echo '</tbody></table>';
           echo '<p class="submit"><input class="button button-primary" type="submit" value="Save Changes" name="wpaf_section_general_button">&nbsp;<input class="button button-primary" type="submit" value="Reset Changes" name="wpaf_section_general_reset"></p>';
           echo '</form>';
           }
   	function wpaf_section_advanced_desc() {
           echo '<form novalidate="novalidate" action="#" method="post" enctype="multipart/form-data">'; 
           echo '<table class="form-table"><tbody>';
           //echo '<tr>';
           //echo '<th scope="row"><label for="wpaf_profilepicture">Admin Profile Picture</label></th>';
           //echo '<td><input type="file" name="wpaf_profilepicture"/></td>'; 
           //echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_howdytext">Welcome Message</label></th>';
           echo '<td><input class="regular-text" type="text" value="'; echo get_option( 'wp_formatter_howdytext' ); echo'" name="wpaf_howdytext" placeholder="Howdy" maxlength="100"></td>';
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_footertext">Footer Text</label></th>';
           echo '<td><input class="regular-text" type="text" value="'; echo get_option( 'wp_formatter_footertext' ); echo'" name="wpaf_footertext" placeholder="Enter your Footer Text" maxlength="100"></td>';
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_hideadminlogo">Wordpress Logo</label></th>';
           echo '<td><fieldset><p><label><input type="checkbox" value="hide" name="wpaf_hideadminlogo"';if(get_option("wp_formatter_hideadminlogo")=="hide"){?> checked <?php }echo '>Hide Logo</label></p><p class="wpaf-field-description">Hide Wordpress Logo from Top Left.</p></fieldset></td>'; 
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_hideadminbar">Admin Bar</label></th>';
           echo '<td><fieldset><p><label><input type="checkbox" value="hide" name="wpaf_hideadminbar"';if(get_option("wp_formatter_hideadminbar")=="hide"){?> checked <?php }echo '>Hide Bar</label></p><p class="wpaf-field-description">Hide Admin Bar on Frontend Pages while you are Loged In.</p></fieldset></td>'; 
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_navigationbackgroundcolor">Navigation Background Color</label></th>';
           echo '<td><input class="regular-text wpaf-color-field" type="text" value="'; echo get_option( 'wp_formatter_navigationbackgroundcolor' ); echo'" name="wpaf_navigationbackgroundcolor" placeholder="#f7f7f7"></td>';
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_navigationfontcolor">Navigation Font Color</label></th>';
           echo '<td><input class="regular-text wpaf-color-field" type="text" value="'; echo get_option( 'wp_formatter_navigationfontcolor' ); echo'" name="wpaf_navigationfontcolor" placeholder="#f7f7f7"></td>';
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_navigationfonthovercolor">Navigation Font Hover Color</label></th>';
           echo '<td><input class="regular-text wpaf-color-field" type="text" value="'; echo get_option( 'wp_formatter_navigationfonthovercolor' ); echo'" name="wpaf_navigationfonthovercolor" placeholder="#f7f7f7"></td>';
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_navigationhoverbackgroundcolor">Navigation Hover Background Color</label></th>';
           echo '<td><input class="regular-text wpaf-color-field" type="text" value="'; echo get_option( 'wp_formatter_navigationhoverbackgroundcolor' ); echo'" name="wpaf_navigationhoverbackgroundcolor" placeholder="#f7f7f7"></td>';
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_subnavigationbackgroundcolor">Sub Navigation Background Color</label></th>';
           echo '<td><input class="regular-text wpaf-color-field" type="text" value="'; echo get_option( 'wp_formatter_subnavigationbackgroundcolor' ); echo'" name="wpaf_subnavigationbackgroundcolor" placeholder="#f7f7f7"></td>';
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_subnavigationfontcolor">Sub Navigation Font Color</label></th>';
           echo '<td><input class="regular-text wpaf-color-field" type="text" value="'; echo get_option( 'wp_formatter_subnavigationfontcolor' ); echo'" name="wpaf_subnavigationfontcolor" placeholder="#f7f7f7"></td>';
           echo '</tr>';
           echo '<tr>';
           echo '<th scope="row"><label for="wpaf_iconscolor">Icons Color</label></th>';
           echo '<td><input class="regular-text wpaf-color-field" type="text" value="'; echo get_option( 'wp_formatter_iconscolor' ); echo'" name="wpaf_iconscolor" placeholder="#f7f7f7"></td>';
           echo '</tr>';
           echo '</tbody></table>';
           echo '<p class="submit"><input class="button button-primary" type="submit" value="Save Changes" name="wpaf_section_advanced_button">&nbsp;<input class="button button-primary" type="submit" value="Reset Changes" name="wpaf_section_advanced_reset"></p>';
           echo '</form>';
   }
   
   
   
   /*
   * Called during admin_menu, adds an options
   * page under Settings called Formatter Settings, rendered
   * using the wpaf_plugin_options_page method.
   */
   function wpaf_add_admin_menus() {
   add_options_page( 'Formatter Settings', 'Formatter Settings', 'manage_options', $this->wpaf_plugin_options_key, array( &$this, 'wpaf_plugin_options_page' ) );
   }
   
   /*
   * Plugin Options page rendering goes here, checks
   * for active tab and replaces key with the related
   * settings key. Uses the wpaf_plugin_options_tabs method
   * to render the tabs.
   */
   function wpaf_plugin_options_page() {
   $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->wpaf_general_settings_key;
    $this->wpaf_plugin_options_tabs();
   echo '<div class="wrap">';
           wp_nonce_field( 'update-options' );
   settings_fields( $tab );
    do_settings_sections( $tab ); 
           echo '</div>';
   
       
   // wp_redirect(admin_url('options-general.php?page=my_plugin_options'));exit;       
   }
   
   
   
   
   
   /*
   * Renders our tabs in the plugin options page,
   * walks through the object's tabs array and prints
   * them one by one. Provides the heading for the
   * wpaf_plugin_options_page method.
   */
   function wpaf_plugin_options_tabs() {
   $current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->wpaf_general_settings_key;
   
   screen_icon();
   echo '<h2 class="nav-tab-wrapper">';
   foreach ( $this->wpaf_plugin_settings_tabs as $tab_key => $tab_caption ) {
   $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
   echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->wpaf_plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
   }
   echo '</h2>';
   }
   }
   
   // Initialize the plugin
   add_action( 'plugins_loaded', create_function( '', '$wpaf_featured_plugin = new wpaf_featured_plugin;' ) );
   
   
   
   
   /* function to show howdy text */ 
   function wpaf_change_howdy_text_toolbar($wp_admin_bar)
   {
   
   $wpaf_howdytext = get_option( 'wp_formatter_howdytext' ); 
   if($wpaf_howdytext){
   
   $wpaf_getgreetings = $wp_admin_bar->get_node('my-account');
   $wpaf_rpctitle = str_replace('Howdy',$wpaf_howdytext,$wpaf_getgreetings->title);
   $wp_admin_bar->add_node(array("id"=>"my-account","title"=>$wpaf_rpctitle));
   
    }
   }
   if(get_option('wp_formatter_howdytext')){
   add_filter('admin_bar_menu','wpaf_change_howdy_text_toolbar');
   }
   /* function to show login logo */ 
   function wpaf_change_login_logo() {
   
   $wpaf_loginlogoimage = get_option('wp_formatter_loginlogo');
   
   if($wpaf_loginlogoimage){
   echo '<style type="text/css"> h1 a { background-image:url("'.$wpaf_loginlogoimage.'") !important; } </style>';
   }
   }
   if(get_option('wp_formatter_loginlogo')){
   add_filter('login_head', 'wpaf_change_login_logo');
   }
   /* function to show footer text */ 
   
   function wpaf_change_footer_text() {
   $wpaf_footertext = get_option( 'wp_formatter_footertext' ); 
   if($wpaf_footertext){
   echo $wpaf_footertext;
   }
   }
   if(get_option( 'wp_formatter_footertext' )){
   add_filter('admin_footer_text', 'wpaf_change_footer_text');
   }
   /* function to show navigation background color */ 
   
   function wpaf_change_navigation_background_color() {
   
   $wpaf_navigationbackgroundcolor = get_option('wp_formatter_navigationbackgroundcolor');
   if($wpaf_navigationbackgroundcolor){
   echo '<style type="text/css"> #adminmenu, #adminmenuwrap, #adminmenuback, #wpadminbar{ background-color:'.$wpaf_navigationbackgroundcolor.' !important; } </style>';
   }
   }
   if(get_option('wp_formatter_navigationbackgroundcolor')){
   add_action('admin_head', 'wpaf_change_navigation_background_color');
   }
   /* function to show navigation font color */ 
   function wpaf_change_navigation_font_color() {
   $wpaf_navigationfontcolor = get_option('wp_formatter_navigationfontcolor');
   if($wpaf_navigationfontcolor){
   echo '<style type="text/css"> #adminmenu a, #wpadminbar a.ab-item, #wpadminbar > #wp-toolbar span.ab-label, #wpadminbar > #wp-toolbar span.noticon, #collapse-menu{color:'.$wpaf_navigationfontcolor.' !important; } </style>';
   }
   }
   if(get_option('wp_formatter_navigationfontcolor')){
   add_action('admin_head', 'wpaf_change_navigation_font_color');
   }
   /* function to show navigation font hover color */ 
   function wpaf_change_navigation_font_hover_color() {
   $wpaf_navigationfonthovercolor = get_option('wp_formatter_navigationfonthovercolor');
   if($wpaf_navigationfonthovercolor){
   echo '<style type="text/css"> #adminmenu a:hover, #adminmenu a:hover:focus, #wp-toolbar li a:hover, #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu, #collapse-menu:hover,#wpadminbar .ab-submenu .ab-item, #wpadminbar .quicklinks .menupop ul li a, #wpadminbar .quicklinks .menupop ul li a strong, #wpadminbar .quicklinks .menupop.hover ul li a:hover, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover{color:'.$wpaf_navigationfonthovercolor.' !important; } </style>';
   }
   
   }
   if(get_option('wp_formatter_navigationfonthovercolor')){
   add_action('admin_head', 'wpaf_change_navigation_font_hover_color');
   }
   /* function to show navigation hover background color */ 
   function wpaf_change_navigation_hover_background_color() {
   $wpaf_navigationhoverbackgroundcolor = get_option('wp_formatter_navigationhoverbackgroundcolor');
   if($wpaf_navigationhoverbackgroundcolor){
   echo '<style type="text/css"> #adminmenu li a:hover, #wp-toolbar li a:hover, #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu, #collapse-menu:hover{background-color:'.$wpaf_navigationhoverbackgroundcolor.' !important; } </style>';
   }
   }
   
   if(get_option('wp_formatter_navigationhoverbackgroundcolor')){
   add_action('admin_head', 'wpaf_change_navigation_hover_background_color');
   }
   /* function to show sub navigation background color */
   function wpaf_change_sub_navigation_background_color() {
   
   $wpaf_subnavigationbackgroundcolor = get_option('wp_formatter_subnavigationbackgroundcolor');
   if($wpaf_subnavigationbackgroundcolor){
   echo '<style type="text/css"> #adminmenu .wp-has-current-submenu .wp-submenu, #adminmenu .wp-has-current-submenu .wp-submenu.sub-open, #adminmenu .wp-has-current-submenu.opensub .wp-submenu, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu, .no-js li.wp-has-current-submenu:hover .wp-submenu, #adminmenu .wp-not-current-submenu .wp-submenu, .folded #adminmenu .wp-has-current-submenu .wp-submenu, .ab-submenu{background-color:'.$wpaf_subnavigationbackgroundcolor.' !important; } </style>';
   }
   }
   if(get_option('wp_formatter_subnavigationbackgroundcolor')){
   add_action('admin_head', 'wpaf_change_sub_navigation_background_color');
   }
   /* function to show sub navigation font color */ 
   function wpaf_change_sub_navigation_font_color() {
   
   $wpaf_subnavigationfontcolor = get_option('wp_formatter_subnavigationfontcolor');
   if($wpaf_subnavigationfontcolor){
   echo '<style type="text/css"> #adminmenu li ul li a , #wpadminbar .ab-submenu .ab-item, #wpadminbar .quicklinks .menupop ul li a, #wpadminbar .quicklinks .menupop ul li a strong, #wpadminbar .quicklinks .menupop.hover ul li a, #wpadminbar.nojs .quicklinks .menupop:hover ul li a{color:'.$wpaf_subnavigationfontcolor.' !important; } </style>';
   }
   }
   if(get_option('wp_formatter_subnavigationfontcolor')){
   add_action('admin_head', 'wpaf_change_sub_navigation_font_color');
   }
   /* function to show icons color */ 
   function wpaf_change_icons_color() {
   
   $wpaf_iconscolor = get_option('wp_formatter_iconscolor');
   if($wpaf_iconscolor){
   echo '<style type="text/css"> #wpadminbar #adminbarsearch::before, #wpadminbar .ab-icon::before, #wpadminbar .ab-item::before, #adminmenu div.wp-menu-image::before, #collapse-button div::after{color:'.$wpaf_iconscolor.' !important; } </style>';
   }
   }
   if(get_option('wp_formatter_iconscolor')){
   add_action('admin_head', 'wpaf_change_icons_color');
   }
   /* function to show favicon image */
   
   function wpaf_change_favicon_image(){
   $wpaf_faviconimage = get_option('wp_formatter_faviconimage');
   if($wpaf_faviconimage){
   echo '<link rel="shortcut icon" href="'.$wpaf_faviconimage.'"/>';
   }
   }
   if(get_option('wp_formatter_faviconimage')){
   add_filter('admin_head', 'wpaf_change_favicon_image');
   add_filter('wp_head', 'wpaf_change_favicon_image');
   }
   /* function to show profile picture */
   
   function wpaf_change_profile_picture( $avatar, $id_or_email, $size, $default, $alt )
   {
   
   $wpaf_current_admin_id = "wp_formatter_profilepicture-".get_current_user_id();
   $wpaf_profilepicture = get_option($wpaf_current_admin_id);
   
   
   if($wpaf_profilepicture){
   $doc = new DOMDocument;
   $doc->loadHTML( $avatar );
   $imgs = $doc->getElementsByTagName('img');
   
   if ( $imgs->length > 0 ) 
   {
   $url = urldecode( $imgs->item(0)->getAttribute('src') );
   $url2 = explode( '?', $url ); // roughly, the first part is the avatar, the second is the default avatar
   $avatar= "<img src='".$wpaf_profilepicture."' alt='' class='avatar avatar-30 photo' height='30' width='30' />";
   }
   return $avatar;
   }
   
   }
   
   $wpaf_profilepicture = "wp_formatter_profilepicture-".get_current_user_id();
   
   if(get_option($wpaf_profilepicture)){
   add_filter( 'get_avatar', 'wpaf_change_profile_picture', 15, 5 );
   }
   /* function to hide admin logo  */
   
   function wpaf_hide_admin_logo(){
   
   $wpaf_hideadminlogo = get_option('wp_formatter_hideadminlogo');
   
   if($wpaf_hideadminlogo=='hide'){
    echo '<style type="text/css"> #wp-admin-bar-wp-logo{ display: none !important; } </style>';
    
   } else{ 
   echo '<style type="text/css"> #wp-admin-bar-wp-logo{ display: block !important; } </style>';
   }
   
   }
   if(get_option('wp_formatter_hideadminlogo')){
   add_filter('admin_head', 'wpaf_hide_admin_logo');
   }
   /* function to hide admin logo */
   function wpaf_hide_admin_bar(){
   
   $wpaf_hideadminbar = get_option('wp_formatter_hideadminbar');
   
   if($wpaf_hideadminbar=='hide'){
    echo '<style type="text/css"> #wpadminbar{ display: none !important; } </style>';
    
   } else{ 
   echo '<style type="text/css"> #wpadminbar{ display: block !important; } </style>';
   }
   
   }
   if(get_option('wp_formatter_hideadminbar')){
   add_filter('wp_head', 'wpaf_hide_admin_bar');
   }
   /* function to show login page background color */ 
   function wpaf_change_login_page_background_color() {
   
   $wpaf_loginpagebackgroundcolor = get_option('wp_formatter_loginpagebackgroundcolor');
   if($wpaf_loginpagebackgroundcolor){
   echo '<style type="text/css"> body,html{background-color:'.$wpaf_loginpagebackgroundcolor.' !important; } </style>';
   }
   }
   if(get_option('wp_formatter_loginpagebackgroundcolor')){
   add_action('login_head', 'wpaf_change_login_page_background_color');
   }
   /* function to increase upload file size */ 
   function wpaf_increase_upload_file_size( $bytes )
   {
    $wpaf_uploadfilesize = get_option('wp_formatter_uploadfilesize');
   if($wpaf_uploadfilesize){  
    $wpaf_uploadfilesize = $wpaf_uploadfilesize * (1024 * 1024);
    
   return $wpaf_uploadfilesize; // 32 megabytes
   }
   }
   if(get_option('wp_formatter_uploadfilesize')){
   add_filter( 'upload_size_limit', 'wpaf_increase_upload_file_size' );
   }
   /* function to show login page background color */ 
   function wpaf_change_login_page_background_image() {
   $wpaf_loginpagebackgroundimage = get_option('wp_formatter_loginpagebackgroundimage');
   if($wpaf_loginpagebackgroundimage){
	echo '<style type="text/css"> body,html{ background-image:url("'.$wpaf_loginpagebackgroundimage.'") !important; background-size: cover !important; background-repeat: no-repeat;  } </style>';
   }
   }
   if(get_option('wp_formatter_loginpagebackgroundimage')){
   add_action('login_head', 'wpaf_change_login_page_background_image');
   }
   /* function to show login page form background color */ 
   function wpaf_change_login_page_form_background_color() {
   
   $wpaf_loginpageformbackgroundcolor = get_option('wp_formatter_loginpageformbackgroundcolor');
   if($wpaf_loginpageformbackgroundcolor){
   echo '<style type="text/css"> .login form,.login .message,.login #login_error{background-color:'.$wpaf_loginpageformbackgroundcolor.' !important; border-left:none !important ;} </style>';
   }
   }
   if(get_option('wp_formatter_loginpageformbackgroundcolor')){
   add_action('login_head', 'wpaf_change_login_page_form_background_color');
   }
   /* function to show login page form field background color */ 
   function wpaf_change_login_page_form_field_background_color() {
   
   $wpaf_loginpageformfieldbackgroundcolor = get_option('wp_formatter_loginpageformfieldbackgroundcolor');
   if($wpaf_loginpageformfieldbackgroundcolor){
	echo '<style type="text/css"> .login form .input, .login form input[type=checkbox], .login input[type=text]{background-color:'.$wpaf_loginpageformfieldbackgroundcolor.' !important;} </style>';
   }
   }
   if(get_option('wp_formatter_loginpageformbackgroundcolor')){
   add_action('login_head', 'wpaf_change_login_page_form_field_background_color');
   }
   /* function to show login page form field background color */ 
   function wpaf_change_login_page_form_font_color() {
   
   $wpaf_loginpageformfontcolor = get_option('wp_formatter_loginpageformfontcolor');
   if($wpaf_loginpageformfontcolor){
	echo '<style type="text/css"> .login label, .login .message,#login p a{color:'.$wpaf_loginpageformfontcolor.' !important;} </style>';
   }
   }
   if(get_option('wp_formatter_loginpageformfontcolor')){
	add_action('login_head', 'wpaf_change_login_page_form_font_color');
   }
   ?>