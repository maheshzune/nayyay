<?php
session_start();
include("db_con/dbCon.php");

// Connect to database
$conn = connect();

// Get the lawyer's ID from the URL query string (e.g. ?u_id=...)
if (!isset($_GET['u_id'])) {
    die("No lawyer specified.");
}
$lawyer_id = mysqli_real_escape_string($conn, $_GET['u_id']);
$lawyer_query = "SELECT * FROM user u, lawyer l 
                 WHERE u.u_id = l.lawyer_id 
                   AND u.status = 'Active' 
                   AND u.u_id = '$lawyer_id'";
$result = mysqli_query($conn, $lawyer_query);
if (!$result) {
    die("Error fetching lawyer details: " . mysqli_error($conn));
}

// Fetch case counts for the lawyer
$active_cases_query = "SELECT COUNT(*) as active FROM `case` WHERE lawyer_id = '$lawyer_id' AND status NOT IN ('Won', 'Lost')";
$won_cases_query = "SELECT COUNT(*) as won FROM `case` WHERE lawyer_id = '$lawyer_id' AND status = 'Won'";
$lost_cases_query = "SELECT COUNT(*) as lost FROM `case` WHERE lawyer_id = '$lawyer_id' AND status = 'Lost'";

$active_result = mysqli_query($conn, $active_cases_query);
$won_result = mysqli_query($conn, $won_cases_query);
$lost_result = mysqli_query($conn, $lost_cases_query);

