<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class subject extends pdocrudhandler{



    public function __construct(){
        $this->_pdo = $this->connect();
    }

    public function CreateSubject($files,$params){

        if (isset($files) && !empty($files)){
            $result = $this->insert('subject',array('subject_name' => $params['subjectname']));
            if ($result['status'] == 'success' && $result['rowsAffected'] == 1){
                $getID = $this->select('subject',array('id'),'where subject_name = ?',array($params['subjectname']));
                $ID = $getID['result'][0]->id;
                if ($ID){
                    $tmpFilePath = $files['image']['tmp_name'];
                    if ($tmpFilePath != ""){
                        $newFilePath = "../Uploads/Icons/".$ID."_".$files['image']['name'];
                        if(move_uploaded_file($tmpFilePath,$newFilePath)) {
                            $update = $this->update('subject',array('picPath'  => $newFilePath),'where id = ? ',array($ID));
                            if ($update['status'] == 'success' && $update['rowsAffected'] == 1){
                                return "file uplaoded successfull";
                            }
                        }
                    }
                }
            }

        }else{
            $result = $this->insert('subject',array('subject_name' => $params['subjectname']));
            return $result;
        }
    }

   



}



?>
