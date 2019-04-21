<?php 
namespace Core;


use Core\Session;


/**
 * FH (Form Helpers)
 * @package Core\FH
*/
class FH 
{


		/**
		 * Input Form
		 * @param string $type 
		 * @param string $label 
		 * @param string $name 
		 * @param string $value 
		 * @param array $inputAttrs 
		 * @param array $divAttrs 
		 * @return string
		 */
		public static function inputBlock(
			$type, 
			$label, 
			$name, 
			$value = '', 
			$inputAttrs = [], 
			$divAttrs = []
		)
		{
			 $divString   = self::stringifyAttrs($divAttrs);
			 $inputString = self::stringifyAttrs($inputAttrs);
		     $html  = '<div'. $divString . '>';
		     $html .= '<label for="'. $name .'">'. $label .'</label>';
		     $html .= '<input type="'.$type.'" id="'.$name.'" name="'.$name.'" value="'.$value.'"'. $inputString.'/>';
		     $html .= '</div>';

		     return $html;
		}


		/**
		 * Generate input submit
		 * @param string $buttonText 
		 * @param array $inputAttrs 
		 * @return string
		 */
		public static function submitTag($buttonText, $inputAttrs=[])
		{
			$inputString = self::stringifyAttrs($inputAttrs);
			$html = '<input type="submit" value="'.$buttonText.'"'. $inputString.' />';
			return $html;
		}



		/**
		 * Generate button submit
		 * @param string $buttonText 
		 * @param array $inputAttrs 
		 * @param array $divAttrs 
		 * @return string
		 */
		public static function submitBlock($buttonText, $inputAttrs=[], $divAttrs=[])
		{
			$divString   = self::stringifyAttrs($divAttrs);
			$inputString = self::stringifyAttrs($inputAttrs);
			$html = '<div'.$divString.'>';
			$html .= '<input type="submit" value="'.$buttonText.'"'. $inputString.' />';
			$html .= '</div>';
			return $html;
		}

        /**
         * Generate input checkbox
         * @param string $label 
         * @param string $name 
         * @param bool $checked
         * @param array $inputAttrs
         * @param array $divAttrs
         * @return string
        */
        public static function checkboxBlock($label, $name, $checked = false, $inputAttrs = [], $divAttrs = [])
        {
              $divString   = self::stringifyAttrs($divAttrs);
			  $inputString = self::stringifyAttrs($inputAttrs);
			  $checkString = ($checked) ? ' checked="checked"' : '';
			  $html = '<div'. $divString.'>';
			  $html .= sprintf('<label for="%s">%s <input type="checkbox" id="%s" name="%s" value="on"%s></label>', $name, $label, $name, $name, $checkString.$inputString);
			  /* 
			    $html .= '<label for="'.$name.'">'. $label .' <input type="checkbox" id="'.$name.'" name="'. $name .'" value="on"'. $checkString . $inputString'></label>';
			  */
			  $html .= '</div>';
			  return $html;
        }



		/**
		 * StringiFy Attributes
		 * @param array $attrs
		 */
		public static function stringifyAttrs($attrs)
		{
		     $string = '';
		     foreach($attrs as $key => $value)
		     {
		     	$string .= ' ' . $key . '="'. $value.'"';
		     }
		     return $string;
		}

        
        /**
         * Generate Token 
         * CSRF [Crost Security Request Form]
         * @return string
         */
		public static function generateToken()
		{
            $token = base64_encode(openssl_random_pseudo_bytes(32));
            Session::set('csrf_token', $token);
            return $token;
		}

        
        /**
         * Check Token if exist and matched given token $token
         * @param string $token 
         * @return bool
        */
		public static function checkToken($token)
		{
			return (Session::exists('csrf_token') && Session::get('csrf_token') == $token);
		}

        
        /**
         * Generate input hidden for csrf
         * @return string
         */
		public static function csrfInput()
		{
			return '<input type="hidden" name="csrf_token" id="csrf_token" value="'. self::generateToken() .'" />';
		}


	    /**
		 * Sanitize data
		 * @param mixed $dirty
		*/
		public static function  sanitize($dirty)
		{
			return htmlentities($dirty, ENT_QUOTES, "UTF-8");
		}


		/**
		 * Sanitize posted data and return them
		 * And conserve values
		 * 
		 * @param array $post from request $_POST
		 * @return array
		*/ 
		public static function  posted_values($post)
		{
		    $clean_array = [];

		    foreach($post as $key => $value)
		    {
		        $clean_array[$key] = self::sanitize($value);
		    }

		    return $clean_array;
		}
        

        /**
          * Display errors
          * @param string $errors
          * @return string
        */
        public static function displayErrors($errors = [])
        {
	           $hasErrors = !(empty($errors)) ? ' has-errors' : '';
	       	   $html = '<div class="form-errors"><ul class="bg-danger'. $hasErrors .'">';

	       	   foreach ($errors as $field => $error) 
	       	   {
	                $html .= '<li class="text-danger">'. $error .'</li>';
	                $html .= '<script>jQuery("document").ready(function(){jQuery("#'. $field .'").parent().closest("div").addClass("has-error");});</script>';
	       	   }

	       	   $html .= '</ul></div>';
	       	   return $html;
        }

}