<?php
require '../config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

$response = [
    'success' => true,
    'generated_at' => gmdate('c'),
    'status' => 'online',
    'modules' => []
];

$queries = [
    'Animals' => 'SELECT COUNT(*) AS total FROM animals',
    'Veterinarians' => 'SELECT COUNT(*) AS total FROM veterinarians',
    'Treatments' => 'SELECT COUNT(*) AS total FROM treatments',
    'Vaccinations' => 'SELECT COUNT(*) AS total FROM vaccinations',
    'Adoptions' => 'SELECT COUNT(*) AS total FROM adoption_requests'
];

foreach ($queries as $name => $sql) {
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $response['modules'][] = [
            'name' => $name,
            'count' => (int)$row['total']
        ];
    } else {
        $response['modules'][] = [
            'name' => $name,
            'count' => 0
        ];
    }
}

$pendingAdoptions = mysqli_query($conn, "SELECT COUNT(*) AS total FROM adoption_requests WHERE request_status = 'Pending'");
if ($pendingAdoptions && mysqli_num_rows($pendingAdoptions) > 0) {
    $pendingRow = mysqli_fetch_assoc($pendingAdoptions);
    $response['pending_adoptions'] = (int)$pendingRow['total'];
} else {
    $response['pending_adoptions'] = 0;
}

mysqli_close($conn);

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
