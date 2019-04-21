<?php 
namespace Core;


use Core\FH;
use Core\Router;



/**
 * This class it's like class Request
 * @package Core\Input
*/
class Input 
{
       
       /**
        * Determine if request is post
        * @return bool
       */
       public function isPost()
       {
           return $this->getRequestMethod() === 'POST';
       }


       /**
        * Determine if request is put
        * @return bool
       */
       public function isPut()
       {
           return $this->getRequestMethod() === 'PUT';
       }


       /**
        * Determine if request is get
        * @return bool
       */
       public function isGet()
       {
           return $this->getRequestMethod() === 'GET';
       }

       
       /**
        * Return Request method
        * @return string
       */
       public function getRequestMethod()
       {
       	   return strtoupper($_SERVER['REQUEST_METHOD']);
       }


       /**
        * Sanitize requests data
        * @param string $input 
        * @return string
       */
	   public function get($input = false)
	   {
	   	   if(!$input)
	   	   {
	   	   	   // return entiere request array and sanitize it
	   	   	   $data = [];
	   	   	   foreach($_REQUEST as $field => $value)
	   	   	   {
	   	   	   	   $data[$field] = FH::sanitize($value);
	   	   	   }
	   	   	   return $data;
	   	   }

	   	   return FH::sanitize($_REQUEST[$input]);
	   }


       /**
        * Check if has valid token
        * @return mixed
       */
	   public function csrfCheck()
	   {
	   	   if(!FH::checkToken($this->get('csrf_token')))
	   	   {
	   	   	    Router::redirect('restricted/badToken');

	   	   }else{
                
                return true;
	   	   }
	   }
}