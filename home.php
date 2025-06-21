<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Events</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            box-sizing: border-box;
        }

        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: #ffd700;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        header h2 {
            margin: 0;
            color: #222;
        }

        .logout {
            padding: 10px 15px;
            background: #ff4d4d;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .container {
            flex: 1;
            padding: 20px;
        }

        .message {
            text-align: center;
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .events {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .event {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .event h3 {
            margin-top: 0;
        }

        .event button {
            margin-top: 10px;
            padding: 10px;
            background: #28a745;
            border: none;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 12px 0;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            header h2 {
                font-size: 16px;
            }

            .logout {
                padding: 8px 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<header>
    <h2>Welcome, <?php echo $_SESSION['user']['username']; ?> to CHATPAY</h2>
    <a href="logout.php" class="logout">Logout</a>
</header>

<div class="container">
    <?php if (isset($_GET['message'])): ?>
        <div class="message" id="successMessage"><?php echo htmlspecialchars($_GET['message']); ?></div>
    <?php endif; ?>

    <h2 style="text-align: center;">Available Events</h2>
    <div class="events">
        <div class="event">
            <h3>Online Coding Bootcamp</h3>
            <p>Learn full-stack development from zero to hero in just 6 weeks.</p>
            <button>Join Now</button>
        </div>
    </div>
</div>

<footer>
    &copy; 2025 CHATPAY. All rights reserved.
</footer>

<script>
    const successMsg = document.getElementById("successMessage");
    if (successMsg) {
        setTimeout(() => {
            successMsg.style.display = "none";
        }, 3000);
    }
</script>

</body>
</html>
