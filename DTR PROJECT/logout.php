<?php
session_start();

// Store the logout message before destroying session
$_SESSION['logout_message'] = "✅ You have been successfully logged out.";

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

// Write session data and close it
session_write_close();

// Start a new clean session just for the message
session_start();
$_SESSION['logout_message'] = "✅ You have been successfully logged out.";

// Redirect to logs page
header("Location: adminhomepage.html");
exit();
?>