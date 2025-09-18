<?php
session_start();
require_once 'config.php';
require_once 'csrf_helper.php';

// Require CSRF token for all POST requests
requireCSRFToken();

function backWith($key, $message) {
    $_SESSION[$key] = $message;
    header('Location: index.php#contact');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    backWith('contact_error', 'Invalid request.');
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $email === '' || $message === '') {
    backWith('contact_error', 'Please complete all fields.');
}

try {
    $stmt = $conn->prepare('INSERT INTO message (name, email, message) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $name, $email, $message);
    $stmt->execute();
    $stmt->close();
    backWith('contact_success', 'Thanks! Your message has been sent.');
} catch (Throwable $e) {
    backWith('contact_error', 'Could not send message.');
}


