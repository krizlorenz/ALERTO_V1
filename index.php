<?php
session_start();
include 'db.php';

// Fetch the latest announcements from database
$announcement_query = "SELECT * FROM announcements ORDER BY created_at DESC LIMIT 5";
$announcements_result = $conn->query($announcement_query);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ALERTO — CSU-Carig Student Council</title>
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
    <div class="ticker-bar">
      <div class="live-tag"><span class="dot"></span> LIVE ADVISORY</div>
      <div class="ticker-track">
        <div class="ticker-content">
          <?php if (isset($announcements_result) && $announcements_result->num_rows > 0): ?>
              <?php while($row = $announcements_result->fetch_assoc()): ?>
                  <span>
                      <strong>[<?php echo htmlspecialchars($row['category']); ?>]</strong> 
                      <?php echo htmlspecialchars($row['title']); ?> — <?php echo htmlspecialchars($row['content']); ?>
                  </span>
              <?php endwhile; ?>
          <?php else: ?>
              <span>No active emergency advisories at this time. Stay safe, COEAns!</span>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <header class="navbar">
      <div class="brand">
        <div class="logo">
          <img src="logo/csulogo.png" alt="CSU Logo">
        </div>

        <div class="brand-text">
          <span class="brand-name">ALERTO</span>
          <span class="brand-sub">CSU-CARIG Student Council</span>
        </div>
      </div>

      <nav class="main-nav">
        <a href="#" class="active">Home</a>
        <a href="#">Live Board</a>
        <a href="#">Advisories</a>
        <a href="#">Resources</a>
        <a href="#">About</a>
        <a href="#">Contact</a>
      </nav>

      <button class="menu-toggle" id="menuToggle" aria-label="Toggle Menu">
        <span></span>
        <span></span>
        <span></span>
      </button>

      <div class="nav-right">
        <a href="request.php" class="btn btn-primary">
          ★ Request Help
        </a>
      </div>

    </header>

    <!-- Hero -->
    <section class="hero">
      <div class="hero-image">
        <img src="images/coea.png" alt="COEA building">

        <div class="hero-content">
          <span class="pill">ALERTO: Always Ready. Always Here.</span>
          <h1>Coordinated relief, <span>when it matters most.</span></h1>
          <p class="hero-copy">ALERTO is a web-based platform that lets affected students request assistance, follow official advisories, and stay informed — while giving the COEA Student Council a clear, centralized way to review, prioritize, and respond.</p>
          <div class="hero-actions">
            <a href="#" class="btn btn-outline">★ View Live Board</a>
            <a href="request.php" class="btn btn-primary">★ Request Assistance</a>
          </div>
        </div>
      </div>
    </section>

    <!-- Stat strip -->
    <section class="stat-strip">
      <a href="#" class="stat-item">
        <div class="stat-icon">
          <img src="icons/question.png" alt="list-icon">
        </div>
        <div class="stat-info">
          <div class="stat-number">18 <span>Active requests</span></div>
          <div class="stat-desc">Needs immediate assistance</div>
          <div class="stat-trend">12% up since monday!</div>
        </div>
      </a>
      <a href="#" class="stat-item">
        <div class="stat-icon">
          <img src="icons/question.png" alt="run-icon">
        </div>
        <div class="stat-info">
          <div class="stat-number">10 <span>Active requests</span></div>
          <div class="stat-desc">Needs immediate assistance</div>
          <div class="stat-trend">12% up since monday!</div>
        </div>
      </a>
      <a href="#" class="stat-item">
        <div class="stat-icon">
          <img src="icons/question.png" alt="call-icon">
        </div>
        <div class="stat-info">
          <div class="stat-number">26 <span>Active requests</span></div>
          <div class="stat-desc">Needs immediate assistance</div>
          <div class="stat-trend">12% up since monday!</div>
        </div>
      </a>
      <a href="#" class="stat-item">
        <div class="stat-icon">
          <img src="icons/question.png" alt="hat-icon">
        </div>
        <div class="stat-info">
          <div class="stat-number">12 <span>Active requests</span></div>
          <div class="stat-desc">Needs immediate assistance</div>
          <div class="stat-trend">12% up since monday!</div>
        </div>
      </a>
    </section>

    <!-- Live Board Overview -->
<section class="section">
    <div class="section-head">
        <div>
            <h2>Live Board Overview</h2>
            <p class="section-sub">
                Real-Time Updates from the CSU-Carig COEA Student Council
            </p>
        </div>
        <a href="#" class="btn btn-ghost">
            View Live Board →
        </a>
    </div>
