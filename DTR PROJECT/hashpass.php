<?php
require 'connect.php';

if (php_sapi_name() !== 'cli') {
    die("This script should only be run from the command line");
}

echo "Starting password migration...\n";

try {
    // Check if plaintext password column exists
    $columnCheck = $conn->query("SHOW COLUMNS FROM admin LIKE 'password'");
    $hasPasswordColumn = $columnCheck->rowCount() > 0;

    if ($hasPasswordColumn) {
        // Migration from plaintext password column to passwordHash
        $stmt = $conn->query("SELECT adminID, password FROM admin WHERE password NOT LIKE '$2y$%'");
    } else {
        // Handle case where only passwordHash exists
        echo "No plaintext password column found. Checking for empty hashes...\n";
        $stmt = $conn->query("SELECT adminID, passwordHash FROM admin WHERE passwordHash IS NULL OR passwordHash = ''");
    }

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($users)) {
        echo "No passwords need migration.\n";
        exit;
    }

    echo "Found " . count($users) . " admin(s) to migrate.\n";

    $conn->beginTransaction();

    foreach ($users as $user) {
        $plaintext = $hasPasswordColumn ? $user['password'] : 'defaultPassword'; // Set a secure default
        $hashed = password_hash($plaintext, PASSWORD_BCRYPT);

        $update = $conn->prepare("UPDATE admin SET passwordHash = ? WHERE adminID = ?");
        $update->execute([$hashed, $user['adminID']]);

        echo "Updated admin ID {$user['adminID']}\n";
    }

    $conn->commit();
    echo "Migration completed successfully!\n";

} catch (Exception $e) {
    $conn->rollBack();
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}