<?php 
namespace Core;


use App\Models\Users;

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
			public static function currentPage() 
			{
			    $currentPage = $_SERVER['REQUEST_URI'];
			    if($currentPage == PROOT || $currentPage == PROOT. strtolower(DEFAULT_CONTROLLER) .'/index') {
			      $currentPage = PROOT . strtolower(DEFAULT_CONTROLLER);
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


			public static function buildMenuListItems($menu,$dropdownClass="")
			{
			    ob_start();
			    $currentPage = self::currentPage();
			    foreach($menu as $key => $val):
			      $active = '';
			      if($key == '%USERNAME%')
			      {
			         $key = (Users::currentUser()) ? "Hello " .Users::currentUser()->fname : $key;
			      }
			      if(is_array($val)): ?>
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$key?> <span class="caret"></span></a>
			          	<ul class="<?=$dropdownClass?>">
			            <?php foreach($val as $k => $v):
			              $active = ($v == $currentPage)? 'active':''; ?>
			              <?php if($k == 'separator'): ?>
			                <li role="separator" class="divider"></li>
			              <?php else: ?>
			                <li><a class="<?= $active ?>" href="<?= $v ?>"><?= $k ?></a></li>
			              <?php endif; ?>
			            <?php endforeach; ?>
			          </ul>
			         </li>
				      <?php else:
				        $active = ($val == $currentPage)? 'active':''; ?>
				        <li><a class="<?= $active ?>" href="<?= $val ?>"><?= $key ?></a></li>
				      <?php endif; ?>
				    <?php endforeach;
				    return ob_get_clean();
  		  }
}