</section>

    <!-- Advisories + Quick access -->
    <section class="section two-col">
      <div class="panel">
        <div class="section-head">
          <div>
            <h3>Latest Advisories</h3>
            <p class="section-sub">Real Time Updates from the CSU-Carig COEA Student Council</p>
          </div>
          <a href="#" class="link-view-all">View All</a>
        </div>

        <ul class="advisory-list">
          <li>
            <div class="advisory-icon icon-purple">
              <img src="icons/settings.png">
            </div>
            <div class="advisory-text">
              <p>Signal No. 2 raised over Cagayan Valley — classes</p>
              <span class="time">8:00 AM</span>
            </div>
            <span class="tag tag-weather">Weather</span>
          </li>
          <li>
            <div class="advisory-icon icon-orange">
              <img src="icons/notif.png">
            </div>
            <div class="advisory-text">
              <p>Water level near Carig bridge steady since 5 AM check.</p>
              <span class="time">8:00 AM</span>
            </div>
            <span class="tag tag-announcement">Announcement</span>
          </li>
          <li>
            <div class="advisory-icon icon-green">
              <img src="icons/user.png">
            </div>
            <div class="advisory-text">
              <p>Signal No. 2 raised over Cagayan Valley — classes suspended, relief hub open at the covered court.</p>
              <span class="time">8:00 AM</span>
            </div>
            <span class="tag tag-relief">Relief</span>
          </li>
        </ul>
        <p class="stay-informed">Stay informed. Stay safe.</p>
      </div>

      <div class="panel">
        <div class="section-head">
          <div>
            <h3>Quick Access</h3>
            <p class="section-sub">Real Time Updates from the CSU-Carig COEA Student Council</p>
          </div>
          <a href="#" class="link-view-all">View All</a>
        </div>

        <div class="quick-grid">
          <a href="#" class="quick-card qc-maroon">
            <div class="quick-top"><span class="quick-icon">
              <img src="icons/headphone.png">
            </span><span class="arrow">↗</span></div>
            <p class="quick-title">Request Help</p>
            <p class="quick-desc">For students, faculty, assistance.</p>
          </a>
          <a href="#" class="quick-card qc-orange">
            <div class="quick-top"><span class="quick-icon">
              <img src="icons/warning.png">
            </span><span class="arrow">↗</span></div>
            <p class="quick-title">Report Incident</p>
            <p class="quick-desc">For students, faculty, assistance.</p>
          </a>
          <a href="#" class="quick-card qc-green">
            <div class="quick-top"><span class="quick-icon">
              <img src="icons/search.png">
            </span><span class="arrow">↗</span></div>
            <p class="quick-title">Find Resources</p>
            <p class="quick-desc">For students, faculty, assistance.</p>
          </a>
          <a href="#" class="quick-card qc-pink">
            <div class="quick-top"><span class="quick-icon">
              <img src="icons/headphone.png">
            </span><span class="arrow">↗</span></div>
            <p class="quick-title">Request Help</p>
            <p class="quick-desc">For students, faculty, assistance.</p>
          </a>
        </div>
        <a href="#" class="see-more">See more →</a>
      </div>
    </section>

    <!-- Relief Operations -->
    <section class="section relief-section">
      <h2 class="relief-heading">RELIEF OPERATIONS</h2>
      <div class="relief-grid">
        <div class="relief-card">
          <div class="relief-img" style="background:linear-gradient(135deg,#C23B59,#7B0525)"></div>
          <div class="relief-info">
            <h4>Relief goods — Building C evacuees</h4>
            <p>34 families served, distribution closed 4:10 PM.</p>
            <span class="time">8:00 AM</span>
          </div>
        </div>
        <div class="relief-card">
          <div class="relief-img" style="background:linear-gradient(135deg,#F05923,#C23B59)"></div>
          <div class="relief-info">
            <h4>Medical assist — covered court</h4>
            <p>First aid team dispatched, 3 students attended to.</p>
            <span class="time">8:00 AM</span>
          </div>
        </div>
        <div class="relief-card">
          <div class="relief-img" style="background:linear-gradient(135deg,#258244,#7B0525)"></div>
          <div class="relief-info">
            <h4>Rescue — flooded parking area</h4>
            <p>12 vehicles moved to higher ground before 6 AM.</p>
            <span class="time">8:00 AM</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="footer-grid">
        <div class="footer-brand">
          <div class="brand">
            <div class="logo" aria-hidden="true">
              <img  src="logo/csulogo.png">
            </div>
            <div class="brand-text">
              <span class="brand-name">ALERTO</span>
              <span class="brand-sub">CSU-CARIG Student Council</span>
            </div>
          </div>
          <p class="footer-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since 1966, when designers at Letraset and James Mosley, the librarian at St Bride</p>
          <div class="social-row">
            <span><img src="icons/mail.png"></span>
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
