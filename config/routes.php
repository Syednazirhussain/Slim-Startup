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


        $app->get('/', HomeController::class . ':landing')->setName('root');
        $app->get('/home', HomeController::class . ':home')->setName('home');
        $app->post('/login',LoginController::class.':login_action');
        $app->get('/logout',LoginController::class.':logout');
        $app->get('/dashboard',LoginController::class.':dashboard')->add($authenticateUser);
        $app->post('/postdata',LoginController::class.':postdata');

        // @TODO this route is reserved for verification of token
        $app->get('/resource',LoginController::class.':verifyToken');
            
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
