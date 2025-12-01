<!DOCTYPE html>

<!-- pano iset up 

1. gawa kau bagong table sa db eto query

CREATE TABLE users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Password VARCHAR(255) NOT NULL
);

2. insert nyo values na to

INSERT INTO users (Username, Password)
VALUES ('admin', '$2y$10$ebXyffilcteRMcWL9cbH/.7rEz00PbdsW6DW/7jToAbSMTgNLUpEW');

3. bale eto credentials niya
username: admin
password: admin123 -->

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TBA Clinic Login</title>
    <link rel='stylesheet' href='style.css'>
</head>
<body class="tba-login-body">

<div class="header">
        <a id="hyperlink-logo" href="./login.php">
            <div class="header-img" id="logo">
                <img id="logo-img" src="./img/logo.svg">
                TBAClinic
            </div>
        </a>
        <ul class="links">
            <li><a href="./homepage-user.php">Home</a></li>
            <li><a href="#footer">Contact</a></li>
        </ul>
        <button id="mobile-menu-btn"><img class="header-img" src="./img/menu.svg"></button>
</div>

<div class='login-modal-container'>
    <div class="tba-login-container">
        <div class="tba-login-title">Welcome to TBA Clinic!</div>
        <div class='tba-login-subtitle'>Please enter your credentials below</div>
        <form action="login_process.php" method="POST" class="tba-login-form">
            <input 
                type="text" 
                name="username" 
                class="tba-login-input" 
                placeholder="Username" 
                required>
            
            <input 
                type="password" 
                name="password" 
                class="tba-login-input" 
                placeholder="Password" 
                required>

            <button type="submit" class="tba-login-button">Log In</button>
        </form>

        <?php if (isset($_GET['error'])): ?>
            <p class="tba-login-error">Invalid username or password.</p>
        <?php endif; ?>
    </div>
</div>
    <?php include('footer.php') ?>

</body>
</html>