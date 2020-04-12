<?php 
   if(isset($_POST['wpaf_section_general_button'])){
       
          if (!($_FILES["wpaf_faviconimage"]["name"]=="")){
            
          /* block code to upload favicon image */ 
         $wp_formatter_faviconimage = sanitize_file_name($_FILES["wpaf_faviconimage"]["name"]);
         $wpaf_uploads = wp_upload_dir(); 
         $wpaf_favicon = $wpaf_uploads['basedir'].'/'.basename($wp_formatter_faviconimage);
         $wpaf_faviconimage =  $wpaf_uploads['baseurl'].'/'.basename($wp_formatter_faviconimage);
         $wpaf_flag_faviconimage = 1;
   // Check if image file is a actual image or fake image
   
    $wpaf_imageFileType = pathinfo($wpaf_favicon,PATHINFO_EXTENSION);
   
   // Check if image file is a actual image or fake image
   
    $wpaf_check = getimagesize($_FILES["wpaf_faviconimage"]["tmp_name"]);
       if($wpaf_check !== false) {
          $wpaf_flag_faviconimage = 1;
       } else {
           echo "File is not an image.";
          $wpaf_flag_faviconimage = 0;
           exit;
       }
   // Check file size
   if ($_FILES["wpaf_faviconimage"]["size"] > 500000) {
       echo "Sorry, your file is too large.";
       $wpaf_flag_faviconimage = 0;
       exit;
   }
   // Allow certain file formats
   if($wpaf_imageFileType != "jpg" && $wpaf_imageFileType != "png" && $wpaf_imageFileType != "jpeg" && $wpaf_imageFileType != "gif" ) {
       echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
       $wpaf_flag_faviconimage = 0;
       exit;
   }
   // Check if $uploadOk is set to 0 by an error
   if ($wpaf_flag_faviconimage == 0) {
       echo "Sorry, your file was not uploaded."; exit;
   // if everything is ok, try to upload file
   } else {
       if (move_uploaded_file($_FILES["wpaf_faviconimage"]["tmp_name"],$wpaf_favicon)) {
           
           
              /* adding values in database  */
            if(get_option("wp_formatter_faviconimage")){ 
               
                 
                
               update_option("wp_formatter_faviconimage", $wpaf_faviconimage );
           }
           else{
               add_option("wp_formatter_faviconimage",$wpaf_faviconimage);
           }
       /* adding values in database ends */ 
           
       } else {
           echo "Sorry, there was an error uploading your file.";  exit;
       }
   }
      /* block code to upload favicon image ends */
      
      
      }
       
   
         
         
         
       if(!($_POST['wpaf_uploadfilesize'])==""){
           
           
             /* block code to increase upload file size */
           $wpaf_uploadfilesize = sanitize_text_field($_POST['wpaf_uploadfilesize']);
           
           if($wpaf_uploadfilesize >= 1 && $wpaf_uploadfilesize <=300 ){
                 
          
           /* adding values in database */ 
          if(get_option("wp_formatter_uploadfilesize")){
               update_option("wp_formatter_uploadfilesize",$wpaf_uploadfilesize);
           }
           else{
               add_option("wp_formatter_uploadfilesize",$wpaf_uploadfilesize);
           }
     
           }
   else{
       
       echo "please follow the range mentioned"; exit;
   }
       /* adding values in database ends */ 
   
       /* block code to increase upload file size ends */ 
       }  
   
          if(!($_POST['wpaf_loginpagebackgroundcolor']=='')){
             /* block code to change login page background color*/
          $wpaf_loginpagebackgroundcolor = sanitize_text_field($_POST['wpaf_loginpagebackgroundcolor']); 
          
           /* adding values in database */ 
          if(get_option("wp_formatter_loginpagebackgroundcolor")){
               update_option("wp_formatter_loginpagebackgroundcolor",$wpaf_loginpagebackgroundcolor);
           }
           else{
               add_option("wp_formatter_loginpagebackgroundcolor",$wpaf_loginpagebackgroundcolor);
           }
       /* adding values in database ends */ 
   
       /* block code to change login page background color ends */ 
       }
   
       
         if (!($_FILES["wpaf_loginpagebackgroundimage"]["name"]=="")){
            
                 /* block code to upload login logo */
             
         $wp_formatter_loginpagebackgroundimage = sanitize_file_name($_FILES["wpaf_loginpagebackgroundimage"]["name"]);
         $wpaf_uploads = wp_upload_dir(); 
         $wpaf_loginpagebackground = $wpaf_uploads['basedir'].'/'.basename($wp_formatter_loginpagebackgroundimage);
         $wpaf_loginpagebackgroundimage =  $wpaf_uploads['baseurl'].'/'.basename($wp_formatter_loginpagebackgroundimage);
         
         $wpaf_flag_loginpagebackgroundimage = 1;
   // Check if image file is a actual image or fake image
   
    $wpaf_imageFileType = pathinfo($wpaf_loginpagebackground,PATHINFO_EXTENSION);
   
   // Check if image file is a actual image or fake image
   
    $wpaf_check = getimagesize($_FILES["wpaf_loginpagebackgroundimage"]["tmp_name"]);
       if($wpaf_check !== false) {
           $wpaf_flag_loginpagebackgroundimage = 1;
       } else {
           echo "File is not an image.";
           $wpaf_flag_loginpagebackgroundimage = 0;
           exit;
       }
   // Check file size
   if ($_FILES["wpaf_loginpagebackgroundimage"]["size"] > 500000) {
       echo "Sorry, your file is too large.";
       $wpaf_flag_loginpagebackgroundimage = 0;
       exit;
   }
   // Allow certain file formats
   if($wpaf_imageFileType != "jpg" && $wpaf_imageFileType != "png" && $wpaf_imageFileType != "jpeg" && $wpaf_imageFileType != "gif" ) {
       echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
       $wpaf_flag_loginpagebackgroundimage = 0;
       exit;
   }
   // Check if $uploadOk is set to 0 by an error
   if ($wpaf_flag_loginpagebackgroundimage == 0) {
       echo "Sorry, your file was not uploaded."; exit;
   // if everything is ok, try to upload file
   } else {
       if (move_uploaded_file($_FILES["wpaf_loginpagebackgroundimage"]["tmp_name"],$wpaf_loginpagebackground)) {
           /* adding values in database  */ 
            if(get_option("wp_formatter_loginpagebackgroundimage")){
               update_option("wp_formatter_loginpagebackgroundimage", $wpaf_loginpagebackgroundimage);
           }
           else{
               add_option("wp_formatter_loginpagebackgroundimage", $wpaf_loginpagebackgroundimage);
           }
      
       /* adding values in database ends */ 
           
       } else {
           echo "Sorry, there was an error uploading your file.";  exit;
       }
   }
      /* block code to upload login logo ends */
      
      
      }
       
             if(!($_POST['wpaf_loginpageformbackgroundcolor']=='')){
             /* block code to change login page form background color */
          $wpaf_loginpageformbackgroundcolor = sanitize_text_field($_POST['wpaf_loginpageformbackgroundcolor']); 
          
           /* adding values in database */ 
          if(get_option("wp_formatter_loginpageformbackgroundcolor")){
               update_option("wp_formatter_loginpageformbackgroundcolor",$wpaf_loginpageformbackgroundcolor);
           }
           else{
               add_option("wp_formatter_loginpageformbackgroundcolor",$wpaf_loginpageformbackgroundcolor);
           }
       /* adding values in database ends */ 
   
        /* block code to change login page form background color */
       }
       
             if(!($_POST['wpaf_loginpageformfieldbackgroundcolor']=='')){
             /* block code to change login page form field background color */
          $wpaf_loginpageformfieldbackgroundcolor = sanitize_text_field($_POST['wpaf_loginpageformfieldbackgroundcolor']); 
          
           /* adding values in database */ 
          if(get_option("wp_formatter_loginpageformfieldbackgroundcolor")){
               update_option("wp_formatter_loginpageformfieldbackgroundcolor",$wpaf_loginpageformfieldbackgroundcolor);
           }
           else{
               add_option("wp_formatter_loginpageformfieldbackgroundcolor",$wpaf_loginpageformfieldbackgroundcolor);
           }
       /* adding values in database ends */ 
   
        /* block code to change login page form field background color */
       }
          if(!($_POST['wpaf_loginpageformfontcolor']=='')){
             /* block code to change login page form font color */
          $wpaf_loginpageformfontcolor = sanitize_text_field($_POST['wpaf_loginpageformfontcolor']); 
          
           /* adding values in database */ 
          if(get_option("wp_formatter_loginpageformfontcolor")){
               update_option("wp_formatter_loginpageformfontcolor",$wpaf_loginpageformfontcolor);
           }
           else{
               add_option("wp_formatter_loginpageformfontcolor",$wpaf_loginpageformfontcolor);
           }
       /* adding values in database ends */ 
   
       /* block code to change login page form font color */
       }
       
       
      if (!($_FILES["wpaf_loginlogo"]["name"]=="")){
            
                 /* block code to upload login logo */ 
          
         $wp_formatter_loginlogo = sanitize_file_name($_FILES["wpaf_loginlogo"]["name"]);
         $wpaf_uploads = wp_upload_dir(); 
         $wpaf_loginlogo = $wpaf_uploads['basedir'].'/'.basename($wp_formatter_loginlogo);
         $wpaf_loginlogoimage =  $wpaf_uploads['baseurl'].'/'.basename($wp_formatter_loginlogo);
         
           $wpaf_flag_loginlogo = 1;
   // Check if image file is a actual image or fake image
   
    $wpaf_imageFileType = pathinfo($wpaf_loginlogo,PATHINFO_EXTENSION);
   
   // Check if image file is a actual image or fake image
   
    $wpaf_check = getimagesize($_FILES["wpaf_loginlogo"]["tmp_name"]);
       if($wpaf_check !== false) {
           $wpaf_flag_loginlogo = 1;
       } else {
           echo "File is not an image.";
           $wpaf_flag_loginlogo = 0;
           exit;
       }
   // Check file size
   if ($_FILES["wpaf_loginlogo"]["size"] > 500000) {
       echo "Sorry, your file is too large.";
       $wpaf_flag_loginlogo = 0;
       exit;
   }
   // Allow certain file formats
   if($wpaf_imageFileType != "jpg" && $wpaf_imageFileType != "png" && $wpaf_imageFileType != "jpeg" && $wpaf_imageFileType != "gif" ) {
       echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
       $wpaf_flag_loginlogo = 0;
       exit;
   }
   // Check if $uploadOk is set to 0 by an error
   if ($wpaf_flag_loginlogo == 0) {
       echo "Sorry, your file was not uploaded."; exit;
   // if everything is ok, try to upload file
   } else {
       if (move_uploaded_file($_FILES["wpaf_loginlogo"]["tmp_name"],$wpaf_loginlogo)) {
           /* adding values in database */ 
            if(get_option("wp_formatter_loginlogo")){
               update_option("wp_formatter_loginlogo", $wpaf_loginlogoimage );
           }
           else{
               add_option("wp_formatter_loginlogo",$wpaf_loginlogoimage);
           }
      
       /* adding values in database ends */ 
           
       } else {
           echo "Sorry, there was an error uploading your file.";  exit;
       }
   }
      /* block code to upload login logo ends */
      
      
      }
   
    
   }  
       
   if(isset($_POST['wpaf_section_general_reset'])){
   delete_option('wp_formatter_faviconimage');
   delete_option('wp_formatter_uploadfilesize');
   delete_option('wp_formatter_loginpagebackgroundcolor');
   delete_option('wp_formatter_loginpageformbackgroundcolor');
   delete_option('wp_formatter_loginpageformfieldbackgroundcolor');
   delete_option('wp_formatter_loginpageformfontcolor');
   delete_option('wp_formatter_loginlogo');
   }
   
       if(isset($_POST['wpaf_section_advanced_button'])){
      if(!($_POST['wpaf_navigationbackgroundcolor']=='')){
          
          
             /* block code to change navigation background color */
          $wpaf_navigationbackgroundcolor = sanitize_text_field($_POST['wpaf_navigationbackgroundcolor']); 
           
           /* adding values in database */ 
           if(get_option("wp_formatter_navigationbackgroundcolor")){
               update_option("wp_formatter_navigationbackgroundcolor",$wpaf_navigationbackgroundcolor);
           }
           else{
               add_option("wp_formatter_navigationbackgroundcolor",$wpaf_navigationbackgroundcolor);
           }
       /* adding values in database ends */ 
   
       /* block code to change navigation background color ends */ 
       }
      
      
      
         
      if(!($_POST['wpaf_navigationfontcolor']=='')){
             /* block code to change navigation font color */
          $wpaf_navigationfontcolor = sanitize_text_field($_POST['wpaf_navigationfontcolor']);
          
        
           /* adding values in database */ 
           if(get_option("wp_formatter_navigationfontcolor")){
               update_option("wp_formatter_navigationfontcolor",$wpaf_navigationfontcolor);
           }
           else{
               add_option("wp_formatter_navigationfontcolor",$wpaf_navigationfontcolor);
           }
       /* adding values in database ends */ 
   
       /* block code to change navigation font color ends */ 
       }
      
       if(!($_POST['wpaf_navigationfonthovercolor']=='')){
             /* block code to change navigation font hover color */
          $wpaf_navigationfonthovercolor = sanitize_text_field($_POST['wpaf_navigationfonthovercolor']); 
           
           /* adding values in database form */ 
           if(get_option("wp_formatter_navigationfonthovercolor")){
               update_option("wp_formatter_navigationfonthovercolor",$wpaf_navigationfonthovercolor);
           }
           else{
               add_option("wp_formatter_navigationfonthovercolor",$wpaf_navigationfonthovercolor);
           }
       /* adding values in database ends */ 
   
       /* block code to change navigation font hover color ends */ 
       }
      
       if(!($_POST['wpaf_navigationhoverbackgroundcolor']=='')){
             /* block code to change navigation font hover background color */
          $wpaf_navigationhoverbackgroundcolor = sanitize_text_field($_POST['wpaf_navigationhoverbackgroundcolor']); 
           /* adding values in database */ 
         if(get_option("wp_formatter_navigationhoverbackgroundcolor")){
               update_option("wp_formatter_navigationhoverbackgroundcolor",$wpaf_navigationhoverbackgroundcolor);
           }
           else{
               add_option("wp_formatter_navigationhoverbackgroundcolor",$wpaf_navigationhoverbackgroundcolor);
           }
       /* adding values in database ends */ 
   
       /* block code to change navigation font hover background color ends */ 
       }
      
      
          if(!($_POST['wpaf_subnavigationbackgroundcolor']=='')){
             /* block code to change sub navigation background color */
          $wpaf_subnavigationbackgroundcolor = sanitize_text_field($_POST['wpaf_subnavigationbackgroundcolor']); 
          
           /* adding values in database */ 
         if(get_option("wp_formatter_subnavigationbackgroundcolor")){
               update_option("wp_formatter_subnavigationbackgroundcolor",$wpaf_subnavigationbackgroundcolor);
           }
           else{
               add_option("wp_formatter_subnavigationbackgroundcolor",$wpaf_subnavigationbackgroundcolor);
           }
       /* adding values in database ends */ 
   
       /* block code to change sub navigation background color ends */ 
       }
      
        if(!($_POST['wpaf_subnavigationfontcolor']=='')){
             /* block code to change sub navigation font color  */
          $wpaf_subnavigationfontcolor = sanitize_text_field($_POST['wpaf_subnavigationfontcolor']); 
         
         
           /* adding values in database */ 
           if(get_option("wp_formatter_subnavigationfontcolor")){
               update_option("wp_formatter_subnavigationfontcolor",$wpaf_subnavigationfontcolor);
           }
           else{
               add_option("wp_formatter_subnavigationfontcolor",$wpaf_subnavigationfontcolor);
           }
       /* adding values in database ends */ 
   
       /* block code to change sub navigation font color ends */ 
       }
      
         if(!($_POST['wpaf_iconscolor']=='')){
             /* block code to change icons color */
          $wpaf_iconscolor = sanitize_text_field($_POST['wpaf_iconscolor']); 
           /* adding values in database */ 
           if(get_option("wp_formatter_iconscolor")){
               update_option("wp_formatter_iconscolor",$wpaf_iconscolor);
           }
           else{
               add_option("wp_formatter_iconscolor",$wpaf_iconscolor);
           }
       /* adding values in database ends */ 
   
       /* block code to change icons color ends */ 
       }
   
         if(!($_POST['wpaf_howdytext'])==""){
             /* block code to change howdy text */
           $wpaf_howdytext = sanitize_text_field($_POST['wpaf_howdytext']);
           /* adding values in database */ 
           if(get_option("wp_formatter_howdytext")){
               update_option("wp_formatter_howdytext", $wpaf_howdytext );
           }
           else{
               add_option("wp_formatter_howdytext",$wpaf_howdytext);
           }
   
       /* adding values in database ends */ 
   
       /* block code to change howdy text ends */ 
       }
      
      
          if(!($_POST['wpaf_footertext'])==""){
             /* block code to change footer text  */
           $wpaf_footertext = sanitize_text_field($_POST['wpaf_footertext']);
           /* adding values in database */ 
             if(get_option("wp_formatter_footertext")){
               update_option("wp_formatter_footertext", $wpaf_footertext );
           }
           else{
               add_option("wp_formatter_footertext",$wpaf_footertext);
           }
       /* adding values in database ends */ 
   
       /* block code to change footer text ends */ 
       }
       
               
       
          if (!($_FILES["wpaf_profilepicture"]["name"]=="")){
              
                 /* block code to upload profile picture */ 
              
         $wp_formatter_profilepicture = sanitize_file_name($_FILES["wpaf_profilepicture"]["name"]);
         $wpaf_uploads = wp_upload_dir(); 
         $wpaf_profilepicture = $wpaf_uploads['basedir'].'/'.basename($wp_formatter_profilepicture);
         $wpaf_profilepictureimage =  $wpaf_uploads['baseurl'].'/'.basename($wp_formatter_profilepicture);
         
           $wpaf_flag_profilepicture = 1;
   // Check if image file is a actual image or fake image
   
    $wpaf_imageFileType = pathinfo($wpaf_profilepicture,PATHINFO_EXTENSION);
   
   // Check if image file is a actual image or fake image
   
    $wpaf_check = getimagesize($_FILES["wpaf_profilepicture"]["tmp_name"]);
       if($wpaf_check !== false) {
           $wpaf_flag_profilepicture = 1;
       } else {
           echo "File is not an image.";
           $wpaf_flag_faviconimage = 0;
           exit;
       }
   // Check file size
   if ($_FILES["wpaf_profilepicture"]["size"] > 500000) {
       echo "Sorry, your file is too large.";
       $wpaf_flag_profilepicture = 0;
       exit;
   }
   // Allow certain file formats
   if($wpaf_imageFileType != "jpg" && $wpaf_imageFileType != "png" && $wpaf_imageFileType != "jpeg" && $wpaf_imageFileType != "gif" ) {
       echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
       $wpaf_flag_profilepicture = 0;
       exit;
   }
   // Check if $uploadOk is set to 0 by an error
   if ($wpaf_flag_profilepicture == 0) {
       echo "Sorry, your file was not uploaded."; exit;
   // if everything is ok, try to upload file
   } else {
       if (move_uploaded_file($_FILES["wpaf_profilepicture"]["tmp_name"],$wpaf_profilepicture)) {
           
           /* adding values in database  */ 
             
          
          $wpaf_current_admin_id = "wp_formatter_profilepicture-".get_current_user_id();
           if(get_option($wpaf_current_admin_id)){
               update_option($wpaf_current_admin_id, $wpaf_profilepictureimage );
           }
           else{
               add_option($wpaf_current_admin_id,$wpaf_profilepictureimage);
           }
           
         
       /* adding values in database ends */ 
           
       } else {
           echo "Sorry, there was an error uploading your file.";  exit;
       }
   }
      /* block code to upload profile picture ends */
      
      
      }
       if(isset($_POST['wpaf_hideadminlogo'])){
           
             /* block code to hide admin logo */
           $wpaf_hideadminlogo = sanitize_text_field($_POST['wpaf_hideadminlogo']);
           
           
           /* adding values in database */ 
           if(get_option("wp_formatter_hideadminlogo")){
               update_option("wp_formatter_hideadminlogo",$wpaf_hideadminlogo);
           }
           else{
               add_option("wp_formatter_hideadminlogo",$wpaf_hideadminlogo);
           }
      
        
           
           }
         else{
             
               $wpaf_hideadminlogo = "show";
           /* adding values in database  */ 
           if(get_option("wp_formatter_hideadminlogo")){
               update_option("wp_formatter_hideadminlogo",$wpaf_hideadminlogo);
           }
           else{
               add_option("wp_formatter_hideadminlogo",$wpaf_hideadminlogo);
           }
           
            /* adding values in database ends */ 
   
       /* block code to hide admin logo */ 
         }
       
       if(isset($_POST['wpaf_hideadminbar'])){
           
             /* block code to hide admin bar  */
           $wpaf_hideadminbar = sanitize_text_field($_POST['wpaf_hideadminbar']);
           
          
           /* adding values in database */ 
           if(get_option("wp_formatter_hideadminbar")){
               update_option("wp_formatter_hideadminbar",$wpaf_hideadminbar);
           }
           else{
               add_option("wp_formatter_hideadminbar",$wpaf_hideadminbar);
           }
        
           
           }
         else{
             
               $wpaf_hideadminbar = "show";
          
           /* adding values in database  */ 
          if(get_option("wp_formatter_hideadminbar")){
               update_option("wp_formatter_hideadminbar",$wpaf_hideadminbar);
           }
           else{
               add_option("wp_formatter_hideadminbar",$wpaf_hideadminbar);
           }
           
            /* adding values in database ends */ 
   
       /* block code to hide admin bar */ 
         }
       }
                      
        if(isset($_POST['wpaf_section_advanced_reset'])){
   
   delete_option('wp_formatter_navigationbackgroundcolor');
   delete_option('wp_formatter_navigationfontcolor');
   delete_option('wp_formatter_navigationfonthovercolor');
   delete_option('wp_formatter_navigationhoverbackgroundcolor');
   delete_option('wp_formatter_subnavigationbackgroundcolor');
   delete_option('wp_formatter_subnavigationfontcolor');
   delete_option('wp_formatter_iconscolor');
   delete_option('wp_formatter_howdytext');
   delete_option('wp_formatter_footertext');
   $wpaf_current_admin_id = "wp_formatter_profilepicture-".get_current_user_id();
   if(get_option($wpaf_current_admin_id)){
   delete_option($wpaf_current_admin_id);
   }
   delete_option('wp_formatter_hideadminlogo');
   delete_option('wp_formatter_hideadminbar');
        }?>