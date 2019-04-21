<?php 
namespace Core;


/**
 * @package Core\Session
*/
class Session 
{
        
        /**
         * Determine if key exist in session
         * @param string $name 
         * @return bool
        */
        public static function exists($name)
        {
        	 return (isset($_SESSION[$name])) ? true : false;
        }	


        
        /**
         * Return session value
         * @param type $name 
         * @return mixed
        */
        public static function get($name)
        {
        	 return $_SESSION[$name];
        }

        
        /**
         * Set item
         * @param string $name 
         * @param mixed $value 
         * @return void
        */
        public static function set($name, $value)
        {
             return $_SESSION[$name] = $value;
        }

        
        /**
         * Delete item from $_SESSION
         * @param string $name 
         * @return void
        */
        public static function delete($name)
        {
              if(self::exists($name))
              {
              	  unset($_SESSION[$name]);
              }
        }



       /**
          * Get all cookies
          * @return array
       */
       public static function all()
       {
            return $_SESSION ?? [];
       }

        
        /**
         * Store version number
         * @return 
        */
        public static function uagent_no_version()
        {
            $uagent = $_SERVER['HTTP_USER_AGENT'];
            $regex = '/\/[a-zA-Z0-9.]+/';
            $newString = preg_replace($regex, '', $uagent);
            return $newString;
        }

        
        /**
         * Add a session alert message
         * @param string $type  can be info, success, warning or danger
         * @param string $msg  the message you want to display in the alert
         * @return void
        */
        public static function addMsg($type, $msg)
        {
              $sessionName = 'alert-'. $type;
              self::set($sessionName, $msg);
        }
        /**
         * Display Message
         * @return string
        */
        public static function displayMsg()
        {
                $alerts = [
                    'alert-info', 
                    'alert-success', 
                    'alert-warning', 
                    'alert-danger'
               ];

               $html = '';
               foreach($alerts as $alert)
               {
                   if(self::exists($alert))
                   {
                       $html .= '<div class="alert '. $alert .' alert-dismissible" role="alert">';
                       $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
                       $html .= self::get($alert);
                       $html .= '</div>';
                       self::delete($alert);
                   }
               }
               return $html;
           
        }
}