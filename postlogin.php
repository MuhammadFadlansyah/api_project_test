<?php
header("Content-Type: application/json");

// Pastikan file koneksi.php telah di-include dengan benar
include 'koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Periksa apakah payload JSON valid
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (isset($data['username']) && isset($data['password'])) {
        $username = $data['username'];
        $password = $data['password'];

        // Lakukan sanitasi terhadap data yang diterima sebelum digunakan dalam query
        $username = mysqli_real_escape_string($koneksi, $username);
        $password = mysqli_real_escape_string($koneksi, $password);

        $query = "SELECT userid, username, name, email FROM login WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($koneksi, $query);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                $response = [
                    "status" => "success",
                    "message" => "Login successful",
                    "user" => [
                        "userid" => $user['userid'],
                        "username" => $user['username'],
                        "name" => $user['name'],
                        "email" => $user['email']
                    ]
                ];
                echo json_encode($response);
                http_response_code(200);
            } else {
                $response = [
                    "status" => "error",
                    "message" => "Invalid username or password"
                ];
                echo json_encode($response);
                http_response_code(401);
            }
        } else {
            $response = [
                "status" => "error",
                "message" => "Query execution failed"
            ];
            echo json_encode($response);
            http_response_code(500);
        }
    } else {
        $response = [
            "status" => "error",
            "message" => "Missing username or password in request"
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
