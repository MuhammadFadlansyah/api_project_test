<?php
header("Content-Type: application/json");

include 'koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['username']) && isset($data['password']) && isset($data['name']) && isset($data['email'])) {
        $username = $data['username'];
        $password = $data['password'];
        $name = $data['name'];
        $email = $data['email'];

        $query = "INSERT INTO login (username, password, name, email) VALUES (?, ?, ?, ?)";
        $stmt = $koneksi->prepare($query);
        
        if ($stmt) {
            $stmt->bind_param("ssss", $username, $password, $name, $email);
            $stmt->execute();

            $newUserId = $stmt->insert_id;

            $response = [
                "status" => "success",
                "message" => "User added successfully",
                "userid" => $newUserId
            ];
            echo json_encode($response);
            http_response_code(201);
            
            $stmt->close();
        } else {
            $response = [
                "status" => "error",
                "message" => "Query preparation failed"
            ];
            echo json_encode($response);
            http_response_code(500);
        }
    } else {
        $response = [
            "status" => "error",
            "message" => "Missing required parameters"
        ];
        echo json_encode($response);
        http_response_code(400);
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
