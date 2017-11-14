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
                            $path = '../../../'.$newFilePath;
                            $update = $this->update('subject',array('picPath'  => $path),'where id = ? ',array($ID));
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

    public function ChangeSubjectIcon($files,$params){

        $result = $this->select('subject',array('*'),'where id = ?',array($params['id']));
//        return ['status' => $result['result'][0]->picPath];

        $arr = str_split($result['result'][0]->picPath, 9);

        $filesPath = '';

        for ($i = 1 ; $i < count($arr) ; $i++){
            $filesPath .= $arr[$i];
        }
        unlink($filesPath);

        $tmpFilePath = $files['image']['tmp_name'];
        if ($tmpFilePath != ""){
            $newFilePath = "../Uploads/Icons/".$params['id']."_".$files['image']['name'];
            if(move_uploaded_file($tmpFilePath,$newFilePath)) {
                $path = '../../../'.$newFilePath;
                $update = $this->update('subject',array('picPath'  => $path),'where id = ? ',array($params['id']));
                if ($update['status'] == 'success' && $update['rowsAffected'] == 1){
                    return $this->select('subject',array('*'));
                }
            }
        }
    }

   



}



?>
