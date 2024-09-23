<?php
// Database connection settings
$host = 'localhost';
$db = 'u969556126_diac';
$user = 'u969556126_diac';
$pass = 'rQdyX3D5PZ!5';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// Your query
$query = "SELECT mat.*, pct.category_name FROM master_arbitrators_tbl mat 
          LEFT JOIN panel_category_tbl as pct ON pct.code = mat.category 
          WHERE mat.whether_on_panel = 1";

$stmt = $pdo->query($query);

$results = $stmt->fetchAll();

// Generate CSV content
$csvFileName = 'exported_data.csv';
$csvFile = fopen($csvFileName, 'w');

// Add column names
if (!empty($results)) {
    fputcsv($csvFile, array_keys($results[0]));
}

// Add data rows
foreach ($results as $row) {
    fputcsv($csvFile, $row);
}

fclose($csvFile);

// Output CSV file for download
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="' . $csvFileName . '";');
readfile($csvFileName);

// Delete the file after download
unlink($csvFileName);
