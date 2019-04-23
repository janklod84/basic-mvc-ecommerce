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
        
        protected static $_table = 'product_images';
        protected static $_softDelete = true;
       

        
        /**
         * Upload Product Image
         * @param int $product_id 
         * @param \App\Lib\Utilities $uploads 
         * @return 
        */
        public static function uploadProductImage($product_id, $uploads)
        {
             $path = 'uploads'. DS . 'product_images'. DS . 'product_' . $product_id . DS;
             
             foreach($uploads->getFiles() as $file)
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
                     $uploads->upload($path, $uploadName, $file['tmp_name']);
                 }
             }
        }


        /**
         * Delete images
         * @param int product_id Product id
         * @param bool $unlink if it setted true, it'll be unlin group images
         * @return void
        */
        public static function deleteImages($product_id, $unlink = false)
        {
              $images = self::find([
                  'conditions' => "product_id = ?",
                  'bind' => [$product_id]
              ]);

              foreach ($images as $image)
              {
                   $image->delete();
              }

              if($unlink)
              {
                  $dirname = ROOT . DS . 'uploads' . DS . 'product_images' . DS . 'product_'. $product_id;

                  # unlink files types
                  array_map('unlink', glob("$dirname/*.*"));

                  # remove directory
                  rmdir($dirname);
              }
        }


}