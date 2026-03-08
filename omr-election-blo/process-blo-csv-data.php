<?php
/**
 * Parse BLO CSV and generate SQL INSERT statements
 * Usage: php parse-blo-csv.php
 */

// Read CSV file
$csvFile = __DIR__ . '/../BLO Details - AC - Shozhinganallur 06-11-2025 12_00_40.xls.csv';
$outputFile = __DIR__ . '/INSERT-BLO-DATA.sql';

if (!file_exists($csvFile)) {
    die("CSV file not found: $csvFile\n");
}

$file = fopen($csvFile, 'r');
if (!$file) {
    die("Cannot open CSV file\n");
}

// Skip header
$header = fgetcsv($file);

// Start SQL output
$sql = "-- MyOMR.in — BLO Data Import for AC Shozhinganallur\n";
$sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n";
$sql .= "-- Source: BLO Details - AC - Shozhinganallur 06-11-2025 12_00_40.xls.csv\n\n";
$sql .= "SET NAMES utf8mb4;\n\n";
$sql .= "INSERT INTO `omr_election_blo` (\n";
$sql .= "  `ac_no`, `ac_name`, `district`, `taluk`, `zone`, `ward_no`,\n";
$sql .= "  `part_no`, `section_no`, `polling_station_code`, `polling_station_name`, `location`, `address`,\n";
$sql .= "  `blo_name`, `blo_designation`, `blo_mobile`, `blo_email`,\n";
$sql .= "  `remarks`, `source_file`\n";
$sql .= ") VALUES\n";

$rows = [];
$rowCount = 0;

// Function to escape SQL strings
function escapeSql($str) {
    return str_replace(["'", "\\"], ["''", "\\\\"], $str);
}

// Function to parse district number and name
function parseDistrict($districtStr) {
    if (preg_match('/^(\d+)-(.+)$/', $districtStr, $matches)) {
        return ['no' => (int)$matches[1], 'name' => trim($matches[2])];
    }
    return ['no' => null, 'name' => trim($districtStr)];
}

// Function to parse AC number and name
function parseAC($acStr) {
    if (preg_match('/^(\d+)-(.+)$/', $acStr, $matches)) {
        return ['no' => (int)$matches[1], 'name' => trim($matches[2])];
    }
    return ['no' => null, 'name' => trim($acStr)];
}

// Function to parse Part Name
function parsePartName($partName) {
    // Format: "1-Chennai Primary School,6th Main Road Nanganallur Ullagaram , Chennai 600091 ,East Face 1st STD A Section"
    // Or: "1-Chennai Primary School,address,section details"
    
    $result = [
        'part_no' => null,
        'polling_station_name' => '',
        'address' => '',
        'section_no' => ''
    ];
    
    // Extract part number from the beginning
    if (preg_match('/^(\d+)-/', $partName, $matches)) {
        $result['part_no'] = (int)$matches[1];
        $partName = substr($partName, strlen($matches[0])); // Remove "1-" prefix
    }
    
    // Split by commas (but be careful with quoted sections)
    $parts = [];
    $current = '';
    $inQuotes = false;
    
    for ($i = 0; $i < strlen($partName); $i++) {
        $char = $partName[$i];
        if ($char == '"' && ($i == 0 || $partName[$i-1] != '\\')) {
            $inQuotes = !$inQuotes;
        } elseif ($char == ',' && !$inQuotes) {
            $parts[] = trim($current);
            $current = '';
        } else {
            $current .= $char;
        }
    }
    if ($current) {
        $parts[] = trim($current);
    }
    
    // Remove leading/trailing quotes from parts
    $parts = array_map(function($p) {
        return trim($p, '"\'');
    }, $parts);
    
    // First part is usually the polling station name
    if (count($parts) > 0) {
        $result['polling_station_name'] = $parts[0];
    }
    
    // Last part is usually the section details
    if (count($parts) > 1) {
        $result['section_no'] = $parts[count($parts) - 1];
        // Middle parts are the address
        if (count($parts) > 2) {
            $result['address'] = implode(', ', array_slice($parts, 1, -1));
        } else {
            $result['address'] = $parts[1];
        }
    }
    
    return $result;
}

// Process each row
while (($row = fgetcsv($file)) !== false) {
    if (count($row) < 6) {
        continue; // Skip incomplete rows
    }
    
    $districtStr = trim($row[0]);
    $acStr = trim($row[1]);
    $partName = trim($row[2]);
    $bloName = trim($row[3]);
    $mobile = trim($row[4]);
    $officeAddress = trim($row[5]); // This is the location
    
    // Skip empty rows
    if (empty($districtStr) || empty($acStr)) {
        continue;
    }
    
    // Parse district and AC
    $district = parseDistrict($districtStr);
    $ac = parseAC($acStr);
    
    // Parse part name
    $partInfo = parsePartName($partName);
    
    // Build SQL values
    $values = [
        $ac['no'] ?? 'NULL',
        "'" . escapeSql($ac['name'] ?? '') . "'",
        $district['name'] ? "'" . escapeSql($district['name']) . "'" : 'NULL',
        'NULL', // taluk
        'NULL', // zone
        'NULL', // ward_no
        $partInfo['part_no'] ?? 'NULL',
        $partInfo['section_no'] ? "'" . escapeSql($partInfo['section_no']) . "'" : 'NULL',
        'NULL', // polling_station_code
        $partInfo['polling_station_name'] ? "'" . escapeSql($partInfo['polling_station_name']) . "'" : 'NULL',
        $officeAddress ? "'" . escapeSql($officeAddress) . "'" : 'NULL',
        $partInfo['address'] ? "'" . escapeSql($partInfo['address']) . "'" : 'NULL',
        "'" . escapeSql($bloName) . "'",
        "'BLO'", // designation
        $mobile ? "'" . escapeSql($mobile) . "'" : 'NULL',
        'NULL', // email
        'NULL', // remarks
        "'BLO Details - AC - Shozhinganallur 06-11-2025 12_00_40.xls'"
    ];
    
    $rows[] = "(" . implode(", ", $values) . ")";
    $rowCount++;
}

fclose($file);

// Write SQL file
$sql .= implode(",\n", $rows) . ";\n\n";
$sql .= "-- Total rows inserted: $rowCount\n";

file_put_contents($outputFile, $sql);

echo "Generated SQL file: $outputFile\n";
echo "Total rows processed: $rowCount\n";
?>

