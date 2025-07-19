<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
  
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --primary-color: #00274D;
      --accent-color: #FFD700;
      --background-color: #F8F9FA;
      --text-light: #FFFFFF;
      --text-dark: #333333;
      --glass-light: rgba(255, 255, 255, 0.9);
      --glass-dark: rgba(0, 39, 77, 0.15);
      --neon-glow: 0 0 15px rgba(255, 215, 0, 0.7);
    }

    body {
      background: var(--background-color);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      font-family: 'Poppins', sans-serif;
      line-height: 1.6;
    }

    /* Navbar (Keep as is) */
    .neo-navbar {
      background: var(--glass-light) !important;
      backdrop-filter: blur(15px);
      box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
      border-bottom: 1px solid var(--glass-dark);
    }

    .navbar-brand img {
      height: 65px;
      transition: transform 0.3s ease;
    }

    .navbar-brand:hover img {
      transform: scale(1.05);
    }

    .nav-link {
      color: var(--primary-color);
      font-weight: 500;
      position: relative;
      margin: 0 1rem;
      padding: 0.5rem 0;
    }

    .nav-link::after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--accent-color);
      transition: width 0.3s ease;
    }

    .nav-link:hover::after,
    .nav-link.active::after {
      width: 100%;
    }

    .gradient-btn {
      background: var(--accent-color);
      color: var(--primary-color);
      padding: 12px 30px;
      border-radius: 50px;
      font-weight: 600;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      border: 2px solid transparent;
      box-shadow: var(--neon-glow);
    }

    .gradient-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(
        120deg,
        transparent,
        rgba(255, 255, 255, 0.3),
        transparent
      );
      transition: 0.5s;
    }

    .gradient-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 20px rgba(255, 215, 0, 0.3);
      border-color: var(--primary-color);
    }

    .gradient-btn:hover::before {
      left: 100%;
    }
    
    .row {
      align-items: center;
    }

    .registerform {
      flex: 1;
      padding: 120px 0 80px;
      background: linear-gradient(135deg, #F8F9FA, #e0e5ea);
    }

    /* FUTURISTIC LOGIN CARD */
    .login-card {
      background: var(--glass-light);
      border-radius: 20px;
      box-shadow: 0 0 20px rgba(0, 39, 77, 0.1), var(--neon-glow);
      backdrop-filter: blur(10px);
      border: 1px solid var(--glass-dark);
      padding: 2.5rem;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .login-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0 25px rgba(0, 39, 77, 0.15), 0 0 30px rgba(255, 215, 0, 0.4);
    }

    .form-control {
      border-radius: 12px;
      padding: 1rem 1.25rem;
      transition: all 0.3s ease;
      border: 1px solid var(--glass-dark);
      background-color: #fff;
    }

    .form-control:focus {
      border-color: var(--accent-color);
      box-shadow: 0 0 10px rgba(255, 215, 0, 0.3);
    }

    .btn-gold {
      background: var(--accent-color);
      color: var(--primary-color);
      font-weight: 600;
      padding: 1rem 2rem;
      border-radius: 12px;
      transition: all 0.3s ease;
      letter-spacing: 0.5px;
      box-shadow: var(--neon-glow);
      border: none;
    }

    .btn-gold:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3);
    }

    .registration-options {
      margin-top: 30px;
      padding: 2rem;
      background: var(--glass-light);
      border-radius: 20px;
      backdrop-filter: blur(10px);
      border: 1px solid var(--glass-dark);
    }

    .option-card {
      background: var(--glass-light);
      border: 2px solid var(--primary-color);
      border-radius: 15px;
      transition: all 0.3s ease;
      cursor: pointer;
      overflow: hidden;
      position: relative;
      padding: 2rem;
      box-shadow: 0 0 10px rgba(0, 39, 77, 0.1);
    }

    .option-card::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(255, 215, 0, 0.1),
        transparent
      );
      transform: rotate(45deg);
      transition: 0.5s;
    }

    .option-card:hover {
      transform: translateY(-5px);
      border-color: var(--accent-color);
      box-shadow: 0 0 25px rgba(255, 215, 0, 0.4);
    }

    .option-card:hover::before {
      animation: shine 1.5s;
    }

    @keyframes shine {
      0% { left: -50%; }
      100% { left: 150%; }
    }

    footer {
      background: var(--primary-color);
      color: var(--text-light);
      padding: 2rem 0;
      margin-top: auto;
      border-top: 2px solid var(--accent-color);
    }

    .alert {
      border-radius: 12px;
      backdrop-filter: blur(10px);
      background: rgba(255, 215, 0, 0.1);
      border: 1px solid var(--accent-color);
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      font-weight: 500;
      color: var(--primary-color);
      margin-bottom: 0.5rem;
    }
  </style>
  <title>Lawyer Connect - Login</title>
