<?php

include_once '../config/config.php';
include_once '../services/db.php';
include_once '../helpers/response.php';
include_once '../classes/panel.php';
include_once './authUser.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

class Logout
{
    public static function logout($chave_secret)
    {
        $sql = Db::Connection();
        $secretKey = isset($GLOBALS['chave_secret']) ? $GLOBALS['chave_secret'] : $chave_secret;
        $today = date("Y-m-d H:i:s");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_COOKIE['jwt'])) {
                $jwt = $_COOKIE['jwt'];
                $user_id = Authentication::getUserIdFromJWT($jwt, $secretKey);

                // Remover o cookie JWT
                setcookie('jwt', '', time() - 3600, '/', '', false, true);

                session_destroy();

                if ($user_id) {
                    $messageLog = "Usuário ID: $user_id realizou logout.";
                    Panel::LogAction($user_id, 'Logout', $messageLog, $today);
                }

                Response::send(true, 'Logout realizado com sucesso', $today);
            } else {
                Response::send(false, 'Nenhum cookie de autenticação encontrado', $today);
            }
        } else {
            Response::send(false, 'Método não permitido', $today);
        }
    }
}

Logout::logout();

?>