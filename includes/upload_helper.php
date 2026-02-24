<?php
// Function for secure file upload
function uploadFile($file, $targetDir, $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png']) {
    $fileName = basename($file['name']);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Generate unique name
    $newFileName = uniqid() . '.' . $fileType;
    $targetPath = $targetDir . $newFileName;

    if (!in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'message' => 'Type de fichier non autorisé.'];
    }

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'fileName' => $newFileName];
    }

    return ['success' => false, 'message' => 'Échec de l\'envoi du fichier.'];
}
?>
