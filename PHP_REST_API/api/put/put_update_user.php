<?php
header('Access-Control-Allow-Origin: *');  //COMPLETE PUBLIC API CHANCE TO PRIVATE<ASK FRANKLIN> 
header('Content-Type: application/json');  //FOR USE JSON

//INCLUDES
include_once '../../config/Database.php';
include_once '../../models/user.php';

//INSTANTIATE DB & CONNECT
$database = new Database();
$db = $database->connect();


//INSTANTIATE BLOG POST OBJECT
$post = new User($db);


if(isset($_GET['user_email']) && isset($_GET['password']) && isset($_GET['user_names']) && isset($_GET['user_last_names']) && isset($_GET['birthdate']) && isset($_GET['cellphone']) && isset($_GET['sex']) && isset($_GET['doctor_profession']))
{

    $post ->update_by_user($_GET['user_email'],
    $_GET['password'],
    $_GET['user_names'],
    $_GET['user_last_names'],
    $_GET['birthdate'],
    $_GET['cellphone'],
    $_GET['sex'],
    $_GET['doctor_profession']);

}
else{
    $error_arraylist = array('jsontype'=> 'Error','message'=> 'Ingrese todos los Datos Correctamente');
    echo json_encode($error_arraylist);
}

?> 