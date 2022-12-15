<?php

    include('connect.php');
    include('functions.php');

   
if($does_not_have_error){
            $toInsert = insertDB ($conn);
                                       
              if ($toInsert) {
            
    
               // Send an email
                read_cb($ch, $email, $length);
                         
                    //Redirect page
                header('Location: login.php?r=232443');
                exit;
            } 
}
?>