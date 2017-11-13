<?php
class MainRouter

    {
    public function configure($app){

        $authenticateUser = function ($request, $response, $next) {

            $uri = $request->getUri()->withPath($this->router->pathFor('root'));
            $status = 0;
            session_regenerate_id();
            $_SESSION['SESSIONID'] = session_id();
            $session = array();

            $_pdo = new pdocrudhandler();
            $result = $_pdo->select('log',array('*'));
            $session = authentication::Session();
            for($i = 0 ; $i < count($result['result']) ; $i++){
                if ( array_key_exists($result['result'][$i]->sessionid,$session) ){
                    if( $result['result'][$i]->IpAddress == $_SERVER['REMOTE_ADDR'] && $result['result'][$i]->User_AGENT == $_SERVER['HTTP_USER_AGENT'] ){
                        $status = 1;
                    }else{
                        $status = 0;
                    }
                }
            }

            if ($status){
                return $next($request, $response);
            }else{
                return $response = $response->withRedirect($uri);
            }

        };

        $ValidApiKeyMiddleware = function($request, $response, $next){
            
            $route = $request->getAttribute('route');
            $arguments = $route->getArguments();
            $validApiKeys = config::getValidApiKeys();
            if(in_array($arguments['auth'],$validApiKeys)){
                return  $next($request, $response);
            }else{
                $this->response = array('status' => false,'code' => 401,'message'=>'authentication error');
                echo json_encode($this->response);
            }
            return $response;
            
        };


        $app->get('/', HomeController::class . ':landing')->setName('root');
        $app->get('/home', HomeController::class . ':home')->setName('home');
        $app->post('/login',LoginController::class.':login_action');
        $app->get('/logout',LoginController::class.':logout');
        $app->get('/dashboard',HomeController::class.':dashboard')->add($authenticateUser);

        // @TODO This routes is related with question
        $app->get('/question',HomeController::class.':GetAllQuestions')->add($authenticateUser);
        $app->post('/question',HomeController::class.':CreateQuestions')->add($authenticateUser);
        $app->put('/question/{questionid}',HomeController::class.':UpdateQuestionById')->add($authenticateUser);
        $app->get('/question/{subjectid}',HomeController::class.':GetAllQuestionByCourseId')->add($authenticateUser);

        // @TODO This routes is related with answer
        $app->get('/answers/{questionid}',HomeController::class.':GetAnswerByQuestionId')->add($authenticateUser);
        $app->post('/answer',HomeController::class.':AddAnswerToQuestion')->add($authenticateUser);
        $app->put('/answer/{id}',HomeController::class.':UpdateAnswerById')->add($authenticateUser);
        $app->delete('/answer/{id}',HomeController::class.':DeleteAnswerById')->add($authenticateUser);


        // @TODO This routes is related with courses
        $app->get('/course',HomeController::class.':GetAllCourse')->add($authenticateUser);
        $app->post('/course',HomeController::class.':CreateCourse')->add($authenticateUser);
        $app->get('/course/{id}',HomeController::class.':GetCourseById')->add($authenticateUser);
        $app->put('/course/{id}',HomeController::class.':UpdateCourseById')->add($authenticateUser);

        // @TODO this route is reserverd for API Calls
        $app->get('/courses',UserApiController::class.':GetAllCourse');
        $app->get('/questions',UserApiController::class.':GetAllQuestion');
        $app->get('/answer/{question_id}',UserApiController::class.':GetAnswerByQuestionId');
        $app->get('/answer/{question_id}/{answer_id}',UserApiController::class.':CheckAnswer');


        // @TODO this route is reserved for verification of token
        //$app->get('/resource',LoginController::class.':verifyToken');
        // @todo This route is used to test simple jwt token
        //$app->post('/postdata',LoginController::class.':postdata');
            
        }


        // auto logout after 15 mintues 
      public static  function auto_logout($field)
        {
            if (!isset($_SESSION[$field])){
                $_SESSION[$field] = time();

            } else {
                 $t = time();
                $t0 = $_SESSION[$field];
                $diff = $t - $t0;
                if ($diff > 1500 || !isset($t0))
                {          
                    return true;
                }
                else
                {
                    $_SESSION[$field] = time();
                }               
            }

        }


    }


?>
