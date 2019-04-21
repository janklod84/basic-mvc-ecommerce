<?php 
namespace Core;


/**
 * @package Core\View
*/
class View 
{
     
	   /**
	     * @var string
	   */
		 protected $head;

		 /**
		  * @var string
		 */
		 protected $body;

		 /**
		  * @var string
		 */
		 protected $siteTitle = SITE_TITLE;

	     
	     /**
	      * @var string
	     */
		 protected $outputBuffer;


		 /**
		  * @var string
		 */
		 protected $layout = DEFAULT_LAYOUT;

         
     /**
      * Constructor
      * @return void
     */
		 public function __construct() {}
	

         
     /**
      * View render
      * @param string $viewName 
      * @return mixed
     */
		 public function render($viewName)
		 {
		 	       $viewArray = explode('/', $viewName);
             $viewString = implode(DS, $viewArray);

             $viewPath = ROOT . DS . 'app' . DS . 'views' . DS . $viewString . '.php';
             $layoutPath = ROOT . DS . 'app' . DS . 'views' . DS . 'layouts' . DS . $this->layout . '.php';

             if(file_exists($viewPath))
             {
                 	include($viewPath);
                 	
                 	if(file_exists($layoutPath))
                 	{
                 		 include($layoutPath);
                 	}

             }else{

             	    die('The view \"' . $viewName . '" does not exist.');
             }
		 }

         
     /**
      * Create content
      * @param string $type 
      * @return 
     */
		 public function content($type)
		 {
             if($type == 'head')
             {
             	  return $this->head;

             }elseif($type == 'body'){

             	  return $this->body;
             }

             return false;
		 }

         
     /**
      * start type
      * @param string $type 
      * @return void
     */
		 public function start($type)
		 {
         $this->outputBuffer = $type;
         ob_start();
		 }
         

     /**
      * end started part
      * @return void
     */
		 public function end()
		 {
    		 	 if($this->outputBuffer == 'head')
    		 	 {
    		 	 	  $this->head = ob_get_clean();

    		 	 }elseif($this->outputBuffer == 'body'){

    		 	 	  $this->body = ob_get_clean();

    		 	 }else{

    		 	 	   die('You must first run the start method.');
    		 	 }
		 }


     /**
      * Render site title
      * @return string
     */
     public function siteTitle()
     {
         return $this->siteTitle;
     }


     /**
      * set site title
      * @param string $title 
      * @return void
     */
		 public function setSiteTitle($title)
		 {
		 	    $this->siteTitle = $title;
		 }


		 /**
        * set layout
        * @param string $path 
        * @return void
     */
		 public function setLayout($path)
		 {
		 	     $this->layout = $path;
		 }

     
     /**
      * Insert some parts
      * @param string $path 
      * @return void
     */
     public function insert($path)
     {
         include ROOT . DS . 'app' . DS . 'views' . DS . $path . '.php';
     }


     
     /**
      * Insert partials
      * @param string $group
      * @param string $partial
      * @return void
     */
     public function partial($group, $partial)
     {
          include ROOT . DS . 'app' . DS . 'views' . DS . $group . DS .'partials' . DS . $partial . '.php';
     }
}