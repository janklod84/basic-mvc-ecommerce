<?php 
namespace App\Models;

use Core\Model;
use Core\Session;
use Core\Cookie;


class UserSessions  extends Model
{
      
      /**
        * Table properties
        * 
        * @var int $id
        * @var int $user_id
        * @var string $session
        * @var string $user_agent
       */
       public $id;
       public $user_id;
       public $session;
       public $user_agent;
       
       protected static $_table = 'user_sessions';


      
      /**
       * Get User from cookie
       * @return 
      */
      public static function getFromCookie()
      {
           if(Cookie::exists(REMEMBER_ME_COOKIE_NAME)) 
           {
                $userSession = self::findFirst([
                  'conditions' => "user_agent = ? AND session = ?",
                  'bind' => [Session::uagent_no_version(), Cookie::get(REMEMBER_ME_COOKIE_NAME)]
                ]);
          }
          
          return $userSession;
      }





}