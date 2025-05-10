<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once "get_products.php";

$response = ["success" => false, 'message' => ""];

try {
    // create variable for script query and condition
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? '';
    $price = is_numeric($price) ? $price : 0;
    $category = $_POST['category'] ?? '';
    $imagePath = '';

    // if the product is exits
    if(!$id) throw new Exception('Product ID is required');

    //Fetch existing product
    $stmt = $pdo->prepare("SELECT image_url FROM products WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        throw new Exception('Product not found');
    }

    // if the image is exits
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/";

        // Make sure uploads directory exists
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $imageName = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $imageName;

        // Move new image
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $imagePath = $targetFile;

            // Delete the old image
            if (!empty($product['image_url']) && file_exists($product['image_url'])) {
                unlink($product['image_url']);
            }
        } else {
            throw new Exception('Failed to upload image');
        }
    } else {
        // No new image sent — keep existing image
        $imagePath = $product['image_url'];
    }

    // update script query
    $sql = "UPDATE products SET name = :name, price = :price, category = :category, image_url = :image_url WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'name' => $name,
        'price' => $price,
        'category' => $category,
        'image_url' => $imagePath,
        'id' => $id
    ]);

    $response["success"] = true;
    $response['message'] = "Product updated successfully";

} catch(Exception $err) {
    $response["success"] = false;
    $response["message"] = $err->getMessage();
}

echo json_encode($response);
?>