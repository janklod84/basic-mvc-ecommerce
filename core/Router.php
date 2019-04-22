<?php 
namespace Core;


use Core\Session;
use App\Models\Users;


/**
 * @package Core\Router
*/
class Router 
{

        /**
         * Run routing
         * $controller can named like $part or $path
         * 
         * @param string $url Ex: $url = http://mvc.loc/users/register/567/new
         * @return mixed
         */
        public static function route($url)
        {

        	 // controller
             $controller = (isset($url[0]) && $url[0] != '') ? ucwords($url[0]) . 'Controller' : DEFAULT_CONTROLLER . 'Controller';
             $controller_name = str_replace('Controller', '', $controller);
             array_shift($url);

             // action 
             $action = (isset($url[0]) && $url[0] != '') ? $url[0] . 'Action' : 'indexAction';
             $action_name = (isset($url[0]) && $url[0] != '') ? $url[0] : 'index';
             array_shift($url);


             // acl check
             $grantAccess = self::hasAccess($controller_name, $action_name);
             
             # if we don't have permission access
             if(!$grantAccess) 
             {
                 # we'll change controller name to:
                 $controller = ACCESS_RESTRICTED.'Controller';
                 $controller_name = ACCESS_RESTRICTED;
                 $action = 'indexAction';
             }



             // params
             $queryParams = $url; // ['0' => '567', '1' => 'new']
 
             
             // dispatching [$dispatch = new Users($controller_name, $action)]
             $controller = 'App\\Controllers\\' . $controller;
             $dispatch = new $controller($controller_name, $action);
             
             // check if method $action exist in class $controller
             if(method_exists($controller, $action))
             {
             	   // $dispatch->{$action}($queryParams)
                   call_user_func_array([$dispatch, $action], $queryParams);

             }else{

             	 die('That method does not exist in the controller \"'. $controller_name . '\"');
             }
        }   

        
        /**
         * Redirect to given param
         * @param string $location 
         * @return void
         */
        public static function redirect($location = '')
        {
            if(!headers_sent())
            {
                 header('Location: '. PROOT . $location);
                 exit();

            }else{
 
                 echo '<script type="text/javascript">';
                 echo 'window.location.href="'. PROOT . $location .'";';
                 echo '</script>';
                 echo '<noscript>';
                 echo '<meta http-equiv="refresh" content="0;url='. $location . '" />';
                 echo '</noscript>';
                 exit();
            }
        }

        
        /**
         * Determine if have access
         * @return bool
        */
        public static function hasAccess($controller_name, $action_name = 'index')
        {
                $acl_file = file_get_contents(ROOT . DS . 'app' . DS . 'acl.json');
                $acl = json_decode($acl_file, true);
                $current_user_acls = ["Guest"];
                $grantAccess = false;

                if(Session::exists(CURRENT_USER_SESSION_NAME))
                {
                     $current_user_acls[] = "LoggedIn";

                     foreach(Users::currentUser()->acls() as $a)
                     {
                          $current_user_acls[] = $a;
                     }
                }

                /* debug($current_user_acls, true); */

                foreach($current_user_acls as $level)
                {
                       if(array_key_exists($level, $acl) && array_key_exists($controller_name, $acl[$level]))
                       {
                              if(in_array($action_name, $acl[$level][$controller_name])
                                || in_array("*", $acl[$level][$controller_name])
                              )
                              {
                                    $grantAccess = true;
                                    break;
                              }
                       }
                }

                
                // check for denied
                foreach($current_user_acls as $level)
                {
                     $denied = $acl[$level]['denied'];

                     if(!empty($denied) 
                        && array_key_exists($controller_name, $denied)
                        && in_array($action_name, $denied[$controller_name])
                      )
                     {
                           $grantAccess = false;
                           break;
                     }
                }

                return $grantAccess;

         }

         
         /**
          * Get Menu
          * @param string $menu 
          * @return mixed
        */
         public static function getMenu($menu)
         {
              $menuArray = [];
              $menuFile = file_get_contents(ROOT . DS . 'app' . DS . $menu . '.json');
              $acl = json_decode($menuFile, true);
              
              foreach($acl as $key => $val)
              {
                  if(is_array($val))
                  {
                          $sub = [];

                          foreach($val as $k => $v)
                          {
                               if($key == 'separator' && !empty($sub))
                               {
                                    $sub[$k] = '';
                                    continue;

                               }else if($finalVal = self::get_link($v)){

                                    $sub[$k] = $finalVal;
                               }
                          }

                          if(!empty($sub))
                          {
                             $menuArray[$key] = $sub;
                          }

                  } else{ // if not an array

                        if($finalVal = self::get_link($val))
                        {
                             $menuArray[$key] = $finalVal;
                        }
                  
                  } // End if is array

              
               } // end foreach
               
               return $menuArray;

         } // end getMenu

         
         /**
          * Return links
          * @param mixed $val 
          * @return mixed
         */
         private static function get_link($val)
         {
               // check if external link [ if pregmatch is true or matches params ]
               if(preg_match('/https?:\/\//', $val) == 1)
               {
                    return $val;

               }else{
   
                    $uArray = explode('/', $val);
                    
                    $controller_name = ucwords($uArray[0]);
                    $action_name = (isset($uArray[1])) ? $uArray[1] : '';
                    
                    # if has access we'll return Link if not we'll return false
                    if(self::hasAccess($controller_name, $action_name))
                    {
                         return PROOT . $val;

                    }

                    return false;
               }
         }




}

/*
Array
(
    [Home] => home
    [Tools] => Array
        (
            [My Tools] => tools
            [Tool 1] => tools/first
            [Tool 2] => tools/second
            [separator] => 
            [Tool 3] => tools/third
        )

    [Google] => https://www.google.com
    [PHP Docs] => http://php.net/manual/fr
    [Login] => register/login
    [Logout] => register/logout
)

*/