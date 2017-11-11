<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class authentication extends pdocrudhandler{
    


    public function __construct(){
        $this->_pdo = $this->connect();
        //session_start();

    }
    
    private function RandomString($length) {
        $keys = array_merge(range('a', 'z'));
        $key = "";
        for($i=0; $i < $length; $i++) {
            $key .= $keys[mt_rand(0, count($keys) - 1)];
        }
        return $key;
    }

    // @TODO this is session based logout
    public function logout(){
        $result = $this->select('log',array('sessionid'));
        $key = $result['result'][0]->sessionid;
        if ( isset( $_SESSION[$key] ) ) {
            $_SESSION = array();
            session_destroy();
            return true;
//            header('Location:'.$GLOBALS['website_url'].'/');
        }else{
            return false;
        }
    }

    // @TODO this is token based Logout
    public function token_logout(){

        $result = $this->select('log',array('*'));

        if ($result['status'] == 'success' && $result['rowsAffected'] == 1) {

            $secretKey = 'syednazir';

            $response = jwt::decode($_SESSION[$result['result'][0]->sessionid],$secretKey);

            if ($response->ExpireAt > time()){

                if ($result['result'][0]->l_id == $response->data->userId && $result['result'][0]->username == $response->data->userName){

                    $_SESSION[$result['result'][0]->sessionid] = '';

                    $resp = $this->update('log',array('sessionid' => $_SESSION[$result['result'][0]->sessionid],'ApiKey' => ''),'where l_id = ?',array($result['result'][0]->l_id));

                    if ( $resp['status'] == 'success' && $resp['rowsAffected'] == 1 ){

                        return ['Message' => 'You are logged out'];

                    }
                }else{

                    return ['Message' => 'Anyone can tamper your api key'];

                }
            }else{

                return ['Message' => 'Your api key expired please login again'];

            }
        }
    }

    public function checkapikey(){

        $result = $this->select('log',array('*'));

        if ($result['status'] == 'success' && $result['rowsAffected'] == 1) {

            if ($result['result'][0]->sessionid != null && $result['result'][0]->ApiKey != ''){

                $secretKey = 'syednazir';

                $response = jwt::decode($_SESSION[$result['result'][0]->sessionid],$secretKey);

                if ($response->ExpireAt > time()){

                    if ($result['result'][0]->l_id == $response->data->userId && $result['result'][0]->username == $response->data->userName){

                        return 1;

                    }else{

                        return ['Message' => 'Anyone can tamper your api key'];

                    }
                }else{

                    return ['Message' => 'Your api key expired please login again'];

                }
            }else{

                return ['Message' => 'Please login to your account'];
                
            }

        }
    }

    public static function Session(){
        return $_SESSION;
    }

    // @TODO this function is allocated for token based authentication in an API
    public function create_token($params){
        $password = md5($params['password']);
        $result = $this->select('log', array("*"), "where username = ? and password = ? ", array($params['username'], $password) );
        if ($result['status'] == 'success' && $result['rowsAffected'] == 1) {
            $userid = $result['result'][0]->l_id;
            $username = $result['result'][0]->username;
            $user_agent =  $_SERVER['HTTP_USER_AGENT'];
            $userid = $result['result'][0]->l_id;
            $encode  = $this->RandomString(10);
            $ip = $_SERVER['REMOTE_ADDR'];

            $tokenId    = base64_encode(mcrypt_create_iv(32));
            $issuedAt   = time();
            $notBefore  = $issuedAt + 10;
            $expire     = $notBefore + 30;
            $serverName = $_SERVER['SERVER_NAME'];
            $data = [
                'IssueAt'  => $issuedAt,
                'TokenId'  => $tokenId,
                'IssueAtHost'  => $serverName,
                'NotBefore'  => $notBefore,
                'ExpireAt'  => $expire,
                'data' => [
                    'userId'   => $userid,
                    'userName' => $username
                ]
            ];
            // @TODO this secret ki come from login form and store in some where as you want
            $secretKey = 'syednazir';
            $jwt = JWT::encode($data, $secretKey);
            $_SESSION[$encode] = $jwt;

            // @TODO this is for JWT web token
/*//                $unencodedArray = ['jwt' => $jwt];
//                return $unencodedArray;*/

            $resp = $this->update('log',array('ApiKey' => $jwt,'lastlogin' => date('Y-m-d h:i:s'),'User_AGENT' => $user_agent,'sessionid' => $encode,'IpAddress' => $ip),'where L_id = ?',array($userid));
            if ($resp['status'] == 'success' && $resp['rowsAffected'] == 1) {
                // @TODO this is for api Login message
                return array_merge(['Message' => 'You are successfully login','ApiKey' => $jwt],$resp);
            }else{
                return ['error' => 'There is some problem'];
            }

        }else{
            return array("status" => "username or password not found");
        }
    }

    public function login($params)
    {
        $_SESSION = array();
        $password = md5($params['password']);
            $result = $this->select('log', array("*"), "where username = ? and password = ? ", array($params['username'], $password) );
            if ($result['status'] == 'success' && $result['rowsAffected'] == 1) {
                $user_agent =  $_SERVER['HTTP_USER_AGENT'];
                $userid = $result['result'][0]->l_id;
                $encode  = $this->RandomString(10);
                $ip = $_SERVER['REMOTE_ADDR'];
                $_SESSION[$encode] = $userid;
                $res = $this->update('log', array('lastlogin' => date('Y-m-d h:i:s'),'User_AGENT' => $user_agent,'sessionid' => $encode,'IpAddress' => $ip), 'where l_id = ?', array($userid));
                return $res;
            }else{
                return array("status" => "username or password not found");
            }
    }

    public function checklogin(){
        $result = $this->select('log',array('*'));
        $key = $result['result'][0]->sessionid;
        if ( isset( $_SESSION[$key] ) ) {
            return array_merge($_SESSION,$result);
            // @TODO Add some extra functionality when user login
            //echo $result['result'][0]->sessionid . "<br><pre>";
            //print_r($result);
            //print_r($_SESSION);
            //die();
        }else{
            //echo "Not working";die();
//            header("location : ");
            
        }
    }
    
}


$mendetoryParam = array(
    'login'       => array('username','password'),
    'logout'      => array()
);



if(isset($_POST['call']) && isset($mendetoryParam[$_POST['call']])){
    $data = array();
    $missingFields = array();
    $flag = true;
    foreach($mendetoryParam[$_POST['call']] as $value){
        if(!isset($_POST[$value])){
            $flag = false;
            $missingFields[] = $value;
        }
    }
    if(count($missingFields) > 0){
        $data['status'] =  false;
        $data['error'] =  'Required parameter(s) missing';
        $data['missingParameters'] = implode(',',$missingFields);
        echo json_encode($data,true);
    }else{
        $user = new user();
        $data = $_POST;
        $mathodToCall = (string)$_POST['call'];
        $response = $user->$mathodToCall($data);
        echo json_encode($response);
    }
}else{
   /* echo json_encode(array('status'=>false,'error'=>'Invalid  called'));*/
}
?>
