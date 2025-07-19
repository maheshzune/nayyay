<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS -->
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css"
      integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
      integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf"
      crossorigin="anonymous"
    />
    <title>Lawyer Registration</title>

    <!-- Custom CSS -->
    <style>
      /* Custom Color Scheme */
      .bg-primary-blue {
        background-color: #00274d !important;
      }
      .text-gold {
        color: #ffd700 !important;
      }
      .btn-primary-blue {
        background-color: #00274d;
        color: #ffd700;
        border: 1px solid #00274d;
      }
      .btn-primary-blue:hover {
        background-color: #001a33;
        color: #ffd700;
        border: 1px solid #001a33;
      }
      .customnav {
        padding: 15px 0;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      }
      .cus-a {
        color: #ffd700 !important;
        font-weight: 500;
        transition: all 0.3s ease;
      }
      .cus-a:hover {
        color: #ffe55c !important;
        text-decoration: none;
      }
      .registerform {
        background-color: #f8f9fa;
        padding: 40px 0;
      }
      .registerform h1 {
        color: #00274d;
        font-size: 2.5rem;
        margin-bottom: 20px;
      }
      .registerform h2 {
        color: #00274d;
        font-size: 1.8rem;
        margin-bottom: 30px;
      }
      .form-control {
        border: 1px solid #00274d;
        border-radius: 4px;
      }
      .form-control:focus {
        border-color: #ffd700;
        box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
      }
      .form-group label {
        color: #00274d;
        font-weight: 500;
      }
      .select-wrapper select {
        border: 1px solid #00274d;
        border-radius: 4px;
      }
      footer {
        padding: 20px 0;
        margin-top: 40px;
      }
      footer h5 {
        color: #ffd700;
        margin: 0;
      }
      /* Checkbox styling */
      .form-check-input:checked {
        background-color: #00274d;
        border-color: #00274d;
      }
      /* File upload custom styling */
      .custom-file-input:focus ~ .custom-file-label {
        border-color: #ffd700;
        box-shadow: 0 0 0 0.2rem rgba(255, 215, 0, 0.25);
      }
      /* Terms & Conditions checkbox */
      #agree {
        margin-right: 10px;
      }
      /* Error messages */
      .has-error .help-block {
        color: red;
      }
    </style>
  </head>
  <body>
    <!-- Header -->
    <header class="customnav bg-primary-blue">
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
                  <li class="">
                    <a class="nav-link cus-a" href="lawyers.php">Lawyers</a>
                  </li>
                  <li class="">
                    <a class="nav-link cus-a" href="#">About Us</a>
                  </li>
                  <li class="">
                    <a class="nav-link cus-a" href="login.php">Login</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle cus-a" href="#" id="navbarDropdown" role="button"
                      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
    <section class="registerform">
      <div class="container">
        <div class="row">
          <!-- Left Column: Welcome Message -->
          <div class="col-md-6">
            <h1>Hello Lawyer <i class="fas fa-user-tie text-gold"></i></h1>
            <h2>Please register here <i class="fas fa-hand-point-right text-gold"></i></h2>
          </div>
          <!-- Right Column: Registration Form -->
          <div class="col-md-6">
            <form action="lawyer_registation.php" method="post" enctype="multipart/form-data" id="validateForm">
              <!-- User Table Details -->
              <div class="form-group">
                <label for="first_Name">First Name</label>
                <input type="text" class="form-control" id="first_Name" name="first_Name" placeholder="Enter your first name" required />
              </div>
              <div class="form-group">
                <label for="last_Name">Last Name</label>
                <input type="text" class="form-control" id="last_Name" name="last_Name" placeholder="Enter your last name" required />
              </div>
              <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required />
              </div>
              <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter a password" required />
              </div>
              <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required />
              </div>
              <hr />
              <!-- Lawyer Table Details -->
              <div class="form-group">
                <label for="contact_number">Contact Number</label>
                <input type="text" class="form-control" id="contact_number" name="contact_number" placeholder="Enter your 10-digit phone number" required />
              </div>
              <div class="form-group">
                <label for="fileToUpload">Profile Image</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="fileToUpload" name="fileToUpload" accept="image/*" required />
                  <label class="custom-file-label" for="fileToUpload">Choose file</label>
                </div>
              </div>
              <div class="form-group">
                <label for="university_College">University/College</label>
                <input type="text" class="form-control" id="university_College" name="university_College" placeholder="Enter your university or college" required />
              </div>
              <div class="form-group">
                <label for="degree">Degree</label>
                <input type="text" class="form-control" id="degree" name="degree" placeholder="Enter your degree" required />
              </div>
              <div class="form-group">
                <label for="passing_year">Passing Year</label>
                <input type="text" class="form-control" id="passing_year" name="passing_year" placeholder="Enter your passing year" required />
              </div>
              <div class="form-group">
                <label for="full_address">Full Address</label>
                <input type="text" class="form-control" id="full_address" name="full_address" placeholder="Enter your full address" required />
              </div>
              <div class="form-group">
                <label for="zip_code">Zip Code</label>
                <input type="text" class="form-control" id="zip_code" name="zip_code" placeholder="Enter your 6-digit zip code" required />
              </div>
              <div class="form-group">
                <label for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" placeholder="Enter your city" required />
              </div>
              <div class="form-group">
                <label for="practise_Length">Practice Length</label>
                <input type="text" class="form-control" id="practise_Length" name="practise_Length" placeholder="Enter your practice length (e.g., 1-5 years)" required />
              </div>
              <!-- Case Handle Section (Checkboxes) -->
              <div class="form-group">
                <label>Case Handle (Select one or more)</label>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="case_handle[]" value="Criminal Cases" id="handleCriminal" />
                  <label class="form-check-label" for="handleCriminal">Criminal Cases</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="case_handle[]" value="Civil Cases" id="handleCivil" />
                  <label class="form-check-label" for="handleCivil">Civil Cases</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="case_handle[]" value="Commercial Cases" id="handleCommercial" />
                  <label class="form-check-label" for="handleCommercial">Commercial Cases</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="case_handle[]" value="Family Cases" id="handleFamily" />
                  <label class="form-check-label" for="handleFamily">Family Cases</label>
                </div>
              </div>
              <!-- Speciality Section (Single-select Dropdown) -->
              <div class="form-group">
                <label for="speciality">Speciality</label>
                <select class="form-control" id="speciality" name="speciality" required>
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
              <!-- New Field: Bar ID -->
              <div class="form-group">
                <label for="bar_id">Bar ID</label>
                <input type="text" class="form-control" id="bar_id" name="bar_id" placeholder="Enter your Bar ID" required />
              </div>
              <!-- New Field: Bar Certificate PDF -->
              <div class="form-group">
                <label for="bar_certificate">Bar Certificate (PDF)</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="bar_certificate" name="bar_certificate" accept="application/pdf" required />
                  <label class="custom-file-label" for="bar_certificate">Choose file</label>
                </div>
              </div>
              <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="agree" name="agree" required />
                <label class="form-check-label" for="agree">I agree to the <a href="#">Terms & Conditions</a></label>
              </div>
              <input name="post" type="submit" class="btn btn-block btn-primary-blue" value="Register" />
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary-blue">
      <div class="container">
        <div class="row">
          <div class="col text-center">
            <h5>All rights reserved 2022</h5>
          </div>
        </div>
      </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" 
      integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGaH2+nqUivzIebhndOJK28anvf" 
      crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" 
      integrity="sha384-smHYKdYF+Pp2BlrZcu0xZ4odxJ2M3QZ9F+8n9E2Xx3y5M9r+8abyOe5z9eX8QQhZ" 
      crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js"></script>
    <script>
      // Form validation script using Bootstrap Validator
      $('#validateForm').bootstrapValidator({
        feedbackIcons: {
          valid: 'glyphicon glyphicon-ok',
          invalid: 'glyphicon glyphicon-remove',
          validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
          first_Name: {
            validators: {
              stringLength: { min: 3, message: 'Please enter your first name with minimum 3 letters' },
              notEmpty: { message: 'Please enter your first name' }
            }
          },
          last_Name: {
            validators: {
              stringLength: { min: 3, message: 'Please enter your last name with minimum 3 letters' },
              notEmpty: { message: 'Please enter your last name' }
            }
          },
          email: {
            validators: {
              notEmpty: { message: 'Please enter your email address' },
              emailAddress: { message: 'Please enter a valid email address' }
            }
          },
          contact_number: {
            validators: {
              stringLength: { min: 10, max: 10, message: 'Mobile number must be exactly 10 digits' },
              numeric: { message: 'The mobile number must be a number' },
              notEmpty: { message: 'Please enter your mobile number' }
            }
          },
          fileToUpload: {
            validators: { notEmpty: { message: 'Please upload your image' } }
          },
          university_College: {
            validators: { notEmpty: { message: 'Please enter your university or college' } }
          },
          degree: {
            validators: { notEmpty: { message: 'Please enter your degree' } }
          },
          passing_year: {
            validators: { notEmpty: { message: 'Please enter your passing year' } }
          },
          full_address: {
            validators: { notEmpty: { message: 'Please enter your full address' } }
          },
          zip_code: {
            validators: {
              stringLength: { min: 6, max: 6, message: 'Zip code must be exactly 6 digits' },
              numeric: { message: 'Zip code must be a number' },
              notEmpty: { message: 'Please enter your zip code' }
            }
          },
          city: {
            validators: { notEmpty: { message: 'Please enter your city' } }
          },
          practise_Length: {
            validators: { notEmpty: { message: 'Please enter your practice length' } }
          },
          'case_handle[]': {
            validators: {
              notEmpty: { message: 'Please select at least one case handle option' }
            }
          },
          speciality: {
            validators: {
              notEmpty: { message: 'Please select a speciality' }
            }
          },
          bar_id: {
            validators: {
              notEmpty: { message: 'Please enter your Bar ID' }
            }
          },
          bar_certificate: {
            validators: {
              notEmpty: { message: 'Please upload your Bar Certificate PDF' },
              file: { extension: 'pdf', type: 'application/pdf', message: 'Please upload a valid PDF file' }
            }
          },
          agree: {
            validators: { notEmpty: { message: 'You must agree to the Terms & Conditions' } }
          }
        }
      });
    </script>
  </body>
</html>
