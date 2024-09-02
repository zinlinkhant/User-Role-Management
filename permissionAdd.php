<?php
session_start();
include 'connect.php';
include 'header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['feature_id'])) {
    $name = $_POST['name'];
    $featureId = $_POST['feature_id'];

    // Insert the new permission into the database
    $stmt = $pdo->prepare("INSERT INTO permissions (name, feature_id) VALUES (:name, :feature_id)");
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':feature_id', $featureId);
    $stmt->execute();

    header("Location: permissionAdd.php");
    exit();
}

// Fetch all features for the dropdown
$featureStmt = $pdo->prepare("SELECT id, name FROM features");
$featureStmt->execute();
$features = $featureStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'navbar.php'; ?>
<div class="container mx-auto mt-10 p-5 w-fit">
    <h1 class="text-3xl font-bold text-center mb-10">Add New Permission</h1>
    <div class="form-control bg-white bg-opacity-5 w-full p-5 rounded-lg shadow-lg">
        <form action="permissionAdd.php" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium mb-2">Permission Name</label>
                <input type="text" name="name" class="input input-bordered w-full" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Feature</label>
                <select name="feature_id" class="select select-bordered w-full">
                    <option disabled selected>Select Feature</option>
                    <?php foreach ($features as $feature): ?>
                    <option value="<?php echo $feature['id']; ?>"><?php echo htmlspecialchars($feature['name']); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <input type="submit" value="Add Permission" class="btn btn-primary w-full text-white">
            </div>
        </form>
    </div>
</div>