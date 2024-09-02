<?php
session_start();
include 'connect.php';
include 'header.php';

// Initialize variables to store form data and error messages
$name = $username = $phone = $email = $address = $gender = "";
$is_active = false;
$success_message = $error_message = "";

// Get the current user's information (assuming the user is logged in and their ID is stored in the session)
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch the current user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $name = $user['name'];
        $username = $user['username'];
        $phone = $user['phone'];
        $email = $user['email'];
        $address = $user['address'];
        $gender = $user['gender'];
        $is_active = $user['is_active'];
    }
} else {
    header('Location: login.php');
    exit();
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form input data
    $name = htmlspecialchars($_POST['name']);
    $username = htmlspecialchars($_POST['username']);
    $phone = htmlspecialchars($_POST['phone']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $gender = $_POST['gender'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];

    try {
        // Prepare an update statement
        $stmt = $pdo->prepare("UPDATE users SET name = :name, username = :username, phone = :phone, email = :email, address = :address, gender = :gender, is_active = :is_active, password = :password WHERE id = :id");

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':is_active', $is_active);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':id', $user_id);

        // Execute the statement
        $stmt->execute();

        // Success message
        $success_message = "User information updated successfully!";
    } catch (PDOException $e) {
        // Error message
        $error_message = "Update failed: " . $e->getMessage();
    }
}
?>

<body class="items-center justify-center min-h-screen">

    <?php include 'navbar.php'; ?>

    <div class="w-full max-w-md shadow-lg rounded-lg p-6 bg-white bg-opacity-5 mx-auto mt-16">
        <h2 class="text-2xl font-semibold text-gray-300 text-center mb-6">Update Your Information</h2>

        <?php
        if (!empty($success_message)) {
            echo "<p class='text-green-500 font-medium text-center mb-4'>$success_message</p>";
        } elseif (!empty($error_message)) {
            echo "<p class='text-red-500 font-medium text-center mb-4'>$error_message</p>";
        }
        ?>

        <form action="userUpdate.php" method="post" class="space-y-4">
            <div class="form-control">
                <label for="name" class="label">
                    <span class="label-text font-medium text-gray-300">Name</span>
                </label>
                <input class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>">
            </div>

            <div class="form-control">
                <label for="username" class="label">
                    <span class="label-text font-medium text-gray-300">Username</span>
                </label>
                <input class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
            </div>

            <div class="form-control">
                <label for="phone" class="label">
                    <span class="label-text font-medium text-gray-300">Phone</span>
                </label>
                <input class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
            </div>

            <div class="form-control">
                <label for="email" class="label">
                    <span class="label-text font-medium text-gray-300">Email</span>
                </label>
                <input class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
            </div>

            <div class="form-control">
                <label for="address" class="label">
                    <span class="label-text font-medium text-gray-300">Address</span>
                </label>
                <input class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>">
            </div>

            <div class="form-control">
                <label for="password" class="label">
                    <span class="label-text font-medium text-gray-300">New Password (Optional)</span>
                </label>
                <input class="input input-bordered w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
                    type="password" id="password" name="password">
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium text-gray-300">Gender</span>
                </label>
                <div class="flex items-center space-x-4">
                    <label class="cursor-pointer label-text text-gray-300">
                        <input type="radio" name="gender" value="1" class="radio"
                            <?php echo $gender == 1 ? 'checked' : ''; ?>>
                        <span class="ml-2">Male</span>
                    </label>
                    <label class="cursor-pointer label-text text-gray-300">
                        <input type="radio" name="gender" value="0" class="radio"
                            <?php echo $gender == 0 ? 'checked' : ''; ?>>
                        <span class="ml-2">Female</span>
                    </label>
                </div>
            </div>


            <div class="form-control">
                <label class="label">
                    <span class="label-text font-medium text-gray-300">Active</span>
                </label>
                <label class="cursor-pointer label-text text-gray-300">
                    <input type="checkbox" name="is_active" value="1" class="checkbox"
                        <?php echo $is_active ? 'checked' : ''; ?>>
                    <span class="ml-2">Active</span>
                </label>
            </div>

            <div class="form-control">
                <input type="submit" value="Update Information" class="btn btn-primary w-full">
            </div>
        </form>
    </div>

</body>

</html>