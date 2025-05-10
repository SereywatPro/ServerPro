<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once 'get_products.php';

$response = ['success' => false, 'message' => ''];

try {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $price = is_numeric($price) ? $price : 0;
    $category = $_POST['category'] ?? '';
    $imagePath = '';

    if (isset($_FILES['image'])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }
        $imageName = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $imageName;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    $sql = "INSERT INTO products (name, price, category, image_url) VALUES (:name, :price, :category, :image_url)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $name, 'price' => $price, 'category' => $category, 'image_url' => $imagePath]);
    
    $response['success'] = true;
    $response['message'] = 'Product added successfully';
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
exit;
?>