<?php
session_start();
include("db_con/dbCon.php"); // Optional: Remove if no DB connection needed
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <title>Lawyer Management System - Services</title>
    <style>
        :root {
            --primary-color: #00274D;       /* Deep navy */
            --accent-color: #FFD700;        /* Bright gold */
            --secondary-color: #1A4066;     /* Lighter navy for contrast */
            --background-color: #F9FAFB;    /* Softer off-white background */
            --text-light: #F5F6FA;         /* Off-white for light text */
            --text-dark: #1F2A44;          /* Softer dark for readability */
            --shadow-sm: 0 4px 12px rgba(0, 0, 0, 0.08);
            --shadow-md: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background-color);
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Enhanced Navbar */
        .neo-navbar {
            background: rgba(255, 255, 255, 0.98) !important;
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
            padding: 1rem 0;
            transition: all 0.3s ease;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .neo-navbar.scrolled {
            box-shadow: var(--shadow-sm);
        }

        .navbar-brand img {
            height: 2.8rem;
            transition: transform 0.3s ease;
        }

        .navbar-brand img:hover {
            transform: scale(1.05);
        }

        .nav-link {
            color: var(--secondary-color);
            font-weight: 500;
            padding: 0.5rem 1.2rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--accent-color) !important;
            background: rgba(255, 215, 0, 0.15);
        }

        /* Better Button Styling */
        .gradient-btn {
            background: linear-gradient(135deg, var(--accent-color), #FFC107);
            border: none;
            color: var(--primary-color);
            padding: 0.8rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(255, 193, 7, 0.25);
        }

        .gradient-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(255, 193, 7, 0.35);
            color: var(--primary-color);
        }

        /* Main Content */
        main.container {
            padding-top: 6rem !important;
            padding-bottom: 4rem;
            flex: 1;
        }

        /* Service Cards */
        .service-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0, 0, 0, 0.02);
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
            height: 100%;
        }

        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
        }

        .service-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .service-card h3 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .service-card p {
            color: var(--text-dark);
            font-size: 0.95rem;
        }

        /* Enhanced Footer */
        .neo-footer {
            background: var(--primary-color);
            color: var(--text-light);
            padding: 2.5rem 0;
            margin-top: auto;
            border-top: 4px solid var(--accent-color);
            position: relative;
            overflow: hidden;
        }

        .neo-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 215, 0, 0.15), transparent);
            opacity: 0.3;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .navbar-brand img {
                height: 2.2rem;
            }

            main.container {
                padding-top: 5rem !important;
            }

            .service-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg neo-navbar fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logo.png" alt="LegalConnect" class="img-fluid">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="lawyers.php">Lawyers</a></li>
                    <li class="nav-item"><a class="nav-link active" href="services.php">Services</a></li>
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
        <h1 class="mb-5 text-center" style="color: var(--primary-color);">Our Legal Services</h1>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="service-card fade-in">
                    <i class="fas fa-gavel service-icon"></i>
                    <h3>Criminal Law</h3>
                    <p>Expert defense for criminal charges, ensuring your rights are protected.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card fade-in">
                    <i class="fas fa-balance-scale service-icon"></i>
                    <h3>Civil Law</h3>
                    <p>Handling disputes over property, contracts, and civil matters.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card fade-in">
                    <i class="fas fa-landmark service-icon"></i>
                    <h3>Writ Jurisdiction</h3>
                    <p>Assistance with writ petitions and constitutional matters.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card fade-in">
                    <i class="fas fa-briefcase service-icon"></i>
                    <h3>Company Law</h3>
                    <p>Support for corporate governance, mergers, and compliance.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card fade-in">
                    <i class="fas fa-file-contract service-icon"></i>
                    <h3>Contract Law</h3>
                    <p>Drafting, reviewing, and resolving contract disputes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card fade-in">
                    <i class="fas fa-building service-icon"></i>
                    <h3>Commercial Law</h3>
                    <p>Legal services for commercial transactions and disputes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card fade-in">
                    <i class="fas fa-tools service-icon"></i>
                    <h3>Construction Law</h3>
                    <p>Guidance on construction contracts and disputes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card fade-in">
                    <i class="fas fa-laptop-code service-icon"></i>
                    <h3>IT Law</h3>
                    <p>Expertise in technology-related legal issues and cybersecurity.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card fade-in">
                    <i class="fas fa-users service-icon"></i>
                    <h3>Family Law</h3>
                    <p>Support for divorce, custody, and family disputes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card fade-in">
                    <i class="fas fa-pray service-icon"></i>
                    <h3>Religious Law</h3>
                    <p>Legal advice on matters involving religious laws and customs.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card fade-in">
                    <i class="fas fa-chart-line service-icon"></i>
                    <h3>Investment Law</h3>
                    <p>Assistance with investment regulations and disputes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card fade-in">
                    <i class="fas fa-user-tie service-icon"></i>
                    <h3>Labour Law</h3>
                    <p>Representation in employment disputes and labor regulations.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card fade-in">
                    <i class="fas fa-home service-icon"></i>
                    <h3>Property Law</h3>
                    <p>Legal services for property transactions and disputes.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card fade-in">
                    <i class="fas fa-coins service-icon"></i>
                    <h3>Taxation Law</h3>
                    <p>Expert advice on tax compliance and disputes.</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="neo-footer">
        <div class="container text-center">
            <p class="mb-0">© <?php echo date("Y"); ?> LegalConnect. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
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