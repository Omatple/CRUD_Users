<?php

namespace App\Utils;

class SessionAlert
{
    public static function displayAlert()
    {
        if ($message = $_SESSION["message"] ?? false) {
            echo <<< HTML
                <script>
                    Swal.fire({
                      position: "center",
                      icon: "success",
                      title: "$message",
                      showConfirmButton: false,
                      timer: 2000
                    }); 
                </script>               
            HTML;
            unset($_SESSION["message"]);
        }
    }
}
