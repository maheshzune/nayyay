<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS and Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <!-- Google Fonts for modern typography -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <title>Register Here</title>

    <style>
        /* Reset and Base Styles */
        body {
            background-color: #F8F9FA;
            font-family: 'Poppins', sans-serif;
        }

        /* Header Styling */
        .customnav {
            background-color: #00274D;
            padding: 15px 0;
        }
        .cus-a {
            color: #F8F9FA !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .cus-a:hover {
            color: #FFD700 !important;
        }

        /* Form Container */
        .registerform {
            max-width: 700px;
            margin: 60px auto;
            background: #FFFFFF;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        /* Headings */
        .registerform h1 {
            color: #00274D;
            font-size: 2.5rem;
            font-weight: 600;
            text-align: center;
            margin-bottom: 10px;
        }
        .registerform h1 i {
            color: #FFD700;
            margin-left: 10px;
        }
        .registerform h3 {
            color: #00274D;
            font-size: 1.25rem;
            font-weight: 400;
            text-align: center;
            margin-bottom: 30px;
        }
        .registerform h3 i {
            color: #FFD700;
            margin-left: 8px;
        }

        /* Form Labels */
        .registerform label {
            color: #00274D;
            font-weight: 500;
        }

        /* Input Fields */
        .registerform .form-control {
            border: 1px solid #33475b;
            border-radius: 8px;
            padding: 10px;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .registerform .form-control:focus {
            border-color: #FFD700;
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
        }

        /* Submit Button */
        .registerform .btn-success {
            background-color: #00274D;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 500;
            color: #FFFFFF;
            transition: all 0.3s ease;
        }
        .registerform .btn-success:hover {
            background-color: #FFD700;
            color: #00274D;
            transform: scale(1.05);
        }

        /* Error Messages */
        .has-error .help-block {
            color: #8B0000;
            font-size: 0.9rem;
        }
        .has-error .form-control {
            border-color: #8B0000;
        }

        /* Form Group Spacing */
        .form-group {
            margin-bottom: 1.75rem;
        }

        /* Custom File Input */
        .custom-file-input ~ .custom-file-label {
            border-radius: 8px;
            border-color: #33475b;
        }
        .custom-file-input:focus ~ .custom-file-label {
            border-color: #FFD700;
            box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
        }

        /* Footer */
        footer {
            background-color: #00274D;
            padding: 20px 0;
            margin-top: 40px;
        }
        footer h5 {
            color: #F8F9FA;
            text-align: center;
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="customnav">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand cus-a" href="#">Lawyer Management System</a>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ml-auto">
                                <li class="active">
                                    <a class="nav-link cus-a" href="index.php">Home <span class="sr-only">(current)</span></a>
                                </li>
                                <li>
                                    <a class="nav-link cus-a" href="lawyers.php">Lawyers</a>
                                </li>
                                <li>
                                    <a class="nav-link cus-a" href="#">About Us</a>
                                </li>
                                <li>
                                    <a class="nav-link cus-a" href="login.php">Login</a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle cus-a" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Register
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="lawyer_register.php">Register as a lawyer</a>
                                        <a class="dropdown-item" href="user_register.php">Register as a user</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Registration Form -->
    <section class="registerform container">
        <h1>Welcome! <i class="fas fa-hand-paper"></i></h1>
        <h3>Register to find the best lawyers <i class="fas fa-hand-point-right"></i></h3>

        <form action="user_registration.php" method="POST" enctype="multipart/form-data" id="validateForm">
            <div class="row">
                <!-- First Name and Last Name -->
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_Name">First Name</label>
                        <input type="text" class="form-control" name="first_Name" id="first_Name" placeholder="First Name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_Name">Last Name</label>
                        <input type="text" class="form-control" name="last_Name" id="last_Name" placeholder="Last Name" required>
                    </div>
                </div>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email Address" required>
            </div>

            <!-- Contact Number -->
            <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" class="form-control" name="contact_number" id="contact_number" placeholder="Contact Number" pattern="\d{10}" title="Please enter a valid 10-digit contact number" required>
                <small class="form-text text-muted">Enter a 10-digit number</small>
            </div>

            <!-- Profile Image -->
            <div class="form-group">
                <label for="image">Upload Profile Image</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="image" name="fileToUpload" accept="image/*" required>
                    <label class="custom-file-label" for="image">Choose file</label>
                </div>
                <small class="form-text text-muted">Max file size: 2MB</small>
            </div>

            <!-- Address -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="full_address">Full Address</label>
                        <input type="text" class="form-control" name="full_address" id="full_address" placeholder="Full Address" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="city">City</label>
                        <select id="city" name="city" class="form-control" required>
                            <option value="" selected>Choose...</option>
                            <option value="Dhaka">Dhaka</option>
                            <option value="Chittagong">Chittagong</option>
                            <option value="Sylhet">Sylhet</option>
                            <option value="Barishal">Barishal</option>
                            <option value="Khulna">Khulna</option>
                            <option value="Mymensingh">Mymensingh</option>
                            <option value="Rajshahi">Rajshahi</option>
                            <option value="Rangpur">Rangpur</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="zip_code">Pin Code</label>
                        <input type="text" class="form-control" name="zip_code" id="zip_code" placeholder="Zip Code" pattern="\d{4}" title="Please enter a valid 4-digit zip code" required>
                        <small class="form-text text-muted">Enter a 6-digit code</small>
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="spassword">Password</label>
                <input type="password" class="form-control" name="spassword" id="spassword" placeholder="Password" minlength="8" required>
            </div>

            <!-- Terms and Conditions -->
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="accept" name="agree" value="y" required>
                <label class="form-check-label" for="accept">I agree to the terms and conditions</label>
            </div>

            <!-- Submit Button -->
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-success btn-block">Register</button>
            </div>
        </form>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col">
                    <h5>All rights reserved 2022</h5>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js"></script>

    <script>
        // Custom file input label update
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Profile image size validation
        document.getElementById('image').addEventListener('change', function() {
            const file = this.files[0];
            if (file.size > 2 * 1024 * 1024) { // 2MB limit
                alert('File size exceeds 2MB. Please choose a smaller file.');
                this.value = '';
            }
        });

        // Bootstrap Validator
        $('#validateForm').bootstrapValidator({
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                first_Name: {
                    validators: {
                        stringLength: {
                            min: 3,
                            message: 'First name must be at least 3 characters'
                        },
                        notEmpty: {
                            message: 'First name is required'
                        }
                    }
                },
                last_Name: {
                    validators: {
                        stringLength: {
                            min: 3,
                            message: 'Last name must be at least 3 characters'
                        },
                        notEmpty: {
                            message: 'Last name is required'
                        }
                    }
                },
                email: {
                    validators: {
                        notEmpty: {
                            message: 'Email is required'
                        },
                        emailAddress: {
                            message: 'Please enter a valid email address'
                        }
                    }
                },
                contact_number: {
                    validators: {
                        stringLength: {
                            min: 10,
                            max: 10,
                            message: 'Contact number must be 10 digits'
                        },
                        numeric: {
                            message: 'Contact number must be numeric'
                        },
                        notEmpty: {
                            message: 'Contact number is required'
                        }
                    }
                },
                fileToUpload: {
                    validators: {
                        notEmpty: {
                            message: 'Profile image is required'
                        }
                    }
                },
                full_address: {
                    validators: {
                        notEmpty: {
                            message: 'Full address is required'
                        }
                    }
                },
                zip_code: {
                    validators: {
                        stringLength: {
                            min: 6,
                            max: 6,
                            message: 'Zip code must be 6 digits'
                        },
                        numeric: {
                            message: 'Zip code must be numeric'
                        },
                        notEmpty: {
                            message: 'Zip code is required'
                        }
                    }
                },
                city: {
                    validators: {
                        notEmpty: {
                            message: 'City is required'
                        }
                    }
                },
                agree: {
                    validators: {
                        notEmpty: {
                            message: 'You must agree to the terms'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>