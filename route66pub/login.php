<?php
session_start();
require_once 'db_connect.php'; 
include 'header.php'; 
include 'navigation.php'; 

$error = "";  
$userExists = false; 

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifier = trim($_POST["identifier"]); 

    // Check if identifier is provided
    if (empty($identifier)) {
        $error = "Please enter a username or email."; 
    } else {
        // Prepare SQL query to check if username or email exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc(); 

        // If user exists, set userExists flag
        if ($user) {
            $userExists = true;
        } else {
            // Redirect to register page if user doesn't exist
            header("Location: register.php?identifier=" . urlencode($identifier));
            exit;
        }
    }

    // If user exists and password is provided, verify password
    if ($userExists && isset($_POST["password"])) {
        $password = $_POST["password"];
        if (password_verify($password, $user['password_hash'])) {
            // Set session variables and redirect to homepage on successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            // Set error if password is incorrect
            $error = "Incorrect password.";
        }
    }
}
?>
<main>
<h2>Login</h2>
<?php if (!empty($error)): ?>
    <!-- Display error message if any -->
    <p class="form-error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="post" class="create-form">
    <label for="identifier">Username or Email:</label>
    <input type="text" id="identifier" name="identifier" required value="<?= isset($identifier) ? htmlspecialchars($identifier) : '' ?>">

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Log In</button>
</form>

<?php include 'footer.php'; ?>  <!-- Include footer template -->


