<?php 
namespace App\Models;

use Core\Model;
use Core\Validators\RequiredValidator;



class Login extends Model
{
      
      /**
       * @var string
      */
      public $username;

      /**
       * @var string
      */
      public $password;


      /**
       * @var bool
      */
      public $remember_me;

      
      /**
       * Constructor
       * @return void
      */
      public function __construct()
      {
          parent::__construct('tmp_fake');
      }


      public function validator()
      {
      	  $this->runValidation(new RequiredValidator($this, [
             'field' => 'username',  
             'msg' => 'Username is required'
      	  ]));

      	   $this->runValidation(new RequiredValidator($this, [
               'field' => 'password',  
               'msg' => 'Password is required'
      	  ]));
      }

      /**
       * Determine if is set remember me
       * @return bool
      */
      public function getRememberMeChecked()
      {
      	return $this->remember_me == 'on';
      }

}