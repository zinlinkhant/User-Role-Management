<?php
// Include the database connection
include 'connect.php';
include 'header.php';

// Initialize variables to store form data and error messages
$name = $username = $email = $password = "";
$success_message = $error_message = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form input class="text-black px-2 py-1" data
    $name = htmlspecialchars($_POST['name']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    try {
        // Prepare an insert statement
        $stmt = $pdo->prepare("INSERT INTO users (name, username, email, password) VALUES (:name, :username, :email, :password)");

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);

        // Execute the statement
        $stmt->execute();

        // Success message
        $success_message = "Registration successful!";
    } catch (PDOException $e) {
        // Error message
        $error_message = "Registration failed: " . $e->getMessage();
    }
}
?>



<body>
    <?php
    include 'navbar.php';
    ?>
    <div class="max-w-md mx-auto mt-16 bg-white bg-opacity-5 p-8 shadow-lg rounded-lg">

        <h2 class="text-2xl font-semibold text-gray-200 text-center mb-6">User Register</h2>

        <?php
        // Display success or error message
        if (!empty($success_message)) {
            echo "<p class='text-center text-green-600 font-medium mb-4'>$success_message</p>";
        } elseif (!empty($error_message)) {
            echo "<p class='text-center text-red-600 font-medium mb-4'>$error_message</p>";
        }
        ?>

        <form action="register.php" method="post" class="space-y-6">
            <div class="form-control">
                <label for="name" class="label">
                    <span class="label-text font-medium text-gray-400">Name</span>
                </label>
                <input class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            </div>

            <div class="form-control">
                <label for="username" class="label">
                    <span class="label-text font-medium text-gray-400">Username</span>
                </label>
                <input class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="text" id="username" name="username" value="<?php echo $username; ?>" required>
            </div>

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
                <input type="submit" value="Register" class="btn btn-primary w-full">
            </div>
        </form>
    </div>
</body>



</html>