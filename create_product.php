<?php
ini_set('display_errors', 0); // Hide warnings from output
ini_set('log_errors', 1);
ini_set('error_log', '/tmp/php-error.log'); // Log errors instead
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
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
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('File upload error: ' . $_FILES['image']['error']);
    }
    $targetDir = "uploads/";
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    $imageName = basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . $imageName;
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $imagePath = $imageName; // Save only the filename
    } else {
        throw new Exception('Failed to move uploaded file.');
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
}catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit;
}

echo json_encode($response);
exit;
?>