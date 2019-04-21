<?php 
namespace Core;

use Core\Application;


/**
 * @package Core\Controller
*/
class Controller extends Application
{

        /**
         * @var string
        */
        protected $controller;


        /**
         * @var string
        */
        protected $action;


        /**
         * @var string
        */
        public $view; // do may protected
 
        
        /**
          * @var object
        */       
        public $request;



        /**
         * Constructor
         * @param string $controller 
         * @param string $action 
         * @return void
        */
        public function __construct($controller, $action)
        {
              parent::__construct(); // Application constructor
              $this->controller = $controller;
              $this->action  = $action;
              $this->request = new Input();
              $this->view = new View();
        }

        
        /**
         * Load model
         * @param string $model 
         * @return void
         */
        protected function loadModel($model)
        {
             $modelPath = 'App\\Models\\' . $model;

             if(class_exists($modelPath))
             {
                  $this->{$model.'Model'} = new $modelPath();
             }
        }

        
        /**
         * Return json data
         * @param array $response
         * @return string
        */
        public function jsonResponse($response)
        {
              header("Access-Control-Allow-Origin: *");
              header("Content-Type: application/json; charset=UTF-8");
              http_response_code(200);
              echo json_encode($response);
              exit();
        }
}