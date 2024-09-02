<?php
session_start();
include 'connect.php';
include 'header.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['featureId']) && isset($_POST['featureName']) && isset($_POST['update'])) {
        // Update feature name
        $featureId = $_POST['featureId'];
        $featureName = $_POST['featureName'];

        $stmt = $pdo->prepare("UPDATE features SET name = :featureName WHERE id = :featureId");
        $stmt->bindParam(':featureName', $featureName);
        $stmt->bindParam(':featureId', $featureId);
        $stmt->execute();
        header("Location: updateFeature.php");
        exit();
    }

    if (isset($_POST['featureId']) && isset($_POST['delete'])) {
        // Delete feature
        $featureId = $_POST['featureId'];
        $stmt = $pdo->prepare("DELETE FROM features WHERE id = :featureId");
        $stmt->bindParam(':featureId', $featureId);
        $stmt->execute();
        header("Location: updateFeature.php");
        exit();
    }
}

// Fetch all features
$stmt = $pdo->prepare("SELECT id, name FROM features");
$stmt->execute();
$features = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<body>
    <?php include 'navbar.php'; ?>
    <div class="container mx-auto mt-10 p-5">
        <h1 class="text-3xl font-bold text-center mb-10">Update or Delete Features</h1>
        <div class="overflow-x-auto bg-white bg-opacity-5 w-full p-5 rounded-lg shadow-lg">
            <table class="table w-full">
                <!-- Table Head -->
                <thead>
                    <tr>
                        <th>Feature ID</th>
                        <th>Feature Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <!-- Table Body -->
                <tbody>
                    <?php foreach ($features as $feature): ?>
                    <tr>
                        <form action="updateFeature.php" method="POST">
                            <td><?php echo htmlspecialchars($feature['id']); ?></td>
                            <td>
                                <input type="text" name="featureName"
                                    value="<?php echo htmlspecialchars($feature['name']); ?>"
                                    class="input input-bordered w-full max-w-xs" required>
                            </td>
                            <td class="space-x-2">
                                <input type="hidden" name="featureId"
                                    value="<?php echo htmlspecialchars($feature['id']); ?>">
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