<?php
// namespace \app\controllers;
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;

class LoginController extends ApplicationController{



    public function login_action($request, $response, $args) {

        // @TODO this is form api login
        $_POST = $request->getParams();


        $status1 = Check_Feild_NotEmpty($_POST);

        $status2 = Check_Valid_Email($_POST['username']);

        $this->error = array_merge($status1,$status2);

        if (count($this->error) == 1 && $this->error['status'] == "ok"){


            // @todo This is Session baseed authentication
/*            $auth = new authentication();
            echo json_encode($auth->login($_POST));*/

            // @todo This is Token based authentication
            $auth = new authentication();
            echo json_encode($auth->create_token($_POST));

        }else{
            echo json_encode($this->error);
        }
        
    }

    public function logout($request, $response, $args){

        // @TODO this is session based logout
/*        $auth = new authentication();
        if ($auth->logout()){
            return $response->withRedirect("/home");
        }*/

        $auth = new authentication();
        return json_encode($auth->token_logout());
//        print_r($auth->token_logout());die();
        
    }

    public function dashboard($request, $response, $args){
        $auth = new authentication();
        $result = $auth->checklogin();
        $data = array_merge(authentication::Session(),$result);
        return $this->view->render($response, 'start.dashboard',$data);
    }

    public function verifyToken($request, $response, $args){


        if ($request->isGet()) {
            $authHeader = $request->getHeader('authorization');
            if ($authHeader) {
                    try {
                        $secretKey = 'syednazir';
                        $token = JWT::decode($authHeader[0], $secretKey);
                        print_r($token);
                    } catch (Exception $e) {
                        return json_encode(array('error' => $e->getMessage()));
                    }
            }
        }

    }


    public function postdata($request, $response, $args){
        $token = jwt::decode($_POST['token'], 'secret_server_key');
        echo $token->id;
    }


}

?>