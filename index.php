<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Upload Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        
        h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
        }
        
        .upload-area {
            border: 2px dashed #764ba2;
            border-radius: 8px;
            padding: 30px 20px;
            margin-bottom: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .upload-area:hover {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.05);
        }
        
        .upload-area p {
            color: #666;
            margin-bottom: 15px;
        }
        
        input[type="file"] {
            display: none;
        }
        
        .file-label {
            display: inline-block;
            background-color: #764ba2;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: 500;
        }
        
        .file-label:hover {
            background-color: #667eea;
        }
        
        .file-name {
            margin-top: 15px;
            font-size: 14px;
            color: #555;
        }
        
        button {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .info {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>CSV Upload Tool</h2>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <div class="upload-area" id="uploadArea">
                <p>Drag and drop your CSV file here or click to select</p>
                <label for="csv_file" class="file-label">Select CSV File</label>
                <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
                <div class="file-name" id="fileName">No file selected</div>
            </div>
            <button type="submit">Upload and Process</button>
        </form>
        <div class="info">
            The CSV file will be uploaded and processed automatically.
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('csv_file');
        const fileName = document.getElementById('fileName');
        const uploadArea = document.getElementById('uploadArea');

        uploadArea.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                fileName.textContent = fileInput.files[0].name;
            } else {
                fileName.textContent = 'No file selected';
            }
        });

        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#667eea';
            uploadArea.style.backgroundColor = 'rgba(102, 126, 234, 0.05)';
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.borderColor = '#764ba2';
            uploadArea.style.backgroundColor = 'transparent';
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#764ba2';
            uploadArea.style.backgroundColor = 'transparent';
            
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                if (fileInput.files[0].name.endsWith('.csv')) {
                    fileName.textContent = fileInput.files[0].name;
                } else {
                    fileName.textContent = 'Please select a CSV file';
                }
            }
        });
    </script>
</body>
</html>
