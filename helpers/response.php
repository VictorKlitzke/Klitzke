<?php 

class Response{

    public static function json($success, $message, $data = null) {
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
    public static function send($type, $message, $data = null) {
        self::json($type, $message, $data);
    }


}

?>