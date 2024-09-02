<?php
include 'connect.php';
include 'header.php';
session_start();
// Now use $pdo to interact with the database

?>
<?php

// Fetch data from the users table
$stmt = $pdo->prepare("SELECT id, name, username, phone, email, gender FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<body>
    <?php include 'navbar.php'; ?>
    <div class="overflow-x-auto mt-5 bg-white bg-opacity-5 mx-10">
        <table class="table w-full">
            <!-- Table Head -->
            <thead>
                <tr>
                    <th>id</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Gender</th>
                </tr>
            </thead>
            <!-- Table Body -->
            <tbody>
                <?php foreach ($users as $index => $user): ?>
                <tr>
                    <th><?php echo htmlspecialchars($index + 1); ?></th>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['phone']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['gender']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>