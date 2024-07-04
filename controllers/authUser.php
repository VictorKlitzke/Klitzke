<?php 

include_once './login.php';

class Authentication {
    private $secretKey;

    public function __construct($secretKey) {
        $this->secretKey = $secretKey;
    }

    public function generateRandomCode($length = 10) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
    }

    public function generateJWT($payload) {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    public static function validateJWT($jwt, $secretKey) {
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

    public static function getUserIdFromJWT($jwt, $secretKey) {
        $tokenParts = explode('.', $jwt);
        if (count($tokenParts) !== 3) {
            return null;
        }

        list($headerBase64, $payloadBase64, $signature) = $tokenParts;
        $payload = json_decode(base64_decode(strtr($payloadBase64, '-_', '+/')), true);

        if (!$payload || !isset($payload['data']['id'])) {
            return null;
        }

        return $payload['data']['id'];
    }
}

?>