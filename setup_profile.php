<?php
session_start();
include 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id_num']);
    $course_year = trim($_POST['course_year']);
    $user_id = $_SESSION['user_id'];

    if (!empty($student_id) && !empty($course_year)) {
        // Save Student ID and Course/Year to user record (ID remains unlocked until verified by admin)
        $stmt = $conn->prepare("UPDATE users SET student_id_num = ?, course_year = ? WHERE id = ?");
        $stmt->bind_param("ssi", $student_id, $course_year, $user_id);
        
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Setup - ALERTO</title>
    <link rel="stylesheet" href="styles/base.css">
    <link rel="stylesheet" href="styles/components.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f6f8;">

    <div style="background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); width: 100%; max-width: 400px;">
        <h2 style="margin-bottom: 0.5rem; color: #800000;">Complete Your Profile</h2>
        <p style="margin-bottom: 1.5rem; color: #666; font-size: 0.9rem;">Welcome, <strong><?php echo htmlspecialchars($_SESSION['full_name']); ?></strong>! Please enter your academic details to continue.</p>

        <form method="POST" action="setup_profile.php">
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Student ID Number</label>
                <input type="text" name="student_id_num" placeholder="e.g. 23-12345" required style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: bold;">Course & Year</label>
                <input type="text" name="course_year" placeholder="e.g. BSCE 2" required style="width: 100%; padding: 0.75rem; border: 1px solid #ccc; border-radius: 4px;">
            </div>

            <button type="submit" style="width: 100%; padding: 0.75rem; background-color: #800000; color: #fff; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">Save & Continue</button>
        </form>
    </div>

</body>
</html>