<?php
// logout.php
require_once 'db/db_connect.php';

// Destroy all session data
session_destroy();

// Redirect to home page
header('Location: index.php');
exit();
?>