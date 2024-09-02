<?php
include 'connect.php';
include 'header.php';
session_start();

// Handle role assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_POST['userId']) || isset($_POST['role_id']) || isset($_POST['action'])) {
    $userId = $_POST['userId'];
    $roleId = $_POST['role_id'];
    $action = $_POST['action'];

    if ($action === 'assign') {
        // Assign role to user
        $sql = $pdo->prepare("UPDATE users SET role_id = :roleId WHERE id = :userId");
        $sql->bindParam(':roleId', $roleId);
        $sql->bindParam(':userId', $userId);
        $sql->execute();
    } else if ($action === 'remove') {
        $sql = $pdo->prepare("UPDATE users SET role_id = NULL WHERE id = :userId");
        $sql->bindParam(':userId', $userId);
        $sql->execute();
        header("Location: index.php");
    }

    header("Location: assignUserToRole.php");
    exit();
}

// Fetch data from the users table
$userStmt = $pdo->prepare("SELECT id, name, username, role_id, phone, email, gender FROM users");
$userStmt->execute();
$users = $userStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
    <?php include 'navbar.php'; ?>
    <div class="overflow-x-auto mt-5 bg-white bg-opacity-5 m-20 p-5 rounded-lg shadow-lg">
        <table class="table w-full">
            <!-- Table Head -->
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td>
                        <?php
                            $fetchRole = $pdo->prepare("SELECT name FROM roles WHERE id = :id");
                            $fetchRole->bindParam(':id', $user['role_id']);
                            $fetchRole->execute();
                            $role = $fetchRole->fetchColumn();
                            echo htmlspecialchars($role);
                            ?>
                    </td>
                    <td>
                        <form action="assignUserToRole.php" method="post">
                            <select name="role_id" class="select select-bordered w-full max-w-xs">
                                <option disabled selected>Select Role</option>
                                <?php
                                    $roleStmt = $pdo->prepare("SELECT id, name FROM roles");
                                    $roleStmt->execute();
                                    $roles = $roleStmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($roles as $role) {
                                        echo '<option value="' . htmlspecialchars($role['id']) . '">' . htmlspecialchars($role['name']) . '</option>';
                                    }
                                    ?>
                            </select>
                            <input type="hidden" name="userId" value="<?php echo htmlspecialchars($user['id']); ?>">
                            <button type="submit" name="action" value="assign"
                                class="btn btn-primary mt-5 text-white ml-5">Assign Role</button>
                            <button type="submit" name="action" value="remove"
                                class="btn btn-error mt-5 text-white ml-5">Remove Role</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>