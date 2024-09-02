<?php
// Include the database connection
include 'connect.php';
include 'header.php';

// Initialize variables to store form data and error messages
$name = $username = $email = $password = "";
$success_message = $error_message = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form input data
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password']; // Don't hash here; we need to verify against the hashed password

    try {
        // Prepare a select statement to find the user by email
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user exists and if the password is correct
        if ($user && password_verify($password, $user['password'])) {
            // Password is correct, log the user in
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;

            // Redirect to a logged-in page or dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            // Invalid email or password
            $login_error = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        // Error message
        $login_error = "Login failed: " . $e->getMessage();
    }
}
?>



<body>
    <?php
    include 'navbar.php';
    ?>
    <div class="max-w-md mx-auto mt-16 bg-white bg-opacity-5 p-8 shadow-lg rounded-lg">


        <h2 class="text-2xl font-semibold text-gray-200 text-center mb-6">User Login</h2>

        <?php
        // Display success or error message
        if (!empty($success_message)) {
            echo "<p class='text-green-300 font-bold'>$success_message</p>";
        } elseif (!empty($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>

        <form action="login.php" method="post" class="space-y-6">
            <div class="form-control">
                <label for="email" class="label">
                    <span class="label-text font-medium text-gray-400">Email</span>
                </label>
                <input class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>

            <div class="form-control">
                <label for="password" class="label">
                    <span class="label-text font-medium text-gray-400">Password</span>
                </label>
                <input class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="password" id="password" name="password" required>
            </div>

            <div class="form-control">
                <input type="submit" value="Login" class="btn btn-primary w-full">
            </div>
        </form>
    </div>
</body>

</html>