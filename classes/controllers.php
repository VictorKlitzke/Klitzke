<?php

include_once 'services/db.php';

class Controllers
{

    public static function InfoProductsSales($name_table, $query = '', $ts = '')
    {
        if ($query != false) {
            $exec = Db::Connection()->prepare("SELECT 
                                                    sales.*,
                                                    users.name users,
                                                    clients.name clients,
                                                    form_payment.name form_payment,
                                                    products.name products,
                                                    sales_items.amount quantity,
                                                    sales_items.price_sales value
                                                FROM
                                                    $name_table 
                                                    INNER JOIN sales_items ON sales_items.id_sales = sales.id
                                                    INNER JOIN products ON products.id = sales_items.id_product
                                                    INNER JOIN form_payment ON form_payment.id = sales.id_payment_method
                                                    INNER JOIN clients ON clients.id = sales.id_client
                                                    LEFT JOIN boxpdv ON boxpdv.id = sales.id_boxpdv
                                                    LEFT JOIN users ON users.id = sales.id_users");
            $exec->execute($ts);
        } else {
            $exec = Db::Connection()->prepare("SELECT 
                                                    sales.*,
                                                    users.name users,
                                                    clients.name clients,
                                                    form_payment.name form_payment,
                                                    products.name products,
                                                    sales_items.amount quantity,
                                                    sales_items.price_sales value
                                                FROM
                                                    $name_table 
                                                    INNER JOIN sales_items ON sales_items.id_sales = sales.id
                                                    INNER JOIN products ON products.id = sales_items.id_product
                                                    INNER JOIN form_payment ON form_payment.id = sales.id_payment_method
                                                    INNER JOIN clients ON clients.id = sales.id_client
                                                    LEFT JOIN boxpdv ON boxpdv.id = sales.id_boxpdv
                                                    LEFT JOIN users ON users.id = sales.id_users");
            $exec->execute();
        }
        return $exec->fetchAll();
    }

    public static function SelectBoxPdv($name_table, $start = null, $end = null, $user_filter = null)
    {
        $sql = Db::Connection();

        $query = "SELECT
                boxpdv.*,
                users.name users,
                (SELECT sangria_boxpdv.value FROM sangria_boxpdv WHERE sangria_boxpdv.id_boxpdv = boxpdv.id LIMIT 1) Withdrawal,
                company.name company
            FROM
                $name_table
                INNER JOIN users ON users.id = boxpdv.id_users
                INNER JOIN company ON company.id = boxpdv.id_company
        ";

        $conditions = [];

        if ($user_filter !== null) {
            $conditions[] = "users.id = :user_filter";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY id ASC";

        if ($start !== null && $end !== null) {
            $query .= " LIMIT :start, :end";
        }


        $exec = $sql->prepare($query);

        if ($user_filter !== null) {
            $exec->bindParam(':user_filter', $user_filter, PDO::PARAM_INT);
        }

        if ($start !== null && $end !== null) {
            $exec->bindParam(':start', $start, PDO::PARAM_INT);
            $exec->bindParam(':end', $end, PDO::PARAM_INT);
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

    public static function SelectSales($name_table, $start = null, $end = null, $userFilter = null, $form_payment = null)
    {
        $sql = Db::Connection();

        $query = "SELECT 
                sales.*,
                users.name users,
                clients.name clients,
                form_payment.name form_payment,
                products.name products,
                sales_items.amount quantity,
                sales_items.price_sales value
              FROM
                sales 
                INNER JOIN sales_items ON sales_items.id_sales = sales.id
                INNER JOIN products ON products.id = sales_items.id_product
                INNER JOIN form_payment ON form_payment.id = sales.id_payment_method
                LEFT JOIN clients ON clients.id = sales.id_client
                LEFT JOIN boxpdv ON boxpdv.id = sales.id_boxpdv
                LEFT JOIN users ON users.id = sales.id_users";

        $conditions = [];

        if ($userFilter !== null) {
            $conditions[] = "users.id = :userFilter";
        }

        if ($form_payment !== null) {
            $conditions[] = "form_payment.id = :form_filter";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY id ASC";

        if ($start !== null && $end !== null) {
            $query .= " LIMIT :start, :end";
        }

        $exec = $sql->prepare($query);

        if ($userFilter !== null) {
            $exec->bindParam(':userFilter', $userFilter, PDO::PARAM_INT);
        }

        if ($form_payment !== null) {
            $exec->bindParam(':form_filter', $form_payment, PDO::PARAM_INT);
        }

        if ($start !== null && $end !== null) {
            $exec->bindParam(':start', $start, PDO::PARAM_INT);
            $exec->bindParam(':end', $end, PDO::PARAM_INT);
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

    public static function Insert($arr)
    {
        $db = Db::Connection();

        $true = true;
        $name_table = $arr['name_table'];
        $query = "INSERT INTO `$name_table` (";
        $columns = $db->query("SHOW COLUMNS FROM `$name_table`")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($columns as $column) {
            if (!array_key_exists($column, $arr)) {
                $arr[$column] = null;
            }
        }
        foreach ($arr as $key => $value) {
            $name = $key;
            if ($name == 'action' || $name == 'name_table')
                continue;
            if ($value == '') {
                $true = true;
                break;
            } else {
                $query .= "`$name`,";
            }
        }
        $query = substr($query, 0, -1);
        $query .= ") VALUES (";

        foreach ($arr as $key => $value) {
            $name = $key;
            if ($name == 'action' || $name == 'name_table')
                continue;
            if ($value == '') {
                $true = true;
                break;
            } else {
                $query .= "?,";
                $param[] = $value;
            }
        }
        $query = substr($query, 0, -1);

        $query .= ")";

        if ($true == true) {
            $sql = $db->prepare($query);
            $sql->execute($param);
        }

        return $true;
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

    public static function Delete($table_name, $id = false)
    {
        $sql = Db::Connection();
        if ($id == false) {
            $exec = $sql->prepare("DELETE FROM `$table_name`");
        } else {
            $exec = $sql->prepare("DELETE FROM `$table_name` WHERE id = '$id'");
        }
        $exec->execute();
    }
}
