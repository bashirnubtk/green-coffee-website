<?php
     
    if(isset($succe_msg)){
        foreach ($succe_msg as $succe_msg) {
            echo '<script>swal(" '.$succe_msg.'", "",success");</script>';
        }
    }

     if(isset($warning_msg)){
        foreach ($warning_msg as $warning_msg) {
            echo '<script>swal(" '.$warning_msg.'", "",warning");</script>';
        }
    }

    if(isset($info_msg)){
        foreach ($info_msg as $info_msg) {
            echo '<script>swal(" '.$info_msg.'", "",info");</script>';
        }
    }

    if(isset($error_msg)){
        foreach ($error_msg as $error_msg) {
            echo '<script>swal(" '.$error_msg.'", "",error");</script>';
        }
    } 

?>