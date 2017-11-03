<?php

 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;


class HomeController extends ApplicationController{

    public function landing($request, $response, $args) {
        return $response->withRedirect("/home");
    }

    public function home($request, $response, $args) {
/*        // using model classes
//        $_pdo = new pdocrudhandler();
//        $result = $_pdo->select('log',array('username'),'where l_id = ?',array(1));
//        echo "<pre>";
//        $json = json_encode($result);
//        echo $json;die();

//        print_r($GLOBALS['config']['mysql']);die();

        // using route path filter
//        echo url_for("");die();

//        $check = checkToken($_GET['token'], $_GET['data']);
//        if ($check !== false){
//            echo json_encode(array("secureData" => "Oo"));
//        }
//        die();


//        print_r($_GET);die();
//        $search = $_GET['search'] ;
//        echo 'Search results for '.$search;
//        echo "working";*/
        $message['root'] = "Bootstrap";
        return $this->view->render($response, 'start.index',$message);
    }

}
