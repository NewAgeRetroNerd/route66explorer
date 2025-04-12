<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Route 66 Explorer</title>
	<link rel="stylesheet" href="styles-main.css">
	<style>
		/* Auth Button Styles */
.auth-btn {
    background-color: #ff6b00; /* Vibrant orange */
    color: white;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: bold;
    font-family: inherit;
    font-size: 1rem;
    display: inline-block;
    margin-left: 10px;
    transition: background-color 0.3s ease, transform 0.2s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
}

.auth-btn:hover {
    background-color: #e55d00;
    transform: translateY(-1px);
    text-decoration: none;
}
</style>
</head>
<body>

<header>
	<img class="home-img" src="images/route66header.png" alt="Route 66 Road">
	<h1>Route 66 Explorer</h1>

    <div class="header-auth">
    <?php if (isset($_SESSION['username'])): ?>
        <span>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</span>
        <a class="auth-btn" href="logout.php">Logout</a>
    <?php else: ?>
        <a class="auth-btn" href="login.php">Login / Register</a>
    <?php endif; ?>
</div>

</header>

