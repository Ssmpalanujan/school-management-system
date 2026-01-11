<?php
// logout.php
require 'includes/functions.php';

session_unset();
session_destroy();

redirect('login.php');
?>
