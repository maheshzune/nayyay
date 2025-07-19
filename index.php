<?php
session_start();
include("db_con/dbCon.php");

// Process the search form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Save search values in session (for now we are only using speciality and location)
    $_SESSION['speciality'] = $_POST['speciality'] ?? "";
    $_SESSION['location'] = $_POST['location'] ?? "";
    
    // Redirect to searchLawyer.php after setting the session
    header("Location: searchLawyer.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>LegalConnect - Modern Legal Solutions</title>
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  
  <!-- SweetAlert CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
  
  <style>
    :root {
      --primary-color: #00274D;
      --accent-color: #FFD700;
      --secondary-color: #6c757d;
      --background-dark: #0f172a;
      --background-light: #f5f7fa;
      --glass-bg: rgba(255, 255, 255, 0.1);
    }
    body {
      font-family: 'Inter', sans-serif;
      background: var(--background-light);
      color: var(--primary-color);
      line-height: 1.6;
      overflow-x: hidden;
    }
    /* Enhanced Navbar */
    .neo-navbar {
      background: rgba(255, 255, 255, 0.95) !important;
      backdrop-filter: blur(15px);
      border-bottom: 1px solid rgba(0, 0, 0, 0.05);
      padding: 1rem 0;
      transition: all 0.3s ease;
    }
    .neo-navbar.scrolled {
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    .navbar-brand img {
      height: 45px;
      transition: all 0.3s ease;
    }
    .nav-link {
      color: var(--primary-color);
      font-weight: 500;
      padding: 0.5rem 1rem;
      border-radius: 8px;
      transition: all 0.3s ease;
    }
    .nav-link:hover, .nav-link.active {
      color: var(--accent-color);
      background: rgba(255, 215, 0, 0.1);
    }
    /* Hero Section */
    .hero-section {
      background: linear-gradient(135deg, var(--primary-color), #1a3766);
      padding: 180px 0 120px;
      position: relative;
      overflow: hidden;
    }
    .hero-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('https://images.unsplash.com/photo-1589829545856-d10d557cf95f') center/cover;
      opacity: 0.1;
      z-index: 0;
    }
    .hero-content {
      position: relative;
      z-index: 2;
      color: white;
      animation: fadeInUp 1s ease-out;
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .search-container {
      background: var(--glass-bg);
      backdrop-filter: blur(15px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }
    .search-container:hover {
      transform: translateY(-5px);
    }
    .form-select {
      border: none;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 12px;
      padding: 0.75rem 1rem;
      transition: all 0.3s ease;
    }
    .form-select:focus {
      box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.3);
      border-color: var(--accent-color);
    }
    /* Lawyer Card */
    .lawyer-card {
      background: white;
      border-radius: 20px;
      padding: 1.5rem;
      transition: all 0.3s ease;
      border: 1px solid rgba(0, 0, 0, 0.05);
    }
    .lawyer-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
    }
    /* Buttons */
    .gradient-btn {
      background: linear-gradient(135deg, var(--accent-color), #E6C200);
      color: var(--primary-color);
      padding: 0.75rem 2rem;
      border-radius: 50px;
      font-weight: 600;
      border: none;
      transition: all 0.3s ease;
    }
    .gradient-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(255, 215, 0, 0.3);
      color: var(--primary-color);
    }
    /* Section Title */
    .section-title {
      font-weight: 700;
      position: relative;
      margin-bottom: 3rem;
      color: var(--primary-color);
      text-align: center;
    }
    .section-title::after {
      content: '';
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: var(--accent-color);
      border-radius: 2px;
    }
    /* Feature & Counter Cards */
    .feature-card, .counter-item {
      background: white;
      padding: 2rem;
      border-radius: 15px;
      transition: all 0.3s ease;
    }
    .feature-card:hover, .counter-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }
    footer {
      background: var(--primary-color);
      color: white;
      padding: 2rem 0;
      margin-top: 4rem;
    }
  </style>
  
  <!-- SweetAlert JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', function() {
      const navbar = document.querySelector('.neo-navbar');
      if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });
  </script>
