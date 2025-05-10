<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

try {
    // Db conf
    /* $host = 'localhost';
    $port = '1553'; // port of PostgreSQL server
    $dbname = 'shop';
    $user = 'postgres';
    $password = '1111';
 */

// Use Railway environment variables
$host = 'tramway.proxy.rlwy.net';
$port = '31672';
$dbname = 'railway';
$user = 'postgres';
$password = 'YdohTFxAnrEAYERDKAcnPGKQstjIstyc';

    // Use PDO instead of pg_connect() (supports scram-sha-256) (note)
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    // Create PDO connection
    $pdo = new PDO($dsn, $user, $password, $options);

    // If this is a direct request to get_products.php, return the products
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pdo->query('SELECT * FROM public.products');
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products);
        exit;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}
?>
