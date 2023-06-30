<?php
class PDO_Connection {
    private $pdo = null;
    public function __construct() {
        try {
            $dns = 'mysql://hostname=localhost;dbname=php_adv';
            $user = 'root';
            $password = '';
            $instruction = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
            );
            $this->pdo = new PDO($dns, $user, $password, $instruction);
        } catch ( PDOException $e ) {
            echo $e->getMessage();
        }
    }

    public function insertRec($name, $age, $salary) {
        $sql = 'insert into employees set name=:name, age=:age,salary=:salary';
        $prepare = $this->pdo->prepare($sql);
        if (
                $prepare->execute(array(
                    ":name" => $name,
                    ":age" => $age,
                    ":salary" => $salary
                )) === true
        ) {
            $message = 'success inserting '.$name;
            $name = '';
        } else {
            $message = 'failed inserting '.$name;
        }
        return $message;
    }
    public function getAllRec() {
        $sql = 'select * from employees order by id desc';
        $statement = $this->pdo->prepare($sql);
        $successStatement = $statement->execute();
        if ( $successStatement === true ) {
            $result = $statement->fetchAll(PDO::FETCH_CLASS, 'Employee');
        }
        return $result;
    }
    public function getRecById($id) {
        $sql = 'select * from employees where id=:id';
        $statement = $this->pdo->prepare($sql);
        if ( $statement->execute( array( ":id" => $id ) ) ) {
            $row = $statement->fetchAll(PDO::FETCH_CLASS, 'Employee');
        }
        return $row[0]; // to get the data in (object)
    }
    public function updateRecById($params) {
        $sql = 'update employees set name=:name, age=:age, salary=:salary where id=:id';
        $statement = $this->pdo->prepare($sql);
        $successStatement = $statement->execute($params);
        if ( $successStatement === true ) {
            $updateMessage = 'employee '.$params["id"].' updated successfully';
        } else {
            $updateMessage = 'employee '.$params["id"].' doesn`t update successfully';
        }
        return $updateMessage;
    }
}