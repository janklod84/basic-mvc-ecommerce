<?php 
namespace Core;



/**
 * Helper
 * @package Core\H
*/
class H 
{


			/**
			 * public static function s for pretty print array data
			 * @param array $data
			 * @param bool $die
			*/
			public static function  dnd($data)
			{
			    echo '<pre>';
			    var_dump($data);
			    echo '</pre>';
			    die();
			}


            /**
             * Debug data
             * @param mixed $data 
             * @param bool $die 
             * @return void
            */
		    public static function  debug($data, $die = true)
		    {
				 echo '<pre>';
			     print_r($data);
			     echo '</pre>';
			     if($die) die;
		    }



			/**
			 * Return current page
			 * @return string
			*/
			public static function  currentPage()
			{
			    $currentPage = $_SERVER['REQUEST_URI'];

			    if($currentPage == PROOT || $currentPage == PROOT . 'home/index')
			    {
			         $currentPage = PROOT . 'home';
			    }

			    return $currentPage;
			}


			/**
			 * Get object properties
			 * @param object $obj 
			 * @return mixed
			*/
			public static function  getObjectProperties($obj)
			{
			    return get_object_vars($obj);
			}
}