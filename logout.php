<?php
session_start();

// Destroy the session completely
session_unset();
session_destroy();

// Redirect to the user login page (which is also at the root)
header("Location: login.php");
exit();
?>