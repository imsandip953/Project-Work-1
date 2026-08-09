<?php
require 'database.php';

// 1. Completely flush the global session variables array object map
$_SESSION = array();

// 2. Teardown the active session cookie tracking tokens inside browser storage
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 3. Destroy the tracking framework instance state inside server cache structures
session_destroy();

// 4. Safely bounce back down to your newly styled, gamified Duolingo login portal gateway
header("Location: index.php");
exit;
?>