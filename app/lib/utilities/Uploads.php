<?php 
namespace App\Lib\Utilities;


/**
 * @package App\Lib\Utilities\Uploads
*/
class Uploads 
{
     
        
        /**
         * @var array  $_errors             contains all errors messages
         * @var array  $_files              contains all files to upload
         * @var int    $_maxAllowedSize     maximum required size of file to upload
         * @var array  $_allowedImageTypes  allowed images
        */
	    private $_errors = [];
	    private $_files  = [];
	    private $_maxAllowedSize = 5242880;
	    private $_allowedImageTypes = [IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG];
        

        /**
         * Constructor
         * @param array $files 
         * @return void
        */
	    public function __construct($files)
	    {
	         $this->_files = self::restructureFiles($files);
	    }
        

        /**
         * Runner validation
         * @return void
        */
	    public function runValidation()
	    {
	         $this->validateSize();
	         $this->validateImageType();
	    }
        

        /**
         * Determine if has valid
         * @return bool|array
        */
	    public function validates()
	    {
	        return (empty($this->_errors)) ? true : $this->_errors;
	    }
        

        /**
         * Uploading proccess
         * move file to uploads/ file
         * @param string $bucket [image url /uploads/produc_images/product_1/]
         * @param string $name 
         * @param string $tmp 
         * @return bool
        */
 	    public function upload($bucket, $name, $tmp)
	    {
		        if(!file_exists($bucket)) 
		        {
		              mkdir($bucket, 0777, true); // mkdir($bucket, 0777, true)
		        }

		        move_uploaded_file($tmp, ROOT.DS.$bucket.$name);
	    }
        


        /**
         * Get Files
         * @return array
        */
	    public function getFiles()
	    {
	         return $this->_files;
	    }
        

        /**
         * Validator type of image
         * @return void
        */
	    protected function validateImageType()
	    {
		      foreach($this->_files as $file)
		      {
			        // checking file type
			        if(!in_array(exif_imagetype($file['tmp_name']), $this->_allowedImageTypes))
			        {
				          $name = $file['name'];
				          $msg = $name . " is not an allowed file type. Please use a jpeg, gif, or png.";
				          $this->addErrorMessage($name, $msg);
			        }
		      }
	    }
        

        /**
         * Validator size of file
         * @return void
        */
	    protected function validateSize()
	    {
		      foreach($this->_files as $file)
		      {
			        $name = $file['name'];
			        if($file['size'] > $this->_maxAllowedSize)
			        {
			            $msg = $name . " is over the max allowed size of 5mb.";
			            $this->addErrorMessage($name, $msg);
			        }
		      }
	    }
        

        /**
         * Add error message
         * @param string $name 
         * @param string $message 
         * @return void
        */
	    protected function addErrorMessage($name, $message)
	    {
		      if(array_key_exists($name,$this->_errors))
		      {
		         $this->_errors[$name] .= $this->_errors[$name] . " " . $message;

		      }else{
		       
		         $this->_errors[$name] = $message;
		      }
	    }
        

        /**
         * Restructure files to upload
         * @param array $files 
         * @return array
        */
	    public static function restructureFiles($files)
	    {
		      $structured = [];
		      foreach($files['tmp_name'] as $key => $val)
		      {
			        $structured[] = [
				          'tmp_name'=>$files['tmp_name'][$key],
				          'name'=>$files['name'][$key],
				          'size'=>$files['size'][$key],
				          'error'=>$files['error'][$key],
				          'type'=>$files['type'][$key]
			        ];
		      }
		      return $structured;
	    }
}