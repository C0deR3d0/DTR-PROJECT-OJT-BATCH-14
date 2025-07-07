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
        // Fetch admins with non-hashed passwords
        $stmt = $conn->query("SELECT adminID, password FROM admin WHERE password NOT LIKE '$2y$%'");
    } else {
        echo "No plaintext password column found. Checking for empty passwordHash values...\n";
        $stmt = $conn->query("SELECT adminID FROM admin WHERE passwordHash IS NULL OR passwordHash = ''");
    }

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($users)) {
        echo "No passwords need migration.\n";
        exit;
    }

    echo "Found " . count($users) . " admin(s) to migrate.\n";

    $conn->beginTransaction();

    foreach ($users as $user) {
        $adminID = $user['adminID'];
        if ($hasPasswordColumn) {
            $plaintext = $user['password'];
        } else {
            // Use a secure randomly generated default password (or skip)
            echo "Admin ID $adminID has no password. Skipping...\n";
            continue;
        }

        $hashed = password_hash($plaintext, PASSWORD_BCRYPT);

        // Update passwordHash
        $update = $conn->prepare("UPDATE admin SET passwordHash = ? WHERE adminID = ?");
        $update->execute([$hashed, $adminID]);

        // Optional: clear the old plaintext password
        if ($hasPasswordColumn) {
            $clear = $conn->prepare("UPDATE admin SET password = NULL WHERE adminID = ?");
            $clear->execute([$adminID]);
        }

        echo "Migrated admin ID $adminID\n";
    }

    $conn->commit();
    echo "Password migration completed successfully.\n";

} catch (Exception $e) {
    $conn->rollBack();
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
