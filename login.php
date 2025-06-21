<?php
session_start();
include('conn.php');

$error = '';
$success = '';

if (isset($_GET['message'])) {
    $success = htmlspecialchars($_GET['message']);
}
if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
                'email' => $user['email'],
                'username' => $user['username']
            ];
            header("Location: home.php?message=" . urlencode("You have successfully logged into your account"));
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No user found with that username.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            height: 100%;
            background: url(images/vecteezy_golden-abstract-background-with-luxury-illustration_48677962.jpg) no-repeat center center;
            background-size: cover;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .logo {
            width: 200px;
            margin: 20px auto 0;
            display: block;
        }

        h2#login {
            text-align: center;
            text-decoration: underline;
            font-size: 24px;
            color: #fff;
            margin-bottom: 10px;
        }

        .form {
            max-width: 400px;
            margin: 10px auto;
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
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        .error {
            background-color: #f44336;
            color: white;
        }

        .success {
            background-color: red;
            color: white;
        }

        .password-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
        }

        #passwordStrength {
            font-weight: bold;
            margin-top: -5px;
            margin-bottom: 15px;
            text-align: left;
            padding-left: 5%;
        }

        footer {
            text-align: center;
            color: white;
            padding: 10px 0;
            font-size: 14px;
            background-color: rgba(0,0,0,0.4);
        }

        a {
            color: #ffd700;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div>
        <img class="logo" src="images/logo.gif" alt="Website Logo">
        <h2 id="login">LOGIN</h2>

        <div class="form">
            <form method="post">
                <label for="loginUsername">Username</label><br>
                <input type="text" id="loginUsername" name="username" placeholder="Enter your username" required><br>

                <label for="loginPassword">Password</label><br>
                <input type="password" id="loginPassword" name="password" placeholder="Enter Your Password" required><br>
                <div id="passwordStrength"></div>

                <div class="password-toggle">
                    <label for="togglePassword">
                        <input type="checkbox" id="togglePassword"> Show Password
                    </label>
                </div>

                <button type="submit" name="submit" class="button">Login</button>
                <p>Don't have an account? <a href="register.php">Register here</a></p>
            </form>

            <?php if ($error): ?>
                <p class="message error" id="errorMessage"><?php echo $error; ?></p>
            <?php endif; ?>

            <?php if ($success): ?>
                <p class="message success" id="successMessage"><?php echo $success; ?></p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        &copy; 2025 CHATPAY. All rights reserved.
    </footer>

    <script>
        const toggle = document.getElementById("togglePassword");
        const passwordField = document.getElementById("loginPassword");
        const strengthText = document.getElementById("passwordStrength");

        toggle.addEventListener("change", function () {
            passwordField.type = this.checked ? "text" : "password";
        });

        passwordField.addEventListener("input", function () {
            const val = passwordField.value;
            let strength = "";
            let color = "";

            if (val.length === 0) {
                strength = "";
            } else if (val.length < 6) {
                strength = "Weak";
                color = "red";
            } else {
                const hasUpper = /[A-Z]/.test(val);
                const hasLower = /[a-z]/.test(val);
                const hasNumber = /[0-9]/.test(val);
                const hasSpecial = /[\W_]/.test(val);
                const score = [hasUpper, hasLower, hasNumber, hasSpecial].filter(Boolean).length;

                if (score >= 3 && val.length >= 8) {
                    strength = "Strong";
                    color = "limegreen";
                } else {
                    strength = "Medium";
                    color = "orange";
                }
            }

            strengthText.textContent = strength ? `Password strength: ${strength}` : "";
            strengthText.style.color = color;
        });

        // Auto-hide messages after 3 seconds
        const errorMessage = document.getElementById("errorMessage");
        const successMessage = document.getElementById("successMessage");

        [errorMessage, successMessage].forEach(msg => {
            if (msg) {
                setTimeout(() => {
                    msg.style.display = "none";
                }, 3000);
            }
        });
    </script>
</body>
</html>
