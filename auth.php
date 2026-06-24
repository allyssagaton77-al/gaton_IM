<?php
session_start();

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['role']) &&
           $_SESSION['role'] === 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        die("Access denied");
    }
}

function getUsername() {
    return $_SESSION['username'] ?? 'Guest';
}