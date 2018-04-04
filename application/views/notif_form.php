<html>
 
   <head> 
      <title>Upload Form</title> 
   </head>
  
   <body> 
      <?php echo form_open_multipart('ControllerTest/singleDevice');?> 
      <form action = "" method = "">
         Kode transaksi : <input type = "text" name = "kode_transaksi" /> <br />
         Token : <input type = "text" name = "token" /> <br />
         Type : <input type = "text" name = "type" /> 
         <br /><br /> 
         <input type = "submit" value = "kirim notif" /> 
      </form> 
    
   </body>
  
</html>