</head>
<body>
  <!-- Navbar -->
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
          <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="lawyers.php">Lawyers</a></li>
          <li class="nav-item"><a class="nav-link" href="service.php">Services</a></li>
          <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
          <li class="nav-item ms-3">
            <?php if(isset($_SESSION['login'])): ?>
              <a href="user_dashboard.php" class="btn gradient-btn">Dashboard</a>
            <?php else: ?>
              <a href="login.php" class="btn gradient-btn">LogIn</a>
            <?php endif; ?>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Section -->
  <section class="registerform">
    <?php
    
      if (isset($_GET['error'])) {
        echo "
          <div style='margin-left:30%; margin-right:30%'>
            <div class='alert alert-danger alert-dismissible'>
              <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
              Sorry User ...<strong>Wrong!</strong> Email or Password.
            </div>
          </div>";
      } else if (isset($_GET['deactivate'])) {
        echo "
          <div style='margin-left:30%; margin-right:30%'>
            <div class='alert alert-danger alert-dismissible'>
              <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
              <center>Sorry User ...<br/>Please Type your Valid Email & Password</center>
            </div>
          </div>";
      }
    ?>

    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <!-- Login Form Box -->
          <div class="login-card">
            <form action="db_con/db_login.php" method="POST" id="validateForm">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email"
                  placeholder="Enter your Valid Email address">
              </div>
              <div class="form-group">
                <label for="num">Password</label>
                <input type="password" class="form-control" name="passord" id="passord"
                  placeholder="Enter your Valid Password">
              </div>
              <input name="login" type="submit" class="btn btn-block btn-success" value="Login" />
            </form>
          </div>
        </div>
      </div>

      <!-- Registration Options -->
      <div class="row justify-content-center mt-5">
        <div class="col-md-8 text-center registration-options">
          <h3 class="mb-4">New to Lawyer Connect?</h3>
          <div class="row">
            <div class="col-md-6 mb-4">
              <div class="option-card p-4 text-center" onclick="location.href='lawyer_register.php'">
                <i class="fas fa-briefcase fa-3x text-primary mb-3"></i>
                <h4>Legal Professional</h4>
                <p>Join our network of verified lawyers</p>
                <button class="btn btn-outline-primary">Register as Lawyer</button>
              </div>
            </div>
            <div class="col-md-6 mb-4">
              <div class="option-card p-4 text-center" onclick="location.href='user_register.php'">
                <i class="fas fa-user-tie fa-3x text-primary mb-3"></i>
                <h4>Client Account</h4>
                <p>Find your perfect legal match</p>
                <button class="btn btn-outline-primary">Register as User</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container text-center">
      <p class="mb-0">&copy; 2023 Lawyer Connect. All rights reserved.</p>
    </div>
  </footer>

  <!-- Bootstrap 5 Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"
    integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T"
    crossorigin="anonymous"></script>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js"></script>

  <script>
    $('#validateForm').bootstrapValidator({
      feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
      },
      fields: {
        email: {
          validators: {
            notEmpty: {
              message: 'Please Enter your email address'
            },
            emailAddress: {
              message: 'Please Enter a valid email address'
            }
          }
        },
        password: {
          validators: {
            notEmpty: {
              message: 'Please Enter Your Password'
            }
          }
        }
      }
    });
  </script>
</body>
</html>
