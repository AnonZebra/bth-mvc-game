<?php

/**
 * Setup the basis for the environment.
 */

declare(strict_types=1);

// Setup error reporting
// error_reporting(-1);                // Report all type of errors
error_reporting(E_ALL);                // https://www.php.net/manual/de/function.error-reporting
ini_set("display_errors", "1");     // Display all errors

// Start the session
session_name(preg_replace("/[^a-z\d]/i", "", __DIR__));
session_start();
