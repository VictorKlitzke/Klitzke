<?php

include_once '../controllers/login.php';

class Panel
{

  public static function ValidadeImg($img)
  {
    if (
      $img['type'] == 'image/jpeg' ||
      $img['type'] == 'imagem/jpg' ||
      $img['type'] == 'imagem/png'
    ) {

      $tam = intval($img['size'] / 1024);
      if ($tam < 300)
        return true;
      else
        return false;
    } else {
      return false;
    }
  }

  public static function UploadsImg($file)
  {
    $fileFormat = explode('.', $file['name']);
    $imageName = uniqid() . '.' . $fileFormat[count($fileFormat) - 1];
    if (move_uploaded_file($file['tmp_name'], BASE_DIR_PAINEL . '/upload/' . $imageName))
      return $imageName;
    else
      return false;
  }

  public static function Logged()
  {
    $token = isset($_COOKIE['jwt']) ? $_COOKIE['jwt'] : null;
    if (!$token) {
      return false;
    }

    $payload = Login::validateJWT($token);
    if ($payload === null) {
      return false;
    }

    $sql = Db::Connection();

    $userId = $payload->data['id'];
    $stmt = $sql->prepare("SELECT COUNT(*) as count FROM users WHERE id = :id");
    $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] == 0) {
      return false;
    }
  }

  public static function Alert($type, $message)
  {
    if ($type == 'sucess') {
      echo '<div id="alert" class="alert alert-sucess"> ' . $message . '</div>';
    } else if ($type == 'error') {
      echo '<div id="alert" class="alert alert-error">' . $message . '</div>';
    } else if ($type == 'attention') {
      echo '<div id="alert" class="alert alert-warning">' . $message . '</div>';
    }
  }

  public static function LoadPage()
  {
    if (isset($_GET["url"])) {
      $url = explode('/', $_GET["url"]);
      if (file_exists('pages/' . $url[0] . '.php')) {
        include ('pages/' . $url[0] . '.php');
      } else {
        header('Location: ' . INCLUDE_PATH_PANEL);
      }
    } else {
      include ('pages/main.php');
    }
  }

  public static function LogAction($user_id, $action, $description, $today)
  {

    $sql = Db::Connection();

    try {
      $exec = $sql->prepare("INSERT INTO logs (users_id, action_type, description, date) 
                            VALUES (:users_id, :action_type, :description, :date");
      $exec->bindValue(':users_id', $user_id, PDO::PARAM_INT);
      $exec->bindValue(':action_type', $action, PDO::PARAM_STR);
      $exec->bindValue(':description', $description, PDO::PARAM_STR);
      $exec->bindValue(':date', $today, PDO::PARAM_STR);
      $exec->execute();
    } catch (PDOException $e) {
      error_log('Erro ao inserir log: ' . $e->getMessage());
    }

  }
}