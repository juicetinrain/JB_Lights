<?php
// save_active_tab.php - Save active tab to session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_active_tab') {
    $tab = $_POST['tab'] ?? '';
    $type = $_POST['type'] ?? '';
    
    if ($type === 'profile') {
        $_SESSION['profile_active_tab'] = $tab;
    } elseif ($type === 'admin') {
        $_SESSION['admin_active_tab'] = $tab;
    }
    
    echo json_encode(['success' => true]);
    exit();
}

http_response_code(400);
echo json_encode(['success' => false, 'error' => 'Invalid request']);