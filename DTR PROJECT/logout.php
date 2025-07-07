<?php
session_start();

// Regenerate session ID for security
session_regenerate_id(true);

// Unset all session variables
$_SESSION = array();

// Delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destroy the session
session_destroy();

// Start a new clean session for logout message
session_start();
$_SESSION['logout_message'] = "✅ You have been successfully logged out.";

// Redirect to homepage (or login)
header("Location: home.html");
exit();
?>