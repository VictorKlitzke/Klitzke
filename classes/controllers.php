<?php

include_once 'services/db.php';

$sql = Db::Connection();

class Controllers
{

    public static function SelectAllWhere($table_name, $where = null, $params = [])
    {
        $sql = Db::Connection();

        $query = "SELECT * FROM $table_name";

        if ($where) {
            $query .= " WHERE $where";
        }

        $exec = $sql->prepare($query);

        foreach ($params as $key => $value) {
            $exec->bindValue($key, $value);
        }

        $exec->execute();

        return $exec->fetchAll();
    }
    public static function SelectRequest($name_table, $table_filter = null, $user_filter = null, $date_start = null, $date_end = null)
    {
        $sql = Db::Connection();

        $query = "SELECT 
                    R.*,
                    CASE 
                        WHEN R.status = 1 THEN 'EM ATENDIMENTO'
                        WHEN R.status = 2 THEN 'INATIVADA'
                        WHEN R.status = 3 THEN 'CONCLUÍDO'
                        WHEN R.status = 4 THEN 'AGRUPADOS'
                    END AS STATUS_REQUEST
                FROM 
                    $name_table R";

        $conditions = [];

        if ($user_filter !== null) {
            $conditions[] = "R.id_users_request = :user_filter";
        }

        if ($table_filter !== null) {
            $conditions[] = "R.id_table = :table_filter";
        }

        if ($date_start !== null && $date_end !== null) {
            $conditions[] = "DATE(R.date_request) BETWEEN :date_start AND :date_end";
        } elseif ($date_start !== null) {
            $conditions[] = "DATE(R.date_request) >= :date_start";
        } elseif ($date_end !== null) {
            $conditions[] = "DATE(R.date_request) <= :date_end";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY R.id ASC";

        $exec = $sql->prepare($query);

        if ($user_filter !== null) {
            $exec->bindParam(':user_filter', $user_filter, PDO::PARAM_INT);
        }

        if ($table_filter !== null) {
            $exec->bindParam(':table_filter', $table_filter, PDO::PARAM_INT);
        }

        if ($date_start !== null) {
            $exec->bindParam(':date_start', $date_start);
        }

        if ($date_end !== null) {
            $exec->bindParam(':date_end', $date_end);
        }

        $exec->execute();

        return $exec->fetchAll();
    }

    public static function SelectBoxPdv($name_table, $user_filter = null, $date_end = null, $date_start = null)
    {
        $sql = Db::Connection();

        $query = "SELECT
                boxpdv.*,
                users.name users,
                (SELECT value FROM sangria_boxpdv WHERE sangria_boxpdv.id_boxpdv = boxpdv.id LIMIT 1) Withdrawal
            FROM
                $name_table
                INNER JOIN users ON users.id = boxpdv.id_users
        ";

        $conditions = [];

        if ($user_filter !== null) {
            $conditions[] = "users.id = :user_filter";
        }

        if ($date_start !== null && $date_end !== null) {
            $conditions[] = "DATE(boxpdv.open_date) BETWEEN :date_start AND :date_end";
        } elseif ($date_start !== null) {
            $conditions[] = "DATE(boxpdv.open_date) >= :date_start";
        } elseif ($date_end !== null) {
            $conditions[] = "DATE(boxpdv.open_date) <= :date_end";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY id ASC";

        $exec = $sql->prepare($query);

        if ($user_filter !== null) {
            $exec->bindParam(':user_filter', $user_filter, PDO::PARAM_INT);
        }

        if ($date_start !== null && $date_end !== null) {
            $exec->bindParam(':date_start', $date_start);
            $exec->bindParam(':date_end', $date_end);
        }

        $exec->execute();

        return $exec->fetchAll();
    }
    public static function SelectAll($name_table, $start = null, $end = null)
    {
        $sql = Db::Connection();

        if ($start == null && $end == null) {
            $exec = $sql->prepare("SELECT * FROM $name_table ORDER BY id ASC");
        } else {
            $exec = $sql->prepare("SELECT * FROM $name_table ORDER BY id ASC LIMIT $start,$end");
        }
        $exec->execute();

        return $exec->fetchAll();
    }
    public static function SelectAllFormPayment($name_table, $start = null, $end = null)
    {
        $sql = Db::Connection();

        $query = "SELECT
                    id AS id,
                    name AS forms_payment
                FROM
                    $name_table
                ORDER BY id ASC";

        if ($start !== null && $end !== null) {
            $query .= " LIMIT $start, $end";
        }

        $exec = $sql->prepare($query);
        $exec->execute();

        return $exec->fetchAll();
    }
    public static function SelectSales($name_table, $userFilter = null, $form_payment = null, $date_start = null, $date_end = null)
    {
        $sql = Db::Connection();

        $query = "SELECT 
                    sales.*,
                    users.name users,
                    clients.name clients,
                    form_payment.name form_payment,
                    case 
                        when sales.status = 1 then 'VENDIDO'
                        when sales.status = 2 then 'CANCELADA'
                        else 'ERRO'
                    end status_sales
                FROM
                    sales 
                    INNER JOIN form_payment ON form_payment.id = sales.id_payment_method
                    LEFT JOIN clients ON clients.id = sales.id_client
                    INNER JOIN boxpdv ON boxpdv.id = sales.id_boxpdv
                    INNER JOIN users ON users.id = sales.id_users";

        $conditions = [];

        if ($userFilter !== null) {
            $conditions[] = "users.id = :userFilter";
        }

        if ($form_payment !== null) {
            $conditions[] = "sales.id_payment_method = :form_filter";
        }

        if ($date_start !== null && $date_end !== null) {
            $conditions[] = "DATE(sales.date_sales) BETWEEN :date_start AND :date_end";
        } elseif ($date_start !== null) {
            $conditions[] = "DATE(sales.date_sales) >= :date_start";
        } elseif ($date_end !== null) {
            $conditions[] = "DATE(sales.date_sales) <= :date_end";
        }
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY id ASC";

        $exec = $sql->prepare($query);

        if ($userFilter !== null) {
            $exec->bindParam(':userFilter', $userFilter, PDO::PARAM_INT);
        }

        if ($form_payment !== null) {
            $exec->bindParam(':form_filter', $form_payment, PDO::PARAM_INT);
        }

        if ($date_start !== null && $date_end !== null) {
            $exec->bindParam(':date_start', $date_start);
            $exec->bindParam(':date_end', $date_end);
        }

        $exec->execute();

        return $exec->fetchAll();
    }
    public static function Select($name_table, $query = '', $ts = '')
    {
        $sql = Db::Connection();

        if ($query != false) {
            $exec = $sql->prepare("SELECT * FROM `$name_table` WHERE $query");
            $exec->execute($ts);
        } else {
            $exec = $sql->prepare("SELECT * FROM $name_table");
            $exec->execute();
        }
        return $exec->fetch();
    }
    public static function SizeClothes($name_table)
    {
        $sql = Db::Connection();
        $exec = $sql->prepare("SELECT size FROM $name_table");
        $exec->execute();

        return $exec->fetchAll();

    }
    public static function Update($arr, $single = false)
    {
        $true = true;
        $first = false;
        $name_table = $arr['name_table'];

        $query = "UPDATE `$name_table` SET ";
        foreach ($arr as $key => $value) {
            $name = $key;
            if ($name == 'action' || $name == 'name_table' || $name == 'id')
                continue;

            if ($first == false) {
                $first = true;
                $query .= "$name=?";
            } else {
                $query .= ",$name=?";
            }

            $param[] = $value;
        }

        if ($true == true) {
            if ($single == false) {
                $param[] = $arr['id'];
                $sql = Db::Connection()->prepare($query .= ' WHERE id=?');
                $sql->execute($param);
            } else {
                $sql = Db::Connection()->prepare($query);
                $sql->execute($param);
            }
        }
        return $true;
    }

}
