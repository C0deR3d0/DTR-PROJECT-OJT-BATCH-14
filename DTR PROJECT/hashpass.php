<?php
require 'connect.php';

// This script should only run from the command line
if (php_sapi_name() !== 'cli') {
    die("This script should only be run from the command line");
}

echo "Starting password migration...\n";

try {
    // Fetch users with unencrypted (non-hashed) passwords
    $stmt = $conn->query("SELECT adminID, password FROM admin WHERE password NOT LIKE '$2y$%'");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($users)) {
        echo "No plaintext passwords found — migration not needed.\n";
        exit;
    }

    echo "Found " . count($users) . " admin(s) to migrate.\n";

    $conn->beginTransaction();

    foreach ($users as $user) {
        $hashed = password_hash($user['password'], PASSWORD_BCRYPT);

        // Update into passwordHash column
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