</head>
<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg neo-navbar fixed-top">
    <div class="container">
      <a class="navbar-brand" href="#">
        <img src="logo.png" alt="LegalConnect">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="lawyers.php">Lawyers</a></li>
          <li class="nav-item"><a class="nav-link" href="service.php">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item ms-3">
            <?php if(isset($_SESSION['login'])): ?>
              <a class="nav-link gradient-btn" href="user_dashboard.php">
                <i class="fas fa-user-circle me-2"></i>
                <?php echo 'Welcome, ' . $_SESSION['first_Name'] . ' ' . $_SESSION['last_Name']; ?>
              </a>
            <?php else: ?>
              <a href="login.php" class="btn gradient-btn">Get Started</a>
            <?php endif; ?>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  <!-- Hero Section -->
  <section class="hero-section">
    <div class="container">
      <div class="hero-content text-center">
        <h1 class="display-4 fw-bold mb-4">Next-Gen Legal Solutions</h1>
        <p class="lead mb-5">Find. Book. Resolve. Your Legal Journey Starts Here.</p>
        <div class="search-container">
          <!-- Search Form: Posts to index.php so we can store search filters in session -->
          <form class="row g-3" method="POST" action="index.php">
            <div class="col-md-4">
              <select name="speciality" class="form-select">
                <option value="" selected>Speciality...</option>
                <option value="Criminal law">Criminal law</option>
                <option value="Civil law">Civil law</option>
                <option value="Writ Jurisdiction">Writ Jurisdiction</option>
                <option value="Company law">Company law</option>
                <option value="Contract law">Contract law</option>
                <option value="Commercial law">Commercial law</option>
                <option value="Construction law">Construction law</option>
                <option value="IT law">IT law</option>
                <option value="Family law">Family law</option>
                <option value="Religious law">Religious law</option>
                <option value="Investment law">Investment law</option>
                <option value="Labour law">Labour law</option>
                <option value="Property law">Property law</option>
                <option value="Taxation law">Taxation law</option>
              </select>
            </div>
            <div class="col-md-4">
              <select name="location" class="form-select">
                <option value="" selected>Location...</option>
                <option value="Mumbai">Mumbai</option>
                <option value="Goa">Goa</option>
                <option value="Surat">Surat</option>
                <option value="Nagpur">Nagpur</option>
                <option value="Bhopal">Bhopal</option>
                <option value="Chandigarh">Chandigarh</option>
              </select>
            </div>
            <div class="col-md-4">
              <button type="submit" class="btn gradient-btn w-100">
                Find Experts <i class="fas fa-arrow-right ms-2"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Top Three Lawyers -->
  <section class="py-5">
    <div class="container">
      <h2 class="section-title">Top Legal Experts</h2>
      <div class="row justify-content-center g-4 text-center">
        <?php 
        $conn = connect();
        $query = "SELECT user.*, lawyer.*,
                    (SELECT COUNT(*) FROM booking WHERE booking.lawyer_id = lawyer.lawyer_id) AS booking_count
                  FROM user 
                  JOIN lawyer ON user.u_id = lawyer.lawyer_id 
                  WHERE user.status = 'Active'
                  ORDER BY booking_count DESC 
                  LIMIT 3";
        $result = mysqli_query($conn, $query);
        $lawyers = [];
        while ($row = mysqli_fetch_assoc($result)) {
          $lawyers[] = $row;
        }

        // Set display order: Rank 2 (index 1), Rank 1 (index 0), Rank 3 (index 2)
        $displayOrder = [1, 0, 2]; 

        foreach ($displayOrder as $index):
          if (isset($lawyers[$index])):
            $lawyer = $lawyers[$index];
            // For display, we'll assign the rank as per the display order.
            $rank = $index + 1;
            // Center rank is where $index==0 in our display order due to our custom order (Rank 1 displayed in center)
            $isCenter = ($index === 0);
        ?>
        <div class="col-lg-4 <?php if($isCenter) echo 'order-2'; elseif($index === 1) echo 'order-1'; else echo 'order-3'; ?>">
          <div class="lawyer-card text-center <?php if($isCenter) echo 'border border-warning shadow-lg'; ?>" 
               style="<?php if($isCenter) echo 'transform: scale(1.15);'; ?>">
            <div class="d-flex flex-column align-items-center mb-3">
              <img src="images/upload/<?= $lawyer['image'] ?>" 
                   class="mb-3" 
                   style="width: <?= $isCenter ? '150px' : '100px' ?>; height: <?= $isCenter ? '150px' : '100px' ?>; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" 
                   alt="<?= $lawyer['first_Name'] ?>">
              <div>
                <h5 class="fw-bold mb-1"><?= $lawyer['first_Name'] ?> <?= $lawyer['last_Name'] ?></h5>
                <small class="text-muted"><?= $lawyer['speciality'] ?></small>
              </div>
            </div>
            <p class="small mb-2 text-muted">🏆 Rank: <?= $rank ?></p>
            <p class="small">Bookings: <?= $lawyer['booking_count'] ?></p>
            <a href="single_lawyer.php?u_id=<?= $lawyer['u_id'] ?>" class="btn gradient-btn btn-sm">
              View Profile <i class="fas fa-arrow-right ms-1"></i>
            </a>
          </div>
        </div>
        <?php 
          endif;
        endforeach;
        ?>
      </div>
    </div>
  </section>
  
  <!-- About & Services -->
  <section class="py-5">
    <div class="container">
      <div class="row g-5">
        <div class="col-lg-6">
          <h2 class="section-title">Who We Are</h2>
          <p class="text-muted">LegalConnect bridges the gap between clients and elite legal professionals through innovative technology.</p>
          <div class="feature-card mt-4">
            <div class="d-flex align-items-center">
              <i class="fas fa-check-circle text-warning me-3" style="font-size: 1.5rem;"></i>
              <div>
                <h5 class="mb-1">Verified Experts</h5>
                <p class="text-muted small mb-0">Only the best, vetted legal professionals</p>
              </div>
            </div>
          </div>
          <a href="about.php" class="btn gradient-btn mt-4">Learn More</a>
        </div>
        <div class="col-lg-6">
          <h2 class="section-title">Our Services</h2>
          <div class="row">
            <div class="col-md-6 mb-4">
              <div class="feature-card">
                <i class="fas fa-gavel text-warning mb-3" style="font-size: 2rem;"></i>
                <h5>Consultation</h5>
                <p class="text-muted small">Expert advice when you need it</p>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <div class="feature-card">
                <i class="fas fa-file-contract text-warning mb-3" style="font-size: 2rem;"></i>
                <h5>Documentation</h5>
                <p class="text-muted small">Professional legal drafting</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Stats -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="row g-4 text-center">
        <div class="col-md-3">
          <div class="counter-item">
            <h2 class="display-5 fw-bold">2500+</h2>
            <p>Cases Won</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="counter-item">
            <h2 class="display-5 fw-bold">98%</h2>
            <p>Satisfaction</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="counter-item">
            <h2 class="display-5 fw-bold">500+</h2>
            <p>Experts</p>
          </div>
        </div>
        <div class="col-md-3">
          <div class="counter-item">
            <h2 class="display-5 fw-bold">24/7</h2>
            <p>Support</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  
  <!-- Footer -->
  <footer class="neo-footer">
    <div class="container text-center">
      <p class="mb-0">© 2025 LegalConnect. All Rights Reserved.</p>
    </div>
  </footer>
  
  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
