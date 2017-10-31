<?php
// namespace \app\controllers;
 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;

class LoginController extends ApplicationController{

    public function login_action($request, $response, $args) {

        


        // This is come form database for testing purpose iam hard coded it
        $username = "syednazir13@gmail.com";
        $password = "pakistan123";
        
        
        $invaildInput = 0;
        $missingFeild = 0;

        if (isset($_POST)){
            $regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
            if ($_POST['username'] != "") {
                if (!preg_match_all($regex, $_POST['username'])) {
                    $this->error['invaid_input'][$invaildInput] = "Email not valid";
                    $invaildInput++;
                }
                if ($_POST['username'] != $username) {
                    $this->error['missing_feild'][$missingFeild] = "username " . $_POST['username'] . " not found ";
                    $missingFeild++;
                }
            }
            if ($_POST['password'] != "") {
                if ($_POST['password'] != $password) {
                    $this->error['missing_feild'][$missingFeild] = "password " . $_POST['password'] . " not found";
                    $missingFeild++;
                }
            }
            if (empty($this->error)){
                $data  = array("result" => $_POST);
                return json_encode($data);
            }else{
                $data['error'] = $this->error;
                return json_encode($data);
            }
//            print_r($this->error);
//            echo "<br>";
//            print_r($this->error['invaid_input']);
//            $data  = array("result" => $_POST);
//            return json_encode($data);
        }
    }


}