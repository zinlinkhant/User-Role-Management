<?php
include 'connect.php';
include 'header.php';

// Handle role update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['roleId']) && isset($_POST['roleName']) && isset($_POST['update'])) {
        // Update role name
        $roleId = $_POST['roleId'];
        $roleName = $_POST['roleName'];
        $stmt = $pdo->prepare("UPDATE roles SET name = :roleName WHERE id = :roleId");
        $stmt->bindParam(':roleName', $roleName);
        $stmt->bindParam(':roleId', $roleId);
        $stmt->execute();
        header("Location: roleUpdate.php");
        exit();
    }

    if (isset($_POST['roleId']) && isset($_POST['delete'])) {
        // Delete role
        $roleId = $_POST['roleId'];
        $stmt = $pdo->prepare("DELETE FROM roles WHERE id = :roleId");
        $stmt->bindParam(':roleId', $roleId);
        $stmt->execute();
        header("Location: roleUpdate.php");
        exit();
    }
}

// Fetch all roles
$stmt = $pdo->prepare("SELECT id, name FROM roles");
$stmt->execute();
$roles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all permissions for each role
$permissionsByRole = [];
foreach ($roles as $role) {
    $roleId = $role['id'];
    $stmt = $pdo->prepare("
        SELECT p.name 
        FROM permissions p 
        JOIN role_permission rp ON p.id = rp.permission_id 
        WHERE rp.role_id = :roleId
    ");
    $stmt->bindParam(':roleId', $roleId);
    $stmt->execute();
    $permissionsByRole[$roleId] = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>

<?php include 'navbar.php'; ?>
<div class="container mx-auto mt-10 p-5">
    <h1 class="text-3xl font-bold text-center mb-10">Update or Delete Roles</h1>
    <div class="overflow-x-auto bg-white bg-opacity-5 w-full p-5 rounded-lg shadow-lg">
        <table class="table w-full">
            <!-- Table Head -->
            <thead>
                <tr>
                    <th>Role ID</th>
                    <th>Role Name</th>
                    <th>Permissions</th>
                    <th>Action</th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
                <?php foreach ($roles as $role): ?>
                <tr>
                    <form action="roleUpdate.php" method="POST">
                        <td><?php echo htmlspecialchars($role['id']); ?></td>
                        <td>
                            <input type="text" name="roleName" value="<?php echo htmlspecialchars($role['name']); ?>"
                                class="input input-bordered w-full max-w-xs" required>
                        </td>
                        <td>
                            <?php
                                if (!empty($permissionsByRole[$role['id']])) {
                                    echo implode(', ', $permissionsByRole[$role['id']]);
                                } else {
                                    echo 'No permissions assigned';
                                }
                                ?>
                        </td>
                        <td class="space-x-2">
                            <input type="hidden" name="roleId" value="<?php echo htmlspecialchars($role['id']); ?>">
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