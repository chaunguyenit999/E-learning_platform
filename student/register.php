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
            const full_name = register_form.querySelector('#full_name');
            const email = register_form.querySelector('#email');
            const password = register_form.querySelector('#password');
            const show_password = register_form.querySelector('#show_password');
            const phone_number = register_form.querySelector('#phone_number');

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
                constructor(dataList) {
                    this.dataList = dataList;
                }
                showErr() {
                    this.dataList.forEach(data => {
                        data.input.onblur = () => {
                            if (data.input.value.length < data.minLength) {
                                showError(data.input, `No less than ${data.minLength} characters`)
                                return;
                            }

                            if (data.input.value.length > data.maxLength) {
                                showError(data.input, `No more than ${data.maxLength} characters`)
                                return;
                            }
                            hideError(data.input);
                        }
                    })
                }
                isError() {
                    let isError = false;
                    this.dataList.forEach(data => {
                        if (data.input.value.length < data.minLength) {
                            showError(data.input, `No less than ${data.minLength} characters`)
                            isError = true;
                            return;
                        }

                        if (data.input.value.length > data.maxLength) {
                            showError(data.input, `No more than ${data.maxLength} characters`)
                            isError = true;
                            return;
                        }
                        hideError(data.input);
                    })
                    return isError;
                }
            }

            class checkExist {
                constructor(dataList) {
                    this.dataList = dataList;
                }
                showErr() {
                    this.dataList.forEach(data => {
                        data.input.oninput = () => {
                            // Create request object
                            let xmlhttp = new XMLHttpRequest();

                            xmlhttp.onreadystatechange = function() {
                                if (this.readyState == 4 && this.status == 200) {
                                    if (xmlhttp.responseText == "existed") {
                                        showError(data.input, "Information already exists!");
                                        return;
                                    }
                                    hideError(data.input);
                                }
                            };
                            // open file to send
                            xmlhttp.open("POST", "http://localhost:3000/HUMG_A1_EXAM/coures4u/helpers/check_exist_process.php", true);

                            const dataSend = {
                                table_name: data.tb,
                                column_check: data.col,
                                input_value: data.input.value,
                            }

                            const jsonData = JSON.stringify(dataSend);
                            // send data
                            xmlhttp.send(jsonData);
                        }
                    })
                }

                isError() {
                    debugger;
                    let isError = false;

                    this.dataList.forEach(data => {
                        let xmlhttp = new XMLHttpRequest();

                        xmlhttp.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                                if (xmlhttp.responseText == "existed") {
                                    showError(data.input, "Information already exists!");
                                    isError = true;
                                    return;
                                }
                                hideError(data.input);
                            }
                        };
                        // open file to send
                        xmlhttp.open("POST", "http://localhost:3000/HUMG_A1_EXAM/coures4u/helpers/check_exist_process.php", true);

                        const dataSend = {
                            table_name: data.tb,
                            column_check: data.col,
                            input_value: data.input.value,
                        }

                        const jsonData = JSON.stringify(dataSend);
                        // send data
                        xmlhttp.send(jsonData);
                    })

                    return isError;
                }
            }

            let check_empty = new checkEmpty([full_name, email, password, phone_number]);
            let check_email = new checkEmail([email]);
            let check_length = new checkLength([{
                    input: phone_number,
                    minLength: 9,
                    maxLength: 13
                },
                {
                    input: password,
                    minLength: 8,
                    maxLength: 20
                }
            ]);
            let check_exist = new checkExist([{
                    input: phone_number,
                    tb: 'user',
                    col: 'phone_number'
                },
                {
                    input: email,
                    tb: 'user',
                    col: 'email'
                }
            ]);

            check_empty.showErr();
            check_email.showErr();
            check_length.showErr();
            check_exist.showErr();

            register_form.addEventListener('submit', (e) => {
                // e.preventDefault();
                let isEmptyError = check_empty.isError()
                let isEmailError = check_email.isError()
                let isLengthError = check_length.isError()
                let isExist = check_exist.isError()

                console.log(isEmptyError)
                console.log(isEmailError)
                console.log(isLengthError)
                console.log(isExist)

                if (isEmptyError || isEmailError || isLengthError || isExist) {
                    isEmptyError
                    isEmailError
                    isLengthError
                    isExist
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