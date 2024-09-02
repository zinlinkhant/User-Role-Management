<?php
session_start();
include 'connect.php'; // Database connection
include 'header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'])) {
    $name = $_POST['name'];

    // Insert the new feature into the database
    $stmt = $pdo->prepare("INSERT INTO features (name) VALUES (:name)");
    $stmt->bindParam(':name', $name);
    $stmt->execute();

    // Redirect to the same page or another page after submission
    header("Location: featureAdd.php");
    exit();
}
?>
<?php include 'navbar.php'; ?>
<div class="container mx-auto mt-10 p-5 w-fit">
    <h1 class="text-3xl font-bold text-center mb-10">Add New Feature</h1>
    <div class="form-control bg-white bg-opacity-5 w-full p-5 rounded-lg shadow-lg">
        <form action="featureAdd.php" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium mb-2">Feature Name</label>
                <input type="text" name="name" class="input input-bordered w-full" required>
            </div>
            <div>
                <input type="submit" value="Add Feature" class="btn btn-primary w-full text-white">
            </div>
        </form>
    </div>
</div>