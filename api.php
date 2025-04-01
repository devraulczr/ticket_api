<?php 

session_start();
$input = file_get_contents("php://input");
$data = json_decode($input, true);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");  // Permite todas as origens
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
date_default_timezone_set('America/Sao_Paulo');
$sql = [
    "host"     => "127.0.0.1:3307",
    "user"     => "root",
    "password" => "",
    "database" => "netron_company"
];

$conn = new mysqli($sql["host"], $sql["user"], $sql["password"], $sql["database"]);
$endpoint = $data["endpoint"] ?? '';

if (!isset($endpoint) or empty($endpoint)) {
    echo json_encode(["error" => "No endpoint provided"]);
    exit();
}

if ($endpoint === "login") {
    $payload = [
        "email"    => $data["email"]    ?? '',
        "password" => $data["password"] ?? ''
    ];
    if (!isset($payload["email"]) || empty($payload["email"])) {
        http_response_code(400);
        echo json_encode(["succes" => false, "message" => "Inválid email"]);
        exit;
    }
    if (!isset($payload["password"]) || empty($payload["password"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Inválid Password"]);
        exit;
    }
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $payload["email"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "User not found"]);
        exit;
    }
    $userR = $result->fetch_assoc();
    if (!password_verify($payload["password"], $userR["password"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Incorrect Password"]);
        exit;
    }
    $_SESSION["user_id"] = $userR["id"];
    $_SESSION["loged"] = true;
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Sucefull login"]);
    exit;
}
if ($endpoint === "get_user_id") {
    if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "User not loged"]);
        exit;
    }
    http_response_code(200);
    echo json_encode(["success" => true, "id" => $_SESSION["user_id"]]);
    exit;
}
if ($endpoint === "get_user_info") {
    if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "User not loged"]);
        exit;
    }
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $_SESSION["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        http_response_code(404);
        echo json_encode(["success" => true, "message" => "None user fount"]);
        exit;
    }
    $userInfo = $result->fetch_assoc();
    $user = [
        "id"    =>  $userInfo["id"],
        "name"  => $userInfo["name"]
    ];
    http_response_code(200);
    echo json_encode(["success" => true, "user" => $user]);
    exit;
}
if ($endpoint === "verify_login") {
    if (!isset($_SESSION["user_id"]) || empty($_SESSION["user_id"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "User not loged"]);
        exit;
    }
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "User loged"]);
    exit;
}
if ($endpoint === "logout") {
    echo json_encode(["success" => true]);

    session_unset();
    session_destroy();
    if (session_status() === PHP_SESSION_NONE) {
        error_log('Session successfully destroyed');
    }
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    exit;
}
if ($endpoint === "create_ticket") {
    $payload = [
        "user_id"     => $_SESSION["user_id"] ?? 0,
        "title"       => $data["title"]       ?? '',
        "description" => $data["description"] ?? '',
        "status"      => "Open"
    ];
    if (!isset($payload["user_id"]) || empty($payload["user_id"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Inválid user_id"]);
        exit;
    }
    if (!isset($payload["title"]) || empty($payload["title"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Empty title"]);
        exit;
    }
    $sql = "SELECT * FROM tickets WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payload["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Ticket aleard created"]);
        exit;
    }
    $sql = "INSERT INTO tickets (status, user_id, title, description) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si",  $payload["status"], $payload["user_id"], $payload["title"], $payload["description"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($stmt->affected_rows == 0) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Nenhum registro foi modificado"]);
        exit;
    }
    http_response_code(200);
    echo json_encode(["success" => true, "message" => "Ticket sucefull created"]);
    exit;
}
if ($endpoint === "list_tickets") {
    $payload = [
        "user_id" => $_SESSION["user_id"] ?? 0,
        "filter"  => $data["filter"]      ?? ''
    ];
    if (!isset($payload["user_id"]) ||  empty($payload["user_id"])) {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Uset not loged"]);
        exit;
    }
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payload["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result ->num_rows == 0) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Uset not loged"]);
        exit;
    }
    $user = $result->fetch_assoc();
    if ($user["admin"] == 1) {
        if (!isset($payload["filter"]) || empty($payload["filter"])) {
            $sql = "SELECT * FROM tickets";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows == 0) {
                echo json_encode(["success" =>  false, "message" => "None ticket found"]);
                exit;
            }
            $tickets = [];
            while ($row = $result->fetch_assoc()) {
                $tickets[] = $row;
            }
            echo json_encode(["success" => true, "tickets" => $tickets]);
            exit;
        }
        $sql = "SELECT * FROM tickets WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $payload["filter"]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "None ticket found"]);
            exit;
        }
        $tickets = [];
        while (($row = $result->fetch_assoc())) {
            $tickets[] = $row;
        }
        echo json_encode(["success" => true, "tickets" => $tickets]);
        exit;
    }
    $sql = "SELECT * FROM tickets WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payload["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "None ticket found"]);
        exit;
    }
    $tickets = [];
    while ($row = $result->fetch_assoc()) {
        $tickets[] = $row;
    }
    echo json_encode(["success" => true, "tickets" => $tickets]);
    exit;
}
if ($endpoint === "send_message") {
    $payload = [
        "ticket_id" => $data["ticket_id"] ?? 0,
        "message"   => $data["message"]   ?? '',
        "user_id"   => $_SESSION["user_id"]
    ];
    if (!isset($payload["user_id"]) || empty($payload["user_id"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "User not logeg"]);
        exit;
    }
    if (!isset($payload["ticket_id"]) || empty($payload["ticket_id"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Empty ticket_id"]);
        exit;
    }
    if (!isset(($payload["message"])) || empty($payload["message"])) {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Message cannot be null"]);
        exit;
    }
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payload["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    $message_content = [
        "message"      => $payload["message"] ?? '',
        "ticket_id"    => $payload["ticket_id"] ?? 0,
        "user_id"      => $payload["user_id"] ?? 0,
        "shiping_date" => date("Y-m-d H:i:s")
    ];
    if ($result->num_rows == 0) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "User not found"]);
        exit;
    }
    $user = $result->fetch_assoc();
    if ($user["admin"] == 1) {
        $sql = "INSERT INTO messages (message, ticket_id, user_id, shipping_date)
        VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("siis", $message_content["message"], $message_content["ticket_id"], $message_content["user_id"], $message_content["shiping_date"]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($stmt->affected_rows == 0) {
            http_response_code(403);
            echo json_encode(["success" => false, "message" => "Error to send message"]);
            exit;
        }
        echo json_encode(["success" => true, "message" => "Mensage sendesd"]);
        exit;
    }
    $sql = "SELECT * FROM tickets WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $message_content["ticket_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Ticket not found"]);
        exit;
    }
    $ticket = $result->fetch_assoc();
    if ($ticket["user_id"] != $payload["user_id"]) {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Incorrect user_id"]);
        exit;
    }
    $sql = "INSERT INTO messages (message, ticket_id, user_id, shipping_date)
    VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siis", $message_content["message"], $message_content["ticket_id"], $message_content["user_id"], $message_content["shiping_date"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($stmt->affected_rows == 0) {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Error to send message"]);
        exit;
    }
    echo json_encode(["success" => true, "message" => "Mensage sended"]);
    exit;
}
if ($endpoint === "messages") {
    $payload = [
        "user_id"   => $_SESSION["user_id"] ?? 0,
        "ticket_id" => $data["ticket_id"]   ?? 0
    ];
    if (!isset($payload["user_id"]) || empty($payload["user_id"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "User not logged"]);
        exit;
    }
    if (!isset($payload["ticket_id"]) || empty($payload["ticket_id"])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Empty ticket_id"]);
        exit;
    }
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payload["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "User not found"]);
        exit;
    }
    $user = $result->fetch_assoc();
    $messages = [];
    if ($user["admin"] == 1) {
        $sql = "SELECT * FROM messages WHERE ticket_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $payload["ticket_id"]);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            http_response_code(404);
            echo json_encode(["success" => false, "message" => "None message found"]);
            exit;
        }
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        echo json_encode(["success" => true, "messages" => $messages]);
        exit;
    }
    $sql = "SELECT * FROM tickets WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payload["ticket_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Ticket not found"]);
        exit;
    }
    $ticket = $result->fetch_assoc();
    if ($user["id"] != $ticket["user_id"]) {
        http_response_code(403);
        echo json_encode(["success" => false, "message" => "Unauthorized Access"]); // Correção: "Unauthorized"
        exit;
    }
    $sql = "SELECT * FROM messages WHERE ticket_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payload["ticket_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "None message found"]);
        exit;
    }
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode(["success" => true, "messages" => $messages]);
    exit;
}
if ($endpoint === "get_id_info") {
    $payload = [
        "user_id" => $data["user_id"]
    ];
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $payload["user_id"]);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "User not found"]);
        exit;
    }
    $user_info = $result->fetch_assoc();
    echo json_encode(["success" => true, "username" => $user_info["name"]]);
    exit;
}
echo json_encode(["success" => false, "message" => "Inválid endpoint"]);
$conn->close();
?>
