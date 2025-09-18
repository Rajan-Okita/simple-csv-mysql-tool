<?php
require 'db.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV Upload Result</title>
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
            max-width: 600px;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        
        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 600;
        }
        
        .message {
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            position: relative;
            overflow: hidden;
        }
        
        .message:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            opacity: 0.7;
            z-index: -1;
        }
        
        .success {
            color: #2e7d32;
        }
        
        .success:before {
            background: linear-gradient(45deg, #43a047, #66bb6a);
        }
        
        .error {
            color: #c62828;
        }
        
        .error:before {
            background: linear-gradient(45deg, #ef5350, #e57373);
        }
        
        .file-name {
            font-weight: 500;
            margin: 10px 0;
            color: #555;
            word-break: break-all;
        }
        
        .details {
            margin-top: 15px;
            line-height: 1.6;
            color: #555;
        }
        
        .back-button {
            background: linear-gradient(to right, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
        }
        
        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .loading {
            display: inline-block;
            margin: 15px 0;
        }

        .loading:after {
            content: ' ';
            display: block;
            width: 20px;
            height: 20px;
            margin: 8px;
            border-radius: 50%;
            border: 3px solid #667eea;
            border-color: #667eea transparent #667eea transparent;
            animation: loading 1.2s linear infinite;
        }

        @keyframes loading {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        .status {
            background: linear-gradient(to right, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: left;
            border-left: 4px solid #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
            $fileTmpPath = $_FILES['csv_file']['tmp_name'];
            $fileName = basename($_FILES['csv_file']['name']);
            $uploadDir = __DIR__ . '/uploads/';
            $destPath = $uploadDir . $fileName;

            if (!file_exists($fileTmpPath)) {
                echo '<h2>Upload Failed</h2>';
                echo '<div class="message error">';
                echo '<p>Uploaded file not found.</p>';
                echo '</div>';
            } else if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Run the Python script in the background
                $pythonScript = escapeshellarg(__DIR__ . '/csv_to_mysql.py');
                $command = "start /B python $pythonScript";
                pclose(popen($command, 'r'));
                
                echo '<h2>Upload Successful</h2>';
                echo '<div class="message success">';
                echo '<p>Your file has been uploaded and is being processed.</p>';
                echo '<p class="file-name">' . htmlspecialchars($fileName) . '</p>';
                echo '</div>';
                
                echo '<div class="status">';
                echo '<p><strong>Status:</strong> CSV import is running in the background.</p>';
                echo '<p class="details">Your data will be available in the database shortly.</p>';
                echo '<div class="loading"></div>';
                echo '</div>';
            } else {
                echo '<h2>Upload Failed</h2>';
                echo '<div class="message error">';
                echo '<p>Failed to save uploaded file.</p>';
                echo '</div>';
            }
        } else {
            echo '<h2>Upload Failed</h2>';
            echo '<div class="message error">';
            echo '<p>No file uploaded.</p>';
            echo '</div>';
        }
        ?>
        <a href="index.php" class="back-button">Back to Upload</a>
    </div>
</body>
</html>