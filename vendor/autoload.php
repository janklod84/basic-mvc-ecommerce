<?php 


/**
 * Autoload classess
 * $className = Core\Session
 * $classArray = [
 *   0 => Core
 * ]
 * $class = Session
 * $subPath = core
 * 
 * @param string $className 
 * @return void
 */
function autoload($className)
{
     $classArray = explode('\\', $className); // 
     $class = array_pop($classArray); // get last element of array
     $subPath = strtolower(implode(DS, $classArray));
     
     $path = ROOT . DS . $subPath . DS . $class . '.php';
     if(file_exists($path))
     {
     	  require_once($path);
     }
}


spl_autoload_register('autoload');