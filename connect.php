<?php

$host = "localhost";
$dbname = "newdatabase"; 
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connection successful!";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

function set_user(PDO $pdo, string $pwd, string $username, string $email)
{
    try {
        $query = "INSERT INTO user (username, pwd, email) VALUES (:username, :pwd, :email)";
        $stmt = $pdo->prepare($query);

        $options = ['cost' => 12];
        $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT, $options);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':pwd', $hashedPwd);
        $stmt->bindParam(':email', $email);

        $stmt->execute();
        echo "User created successfully!";
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
}

// Example usage
set_user($pdo, 'examplePassword', 'exampleUsername', 'exampleEmail@example.com');

?>
