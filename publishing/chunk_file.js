const CHUNK_SIZE = 10 * (1024 * 1024); // 10Megs per chunk

// This function starts the upload process.
function startUpload() {
    const fileInput = document.getElementById('fileInput');
    const file = fileInput.files[0];
    
    if (!file) {
        alert("Please select a file to upload.");
        return;
    }

    const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
    let currentChunk = 0;

    // Function to handle uploading of each chunk.
    function uploadChunk() {
        const start = currentChunk * CHUNK_SIZE;
        const end = Math.min(start + CHUNK_SIZE, file.size);
        const chunk = file.slice(start, end);
        
        const formData = new FormData();
        formData.append('file', chunk);
        formData.append('chunk', currentChunk);
        formData.append('totalChunks', totalChunks);
        formData.append('filename', file.name);

        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                currentChunk++;
                if (currentChunk < totalChunks) {
                    uploadChunk();
                } else {
                    alert('Upload complete!');
                }
            } else {
                alert('Error uploading chunk ' + currentChunk);
            }
        })
        .catch(error => {
            console.error('Upload error:', error);
            alert('Upload failed.');
        });
    }

    // Start uploading the first chunk
    uploadChunk();
}
