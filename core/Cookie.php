<?php 
namespace Core;



/**
 * @package Core\Cookie
*/
class Cookie
{
     
     /**
      * Set cookie
      * @param string $name 
      * @param mixed $value 
      * @param int $expiry 
      * @return bool
      */
  	 public static function set($name, $value, $expiry)
  	 {
      	 	  if(setcookie($name, $value, time() + $expiry, '/'))
      	 	  {
      	 	  	   return true;
      	 	  }
  	 	      return false;
  	 }

     
     /**
      * Delete cookie
      * @param string $name 
      * @return void
      */
  	 public static function delete($name)
  	 {
            self::set($name, '', time() - 1);
  	 }

     
     /**
      * Get cookie
      * @param string $name 
      * @return mixed
     */
  	 public static function get($name)
  	 {
  	 	  return $_COOKIE[$name];
  	 }

     
     /**
      * Determine if $key has in $_COOKIE
      * @param string $name 
      * @return bool
     */
  	 public static function exists($name)
  	 {
           return isset($_COOKIE[$name]);
  	 }


     /**
      * Get all cookies
      * @return array
     */
     public static function all()
     {
         return $_COOKIE ?? [];
     }

	 
}