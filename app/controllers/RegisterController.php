<?php 
namespace App\Controllers;

use Core\Controller;
use Core\Router;
use App\Models\Users;
use App\Models\Login;




// http://mvc.loc/register/[login]
class RegisterController extends Controller 
{

      /**
       * Constructor
       * @param string $controller 
       * @param string $action 
       * @return void
      */
      public function __construct($controller, $action)
      {
           parent::__construct($controller, $action);
           $this->loadModel('Users'); 
           $this->view->setLayout('default');
      }


      /**
       * Login action
       * @return void
      */
  	  public function loginAction()
  	  {
           $loginModel = new Login();

           if($this->request->isPost())
           {
                
                // form validation
                $this->request->csrfCheck();
                $loginModel->assign($this->request->get());
                $loginModel->validator();

                if($loginModel->validationPassed())
                {
                      $user = $this->UsersModel->findByUsername($_POST['username']);
                     
                     if($user && password_verify($this->request->get('password'), $user->password))
                     {
                          $remember = $loginModel->getRememberMeChecked();
                          $user->login($remember);
                          Router::redirect(''); // redirect to home page '/'

                     }else{

                         $loginModel->addErrorMessage('username', 'There is an error with your username or password.');
                     }

                }
                     
           }
           
           $this->view->login = $loginModel;
           $this->view->displayErrors = $loginModel->getErrorMessages();
           $this->view->render('register/login');
  	  }


      /**
       * Logout action
       * @return void
      */
      public function logoutAction()
      {
         if(Users::currentUser())
         {
              Users::currentUser()->logout();
         }

         Router::redirect('register/login');
      }

      
      /**
       * Register action
       * @return void
      */
      public function registerAction()
      {
         $newUser = new Users();

         if($this->request->isPost())
         {
             $this->request->csrfCheck();
             $newUser->assign($this->request->get());
             $newUser->setConfirm($this->request->get('confirm'));

             if($newUser->save())
             {
                 Router::redirect('register/login');
             }
       
         }
      
         $this->view->newUser = $newUser;
         $this->view->displayErrors = $newUser->getErrorMessages();
         $this->view->render('register/register');
      }
}