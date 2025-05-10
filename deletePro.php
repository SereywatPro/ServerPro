<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once 'get_products.php'; // connects to database

$response = ['success' => false, 'message' => ''];

try {
    $productId = $_POST['id'] ?? $_GET['id'] ?? null;

    if (!$productId) {
        throw new Exception("Product ID is required.");
    }

    // Optional: Delete image file if needed
    // $stmt = $pdo->prepare("SELECT image_url FROM products WHERE id = :id");
    // $stmt->execute(['id' => $productId]);
    // $product = $stmt->fetch(PDO::FETCH_ASSOC);
    // if (!empty($product['image_url']) && file_exists($product['image_url'])) {
    //     unlink($product['image_url']);
    // }

    // Delete product
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $stmt->execute(['id' => $productId]);

    $response['success'] = true;
    $response['message'] = "Product deleted.";
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
