<?php
/**
 * Logout
 * Destroys the session and redirects to the home page or login.
 */
require_once 'config/database.php';

session_unset();
session_destroy();

// If it's a guest or just a regular user, redirect to index. 
// If it was an admin, maybe redirect to login.
header('Location: login.php');
exit;
