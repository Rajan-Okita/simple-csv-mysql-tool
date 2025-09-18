# CSV to MySQL Uploader

A simple web application that allows users to upload CSV files and import them into a MySQL database. The project includes both PHP-based web interface and a Python script for direct CSV processing.

## Features

- **CSV File Upload**: Easy-to-use interface for uploading CSV files
- **Database Integration**: Automatically imports CSV data into MySQL tables
- **Validation**: Checks CSV format and data integrity before import
- **Logging**: Tracks import operations for audit purposes
- **Dual Implementation**: 
  - PHP-Based web interface for browser uploads
  - Python script for programmatic/scheduled imports

## Installation

### Prerequisites

- PHP 7.0 or higher
- Python 3.6 or higher (for the Python script)
- MySQL/MariaDB database
- Web server (Apache, Nginx, etc.)
- XAMPP/WAMP/MAMP (for local development)
- Python packages: `mysql-connector-python` (for the Python script)

### Setup Instructions

1. Clone the repository:
   ```
   git clone https://github.com/Rajan-Okita/simple-csv-mysql-tool.git
   ```

2. Import the database schema:
   ```
   mysql -u username -p database_name < csv_uploader.sql
   ```

3. Configure database connection:
   - Open `db.php` and update the database credentials for PHP
   - For Python script, update the database config in `csv_to_mysql.py`

4. Install required Python packages (if using the Python script):
   ```
   pip install mysql-connector-python
   ```

5. Access the application via your web server:
   ```
   http://localhost/csvUploader/
   ```

## Usage

### Web Interface

1. Open the application in your web browser
2. Click "Choose File" and select a CSV file from your computer
3. Click "Upload" to upload and process the file
4. Review import results and check for any errors
5. The data will now be available in the specified MySQL table

### Python Script

The project includes a Python script (`csv_to_mysql.py`) that can be used for:
- Automated/scheduled imports
- Processing large CSV files
- Command-line operations

To use the Python script:

1. Place your CSV file in the `uploads` directory
2. Run the script:
   ```
   python csv_to_mysql.py
   ```
3. The script will automatically process the most recent CSV file in the uploads directory
4. Check the `import_log.txt` for import results

## CSV File Format

The application expects CSV files with the following characteristics:
- First row contains column headers
- Values separated by commas
- Text fields may be enclosed in double quotes
- Example:
  ```
  id,name,email,age
  1,"Smith, John",john@example.com,30
  2,"Doe, Jane",jane@example.com,25
  ```

## File Structure

```
├── csv_to_mysql.py     # Python script for direct CSV to MySQL conversion
├── csv_uploader.sql    # Database schema for the application
├── db.php              # Database connection configuration
├── import_log.txt      # Log file for import operations
├── index.php           # Main application interface
├── upload.php          # Handles file uploads and processing
└── uploads/            # Directory for storing uploaded CSV files
```