$active_count = mysqli_fetch_assoc($active_result)['active'];
$won_count = mysqli_fetch_assoc($won_result)['won'];
$lost_count = mysqli_fetch_assoc($lost_result)['lost'];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
    :root {
        --primary-color: #00274D;
        --accent-color: #FFD700;
        --bg-light: #F8F9FA;
        --text-dark: #333333;
        --text-light: #ffffff;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background: var(--bg-light);
        padding-top: 80px;
        color: var(--text-dark);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    /* Navbar Styles */
    .neo-navbar {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }

    .navbar-brand img {
        height: 50px;
    }

    .nav-link {
        color: var(--primary-color);
        font-weight: 500;
        padding: 0.5rem 1rem;
        transition: color 0.3s ease;
    }

    .nav-link.active {
        color: var(--accent-color) !important;
        font-weight: 600;
    }

    .nav-link:hover {
        color: rgb(122, 144, 232);
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
        color: var(--primary-color);
    }

    /* Back Button Styles */
    .back-btn {
        position: absolute;
        top: 90px; /* Below navbar (80px height + 10px spacing) */
        left: 20px;
        background: linear-gradient(135deg, var(--accent-color), #E6C200);
        border: none;
        color: var(--primary-color);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .back-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
        color: var(--primary-color);
    }

    /* Profile Card Styles */
    .profile-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
    }

    .profile_img {
        width: 200px;
        height: 200px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 1rem;
        border: 4px solid var(--accent-color);
    }

    .info-label {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    /* Case Stats Boxes */
    .stats-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .stats-card h5 {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .stats-card .count {
        font-size: 2rem;
        font-weight: 700;
        color: var(--accent-color);
    }

    .stats-card.active-cases {
        border-left: 5px solid #28a745; /* Green for active */
    }

    .stats-card.won-cases {
        border-left: 5px solid #17a2b8; /* Cyan for won */
    }

    .stats-card.lost-cases {
        border-left: 5px solid #dc3545; /* Red for lost */
    }

    /* Booking Card Styles */
    .booking-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #ddd;
        padding: 0.75rem;
    }

    .form-control:focus {
        border-color: var(--accent-color);
        box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
    }

    .form-label {
        font-weight: 500;
        color: var(--primary-color);
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
        margin: 0 auto;
    }

    /* Footer */
    footer {
        background: var(--primary-color);
        color: var(--text-light);
        padding: 1.5rem 0;
        margin-top: auto;
    }

    footer p {
        margin: 0;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .profile_img {
            width: 150px;
            height: 150px;
        }

        .profile-card, .booking-card, .stats-card {
            padding: 1.5rem;
        }

        .gradient-btn {
            padding: 10px 20px;
        }

        .back-btn {
            top: 70px; /* Adjust for smaller navbar */
            left: 15px;
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        body {
            padding-top: 60px;
        }

        .navbar-brand img {
            height: 40px;
        }

        .profile_img {
            width: 120px;
            height: 120px;
        }

        .login-prompt {
            padding: 1.5rem 2rem;
            margin: 1rem;
        }

        .back-btn {
            top: 60px; /* Adjust for smaller navbar */
            left: 10px;
            width: 30px;
            height: 30px;
            font-size: 0.9rem;
        }
    }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg neo-navbar fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="logo.png" alt="LegalConnect">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="lawyers.php">Lawyers</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">About</a></li>
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

    <!-- Back Button -->
    <a href="lawyers.php" class="back-btn"><i class="fas fa-arrow-left"></i></a>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <?php if(isset($_SESSION['login'])): ?>
            <!-- Main Content -->
            <section class="py-5">
                <div class="container">
                    <?php while($row = mysqli_fetch_array($result)) { ?>
                    <div class="profile-card">
                        <div class="row">
                            <div class="col-lg-4 text-center mb-4">
                                <img src="images/upload/<?php echo $row["image"]; ?>" class="profile_img" alt="profile picture">
                                <h2 class="mb-1"><?php echo $row["first_Name"]; ?> <?php echo $row["last_Name"]; ?></h2>
                                <h4 class="text-muted"><?php echo $row["speciality"]; ?></h4>
                            </div>
                            <div class="col-lg-8">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="info-label"><i class="fas fa-phone me-2"></i>Contact Number</div>
                                        <p class="mb-3"><?php echo $row["contact_Number"]; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label"><i class="fas fa-envelope me-2"></i>Email</div>
                                        <p class="mb-3"><?php echo $row["email"]; ?></p>
                                    </div>
                                    <div class="col-12">
                                        <div class="info-label"><i class="fas fa-graduation-cap me-2"></i>Education</div>
                                        <p class="mb-0"><?php echo $row["university_College"]; ?></p>
                                        <p class="mb-0"><?php echo $row["degree"]; ?> (<?php echo $row["passing_year"]; ?>)</p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label"><i class="fas fa-map-marker-alt me-2"></i>Location</div>
                                        <p class="mb-0"><?php echo $row["full_address"]; ?></p>
                                        <p class="mb-0"><?php echo $row["city"]; ?>, <?php echo $row["zip_code"]; ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-label"><i class="fas fa-briefcase me-2"></i>Experience</div>
                                        <p class="mb-0"><?php echo $row["practise_Length"]; ?> Years Experience</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Case Statistics Section -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="stats-card active-cases">
                                <h5>Active Cases</h5>
                                <div class="count"><?php echo $active_count; ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card won-cases">
                                <h5>Cases Won</h5>
                                <div class="count"><?php echo $won_count; ?></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card lost-cases">
                                <h5>Cases Lost</h5>
                                <div class="count"><?php echo $lost_count; ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Section -->
                    <div class="booking-card">
                        <h4 class="mb-4"><i class="fas fa-calendar-check me-2"></i>Book Consultation</h4>
                        <form action="save_booking.php" method="post" enctype="multipart/form-data">
                            <div class="row g-3 align-items-end">
                                <input type="hidden" name="lawyer_id" value="<?php echo $row['u_id']; ?>">
                                <input type="hidden" name="client_id" value="<?php echo $_SESSION['client_id']; ?>">
                                <div class="col-md-4">
                                    <label class="form-label">Subject</label>
                                    <input type="text" name="subject" class="form-control" placeholder="Enter subject" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Case Details</label>
                                    <textarea name="description" class="form-control" rows="2" placeholder="Brief case description..." required></textarea>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Upload Document</label>
                                    <input type="file" name="document" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn gradient-btn w-100">Request Booking</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php } // end while ?>
                </div>
            </section>
        <?php else: ?>
            <!-- Login Prompt -->
            <div class="login-prompt">
                <h3>Login to Continue</h3>
                <p>Please log in to view lawyer profiles and book consultations.</p>
                <a href="login.php" class="btn gradient-btn mt-3">Login</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark py-4">
        <div class="container text-center">
            <p class="text-light mb-0">© <?php echo date("Y"); ?> LegalConnect. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
<?php mysqli_close($conn); ?>