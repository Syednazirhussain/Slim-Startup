<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class UserApiController{

    public function GetAllCourse($request, $response, $args){

        $auth = new authentication();
        $result = $auth->checkapikey();
        if (is_array($result)){
            return json_encode($result);
        }else{
            $pdo = new pdocrudhandler();
            $data = $pdo->select('subject',array('*'));
            return $response->withjson($data,200);
        }
        
    }

    public function GetAllQuestion($request, $response, $args){

        $auth = new authentication();
        $result = $auth->checkapikey();
        if (is_array($result)){
            return json_encode($result);
        }else{
            $pdo = new pdocrudhandler();
            $data = $pdo->select('questions',array('*'));
            return $response->withjson($data,200);
        }

    }

    public function GetAnswerByQuestionId($request, $response, $args){

        $id = $request->getAttribute('question_id');
        $auth = new authentication();
        $result = $auth->checkapikey();
        if (is_array($result)){
            return json_encode($result);
        }else{
            $pdo = new pdocrudhandler();
            $data = $pdo->select('answer',array('*'),'where questionid = ?',array($id));
            return $response->withjson($data,200);
        }

    }

    public function CheckAnswer($request, $response, $args){

        $qid = $request->getAttribute('question_id');
        $aid = $request->getAttribute('answer_id');
        $auth = new authentication();
        $result = $auth->checkapikey();
        if (is_array($result)){
            return json_encode($result);
        }else{
            $pdo = new pdocrudhandler();
            $data = $pdo->select('answer',array('*'),'where questionid = ? and id  = ?',array($qid,$aid));
            return $response->withjson($data,200);
        }

    }

}




?>