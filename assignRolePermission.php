<?php
session_start();
include 'connect.php'; // Database connection
include 'header.php';

// Handle form submission for assigning role to permission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['role_id']) && isset($_POST['permission_id'])) {
    $roleId = $_POST['role_id'];
    $permissionId = $_POST['permission_id'];

    // Insert the new role-permission assignment into the database
    $stmt = $pdo->prepare("INSERT INTO role_permission (role_id, permission_id) VALUES (:role_id, :permission_id)");
    $stmt->bindParam(':role_id', $roleId);
    $stmt->bindParam(':permission_id', $permissionId);
    $stmt->execute();

    header("Location: assignRolePermission.php");
    exit();
}

// Handle form submission for deleting role-permission assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_role_id']) && isset($_POST['delete_permission_id'])) {
    $deleteRoleId = $_POST['delete_role_id'];
    $deletePermissionId = $_POST['delete_permission_id'];

    // Delete the role-permission assignment from the database
    $stmt = $pdo->prepare("DELETE FROM role_permission WHERE role_id = :role_id AND permission_id = :permission_id");
    $stmt->bindParam(':role_id', $deleteRoleId);
    $stmt->bindParam(':permission_id', $deletePermissionId);
    $stmt->execute();

    header("Location: assignRolePermission.php");
    exit();
}

// Fetch all roles for the dropdown
$roleStmt = $pdo->prepare("SELECT id, name FROM roles");
$roleStmt->execute();
$roles = $roleStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all permissions for the dropdown
$permissionStmt = $pdo->prepare("SELECT id, name FROM permissions");
$permissionStmt->execute();
$permissions = $permissionStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all role-permission assignments
$assignmentStmt = $pdo->prepare("SELECT rp.role_id, rp.permission_id, r.name AS role_name, p.name AS permission_name
                                 FROM role_permission rp
                                 JOIN roles r ON rp.role_id = r.id
                                 JOIN permissions p ON rp.permission_id = p.id");
$assignmentStmt->execute();
$assignments = $assignmentStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'navbar.php'; ?>
<div class="container mx-auto mt-10 p-5 w-fit">
    <h1 class="text-3xl font-bold text-center mb-10">Assign Role to Permission</h1>
    <div class="form-control bg-white bg-opacity-5 w-full p-5 rounded-lg shadow-lg mb-10">
        <form action="assignRolePermission.php" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-medium mb-2">Select Role</label>
                <select name="role_id" class="select select-bordered w-full">
                    <option disabled selected>Select Role</option>
                    <?php foreach ($roles as $role): ?>
                    <option value="<?php echo htmlspecialchars($role['id']); ?>">
                        <?php echo htmlspecialchars($role['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Select Permission</label>
                <select name="permission_id" class="select select-bordered w-full">
                    <option disabled selected>Select Permission</option>
                    <?php foreach ($permissions as $permission): ?>
                    <option value="<?php echo htmlspecialchars($permission['id']); ?>">
                        <?php echo htmlspecialchars($permission['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <input type="submit" value="Assign Permission" class="btn btn-primary w-full text-white">
            </div>
        </form>
    </div>

    <h2 class="text-2xl font-bold text-center mb-5">Existing Role-Permission Assignments</h2>
    <div class="overflow-x-auto bg-white bg-opacity-5 w-full p-5 rounded-lg shadow-lg">
        <table class="table w-full">
            <!-- Table Head -->
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Permission</th>
                    <th>Action</th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
                <?php foreach ($assignments as $assignment): ?>
                <tr>
                    <td><?php echo htmlspecialchars($assignment['role_name']); ?></td>
                    <td><?php echo htmlspecialchars($assignment['permission_name']); ?></td>
                    <td>
                        <form action="assignRolePermission.php" method="POST">
                            <input type="hidden" name="delete_role_id"
                                value="<?php echo htmlspecialchars($assignment['role_id']); ?>">
                            <input type="hidden" name="delete_permission_id"
                                value="<?php echo htmlspecialchars($assignment['permission_id']); ?>">
                            <input type="submit" value="Delete" class="btn btn-danger text-white">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>

</html>