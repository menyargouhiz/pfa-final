<?php

/**
 * Global Error Handler
 */

// Set error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    error_log("[$errno] $errstr in $errfile:$errline");
    
    // Only show generic error to user, not internal details
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        sendError("Error: $errstr", 500);
    } else {
        sendError("An error occurred. Please try again later.", 500);
    }
    
    return true;
});

// Set exception handler
set_exception_handler(function($exception) {
    error_log("Exception: " . $exception->getMessage());
    
    if (defined('DEBUG_MODE') && DEBUG_MODE) {
        sendError($exception->getMessage(), 500);
    } else {
        sendError("An error occurred. Please try again later.", 500);
    }
});

?>