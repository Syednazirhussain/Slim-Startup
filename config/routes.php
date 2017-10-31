<?php
class MainRouter

    {
    public function configure($app)
        {

        $app->get('/', HomeController::class . ':landing')->setName('root');
        $app->get('/home', HomeController::class . ':home')->setName('home');
        $app->post('/login',LoginController::class.':login_action');
            
            
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
