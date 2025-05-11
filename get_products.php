<?php
// get_products.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once 'DB.php';

try {
    $stmt = $pdo->query('SELECT * FROM public.products ORDER BY id DESC');
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($products);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error fetching products: ' . $e->getMessage()]);
}
?>