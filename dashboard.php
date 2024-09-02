<?php
include 'connect.php';
include 'header.php';
session_start();
if (isset($_SESSION['user_id'])) {
    $username = $pdo->prepare("SELECT name FROM users WHERE id = :id");
    $username->bindParam(':id', $_SESSION['user_id']);
    $username->execute();
    $name = $username->fetchColumn();
    $loginCondition = "Welcome " . $name;
} else {
    $loginCondition = "Please login first";
}

?>

<body>
    <?php
    include 'navbar.php';
    ?>
    <h1 class="text-3xl font-bold underline">
        <?php
        echo $loginCondition
        ?>
    </h1>
    <a href="logout.php"><input type="submit" value="Logout" class="bg-red-800 px-5 py-2 text-white rounded"></a>
</body>