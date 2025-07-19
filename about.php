<?php
session_start();
include("db_con/dbCon.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LegalConnect - Modern Legal Solutions</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Glassmorphism CSS -->
    <style>
        :root {
            --primary-color: #00274D;
            --accent-color: #FFD700;
            --gradient-start: rgba(0, 39, 77, 0.9);
            --gradient-end: rgba(0, 39, 77, 0.7);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
        }

        .neo-navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand img {
            height: 50px;
        }

        .nav-link {
            color: var(--primary-color) ;
            font-weight: 500;
        }

        .nav-link.active {
            color: var(--accent-color) !important;
            font-weight: 600;
        }
        .nav-link:hover{
            color:rgb(122, 144, 232);
        }
        

        .hero-section {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end)),
                        url('https://images.unsplash.com/photo-1589829545856-d10d557cf95f') center/cover;
            padding: 160px 0 100px;
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
        }

        .search-container {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .lawyer-card {
            background: white;
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
        }

        .lawyer-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .gradient-btn {
            background: linear-gradient(135deg, var(--accent-color), #E6C200);
            border: none;
            color: var(--primary-color);
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .gradient-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        }

        .section-title {
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 40px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--accent-color);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: rgba(255, 215, 0, 0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--accent-color);
            margin-bottom: 20px;
        }
		.counter-item {
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }
    .counter-item:hover {
        transform: translateY(-5px);
    }
    .counter-item h2 {
        font-weight: 700;
        margin-bottom: 10px;
    }
    .counter-item p {
        font-size: 1.1rem;
        margin-bottom: 0;
    }
    .containera{
        margin-top: 80px;
    }

        .section {
            background-color: white;
            padding: 2rem;
            border-radius: 8px;
            margin-top: 50px;
            margin: 2rem 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        h2 {
            color: #00274D;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid #FFD700;
            padding-bottom: 0.5rem;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .feature-card {
            padding: 1.5rem;
            border-left: 4px solid #FFD700;
            background-color: #F8F9FA;
        }

        .cta-section {
            text-align: center;
            padding: 3rem;
            background-color: #00274D;
            color: white;
            margin-top: 2rem;
        }

        .button {
            display: inline-block;
            padding: 1rem 2rem;
            margin: 1rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.3s ease;
        }

        .button-primary {
            background-color: #FFD700;
            color: #00274D;
        }

        .button-secondary {
            background-color: #00274D;
            color: #FFD700;
            border: 2px solid #FFD700;
        }

        .button:hover {
            transform: translateY(-2px);
        }

        footer {
            background-color: #00274D;
            color: #FFD700;
            text-align: center;
            padding: 1.5rem;
            margin-top: 3rem;
        }

        .how-it-works {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .how-it-works {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Modern Glassmorphic Navigation -->
    <nav class="navbar navbar-expand-lg neo-navbar fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logo.png" alt="LegalConnect" style="height: 60px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link " href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="lawyers.php">Lawyers</a></li>
                    <li class="nav-item"><a class="nav-link" href="service.php">Services</a></li>
                    <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
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

    <div class="containera">
        <div class="section">
            <h2>About Our Platform</h2>
            <p>LegalConnect is a premier digital platform designed to seamlessly connect qualified legal professionals with clients in need of expert legal services. Our mission is to simplify the process of finding and retaining legal counsel while providing lawyers with quality client opportunities.</p>
            
            <div class="how-it-works">
                <div>
                    <h3>For Lawyers</h3>
                    <div class="feature-card">
                        <p>1. Create your professional profile</p>
                        <p>2. Showcase your expertise and experience</p>
                        <p>3. Connect with clients needing your services</p>
                        <p>4. Manage cases through our secure platform</p>
                    </div>
                </div>
                <div>
                    <h3>For Clients</h3>
                    <div class="feature-card">
                        <p>1. Register your legal needs</p>
                        <p>2. Search qualified lawyers by specialty</p>
                        <p>3. Compare profiles and reviews</p>
                        <p>4. Connect with your chosen attorney</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="section">
            <h2>Why Choose LegalConnect?</h2>
            <div class="features">
                <div class="feature-card">
                    <h4><i class="fas fa-shield-alt"></i> Verified Professionals</h4>
                    <p>All lawyers undergo rigorous verification and credential checks</p>
                </div>
                <div class="feature-card">
                    <h4><i class="fas fa-lock"></i> Secure Communication</h4>
                    <p>End-to-end encrypted messaging and document sharing</p>
                </div>
                <div class="feature-card">
                    <h4><i class="fas fa-star"></i> Rating System</h4>
                    <p>Transparent client reviews and peer evaluations</p>
                </div>
            </div>
        </div>
    </div>

    <div class="cta-section">
        <h2>Ready to Get Started?</h2>
        <p>Join our growing community of legal professionals and clients</p>
        <a href="#" class="button button-primary">I'm a Lawyer</a>
        <a href="#" class="button button-secondary">I Need a Lawyer</a>
    </div>
   
<footer class ="bg-light">
			<div class="container ">
				<div class="row">
					<div class="col">
					<center>	<h5>All rights reserved &copy; 2025</h5></center>
					</div>
				</div>
			</div>
		</footer>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/your-kit-code.js"></script>
</body>
</html>