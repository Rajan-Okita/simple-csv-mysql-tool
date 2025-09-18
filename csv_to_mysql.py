
import csv
import mysql.connector
import os
import sys

# Database connection settings
config = {
    'user': 'root',
    'password': '',
    'host': 'localhost',
    'database': 'csv_uploader',
    'raise_on_warnings': True
}

# Find the latest CSV file in uploads/
uploads_dir = os.path.join(os.path.dirname(__file__), 'uploads')
csv_files = [f for f in os.listdir(uploads_dir) if f.lower().endswith('.csv')]
if not csv_files:
    print('No CSV files found in uploads directory.')
    exit(1)
latest_csv = max([os.path.join(uploads_dir, f) for f in csv_files], key=os.path.getctime)
log_file = os.path.join(os.path.dirname(__file__), 'import_log.txt')
def log(msg):
    from datetime import datetime
    timestamp = datetime.now().strftime('[%Y-%m-%d %H:%M:%S] ')
    print(timestamp + str(msg))
    with open(log_file, 'a', encoding='utf-8') as f:
        f.write(timestamp + str(msg) + '\n')

log(f'Processing file: {latest_csv}')

# Connect to MySQL
conn = mysql.connector.connect(**config)
cursor = conn.cursor()

# Log start of new import with separator
log("=" * 50)
log(f"IMPORT STARTED for {os.path.basename(latest_csv)}")
log("=" * 50)

with open(latest_csv, newline='', encoding='latin1') as csvfile:
    reader = csv.reader(csvfile)
    header = next(reader)
    row_count = 0
    error_count = 0
    from datetime import datetime
    batch = []
    batch_size = 1000
    for idx, row in enumerate(reader, start=1):
        try:
            # Data cleaning and transformation
            try:
                invoice_date = datetime.strptime(row[4], '%m/%d/%Y %H:%M').strftime('%Y-%m-%d %H:%M:00')
            except Exception as date_err:
                log(f"Date format error on row {idx}: {date_err}")
                invoice_date = None
            customer_id = row[6] if row[6].strip() != '' else None
            values = [row[0], row[1], row[2], row[3], invoice_date, row[5], customer_id, row[7]]
            batch.append(values)
            row_count += 1
            if len(batch) == batch_size:
                try:
                    cursor.executemany(
                        """
                        INSERT INTO invoices (InvoiceNo, StockCode, Description, Quantity, InvoiceDate, UnitPrice, CustomerID, Country)
                        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
                        """,
                        batch
                    )
                    conn.commit()
                    log(f"Inserted {row_count} rows so far...")
                except Exception as e:
                    log(f"Batch insert error at row {row_count}: {e}")
                    error_count += len(batch)
                batch = []
        except Exception as e:
            log(f"Error on row {idx}: {e}")
            error_count += 1
    # Insert any remaining rows
    if batch:
        try:
            cursor.executemany(
                """
                INSERT INTO invoices (InvoiceNo, StockCode, Description, Quantity, InvoiceDate, UnitPrice, CustomerID, Country)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s)
                """,
                batch
            )
            conn.commit()
            log(f"Inserted final {len(batch)} rows. Total inserted: {row_count}")
        except Exception as e:
            log(f"Final batch insert error: {e}")
            error_count += len(batch)

conn.commit()
cursor.close()
conn.close()
log(f"Upload complete. Rows inserted: {row_count}, Rows failed: {error_count}")
log("=" * 50)
log("IMPORT COMPLETED")
log("=" * 50)
