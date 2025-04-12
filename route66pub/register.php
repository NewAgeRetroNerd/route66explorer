<?php
session_start();
require_once 'db_connect.php';  
include 'header.php';  
include 'navigation.php';  

$error = "";  
$success = ""; 
$username = $_GET['identifier'] ?? ''; 

// Function to validate password according to the required rules
function validate_password($password) {
    $errors = [];
    if (strlen($password) < 12) $errors[] = "at least 12 characters";
    if (!preg_match('/[a-z]/', $password)) $errors[] = "one lowercase letter";
    if (!preg_match('/[A-Z]/', $password)) $errors[] = "one uppercase letter";
    if (!preg_match('/[0-9]/', $password)) $errors[] = "one number";
    if (!preg_match('/[\W_]/', $password)) $errors[] = "one special character";
    return $errors;  // Return an array of password validation errors
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve the form input values
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $passwordErrors = validate_password($password);  // Validate the password

    // Check if any of the form fields are empty
    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!empty($passwordErrors)) {  // Check if password does not meet the requirements
        $error = "Password must contain: " . implode(", ", $passwordErrors);
    } else {
        // Prepare a query to check if the username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        // If the username or email already exists, display an error
        if ($stmt->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            // Hash the password and insert the new user into the database
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $passwordHash);
            if ($stmt->execute()) {  
                $success = "Registration successful. You can now <a href='login.php'>log in</a>.";
            } else {  
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<main>
<h2>Register</h2>
<?php if (!empty($error)): ?>  <!-- Display error if it exists -->
    <p class="form-error"><?= htmlspecialchars($error) ?></p>
<?php elseif (!empty($success)): ?>  <!-- Display success message if registration was successful -->
    <p class="form-success"><?= $success ?></p>
<?php endif; ?>
<form method="post" class="create-form">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required value="<?= htmlspecialchars($username) ?>">

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required oninput="validatePassword()">  <!-- Validate password as user types -->

    <ul id="password-rules">
        <!-- Display password requirements and validation status -->
        <li id="length" class="invalid">At least 12 characters</li>
        <li id="lower" class="invalid">One lowercase letter</li>
        <li id="upper" class="invalid">One uppercase letter</li>
        <li id="number" class="invalid">One number</li>
        <li id="special" class="invalid">One special character</li>
    </ul>

    <button type="submit">Register</button>
</form>

<script>
// Function to dynamically check password validity on input
function validatePassword() {
    const pwd = document.getElementById('password').value;
    document.getElementById('length').className = pwd.length >= 12 ? 'valid' : 'invalid';
    document.getElementById('lower').className = /[a-z]/.test(pwd) ? 'valid' : 'invalid';
    document.getElementById('upper').className = /[A-Z]/.test(pwd) ? 'valid' : 'invalid';
    document.getElementById('number').className = /\d/.test(pwd) ? 'valid' : 'invalid';
    document.getElementById('special').className = /[\W_]/.test(pwd) ? 'valid' : 'invalid';
}
</script>

<?php include 'footer.php'; ?> 



