<?php

 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;


class HomeController extends ApplicationController{

    public function landing($request, $response, $args) {

        
        // @todo to generate random string
        //echo md5(uniqid(mt_rand(),true));die();

        // @todo Prevent Session Hijacking Code Example
        //  session_regenerate_id();

        // @todo Validate form post and get params
        // strip_tags();

        return $response->withRedirect("/home");
    }

    public function home($request, $response, $args) {
        $message['root'] = "Bootstrap";
        return $this->view->render($response, 'start.index',$message);
    }

}
