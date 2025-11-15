<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// OPTIONS 요청 처리 (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// 데이터베이스 연결 설정
$servername = "db";
$username = "root";
$password = "example";
$dbname = "todo_app";

// MySQL 연결
$conn = new mysqli($servername, $username, $password, $dbname);

// 연결 확인
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => '데이터베이스 연결 실패: ' . $conn->connect_error]);
    exit();
}

// UTF-8 설정
$conn->set_charset("utf8mb4");

// 요청 메서드 처리
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        getTodos($conn);
        break;
    case 'POST':
        addTodo($conn);
        break;
    case 'PUT':
        toggleTodo($conn);
        break;
    case 'DELETE':
        deleteTodo($conn);
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => '지원하지 않는 메서드입니다.']);
}

$conn->close();

// 전체 할일 조회
function getTodos($conn) {
    $result = $conn->query("SELECT * FROM todos ORDER BY created_at DESC");
    $todos = [];

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $todos[] = [
                'id' => (int)$row['id'],
                'task' => $row['task'],
                'completed' => (bool)$row['completed'],
                'created_at' => $row['created_at'],
                'updated_at' => $row['updated_at']
            ];
        }
    }

    echo json_encode($todos);
}

// 새 할일 추가
function addTodo($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['task']) || empty(trim($data['task']))) {
        http_response_code(400);
        echo json_encode(['error' => '할 일 내용을 입력해주세요.']);
        return;
    }

    $task = $conn->real_escape_string(trim($data['task']));
    $sql = "INSERT INTO todos (task, created_at) VALUES ('$task', NOW())";

    if ($conn->query($sql)) {
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'id' => $conn->insert_id,
            'message' => '할 일이 추가되었습니다.'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => '할 일 추가 실패: ' . $conn->error]);
    }
}

// 완료 상태 토글
function toggleTodo($conn) {
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID가 필요합니다.']);
        return;
    }

    $id = (int)$data['id'];
    $sql = "UPDATE todos SET completed = NOT completed WHERE id = $id";

    if ($conn->query($sql)) {
        echo json_encode([
            'success' => true,
            'message' => '할 일 상태가 변경되었습니다.'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => '상태 변경 실패: ' . $conn->error]);
    }
}

// 할일 삭제
function deleteTodo($conn) {
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID가 필요합니다.']);
        return;
    }

    $id = (int)$_GET['id'];
    $sql = "DELETE FROM todos WHERE id = $id";

    if ($conn->query($sql)) {
        echo json_encode([
            'success' => true,
            'message' => '할 일이 삭제되었습니다.'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => '삭제 실패: ' . $conn->error]);
    }
}
?>
