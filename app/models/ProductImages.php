<?php 
namespace App\Models;

use Core\Model;
use Core\H;


class ProductImages extends Model
{

      
      /**
       * @var int $id
       * @var string $url
       * @var int $product_id
       * @var int $deleted
      */
      public $id;
      public $url;
      public $product_id;
      public $name;
      public $deleted = 0;
      

      /**
       * Constructor
       * @return void
      */
      public function __construct()
      {  
          $table = 'product_images';
          parent::__construct($table);
      }

      
      /**
       * Validation images
       * Ex: 5 megabytes to bytes = 5 * 1000 * 1000 [ 1mb = 1Kb = 1000 b]
       * @param array $images 
       * @return void
      */
      public function validateImages($images)
      {
      	 $files = self::restructureFiles($images); /*  H::debug($files); */
             $errors = [];
             $maxsize = 5242880; // 52428, bytes (mb = 5242880 / 1000)
             
             # constantes predefinis dans PHP [ voir doc exif_imagetype]
             # https://www.php.net/manual/fr/function.exif-imagetype.php
             $allowedTypes = [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG];

             foreach($files as $file)
             {
                  $name = $file['name'];
                  
                  // check filesize
                  if($file['size'] > $maxsize)
                  {
                       $errors[$name] = $name . ' is over the max allowed size of 5mb';
                  }

                  // check file type
                  if($file['tmp_name'] && !in_array(exif_imagetype($file['tmp_name']), $allowedTypes))
                  {
                       $errors[$name] = $name ." is not an allowed file type. Please use a jpeg, gif, or png";
                  }
             }

             /* H::debug($errors); */

             return (empty($errors)) ? true : $errors;
      }

      
      /**
       * Upload Product Image
       * @param int $product_id 
       * @param array $files 
       * @return 
      */
      public static function uploadProductImage($product_id, $files)
      {
           $path = 'uploads'. DS . 'product_images'. DS . 'product_' . $product_id . DS;
           
           foreach($files as $file)
           {
               $parts = explode('.', $file['name']);
               $ext = end($parts);
               $hash = sha1(time(). $product_id . $file['tmp_name']);
               $uploadName = $hash . '.' . $ext;
               $image = new self();
               $image->url = $path . $uploadName;
               $image->name = $uploadName;
               $image->product_id = $product_id;

               if($image->save())
               {
                    if(!file_exists($path))
                    {
                         mkdir($path, 0777, true);
                    }
                    move_uploaded_file($file['tmp_name'], ROOT . DS . $image->url);
               }
           }
      }

      
      /**
       * Restructure data files
       * @param array $files 
       * @return 
      */
      public static function restructureFiles($files)
      {
           $structured = [];
           foreach($files['tmp_name'] as $key => $value)
           {
               $structured[] = [
                  'tmp_name' => $files['tmp_name'][$key],
                  'name'     => $files['name'][$key],
                  'size'     => $files['size'][$key],
                  'error'    => $files['error'][$key],
                  'type'     => $files['type'][$key]
               ];
           }

           return $structured;
      }

}