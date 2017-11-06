<?php
// namespace \app\controllers;
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;

class LoginController extends ApplicationController{

    public function login_action($request, $response, $args) {

        $status1 = Check_Feild_NotEmpty($_POST);
        $status2 = Check_Valid_Email($_POST['username']);

        $this->error = array_merge($status1,$status2);

        if (count($this->error) == 1 && $this->error['status'] == "ok"){
            $auth = new authentication();
            print_r($auth->login($_POST));
        }else{
            return print_r($this->error);
        }



    }

    public function logout($request, $response, $args){
        $auth = new authentication();
        if ($auth->logout()){
            return $response->withRedirect("/home");
        }
    }

    public function dashboard($request, $response, $args){
        $auth = new authentication();
        $result = $auth->checklogin();
        $data = array_merge(authentication::Session(),$result);
        return $this->view->render($response, 'start.dashboard',$data);
    }




    public function postdata($request, $response, $args){
        $token = jwt::decode($_POST['token'], 'secret_server_key');
        echo $token->id;
    }


}

?>