<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the current working directory
error_log("Current working directory: " . __DIR__);

require 'vendor/autoload.php';

// Check if .env file exists
if (!file_exists(__DIR__ . '/.env')) {
    error_log(".env file not found in " . __DIR__);
    die(".env file not found");
}

// Try to load .env file
try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Exception $e) {
    error_log("Error loading .env file: " . $e->getMessage());
    die("Error loading .env file: " . $e->getMessage());
}

// Function to get environment variable
function getEnvVar($key) {
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    } elseif (isset($_SERVER[$key])) {
        return $_SERVER[$key];
    } elseif (getenv($key) !== false) {
        return getenv($key);
    }
    return null;
}

// Check if APP_KEY is set
$appKey = getEnvVar('APP_KEY');
if ($appKey === null) {
    error_log("Failed to load APP_KEY from environment");
    // Dump all environment variables for debugging
    error_log("All environment variables: " . print_r($_ENV, true));
    echo "Failed to load APP_KEY from environment";
} else {
    error_log("APP_KEY loaded successfully: " . $appKey);
}

// Update the rest of your code to use getEnvVar instead of getenv
$API_BASE_URL = 'https://decodl.net/api';
$APP_KEY = getEnvVar('APP_KEY');
$AUTH_TOKEN = 'Bearer ' . getEnvVar('AUTH_TOKEN');

function makeRequest($url, $method, $data = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'x-app-key: ' . $GLOBALS['APP_KEY'],
        'authorization: ' . $GLOBALS['AUTH_TOKEN']
    ]);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    } elseif ($method === 'GET') {
        curl_setopt($ch, CURLOPT_HTTPGET, true);
    }

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ['body' => $response, 'httpCode' => $httpCode];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputData = json_decode(file_get_contents('php://input'), true);
    $response = makeRequest($API_BASE_URL . '/product/dev', 'POST', $inputData);
    http_response_code($response['httpCode']);
    echo $response['body'];
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['jobId'])) {
    $jobId = $_GET['jobId'];
    $response = makeRequest($API_BASE_URL . '/job/dev/' . $jobId, 'GET');
    http_response_code($response['httpCode']);
    echo $response['body'];
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
}
?>