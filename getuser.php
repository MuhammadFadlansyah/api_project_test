<?php
header("Content-Type: application/json");

include 'koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT userid, username, name, email FROM login";
    $result = $koneksi->query($query);

    if ($result) {
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = [
                "userid" => $row['userid'],
                "username" => $row['username'],
                "name" => $row['name'],
                "email" => $row['email']
            ];
        }

        echo json_encode($users);
        http_response_code(200);
    } else {
        $response = [
            "status" => "error",
            "message" => "Failed to fetch users"
        ];
        echo json_encode($response);
        http_response_code(500);
    }
} else {
    $response = [
        "status" => "error",
        "message" => "Invalid request method"
    ];
    echo json_encode($response);
    http_response_code(405);
}

$koneksi->close(); 
?>
