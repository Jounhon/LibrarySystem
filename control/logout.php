<?php
session_start();
session_destroy();
unset($_SESSION['userAccount']);
unset($_SESSION['userAuthority']);
unset($_SESSION['userName']);
//header("Location:http://localhost");
echo "success";	
?>