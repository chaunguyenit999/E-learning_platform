<?php
// this function fixes page navigation errors
ob_start();
?>

<!-- Load helper files -->
<?php
require "C:/xampp/htdocs/HUMG_A1_EXAM/coures4u/config.php";

require abs_path('db/db_helper.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses4U | Register</title>
    <!-- Import css & script link -->
    <?php require abs_path('student/layout/css_link.php'); ?>
    <link rel="stylesheet" href=<?= abs_url('assets/css/form.css') ?>>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const register_form = document.querySelector('#register_form');
            const full_name = document.querySelector('#full_name');
            const email = document.querySelector('#email');
            const password = document.querySelector('#password');
            const show_password = document.querySelector('#show_password');
            const phone_number = document.querySelector('#phone_number');

            // Show password
            show_password.onclick = () => {
                if (password.getAttribute("type") == 'password') {
                    password.setAttribute("type", "text");
                } else {
                    password.setAttribute("type", "password");
                };
            }

            function showError(input, message) {
                // Get parent class
                let parent = input.parentElement.parentElement;
                let alert_message = parent.querySelector('span');
                // class list property
                input.classList.add('border-danger');
                alert_message.innerHTML = message;
            }

            function hideError(input) {
                // Get parent class
                let parent = input.parentElement.parentElement;
                let alert_message = parent.querySelector('span');
                // class list property with 4 methods: add, remove, contains, toggle
                input.classList.remove('border-danger');
                alert_message.innerHTML = '';
            }
            class checkEmpty {
                constructor(inputList) {
                    this.inputList = inputList;
                }
                showErr() {
                    this.inputList.forEach(input => {
                        input.onblur = () => {
                            if (!input.value) {
                                showError(input, "Please fill out this field")
                            } else {
                                hideError(input);
                            }
                        }
                    })
                }
                isError() {
                    let isError = false;
                    this.inputList.forEach(input => {
                        if (!input.value) {
                            isError = true;
                            showError(input, "Please fill out this field")
                        }
                    })
                    return isError;
                }
            }

            class checkEmail {
                constructor(inputList) {
                    this.inputList = inputList;
                    this.regexEmail = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
                }
                showErr() {
                    this.inputList.forEach(input => {
                        input.onblur = () => {
                            if (!this.regexEmail.test(input.value)) {
                                showError(input, "Email invalid");
                            } else {
                                hideError(input);
                            }
                        }
                    })
                }
                isError() {
                    let isError = false;
                    this.inputList.forEach(input => {
                        if (!this.regexEmail.test(input.value)) {
                            isError = true;
                            showError(input, "Email invalid");
                        }
                    })
                    return isError;
                }
            }

            class checkLength {
                constructor(inputList, minLength, maxLength) {
                    this.inputList = inputList;
                    this.minLength = minLength;
                    this.maxLength = maxLength;
                }
                showErr() {
                    this.inputList.forEach(input => {
                        input.onblur = () => {
                            if (input.value.length < this.minLength) {
                                showError(input, `No less than ${this.minLength} characters`)
                            } else if (input.value.length > this.maxLength) {
                                showError(input, `No more than ${this.maxLength} characters`)
                            } else {
                                hideError(input);
                            }
                        }
                    })
                }
                isError() {
                    let isError = false;
                    this.inputList.forEach(input => {
                        if (input.value.length < this.minLength) {
                            showError(input, `No less than ${this.minLength} characters`)
                            isError = true;
                        } else if (input.value.length > this.maxLength) {
                            showError(input, `No more than ${this.maxLength} characters`)
                            isError = true;
                        } else {
                            hideError(input);
                        }
                    })
                    return isError;
                }
            }

            let check_empty = new checkEmpty([full_name, email, password, phone_number]);
            let check_email = new checkEmail([email]);
            let check_length = new checkLength([password], 8, 20);
            check_empty.showErr();
            check_email.showErr();
            check_length.showErr();

            register_form.addEventListener('submit', (e) => {
                let isEmptyError = check_empty.isError()
                let isEmailError = check_email.isError()
                let isLengthError = check_length.isError()
                console.log(isEmptyError)
                console.log(isEmailError)
                console.log(isLengthError)

                if (isEmptyError || isEmailError || isLengthError) {
                    isEmptyError
                    isEmailError
                    isLengthError
                    e.preventDefault();
                } else {
                    register_form.submit()
                }
            })
        })
    </script>
</head>

<body>
    <?php
    if (isset($_POST['register'])) {
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $phone_number = $_POST['phone_number'];

        require abs_path('db/db_connect.php');
        // Check email already exists
        $sql = "SELECT email FROM user WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            // If the email already exists, check the phone number 
            $sql = "SELECT phone_number FROM user WHERE phone_number = $phone_number";
            $result = $conn->query($sql);
            if ($result->num_rows == 0) {
                // Phone number does not exist, insert data
                $sql = "INSERT INTO user (real_name, email, password, phone_number) VALUES (N'$full_name', '$email', '$password', $phone_number)";
                excute($sql);
                header("Location: login.php");
            } else {
                echo "<script>alert('This phone number already exists!');</script>";
            }
        } else {
            echo "<script>alert('This email already existst!');</script>";
        }
    }
    ?>

    <!-- NAVBAR -->
    <?php require abs_path('student/layout/nav.php'); ?>

    <div class="form-wrapper">
        <h4 class="form-wrah4per__title"><b>Sign up and start learning</b></h4>
        <form method="POST" id="register_form">
            <div class="mb-3">
                <div class="input-group">
                    <input class="form-control" type="text" id="full_name" name="full_name" placeholder="Full Name"><br>
                </div>
                <span class="text-danger"></span>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <input class="form-control" type="phone" id="email" name="email" placeholder="Email">
                </div>
                <span class="text-danger"></span>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <input class="form-control" type="password" id="password" name="password" placeholder="Password">
                    <a class="btn btn-outline-secondary" type="button" id="show_password"><i class="fa-solid fa-eye"></i></a>
                </div>
                <span class="text-danger"></span>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <input class="form-control" type="number" id="phone_number" name="phone_number" placeholder="Phone Number">
                </div>
                <span class="text-danger"></span>
            </div>

            <p style="text-align:center">By signing up, you agree to our <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.</p>
            <input class="form-submit" type="submit" value="Register" id="register" name="register">
        </form>
        <p style="text-align:center; margin-top: 1vw;">Already have an account <a href="./login.php"><b>Login</b></a>
        <p>
    </div>

    <!-- FOOTER -->
    <?php require abs_path('student/layout/footer.php'); ?>
</body>

</html>