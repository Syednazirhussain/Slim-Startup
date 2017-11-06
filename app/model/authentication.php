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

    public static function Session(){
        return $_SESSION;
    }

    public function create_token($params){
        $password = md5($params['password']);
        $result = $this->select('log', array("*"), "where username = ? and password = ? ", array($params['username'], $password) );
        if ($result['status'] == 'success' && $result['rowsAffected'] == 1) {
            $userid = $result['result'][0]->l_id;
            $username = $result['result'][0]->username;
            $tokenId    = base64_encode(mcrypt_create_iv(32));
            $issuedAt   = time();
            $notBefore  = $issuedAt + 10;
            $expire     = $notBefore + 60;
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
            $secretKey = 'syednazir';
            $jwt = JWT::encode(
                $data,
                $secretKey
            );
            $unencodedArray = ['jwt' => $jwt];
            return $unencodedArray;
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
                return json_encode($res);
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
