<?php
session_start();
include('conn.php');

$error = '';

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($email) || empty($username) || empty($password) || empty($confirm_password)) {
        $error = "Please fill all fields";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        $query = "SELECT * FROM users WHERE username = '$username'";
        $run = mysqli_query($conn, $query);

        if (mysqli_num_rows($run) > 0) {
            $error = "Username is already taken";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = "INSERT INTO users (email, username, password) VALUES ('$email', '$username', '$hashed_password')";

            if (mysqli_query($conn, $insert)) {
                $_SESSION['user'] = [
                    'email' => $email,
                    'username' => $username
                ];
                header("Location: home.php?message=" . urlencode("You have successfully registered"));
                exit();
            } else {
                $error = "There was an error inserting the data.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register Page</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            background: url(images/vecteezy_golden-abstract-background-with-luxury-illustration_48677962.jpg) no-repeat center center;
            background-size: cover;
        }

        .wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px 0;
        }

        .logo {
            width: 200px;
            margin-top: 10px;
        }

        #register {
            text-align: center;
            text-decoration: underline;
            font-size: 24px;
            color: #e6b800;
            margin: 10px 0;
        }

        .form-container {
            max-width: 400px;
            width: 90%;
            padding: 20px;
            background-color: rgba(125, 125, 125, 0.7);
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .button {
            width: 100%;
            padding: 12px;
            background-color: #ffd700;
            color: black;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        .button:hover {
            background-color: #e6b800;
            transform: scale(1.05);
        }

        input:focus {
            outline: none;
            border: 2px solid #ffd700;
        }

        .message {
            text-align: center;
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }

        small#matchInfo, small#strengthInfo {
            display: block;
            text-align: left;
            padding-left: 10px;
            font-weight: bold;
        }

        .togglepassword {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
            padding-left: 5px;
            text-align: center;
        }

        a {
            color: #ffd700;
            text-decoration: none;
        }

        footer {
            text-align: center;
            color: white;
            padding: 10px 0;
            font-size: 14px;
            background-color: rgba(0, 0, 0, 0.4);
        }
        @media (max-width: 600px) {
    .form {
        width: 90%;
        margin: 20px auto;
        padding: 15px;
    }

    input, .button {
        width: 100%;
        font-size: 16px;
    }

    .logo {
        width: 150px;
        margin-top: 10px;
    }

    h2#register,
    h2#login {
        font-size: 20px;
    }

    body, html {
        font-size: 14px;
    }

    label {
        font-size: 14px;
    }

    .password-toggle {
        font-size: 14px;
    }

    footer {
        font-size: 12px;
        padding: 8px 0;
    }
}

    </style>
</head>
<body>
    <div class="wrapper">
        <img class="logo" src="images/logo.gif" alt="Website Logo">
        <h2 id="register">REGISTER</h2>

        <div class="form-container">
            <form method="post">
                <label for="registerEmail">Email</label><br>
                <input type="email" id="registerEmail" name="email" placeholder="Enter your Email" required><br>

                <label for="registerUsername">Username</label><br>
                <input type="text" id="registerUsername" name="username" placeholder="Enter your Username" required><br>

                <label for="registerPassword">Password</label><br>
                <input type="password" id="registerPassword" name="password" placeholder="Enter Your Password" required><br>
                <small id="strengthInfo"></small>

                <label for="registerConfirmPassword">Confirm Password</label><br>
                <input type="password" id="registerConfirmPassword" name="confirm_password" placeholder="Confirm your Password" required><br>
                <small id="matchInfo"></small>

                <div class="togglepassword">
                    <input type="checkbox" id="togglePassword">
                    <label for="togglePassword">Show Password</label>
                </div>

                <button type="submit" name="submit" class="button">Sign up</button>
                <p style="margin-top:10px;">Already have an account? <a href="login.php">Login</a></p>
            </form>

            <?php if ($error): ?>
                <p class="message" id="errorMessage"><?php echo $error; ?></p>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer">
        &copy; 2025 CHATPAY. All rights reserved.
    </footer>

    <script>
        const passwordField = document.getElementById("registerPassword");
        const confirmField = document.getElementById("registerConfirmPassword");
        const toggle = document.getElementById("togglePassword");
        const matchInfo = document.getElementById("matchInfo");
        const strengthInfo = document.getElementById("strengthInfo");

        toggle.addEventListener("change", function () {
            const type = this.checked ? "text" : "password";
            passwordField.type = type;
            confirmField.type = type;
        });

        confirmField.addEventListener("input", function () {
            if (confirmField.value === "") {
                matchInfo.textContent = "";
                confirmField.style.borderColor = "";
            } else if (passwordField.value === confirmField.value) {
                matchInfo.textContent = "Passwords match";
                matchInfo.style.color = "lime";
                confirmField.style.borderColor = "lime";
            } else {
                matchInfo.textContent = "Passwords do not match";
                matchInfo.style.color = "red";
                confirmField.style.borderColor = "red";
            }
        });

        passwordField.addEventListener("input", function () {
            const value = passwordField.value;
            let strength = "";
            let color = "";

            if (value.length < 6) {
                strength = "Weak (min 6 characters)";
                color = "red";
            } else if (!/[A-Z]/.test(value) || !/[0-9]/.test(value) || !/[!@#$%^&*]/.test(value)) {
                strength = "Medium (use uppercase, number, symbol)";
                color = "orange";
            } else {
                strength = "Strong Password";
                color = "lime";
            }

            strengthInfo.textContent = strength;
            strengthInfo.style.color = color;
        });

        const errorMessage = document.getElementById("errorMessage");
        if (errorMessage) {
            setTimeout(() => {
                errorMessage.style.display = "none";
            }, 3000);
        }
    </script>
</body>
</html>
