<?php
session_start();
include("db_con/dbCon.php");
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #00274D;       /* Deep navy blue */
            --accent-color: #FFD700;        /* Bright gold */
            --secondary-color: #1A4066;     /* Lighter navy for contrast */
            --background-light: #F9FAFB;    /* Soft off-white background */
            --text-dark: #1F2A44;          /* Softer dark text */
            --glass-bg: rgba(255, 255, 255, 0.97); /* Opaque glass effect */
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08); /* Soft small shadow */
            --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.12); /* Medium shadow */
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background-light);
            color: var(--text-dark);
            line-height: 1.6;
            margin: 0; /* Remove default margin */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navbar */
        .neo-navbar {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
            padding: 1rem 0;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            transition: box-shadow 0.3s ease;
        }

        .neo-navbar.scrolled {
            box-shadow: var(--shadow-sm);
        }

        .navbar-brand img {
            height: 50px;
            transition: transform 0.3s ease;
        }

        .navbar-brand img:hover {
            transform: scale(1.05);
        }

        .nav-link {
            color: var(--secondary-color);
            font-weight: 500;
            padding: 0.5rem 1.2rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--accent-color);
            background: rgba(255, 215, 0, 0.15);
        }

        /* Buttons */
        .gradient-btn,
        .search-btn {
            background: linear-gradient(135deg, var(--accent-color), #FFC107);
            color: var(--primary-color);
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.25);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .gradient-btn:hover,
        .search-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.35);
            color: var(--primary-color);
        }

        .search-btn i {
            margin-right: 0.5rem;
        }

        /* Lawyer Card */
        .lawyer-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.02);
            box-shadow: var(--shadow-sm);
        }

        .lawyer-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
        }

        .specialty-badge {
            background: linear-gradient(135deg, var(--accent-color), #FFC107);
            color: var(--primary-color);
            font-weight: 600;
            border-radius: 10px;
            padding: 0.4rem 1rem;
            font-size: 0.9rem;
            display: inline-block;
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.2);
        }

        .experience-chip {
            background: rgba(26, 64, 102, 0.08);
            color: var(--secondary-color);
            border-radius: 25px;
            padding: 0.4rem 1.2rem;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.3s ease;
        }

        .experience-chip:hover {
            background: rgba(26, 64, 102, 0.12);
        }

        /* Main Content */
        main.container {
            padding-top: 120px;
            padding-bottom: 60px;
            flex: 1;
        }

        .section-title {
            color: var(--secondary-color);
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 40px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, var(--accent-color), #FFC107);
            border-radius: 2px;
        }

        /* Login Prompt */
        .login-prompt {
            background: var(--primary-color);
            color: white;
            padding: 2rem 3rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            margin: 60px auto; /* Center with spacing */
        }

        /* Footer */
        .footer {
            background: var(--primary-color);
            color: #F5F6FA;
            padding: 3rem 0;
            margin-top: auto;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.15), transparent);
            opacity: 0.3;
        }

        .footer h5 {
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: white;
        }

        .footer a {
            color: var(--accent-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer a:hover {
            color: #F5F6FA;
            padding-left: 0.5rem;
        }

        .footer .social-icons a {
            font-size: 1.5rem;
            margin: 0 0.75rem;
            transition: all 0.3s ease;
        }

        .footer .social-icons a:hover {
            transform: translateY(-3px);
            color: #FFC107;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeInUp 0.6s ease-out forwards;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .navbar-brand img {
                height: 40px;
            }

            main.container {
                padding-top: 100px;
            }

            .lawyer-card {
                padding: 1rem;
            }

            .login-prompt {
                margin: 30px auto;
                padding: 1.5rem 2rem;
            }

            .section-title {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 576px) {
            .gradient-btn,
            .search-btn {
                padding: 0.6rem 1.5rem;
                font-size: 0.9rem;
            }

            .footer {
                padding: 2rem 0;
            }
        }
    </style>
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link active" href="lawyers.php">Lawyers</a></li>
                    <li class="nav-item"><a class="nav-link" href="service.php">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item ms-3">
                        <?php if(isset($_SESSION['login'])): ?>
                            <a href="user_dashboard.php" class="btn gradient-btn">Dashboard</a>
                        <?php else: ?>
                            <a href="login.php" class="btn gradient-btn">Get Started</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container">
        <?php if(isset($_SESSION['login'])): ?>
            <div class="d-flex justify-content-between align-items-center mb-5 fade-in">
                <h1 class="section-title">Expert Legal Professionals</h1>
                <a href="searchLawyer.php" class="search-btn">
                    <i class="fas fa-search me-2"></i> Find Lawyer
                </a>
            </div>

            <div class="row g-4">
                <?php
                $conn = connect();
                $result = mysqli_query($conn, "SELECT * FROM user, lawyer WHERE user.u_id = lawyer.lawyer_id AND user.status = 'Active'");
                while($row = mysqli_fetch_array($result)) { ?>
                    <div class="col-lg-4 col-md-6 fade-in">
                        <div class="lawyer-card">
                            <div class="d-flex align-items-center mb-4">
                                <img src="images/upload/<?php echo $row["image"]; ?>" 
                                     class="rounded-circle me-3" 
                                     style="width: 80px; height: 80px; object-fit: cover; border: 2px solid var(--accent-color);">
                                <div>
                                    <h4 class="mb-2" style="color: var(--primary-color);">
                                        <?php echo $row["first_Name"]." ".$row["last_Name"]; ?>
                                    </h4>
                                    <div class="specialty-badge">
                                        <?php echo $row["speciality"]; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="experience-chip">
                                    <i class="fas fa-briefcase"></i>
                                    <?php echo $row["practise_Length"]; ?> Exp
                                </div>
                                <div style="color: var(--secondary-color);">
                                    <i class="fas fa-star" style="color: var(--accent-color);"></i> 4.9
                                </div>
                            </div>
                            <a href="single_lawyer.php?u_id=<?php echo $row["u_id"]; ?>" 
                               class="btn btn-block gradient-btn">
                                View Profile <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php else: ?>
            <div class="login-prompt fade-in">
                <h2>Login to Continue</h2>
                <p>Please log in to view lawyer profiles and book consultations.</p>
                <a href="login.php" class="btn gradient-btn">Login Now</a>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5>LegalConnect</h5>
                    <p class="opacity-75">Connecting you with trusted legal professionals nationwide.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="about.php"><i class="fas fa-chevron-right me-2"></i>About Us</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Services</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Connect With Us</h5>
                    <div class="social-icons">
                        <a href="#" class="text-accent"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-accent"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-accent"><i class="fab fa-facebook"></i></a>
                    </div>
                </div>
            </div>
            <hr class="my-4 opacity-25">
            <div class="text-center pt-2">
                <small class="opacity-75">© 2023 LegalConnect. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.neo-navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>