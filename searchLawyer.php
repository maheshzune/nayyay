<?php
session_start();
include("db_con/dbCon.php");

// Initialize filter variables
$experience = "";
$speciality = "";
$location = "";

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Use null coalescing operator to set defaults if fields are empty
    $experience = $_POST['experience'] ?? "";
    $speciality = $_POST['speciality'] ?? "";
    $location   = $_POST['location'] ?? "";
    
    // Save search values in session
    $_SESSION['experience'] = $experience;
    $_SESSION['speciality'] = $speciality;
    $_SESSION['location']   = $location;
} else {
    // Retrieve values from session if available
    $experience = $_SESSION['experience'] ?? "";
    $speciality = $_SESSION['speciality'] ?? "";
    $location   = $_SESSION['location'] ?? "";
}
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

    <style>
        :root {
            --primary-color: #00274D;
            --accent-color: #FFD700;
            --secondary-color: #1A4066;
            --background-color: #F9FAFB;
            --text-light: #F5F6FA;
            --text-dark: #1F2A44;
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
        /* Navbar */
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
        .nav-link:hover, .nav-link.active {
            color: var(--accent-color) !important;
            background: rgba(255, 215, 0, 0.15);
        }
        /* Search Form */
        .search-form .form-select {
            border: 2px solid var(--secondary-color);
            border-radius: 12px;
            padding: 0.8rem 1.2rem;
            font-size: 0.95rem;
            background: white;
            transition: all 0.3s ease;
        }
        .search-form .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(255, 215, 0, 0.25);
        }
        .search-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--secondary-color);
            font-size: 1rem;
        }
        /* Lawyer Card */
        .lawyer-card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.02);
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
            height: 100%;
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
            padding: 0.4rem 1rem;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }
        .experience-chip:hover {
            background: rgba(26, 64, 102, 0.12);
        }
        /* Footer */
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
        /* Button Styling */
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
        /* Profile Image in Lawyer Card */
        .lawyer-card img {
            border: 2px solid var(--accent-color);
            padding: 2px;
            transition: transform 0.3s ease;
        }
        .lawyer-card:hover img {
            transform: scale(1.05);
        }
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 0;
            color: var(--secondary-color);
        }
        .empty-state i {
            font-size: 3rem;
            color: var(--accent-color);
            margin-bottom: 1.5rem;
        }
        .empty-state h3 {
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--primary-color);
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
            .search-form .form-select {
                margin-bottom: 1rem;
            }
            .lawyer-card {
                padding: 1rem;
                margin-bottom: 1.2rem;
            }
            main.container {
                padding-top: 5rem !important;
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
        
        <!-- Search Section -->
        <section class="fade-in">
            <form method="post" class="search-form">
                <div class="row g-4">
                    <div class="col-md-4">
                        <label>Experience</label>
                        <select name="experience" class="form-select">
                            <option value="" <?php echo ($experience === "") ? "selected" : ""; ?>>Choose...</option>
                            <option value="1-5 years" <?php echo ($experience === "1-5 years") ? "selected" : ""; ?>>1-5 years</option>
                            <option value="6-10 years" <?php echo ($experience === "6-10 years") ? "selected" : ""; ?>>6-10 years</option>
                            <option value="11-15 years" <?php echo ($experience === "11-15 years") ? "selected" : ""; ?>>11-15 years</option>
                            <option value="16-20 years" <?php echo ($experience === "16-20 years") ? "selected" : ""; ?>>16-20 years</option>
                            <option value="Most Senior" <?php echo ($experience === "Most Senior") ? "selected" : ""; ?>>Most Senior</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Speciality</label>
                        <select name="speciality" class="form-select">
                            <option value="" <?php echo ($speciality === "") ? "selected" : ""; ?>>Choose...</option>
                            <option value="Criminal law" <?php echo ($speciality === "Criminal law") ? "selected" : ""; ?>>Criminal law</option>
                            <option value="Civil law" <?php echo ($speciality === "Civil law") ? "selected" : ""; ?>>Civil law</option>
                            <option value="Writ Jurisdiction" <?php echo ($speciality === "Writ Jurisdiction") ? "selected" : ""; ?>>Writ Jurisdiction</option>
                            <option value="Company law" <?php echo ($speciality === "Company law") ? "selected" : ""; ?>>Company law</option>
                            <option value="Contract law" <?php echo ($speciality === "Contract law") ? "selected" : ""; ?>>Contract law</option>
                            <option value="Commercial law" <?php echo ($speciality === "Commercial law") ? "selected" : ""; ?>>Commercial law</option>
                            <option value="Construction law" <?php echo ($speciality === "Construction law") ? "selected" : ""; ?>>Construction law</option>
                            <option value="IT law" <?php echo ($speciality === "IT law") ? "selected" : ""; ?>>IT law</option>
                            <option value="Family law" <?php echo ($speciality === "Family law") ? "selected" : ""; ?>>Family law</option>
                            <option value="Religious law" <?php echo ($speciality === "Religious law") ? "selected" : ""; ?>>Religious law</option>
                            <option value="Investment law" <?php echo ($speciality === "Investment law") ? "selected" : ""; ?>>Investment law</option>
                            <option value="Labour law" <?php echo ($speciality === "Labour law") ? "selected" : ""; ?>>Labour law</option>
                            <option value="Property law" <?php echo ($speciality === "Property law") ? "selected" : ""; ?>>Property law</option>
                            <option value="Taxation law" <?php echo ($speciality === "Taxation law") ? "selected" : ""; ?>>Taxation law</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Location</label>
                        <select name="location" class="form-select">
                            <option value="" <?php echo ($location === "") ? "selected" : ""; ?>>Choose...</option>
                            <option value="Mumbai" <?php echo ($location === "Mumbai") ? "selected" : ""; ?>>Mumbai</option>
                            <option value="Goa" <?php echo ($location === "Goa") ? "selected" : ""; ?>>Goa</option>
                            <option value="Surat" <?php echo ($location === "Surat") ? "selected" : ""; ?>>Surat</option>
                            <option value="Nagpur" <?php echo ($location === "Nagpur") ? "selected" : ""; ?>>Nagpur</option>
                            <option value="Bhopal" <?php echo ($location === "Bhopal") ? "selected" : ""; ?>>Bhopal</option>
                            <option value="Chandigarh" <?php echo ($location === "Chandigarh") ? "selected" : ""; ?>>Chandigarh</option>
                        </select>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" name="submit" class="btn gradient-btn">
                            <i class="fas fa-search me-2"></i>Search Lawyers
                        </button>
                    </div>
                </div>
            </form>
        </section>

        <!-- Results Section -->
        <section class="result my-5 fade-in">
            <div class="row g-4">
                <?php
                $conn = connect();
                // Check if any filter is provided
                if (!empty($experience) || !empty($speciality) || !empty($location)) {
                    // Build conditions array; using AND logic so all filters must match if provided
                    $conditions = [];
                    if (!empty($experience)) {
                        $conditions[] = "practise_Length = '$experience'";
                    }
                    if (!empty($speciality)) {
                        $conditions[] = "speciality = '$speciality'";
                    }
                    if (!empty($location)) {
                        $conditions[] = "city = '$location'";
                    }
                
                    // Construct the SQL query with AND between conditions
                    $query = "SELECT * FROM user, lawyer WHERE user.u_id = lawyer.lawyer_id AND user.status = 'Active'";
                    if (!empty($conditions)) {
                        $query .= " AND " . implode(" AND ", $conditions);
                    }
                
                    $result = mysqli_query($conn, $query);
                
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="lawyer-card p-4">
                            <div class="d-flex align-items-center mb-4">
                                <img src="images/upload/<?php echo $row["image"]; ?>" 
                                     class="rounded-circle me-3" 
                                     style="width: 80px; height: 80px; object-fit: cover;">
                                <div>
                                    <h4 class="mb-0" style="color: var(--primary-color);">
                                        <?php echo $row["first_Name"]." ".$row["last_Name"]; ?>
                                    </h4>
                                    <div class="specialty-badge mt-2 py-1 px-3 d-inline-block">
                                        <?php echo $row["speciality"]; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="experience-chip">
                                    <i class="fas fa-briefcase me-2"></i>
                                    <?php echo $row["practise_Length"]; ?> Experience
                                </div>
                                <div class="text-primary-color" style="color: var(--secondary-color);">
                                    <i class="fas fa-star" style="color: var(--accent-color);"></i> 4.9
                                </div>
                            </div>

                            <a href="single_lawyer.php?u_id=<?php echo $row["u_id"]; ?>" 
                               class="btn btn-block mt-3 gradient-btn">
                                View Full Profile <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                <?php 
                        }
                    } else {
                ?>
                    <div class="empty-state fade-in">
                        <i class="fas fa-search"></i>
                        <h3>No Lawyers Found</h3>
                        <p>Try adjusting your search filters</p>
                    </div>
                <?php
                    }
                } else {
                ?>
                    <div class="empty-state fade-in">
                        <i class="fas fa-search"></i>
                        <h3>No Lawyers Found</h3>
                        <p>Try adjusting your search filters</p>
                    </div>
                <?php } ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="neo-footer">
        <div class="container text-center">
            <p class="mb-0">© 2023 LegalConnect. All rights reserved.</p>
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
