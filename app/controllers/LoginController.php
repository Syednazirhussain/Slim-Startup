<?php
// namespace \app\controllers;
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;

class LoginController extends ApplicationController{

    public function login_action($request, $response, $args) {
        


        // @todo  This field come form database for testing purpose iam hard coded it
        $username = "syednazir13@gmail.com";
        $password = "pakistan123";


        if ($_POST['username'] != $username){
            $this->error['error'] = 'username not match';
        }
        if ($_POST['password'] != $password){
            $this->error['error'] = 'password does not match';
        }
        if (empty($this->error['error'])){
            $token = array();
            $token['id'] = $username;

            $key =  jwt::encode($token,'secret_server_key');
            if ($key){
                return $key;
            }
        }else{
            print_r($this->error);
        }

        /*
        $invaildInput = 0;
        $missingFeild = 0;
        if (isset($_POST)) {
            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
            if ($_POST['username'] != "") {
                if (!preg_match_all($regex, $_POST['username'])) {
                    $this->error['invaid_input'][$invaildInput] = "Email not valid";
                    $invaildInput++;
                }
            }
            if ($_POST['username'] == "") {
                $this->error['missing_feild'][$missingFeild] = "username must be entered";
                $missingFeild++;
            }
            if ($_POST['password'] == "") {
//                if ($_POST['password'] != $password) {
                    $this->error['missing_feild'][$missingFeild] = "password must be entered";
                    $missingFeild++;
//                }
            }
            if (empty($this->error)) {
//                $data = array("result" => $_POST);
//                return json_encode($data);
                $auth = new authentication();
                print_r($auth->login($_POST));
            } else {
                $data['error'] = $this->error;
                print_r($data);
//                return json_encode($data);
            }
        }
        */
        
    }

    public function dashboard($request, $response, $args){
        return $this->view->render($response, 'start.profile');
    }

    public function postdata($request, $response, $args){
        $token = jwt::decode($_POST['token'], 'secret_server_key');
        echo $token->id;
    }


}