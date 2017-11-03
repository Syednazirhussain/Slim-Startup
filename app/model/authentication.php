<?php

class authentication extends pdocrudhandler{

    public function __construct(){
        $this->_pdo = $this->connect();
        session_start();
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
            unset( $_SESSION[$key] );
            return true;
        }else{
            return false;
        }
    }

    public function login($params)
    {
        $password = md5($params['password']);
            $result = $this->select('log', array("*"), "where username = ? and password = ? ", array($params['username'], $password) );
            if ($result['status'] == 'success' && $result['rowsAffected'] == 1) {
                $userid = $result['result'][0]->l_id;
                //$_SESSION['login'] = true;
                //$_SESSION["userid"] = $userid;
                $encode  = $this->RandomString(10);
                $ip = $_SERVER['REMOTE_ADDR'];
                $_SESSION[$encode] = $userid;
                $res = $this->update('log', array('lastlogin' => date('Y-m-d h:i:s'),'sessionid' => $encode,'IpAddress' => $ip), 'where l_id = ?', array($userid));
                return $res;
            }else{
                return array("status" => "username or password not found");
            }
    }

    public function checklogin(){
        //$this->softwaresecuritychk();
        $result = $this->select('log',array('sessionid'));
        $key = $result['result'][0]->sessionid;
        if ( isset( $_SESSION[$key] ) ) {
            // @TODO Add some extra functionality when user login
            //echo $result['result'][0]->sessionid . "<br><pre>";
            //print_r($result);
            //print_r($_SESSION);
            //die();
        }else{
            //echo "Not working";die();
            header("location:index.php");
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
