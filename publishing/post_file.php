<?php
// Set max execution time and memory limit to handle large uploads
set_time_limit(0);
ini_set('memory_limit', '1024M');

$groupsIds = $_POST['groupsids'];
$uploadDir = 'vaults/';
$uploadDir = $uploadDir . $groupsIds . '/';
$chunk = $_POST['chunk'];
$totalChunks = $_POST['totalChunks'];
$filename = $_POST['filename'];
$tempFilePath = $uploadDir . $filename . '.part';

// Create the upload directory if it doesn't exist
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Handle the incoming chunk
if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $chunkData = $_FILES['file']['tmp_name'];

    // Open the temporary file for appending chunks\
    $file = fopen($tempFilePath, 'ab'); // 'ab' mode is append binary
    $chunkData = fopen($chunkData, 'rb');

    while ($data = fread($chunkData, 1024 * 8)) {
        fwrite($file, $data);
    }

    fclose($file);
    fclose($chunkData);

    // Check if all chunks have been uploaded
    if ($chunk == $totalChunks - 1) {
        // Renaming the temporary file after all chunks are uploaded
        rename($tempFilePath, $uploadDir . $filename);
        echo json_encode(['success' => true, 'message' => 'Chunk uploaded successfully']);
    } else {
        echo json_encode(['success' => true, 'message' => 'Chunk uploaded successfully']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to upload chunk']);
}
?>
