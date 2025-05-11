<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once 'DB.php';

$response = ['success' => false, 'message' => ''];

try {
    $name = $_POST['name'] ?? '';
    $price = is_numeric($_POST['price'] ?? '') ? $_POST['price'] : 0;
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
        } else {
            throw new Exception("Failed to upload image.");
        }
    }

    $sql = "INSERT INTO products (name, category, image_url, price) 
            VALUES (:name, :category, :image_url, :price)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name' => $name,
        'category' => $category,
        'image_url' => $imagePath,
        'price' => $price
    ]);

    $response['success'] = true;
    $response['message'] = 'Product added successfully';
} catch (Exception $e) {
    http_response_code(500);
    $response['message'] = 'Error: ' . $e->getMessage();
}

echo json_encode($response);
exit;
?>