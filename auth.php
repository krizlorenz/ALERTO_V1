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

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — ALERTO</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="styles/variables.css">
    <link rel="stylesheet" href="styles/base.css">
    <link rel="stylesheet" href="styles/layout.css">
    <link rel="stylesheet" href="styles/components.css">
    <link rel="stylesheet" href="styles/sections.css">
    <link rel="stylesheet" href="styles/animations.css">
    <link rel="stylesheet" href="styles/utilities.css">
    </head>
    <body>

    <!-- Ticker -->
    <div class="ticker-bar">
        <div class="live-tag"><span class="dot"></span> LIVE ADVISORY</div>
        <div class="ticker-track">
        <div class="ticker-content">
            <span>No active emergency advisories at this time. Stay safe, COEAns!</span>
        </div>
        </div>
    </div>

    <!-- Login / Portal section -->
    <section class="login-wrap">

        <div class="login-copy">
        <span class="pill pill-solid">Official platform</span>
        <h1 class="login-heading">Welcome to<span>ALERTO</span></h1>
        <p class="login-sub">
            ALERTO is a web-based platform that lets affected students request assistance,
            follow official advisories, and stay informed — while giving the COEA Student
            Council a clear, centralized way to review, prioritize, and respond.
        </p>
        </div>

        <div class="portal-card">

        <div class="portal-head">
            <div class="portal-avatar icon-maroon">
            <img src="icons/user.png" alt="" class="icon icon-lg">
            </div>
            <div>
            <h2 class="portal-title">User Portal</h2>
            <p class="portal-sub">For students, faculty, and community services who need assistance.</p>
            </div>
        </div>

        <a href="credentials.html" class="btn btn-primary w-full">
            Sign in
            <img src="icons/arrow-right.png" alt="" class="icon icon-sm">
        </a>

        <div class="divider-or"><span>OR</span></div>

        <div class="oauth-group">
            <a href="#" class="btn btn-oauth w-full">
            <img src="icons/google.png" alt="" class="icon icon-md">
            Connect with Google
            </a>
            <a href="#" class="btn btn-oauth w-full">
            <img src="icons/facebook.png" alt="" class="icon icon-md">
            Connect with Facebook
            </a>
        </div>

        <div class="hotline-box">
            <div class="advisory-icon icon-maroon">
            <img src="icons/phone.png" alt="" class="icon icon-md">
            </div>
            <div class="hotline-text">
            <p class="hotline-label">Need immediate help?</p>
            <p class="hotline-sub">contact this emergency hotline</p>
            <a href="tel:09059677194" class="hotline-number">0905 967 7194</a>
            </div>
        </div>

        <a href="admin-login.html" class="link-view-all admin-link">
            Admin Portal <img src="icons/arrow-up-right.png" alt="" class="icon icon-sm">
        </a>
        </div>

    </section>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-grid">
        <div class="footer-brand">
            <div class="brand">
            <div class="logo" aria-hidden="true">
                <img src="logo/csulogo.png">
            </div>
            <div class="brand-text">
                <span class="brand-name">ALERTO</span>
                <span class="brand-sub">CSU-CARIG Student Council</span>
            </div>
            </div>
            <p class="footer-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride</p>
            <div class="social-row">
            <span><img src="icons/user.png"></span>
            <span><img src="icons/facebook.png"></span>
            <span><img src="icons/mess.png"></span>
            <span><img src="icons/insta.png"></span>
            </div>
        </div>

        <div class="footer-col">
            <h5>Quick links</h5>
            <a href="#">Live Board</a>
            <a href="#">Advisories</a>
            <a href="#">Resources</a>
            <a href="#">About us</a>
            <a href="#">Contact</a>
        </div>

        <div class="footer-col">
            <h5>Contact us</h5>
            <a href="mailto:krizlorenz30@gmail.com">✉ krizlorenz30@gmail.com</a>
            <a href="tel:09059677194">📞 0905 967 7194</a>
            <a href="#">📍 CollegeOfEngineering-StudentEdition@gmail.com</a>
        </div>

        <div class="footer-col">
            <h5>Stay Updated</h5>
            <p class="footer-desc small">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride</p>
            <form class="subscribe-form">
            <input type="email" placeholder="Enter your email here.">
            <button type="submit">→</button>
            </form>
        </div>
        </div>

        <div class="footer-bottom">
        <span>@ALERTO - Cagayan State University-COEA Student Council</span>
        <span>Always Ready. Always Here.</span>
        </div>
    </footer>

    </body>
</html>