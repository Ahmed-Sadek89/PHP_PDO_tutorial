<?php
    session_start();
    require_once './db_connection.php';
    require_once './employee.php';

    // ############### db connection
    $pdo = new PDO_Connection();

    // ############### class variables
    $employee = new Employee();
    $employee->name = filter_input(INPUT_POST, 'name');
    $employee->age = filter_input(INPUT_POST, 'age');
    $employee->salary = filter_input(INPUT_POST, 'salary');

    // ############### insert new record
    if ( isset($_POST['insert']) ) {
        $name = filter_input(INPUT_POST, 'name');
        $age = filter_input(INPUT_POST, 'age');
        $salary = filter_input(INPUT_POST ,"salary");
        $message = $pdo->insertRec($name, $age, $salary);
        $_SESSION['message'] = $message;
        session_write_close();
    } 

    // ############### read all records
    $result = $pdo->getAllRec();

    // ############### get rec by id
    if ( isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] === 'edit' ) {
        $id = $_GET["id"];
        $selectedEmployee = $pdo->getRecById($id);
    }
    // ############### update rec by id
    if ( isset($_POST['update']) ) {
        $id = $_GET["id"];
        $name = filter_input(INPUT_POST, 'name');
        $age = filter_input(INPUT_POST, 'age');
        $salary = filter_input(INPUT_POST ,"salary");
        $params = array(
            "id" => $id,
            "name" => $name,
            "age" => $age,
            "salary" => $salary
        );
        $updateMessage = $pdo->updateRecById($params);
        $_SESSION['message'] = $updateMessage;
        header('location: http://localhost:3000/index.php');
        session_write_close();
    }

    // ############### delete rec by id
    if ( isset($_GET['action']) && isset($_GET['id']) && $_GET['action'] === 'delete' ) {
        $id = $_GET["id"];
        echo 'delete record number '.$id;
        // $pdo->deleteRec($id);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDO</title>
    <style><?= file_get_contents("main.css");?></style>
</head>
<body>
    <form method="post" enctype="application/x-www-form-urlencoded">
        <input 
            required 
            type="text" 
            name="name" 
            placeholder="enter name here" 
            value="<?= isset($selectedEmployee) ? $selectedEmployee->name : '' ?>"
        />
        <input 
            required 
            type="number" 
            name="age" 
            placeholder="enter age here" 
            value="<?= isset($selectedEmployee) ? $selectedEmployee->age : '' ?>"
        />
        <input 
            required 
            type="text" 
            name="salary" 
            placeholder="enter salary here" 
            value="<?= isset($selectedEmployee) ? $selectedEmployee->salary : '' ?>"
        />
        <?php
            if ( isset($selectedEmployee)) {
        ?>
            <input type="submit" name="update" value="update" />
        <?php } else {?>
            <input type="submit" name="insert" value="insert" />
        <?php }?>
        <a href='/index.php' class="update-btn" >home</a>
    </form>
    <?php 
        if ( isset($_SESSION['message']) ) { 
    ?>
        <h4><?= $_SESSION['message'] ?></h4>
    <?php 
        }
        unset($_SESSION['message']);
    ?>
    <br />
    <table>
        <tr>
            <th>ID</th>
            <th>username</th>
            <th>age</th>
            <th>salary</th>
            <th>action</th>
        </tr>
        <?php 
            if ( !empty($result) ){
                foreach( $result as $employee ){
        ?>
        <tr>
            <td><?= $employee->id ?></td>
            <td><?= $employee->name ?></td>
            <td><?= $employee->age ?></td>
            <td><?= $employee->salary ?></td>
            <td>
                <a 
                    class="update-btn" 
                    href="/index.php/?action=edit&id=<?= $employee->id ?>"
                >
                    update
                </a>
                <a 
                    class="delete-btn"
                    href="/index.php/?action=delete&id=<?= $employee->id ?>"
                >delete</a>
            </td>
        </tr>
        <?php }} else { ?>
                <tr>
                    <th colspan="5" style="font-style: italic">empolyees are empty !</th>
                </tr>
        <?php }?>
    </table>
</body>
</html>