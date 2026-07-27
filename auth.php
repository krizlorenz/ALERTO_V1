<?php
session_start();
include 'db.php';

// Example: Handling payload sent from Google/Facebook OAuth SDK or form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $provider = $_POST['social_provider']; // 'google' or 'facebook'
    $provider_id = $_POST['social_provider_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];

    // 1. Check if user already exists
    $stmt = $conn->prepare("SELECT id, status, student_id_num, role FROM users WHERE social_provider_id = ?");
    $stmt->bind_param("s", $provider_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if user is Banned
        if ($user['status'] === 'Banned') {
            die("Your account has been blacklisted due to terms violation.");
        }

        // Log existing user in
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['full_name'] = $full_name;
        $_SESSION['role'] = $user['role'];
        $_SESSION['status'] = $user['status'];

        header("Location: index.php");
        exit();

    } else {
        // 2. First-time user registration (Unverified state)
        $insert_stmt = $conn->prepare("INSERT INTO users (social_provider, social_provider_id, full_name, email, status) VALUES (?, ?, ?, ?, 'Unverified')");
        $insert_stmt->bind_param("ssss", $provider, $provider_id, $full_name, $email);
        
        if ($insert_stmt->execute()) {
            $_SESSION['user_id'] = $insert_stmt->insert_id;
            $_SESSION['full_name'] = $full_name;
            $_SESSION['role'] = 'student';
            $_SESSION['status'] = 'Unverified';

            // Redirect to profile setup modal/page to ask for Student ID & Course/Year
            header("Location: setup_profile.php");
            exit();
        }
    }
}
?>