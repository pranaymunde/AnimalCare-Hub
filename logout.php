<?php

require 'config.php';

$_SESSION = [];

session_destroy();

header("Location: index.php?message=" . urlencode("You have been logged out successfully."));
exit;

?>