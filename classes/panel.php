<?php

class Panel
{

  public static function Loggout()
  {
    setcookie('remember', 'false', time() - 1, '/');
    session_destroy();
    header('Location: ' . INCLUDE_PATH);
  }

  public static function Logged()
  {
    return isset($_SESSION['login']) && $_SESSION['login'] === true;
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
                            VALUES (:users_id, :action_type, :description, :date)");
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