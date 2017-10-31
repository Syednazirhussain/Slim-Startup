<?php
// namespace \app\controllers;
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;

//use pdocrudhandler;

//require '../core/function/application.php';

class HomeController extends ApplicationController{

    public function landing($request, $response, $args) {
        return $response->withRedirect("/home");
    }

    public function home($request, $response, $args) {

        // using model classes
//        $_pdo = new pdocrudhandler();
//        $result = $_pdo->select('log',array('*'));
//        echo "<pre>";
//        print_r($result);die();

//        print_r($GLOBALS['config']['mysql']);die();

        // using route path filter
//        echo url_for("");die();
        
        $message['root'] = "Bootstrap";
        return $this->view->render($response, 'start.index',$message);
    }

}
