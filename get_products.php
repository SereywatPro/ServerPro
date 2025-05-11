<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

try {
    // Get DATABASE_URL from environment
    $url = getenv('DATABASE_URL');
    if (!$url) {
        throw new Exception('DATABASE_URL not set in environment variables.');
    }

    // Parse the URL
    $db = parse_url($url);

    $host = $db['host'];
    $port = $db['port'];
    $user = $db['user'];
    $pass = $db['pass'];
    $dbname = ltrim($db['path'], '/');

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);

    // If this is a direct request to get_products.php, return the products
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pdo->query('SELECT * FROM public.products');
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($products);
        exit;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}
?>