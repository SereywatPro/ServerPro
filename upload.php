<?php
$targetDir = "uploads/";
$targetFile = $targetDir . basename($_FILES["image"]["name"]);
if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
    echo "The file has been uploaded.";
} else {
    echo "Error uploading file.";
}
?>
