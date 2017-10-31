<?php
// namespace \app\controllers;
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;

class ApplicationController 
{
   protected $view;
   protected $error = array();
    public function __construct(\Slim\Views\Blade $view) {
        $this->view = $view;
    }
	
}