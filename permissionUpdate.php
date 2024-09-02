<?php
session_start();
include 'connect.php'; // Database connection
include 'header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['permissionId']) && isset($_POST['permissionName']) && isset($_POST['feature_id']) && isset($_POST['update'])) {
        // Update permission
        $permissionId = $_POST['permissionId'];
        $permissionName = $_POST['permissionName'];
        $featureId = $_POST['feature_id'];

        $stmt = $pdo->prepare("UPDATE permissions SET name = :permissionName, feature_id = :featureId WHERE id = :permissionId");
        $stmt->bindParam(':permissionName', $permissionName);
        $stmt->bindParam(':featureId', $featureId);
        $stmt->bindParam(':permissionId', $permissionId);
        $stmt->execute();
        header("Location: permissionUpdate.php");
        exit();
    }

    if (isset($_POST['permissionId']) && isset($_POST['delete'])) {
        // Delete permission
        $permissionId = $_POST['permissionId'];
        $stmt = $pdo->prepare("DELETE FROM permissions WHERE id = :permissionId");
        $stmt->bindParam(':permissionId', $permissionId);
        $stmt->execute();
        header("Location: permissionUpdate.php");
        exit();
    }
}

// Fetch all permissions
$stmt = $pdo->prepare("SELECT p.id, p.name, p.feature_id, f.name AS feature_name FROM permissions p JOIN features f ON p.feature_id = f.id");
$stmt->execute();
$permissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all features for the dropdown
$featureStmt = $pdo->prepare("SELECT id, name FROM features");
$featureStmt->execute();
$features = $featureStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'navbar.php'; ?>
<div class="container mx-auto mt-10 p-5">

    <h1 class="text-3xl font-bold text-center mb-10">Update or Delete Permissions</h1>
    <div class="overflow-x-auto bg-white bg-opacity-5 w-full p-5 rounded-lg shadow-lg">
        <table class="table w-full">
            <!-- Table Head -->
            <thead>
                <tr>
                    <th>Permission ID</th>
                    <th>Permission Name</th>
                    <th>Feature</th>
                    <th>Action</th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
                <?php foreach ($permissions as $permission): ?>
                <tr>
                    <form action="permissionUpdate.php" method="POST">
                        <td><?php echo htmlspecialchars($permission['id']); ?></td>
                        <td>
                            <input type="text" name="permissionName"
                                value="<?php echo htmlspecialchars($permission['name']); ?>"
                                class="input input-bordered w-full max-w-xs" required>
                        </td>
                        <td>
                            <select name="feature_id" class="select select-bordered w-full max-w-xs">
                                <?php foreach ($features as $feature): ?>
                                <option value="<?php echo $feature['id']; ?>"
                                    <?php if ($feature['id'] == $permission['feature_id']) echo 'selected'; ?>>
                                    <?php echo htmlspecialchars($feature['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td class="space-x-2">
                            <input type="hidden" name="permissionId"
                                value="<?php echo htmlspecialchars($permission['id']); ?>">
                            <button type="submit" name="update" class="btn btn-primary text-white">Update</button>
                            <button type="submit" name="delete" class="btn btn-error text-white">Delete</button>
                        </td>
                    </form>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>

</html>