<?php
include 'connect.php';
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['roleName'])) {
    // Start a transaction
    $pdo->beginTransaction();

    try {
        $roleName = $_POST['roleName'];

        // Insert the new role into the database
        $sql = $pdo->prepare("INSERT INTO roles (name) VALUES (:name)");
        $sql->bindParam(':name', $roleName);
        $sql->execute();

        // Get the last inserted role ID
        $roleId = $pdo->lastInsertId();

        // Assign selected permissions to the new role
        if (isset($_POST['permission_ids']) && is_array($_POST['permission_ids'])) {
            $permissions = $_POST['permission_ids'];
            $stmt = $pdo->prepare("INSERT INTO role_permission (role_id, permission_id) VALUES (:role_id, :permission_id)");
            $stmt->bindParam(':role_id', $roleId);
            foreach ($permissions as $permissionId) {
                $stmt->bindParam(':permission_id', $permissionId);
                $stmt->execute();
            }
        }

        // Commit the transaction
        $pdo->commit();

        // Redirect to the role creation page
        header("Location: roleAdd.php");
        exit();
    } catch (Exception $e) {
        // Rollback the transaction if something goes wrong
        $pdo->rollBack();
        echo "Failed to add role and assign permissions: " . $e->getMessage();
    }
}

// Fetch all available permissions
$permissionStmt = $pdo->prepare("SELECT id, name FROM permissions");
$permissionStmt->execute();
$permissions = $permissionStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <?php include 'navbar.php'; ?>
    <div class="form-control mt-10">
        <!-- Role creation and permission assignment form -->
        <form action="roleAdd.php" method="POST" class="form-control bg-white bg-opacity-5 w-fit px-5 py-3 mx-auto">
            <label class="input-group">
                <span>Role Name</span>
                <input type="text" name="roleName" class="input input-bordered" required />
            </label>

            <div class="mt-5">
                <h3 class="text-lg font-semibold">Assign Permissions</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mt-3">
                    <?php foreach ($permissions as $permission): ?>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" name="permission_ids[]"
                            value="<?php echo htmlspecialchars($permission['id']); ?>" class="checkbox">
                        <span><?php echo htmlspecialchars($permission['name']); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <input type="submit" value="Create Role & Assign Permissions" class="btn btn-primary mt-5 text-white">
        </form>
    </div>
</body>

</html>