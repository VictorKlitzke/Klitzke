<?php

include_once '../config/config.php';
include_once '../services/db.php';
include_once '../classes/panel.php';
include_once '../classes/controllers.php';
include_once '../helpers/response.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

$sql = Db::Connection();
$secretKey = isset($GLOBALS['chave_secret']) ? $GLOBALS['chave_secret'] : $chave_secret;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $response_users = $data;

    if (isset($data['type']) && $data['type'] == 'login') {

        $login = new Login($sql, $secretKey);
        $login->login($data);

    } else {
        Response::json(false, 'Ação não reconhecida', date("Y-m-d H:i:s"));
    }
}

class Login
{
    private $sql;
    private $secretKey;

    public function __construct($sql, $secretKey)
    {
        $this->sql = $sql;
        $this->secretKey = $secretKey;
    }

    public function login($response_users)
    {
        $disable = 1;
        $today = date("Y-m-d H:i:s");

        $name = filter_var($response_users['name'], FILTER_SANITIZE_STRING);
        $password = filter_var($response_users['password'], FILTER_SANITIZE_STRING);

        if (!$password || !$name) {
            Response::json(false, 'Campos inválidos', $today);
            return;
        }

        try {
            $this->sql->beginTransaction();

            $exec = $this->sql->prepare("SELECT * FROM users WHERE name = :username AND disable = :disable");
            $exec->bindValue(':username', $name, PDO::PARAM_STR);
            $exec->bindValue(':disable', $disable, PDO::PARAM_INT);
            $exec->execute();

            if ($exec->rowCount() == 1) {
                $info = $exec->fetch();
                $storedPassword = $info['password'];

                if (password_verify($password, $storedPassword)) {
                    $this->processLogin($info, $name);
                } else {
                    Response::json(false, 'Credenciais inválidas', $today);
                }
            } else {
                Response::json(false, 'Usuário não encontrado', $today);
            }

            $this->sql->commit();
        } catch (Exception $e) {
            $this->sql->rollBack();
            http_response_code(500);
            echo json_encode(['error' => 'Erro no banco de dados', 'code' => $e->getCode()]);
        }
    }

    private function processLogin($info, $login)
    {
        $today = date("Y-m-d H:i:s");

        if ($info['access'] != 100) {
            $random_code = $this->generateRandomCode(10) . date('Ymd');
            $dateStart = date('Y-m-d H:i:s');
            $dateFinal = date('Y-m-d H:i:s', strtotime($dateStart . ' + 15 days'));

            $insertCode = $this->sql->prepare("INSERT INTO validade_system (code, date_start, date_final, id_users)
                                         VALUES(:code, :date_start, :date_final, :id_users)");
            $insertCode->bindValue(':code', $random_code, PDO::PARAM_STR);
            $insertCode->bindValue(':date_start', $dateStart, PDO::PARAM_STR);
            $insertCode->bindValue(':date_final', $dateFinal, PDO::PARAM_STR);
            $insertCode->bindValue(':id_users', $info['id'], PDO::PARAM_INT);
            $insertCode->execute();
        }

        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => [
                'id' => $info['id'],
                'name' => $login,
            ]
        ];

        $jwt = $this->generateJWT($payload);

        setcookie('jwt', $jwt, [
            'expires' => $expirationTime,
            'path' => '/',
            'domain' => '',
            'secure' => false,
            'httponly' => true,
            'samesite' => 'Lax'
        ]);

        $message_log = "Usuário $login logado com sucesso";
        Panel::LogAction($info['id'], 'Login Usuário', $message_log, $today);

        Response::send(true, 'Seja bem vindo!', $today);

        if (!headers_sent()) {
            header('Location: ' . INCLUDE_PATH_HOME);
            exit();
        } else {
            echo "Erro: Não foi possível redirecionar, os cabeçalhos já foram enviados.";
        }
    }

    private function generateRandomCode($length = 10)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    private function generateJWT($payload)
    {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function validateJWT($jwt)
    {
        // Chave secreta para a validação do JWT
        $secretKey = isset($GLOBALS['chave_secret']) ? $GLOBALS['chave_secret'] : "";

        // Verificação básica do formato do token
        $tokenParts = explode('.', $jwt);
        if (count($tokenParts) !== 3) {
            return null; // Token inválido
        }

        // Separação do token em partes: cabeçalho, payload e assinatura
        list($headerBase64, $payloadBase64, $signature) = $tokenParts;

        // Decodificação das partes do token
        $header = json_decode(base64_decode(strtr($headerBase64, '-_', '+/')), true);
        $payload = json_decode(base64_decode(strtr($payloadBase64, '-_', '+/')), true);

        // Verificação se a decodificação foi bem-sucedida
        if (!$header || !$payload) {
            return null; // Token inválido
        }

        $signatureToVerify = hash_hmac('sha256', $headerBase64 . '.' . $payloadBase64, $secretKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signatureToVerify));

        if ($base64UrlSignature !== $signature) {
            return null;
        }

        if (isset($payload['exp']) && $payload['exp'] >= time()) {
            return (object) $payload;
        }

        return null;
    }
}
?>