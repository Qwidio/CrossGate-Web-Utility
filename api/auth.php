<?php
require_once "../processes/database.php";
session_start();

$vercode = $_GET['vercode'];
if (isset($vercode)) {
    header("Content-Type:application/json");
    $method = $_SERVER['REQUEST_METHOD'];
    $input = json_decode(file_get_contents('php://input'), true);
    switch ($method) {
        case 'PUT':
            $sessiontokens = $input['tokens'];
            $addrss = $input['addrss'];
            $osids = $input['osids'];
            if (!isset($sessiontokens) || !isset($addrss) || !isset($osids)) {
                echo json_encode(["message" => "Missing data"]);
                exit;
            }
            $session_check = $connects->prepare("SELECT profileTags, expirationDate FROM sessionlogs WHERE sessiontokens = ?;");
            $session_check->bind_param("s", $sessiontokens);
            $session_check->execute();
            $result_session_check = $session_check->get_result();
            $data = $result_session_check->fetch_assoc();
            if (isset($data)) {
                $Tags = $data['profileTags'];
                $exps = $data['expirationDate'];
                $y = date("Y");
                $m = date("m");
                $d = date("d");
                if ($m < 10) {
                    $m = "0" . $m;
                }
                $curdt = $y . "-" . $m . "-" . $d;
                if ($exps > $curdt) {
                    echo json_encode(["message" => "Session Have been expired"]);
                    exit;
                }
            } else {
                echo json_encode(["message" => "Failed to find sessions"]);
                exit;
            }
            $update_auth = $connects->prepare("UPDATE sessionlogs SET addrss = ?, osids = ?, lastlogs = NOW() WHERE sessiontokens = ? ;");
            $update_auth->bind_param("sss", $addrss, $osids, $sessiontokens);
            $update_auth->execute();
            if ($update_auth) {
                $check_profile = $connects->prepare("SELECT * FROM profiles WHERE profileTags = ? ;");
                $check_profile->bind_param("s", $Tags);
                $check_profile->execute();
                $result_check_profile = $check_profile->get_result();
                $value = $result_check_profile->fetch_assoc();
                if ($value) {
                    echo json_encode([
                        "message" => "Logged in successfully",
                        "profileTags" => $Tags,
                        "profileAttachs" => $value['profileAttachs'],
                        "profileNames" => $value['profileNames'],
                        "profileBios" => $value['profileBios'],
                        "profileJDates" => $value['profileJDates'],
                        "specialBadge" => $value['specialBadge'],
                        "oState" => $value['oState']
                    ]);
                }
            }
            break;
        default:
            echo json_encode(["message" => "Invalid request"]);
            break;
    }
    $connects->close();
} else {
    echo json_encode(["message" => "Invalid Code"]);
}
?>