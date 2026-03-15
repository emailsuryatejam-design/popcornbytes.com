<?php
require_once __DIR__ . '/includes/config.php';

header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));

if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

$url  = 'https://' . MAILCHIMP_DC . '.api.mailchimp.com/3.0/lists/' . MAILCHIMP_LIST_ID . '/members';
$data = json_encode([
    'email_address' => $email,
    'status'        => 'subscribed',
]);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $data,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 10,
    CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Authorization: Basic ' . base64_encode('anystring:' . MAILCHIMP_API_KEY),
    ],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$body = json_decode($response, true);

if ($httpCode === 200) {
    echo json_encode(['success' => true, 'message' => 'Subscribed!']);
} elseif ($httpCode === 400 && isset($body['title']) && $body['title'] === 'Member Exists') {
    echo json_encode(['success' => true, 'message' => 'Already subscribed!']);
} else {
    $msg = isset($body['detail']) ? $body['detail'] : 'Something went wrong. Please try again.';
    echo json_encode(['success' => false, 'message' => $msg]);
}
