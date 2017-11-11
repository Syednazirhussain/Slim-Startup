<?php

 use \Psr\Http\Message\ServerRequestInterface as Request;
 use \Psr\Http\Message\ResponseInterface as Response;


class HomeController extends ApplicationController{

    public function landing($request, $response, $args) {

        
        // @todo to generate random string
        //echo md5(uniqid(mt_rand(),true));die();

        // @todo Prevent Session Hijacking Code Example
        //  session_regenerate_id();

        // @todo Validate form post and get params
        // strip_tags();

        return $response->withRedirect("/home");
    }

    public function home($request, $response, $args) {
        $message['root'] = "Bootstrap";
        return $this->view->render($response, 'start.index',$message);
    }

    public function dashboard($request, $response, $args){

        $auth = new authentication();
        $result = $auth->checklogin();
        $data = array_merge(authentication::Session(),$result);
        return $this->view->render($response, 'start.dashboard',$data);

    }

    public function CreateCourse($request, $response, $args){

        $sub = new subject();
        return $sub->CreateSubject($_FILES,$request->getParams());

    }

    public function CreateQuestions($request, $response, $args){

        $params = $request->getParams();
        $pdo = new pdocrudhandler();
        $result = $pdo->insert('questions',array('question' => $params['question'] , 'subjectid' => $params['subjectid']));
        return json_encode($result);

    }

    public function GetAllQuestionByCourseId($request, $response, $args){


        $subjectid = $request->getAttribute('subjectid');
        $pdo = new pdocrudhandler();
        $result = $pdo->select('questions',array('*'),'where subjectid = ?',array($subjectid));
        return json_encode($result);

    }

    public function AddAnswerToQuestion($request, $response, $args){
        $params = $request->getParams();



        $querey = "insert into answer(questionid,ans,status) ";

        for ($i=0 ; $i<count($params) ; $i++){
            ($i != (count($params)-1) ) ? $querey .= "select {$params[0]['questionid']},'{$params[$i]['answer']}','{$params[$i]['status']}' union all " : $querey .= "select {$params[0]['questionid']},'{$params[$i]['answer']}','{$params[$i]['status']}'";
        }

        $pdo = new pdocrudhandler();
        $result = $pdo->executeqry($querey);
        if ($result['status'] == 'success'){
            return json_encode(['Message' => 'Record added successfully']);
        }else{
            return json_encode(['Message' => 'Record not added successfully']);
        }

    }

    public function GetAllCourse($request, $response, $args){
        $pdo = new pdocrudhandler();
        $result = $pdo->select('subject',array('*'));
        return json_encode($result);
    }

    public function GetCourseById($request, $response, $args){
        $id = $destination = $request->getAttribute('id');
        $pdo = new pdocrudhandler();
        $result = $pdo->select('subject',array('*'),'where id = ?',array($id));
        return json_encode($result);
    }

    public function UpdateCourseById($request, $response, $args){
        $params = $request->getParams();
        $id = $request->getAttribute('id');
        $pdo = new pdocrudhandler();
        $result = $pdo->update('subject',array('subject_name' => $params['course_name']),'where id = ?',array($id));
        return json_encode($result);
    }



}
