<?php
// Test database connection and authentication setup
require_once 'config/db.php';
require_once 'core/auth.php';

echo "<h2>Database Connection Test</h2>";

// Test database connection
$database = new Database();
$conn = $database->getConnection();

if ($conn) {
    echo "✅ Database connection successful<br>";
    
    // Check if tables exist
    $tables = ['employees', 'users', 'departments', 'designations'];
    foreach ($tables as $table) {
        $query = "SHOW TABLES LIKE '$table'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo "✅ Table '$table' exists<br>";
        } else {
            echo "❌ Table '$table' does NOT exist<br>";
        }
    }
    
    // Check users count
    $query = "SELECT COUNT(*) as count FROM users";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<br>📊 Total users in database: " . $result['count'] . "<br><br>";
    
    // Test authentication
    echo "<h2>Authentication Test</h2>";
    $auth = new Auth();
    
    // Test with employee credentials
    $result = $auth->login('employee@ssspl.com', 'demo@123');
    
    if ($result['success']) {
        echo "✅ Login successful!<br>";
        echo "Role: " . $result['role'] . "<br>";
        echo "Message: " . $result['message'] . "<br>";
    } else {
        echo "❌ Login failed!<br>";
        echo "Message: " . $result['message'] . "<br>";
    }
    
} else {
    echo "❌ Database connection failed<br>";
}
?>
