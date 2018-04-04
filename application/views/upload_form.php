<html>
 
   <head> 
      <title>Upload Form</title> 
   </head>
  
   <body> 
      <?php echo $error;?> 
      <?php echo form_open_multipart('generalcontroller/ControllerMain/do_upload');?> 
    
      <form action = "" method = "">
         file:<input type = "file" name = "userfile" size = "20" /> <br />
         usertype: <input type = "text" name = "usertype" size = "20" /> <br />
         kode/fcm: <input type = "text" name = "fcm" size = "20" /> 
         <br /><br /> 
         <input type = "submit" value = "upload" /> 
      </form> 
    
   </body>
  
</html>