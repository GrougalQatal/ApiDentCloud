<?php
    //HEADERS
    header ('Access-Control-Allow-Origin: *');  //COMPLETE PUBLIC API CHANCE TO PRIVATE<ASK FRANKLIN> 
    header ('Content-Type: application/json');  //FOR USE JSON

    //INCLUDES
    include_once '../../config/Database.php';
    include_once '../../models/publications.php';

    //INSTANTIATE DB & CONNECT
    $database = new Database();
    $db = $database->connect();

    //INSTANTIATE BLOG POST OBJECT
    $post = new Publications($db);

    
        //BLOG POST QUERY
        $result = $post->get_publications_by_business($_GET['business_ruc']);
        // echo $result;
    // echo $result->num_rows;
        if ($result->num_rows > 0) {
            //POST ARRAY
            $post_arraylist = array('jsontype'=> 'response');
            $post_arraylist['publicaciones_negocio'] = array();
    
            while ($row = mysqli_fetch_assoc($result)) {
    
                $post_item = array(
                    'usuario' => utf8_encode($row['usuario']),
                    'descripcion' => utf8_encode($row['descripcion']),
                    'archivo' => utf8_encode($row['archivo']),
                    'fecha' => utf8_encode($row['fecha']),
                    'negocio' => utf8_encode($row['negocio'])
                );
                //PUSH TO DATA
                array_push($post_arraylist['publicaciones_negocio'], $post_item);
            }
            //TURN IT TO JSON & OUTPUT
            echo json_encode($post_arraylist);
        } else {
            //NO POST
            
                $error_arraylist = array('jsontype'=> 'ERROR','message'=> 'NO POST FOUND');
                echo json_encode($error_arraylist);
            
        }

?>     