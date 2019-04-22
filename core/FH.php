<?php
namespace Core;
use Core\Session;


/**
 * if you use bootstrap 4
 * change class ="help-block" by class="invalid-feedback"
 * for showing best feedback message
 * @package Core\FH
 */
class FH 
{

      /**
       * Creates an input block to be used in a form
       * @method inputBlock
       * @param  string     $type       type of input ie text, password, phone ...
       * @param  string     $label      The label that will be displayed for the input
       * @param  string     $name       The id and name of the input will be set to this value
       * @param  string     $value      (optional) The value of the input
       * @param  array      $inputAttrs (optional) attributes of input
       * @param  array      $divAttrs   (optional) attributes of surrounding div
       * @param  array      $errors     (optional) array of all form errors
       * @return string                 returns an html string for input block
       */
      public static function inputBlock(
        $type, 
        $label, 
        $name, 
        $value='', 
        $inputAttrs=[], 
        $divAttrs=[],
        $errors=[]
      ){
          $inputAttrs = self::appendErrorClass($inputAttrs,$errors,$name,'is-invalid');
          $divString = self::stringifyAttrs($divAttrs);
          $inputString = self::stringifyAttrs($inputAttrs);
          $id = $name;
          $name = ($type == 'file' && array_key_exists('multiple', $inputAttrs))? $name.'[]' : $name;
          $html = '<div' . $divString . '>';
          $html .= '<label class="control-label" for="'.$id.'">'.$label.'</label>';
          $html .= '<input type="'.$type.'" id="'.$id.'" name="'.$name.'" value="'.$value.'"'.$inputString.' />';
          $html .= '<span class="help-block">'.self::errorMsg($errors,$id).'</span>';
          $html .= '</div>';
          return $html;
      }

      /**
       * Creates a submit input
       * @method submitTag
       * @param  string    $buttonText Text that will be displayed on button
       * @param  array     $inputAttrs (optional) Attributes of input
       * @return string                Returns an html string for submit button
       */
      public static function submitTag($buttonText, $inputAttrs=[])
      {
          $inputString = self::stringifyAttrs($inputAttrs);
          $html = '<input type="submit" value="'.$buttonText.'"'.$inputString.' />';
          return $html;
      }

      /**
       * Creates a submit block
       * @method submitBlock
       * @param  string      $buttonText Text that will be displayed on button
       * @param  array       $inputAttrs (optional) Attributes for input
       * @param  array       $divAttrs   (optional) Atributes for surrounding div
       * @return string                  Returns an html string for submit block
       */
      public static function submitBlock($buttonText, $inputAttrs=[], $divAttrs=[])
      {
          $divString = self::stringifyAttrs($divAttrs);
          $inputString = self::stringifyAttrs($inputAttrs);
          $html = '<div'.$divString.'>';
          $html .= '<input type="submit" value="'.$buttonText.'"'.$inputString.' />';
          $html .= '</div>';
          return $html;
      }

      /**
       * Creates a checkbox block
       * @method checkboxBlock
       * @param  string        $label      Text of label of checkbox
       * @param  string        $name       id and name of checkbox
       * @param  boolean       $checked    (optional) Checks the checkbox on true
       * @param  array         $inputAttrs (optional) Attributes for checkbox
       * @param  array         $divAttrs   (optional) Attributes for surrounding div
       * @param  array         $errors     (optional) Pass in all form errors
       * @return string                    Returns an html string for checkbox block
       */
      public static function checkboxBlock(
        $label,
        $name,
        $checked=false,
        $inputAttrs=[],
        $divAttrs=[],
        $errors=[]
      ){

        $divAttrs = self::blockErrors($divAttrs,$errors,$name);
        $divString = self::stringifyAttrs($divAttrs);
        $inputString = self::stringifyAttrs($inputAttrs);

        $checkString = ($checked)? ' checked="checked"' : '';
        $html = '<div'.$divString.'>';
        $html .= '<label for="'.$name.'" class="control-label">'.$label.' <input type="checkbox" id="'.$name.'" name="'.$name.'" value="on"'.$checkString.$inputString.'>';
        $html .= '<span class="help-block">'.self::errorMsg($errors,$name).'</span>';
        $html .= '</label>';
        $html .= '</div>';
        return $html;
      }

      /**
       * Creates a Select Block
       * @method selectBlock
       * @param  string      $label      Text for label
       * @param  string      $name       id and name for select
       * @param  string      $value      value of select
       * @param  array       $options    options for select the ['value for option'=>'Display of Option']
       * @param  array       $inputAttrs (optional) Attributes for select
       * @param  array       $divAttrs   (optional) Attributes for surrounding div
       * @param  array       $errors     (optional) Pass in form errors
       * @return string                  Returns an html string for select block
       */
      public static function selectBlock(
        $label,
        $name,
        $value,
        $options,
        $inputAttrs=[],
        $divAttrs=[],
        $errors=[]
      ){
        $divAttrs = self::blockErrors($divAttrs,$errors,$name);
        $divString = self::stringifyAttrs($divAttrs);
        $inputString = self::stringifyAttrs($inputAttrs);
        $html = '<div' . $divString . '>';
        $html .= '<label for="'.$name.'" class="control-label">' . $label . '</label>';
        $html .= '<select id="'.$name.'" name="'.$name.'" value="'.$value.'"'.$inputString.'>'.self::optionsForSelect($options).'</select>';
        $html .= '<span class="help-block">'.self::errorMsg($errors,$name).'</span>';
        $html .= '</div>';
        return $html;
      }

      /**
       * Creates a textarea block
       * @method textareaBlock
       * @param  string        $label      Text for label
       * @param  string        $name       id and name for textarea
       * @param  string        $value      value of text area
       * @param  array         $inputAttrs (optional) Attributes for textarea
       * @param  array         $divAttrs   (optional) Attributes for surrounding div
       * @param  array         $errors     (optional) Pass in the form errors
       * @return string                    Returns an html string for textarea block
       */
      public static function textareaBlock(
        $label,
        $name,
        $value,
        $inputAttrs=[],
        $divAttrs=[],
        $errors=[]
      ){
        $divAttrs = self::blockErrors($divAttrs,$errors,$name);
        $divString = self::stringifyAttrs($divAttrs);
        $inputString = self::stringifyAttrs($inputAttrs);
        $html = '<div' . $divString . '>';
        $html .= '<label for="'.$name.'" class="control-label">' . $label . '</label>';
        $html .= '<textarea id="'.$name.'" name="'.$name.'"'.$inputString.'>'.$value.'</textarea>';
        $html .= '<span class="help-block">'.self::errorMsg($errors,$name).'</span>';
        $html .= '</div>';
        return $html;
      }

      /**
       * Turns an array of attributes into an attribute string to be used in an html tag
       * @method stringifyAttrs
       * @param  array          $attrs ['class'=>'foo'] will return class="foo"
       * @return string                string to be used in html tag
       */
      public static function stringifyAttrs($attrs)
      {
            $string = '';
            foreach($attrs as $key => $val){
              $string .= ' ' . $key . '="' . $val . '"';
            }
            return $string;
      }

      /**
       * Creates a csrf token and stores it in $_SESSION
       * @method generateToken
       * @return string        value of the token set in $_SESSION
       */
      public static function generateToken()
      {
          $token = base64_encode(openssl_random_pseudo_bytes(32));
          Session::set('csrf_token',$token);
          return $token;
      }

      /**
       * Check to see if the csrf token is valid
       * @method checkToken
       * @param  string     $token value that was posted
       * @return boolean           returns whether or not the token was correct
       */
      public static function checkToken($token)
      {
        return (Session::exists('csrf_token') && Session::get('csrf_token') == $token);
      }

      /**
       * Creates a hidden input to be used in a form for csrf
       * @method csrfInput
       * @return string    return html string for form input
       */
      public static function csrfInput()
      {
         return '<input type="hidden" name="csrf_token" id="csrf_token" value="'.self::generateToken().'" />';
      }

      /**
       * Cleans user input with htmlentities
       * @method sanitize
       * @param  string   $dirty string of dirty user input
       * @return string          string of cleaned user input
       */
      public static function sanitize($dirty) 
      {
          return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
      }

      /**
       * Creates a styled list of form errors used to display on front-end to user.
       * @method displayErrors
       * @param  array         $errors pass in the form errors ['field'=>'message']
       * @return string                return html string with styled form errors.
       */
      public static function displayErrors($errors) 
      {
          $hasErrors = (!empty($errors))? ' has-errors' : '';
          $html = '<div class="form-errors"><ul class="bg-danger'.$hasErrors.'">';
          foreach($errors as $field => $error) 
          {
              $html .= '<li class="text-danger">'.$error.'</li>';
              $html .= '<script>jQuery("document").ready(function(){jQuery("#'.$field.'").parent().closest("div").addClass("has-error");});</script>';
          }
          $html .= '</ul></div>';
          return $html;
      }

      
      /**
         * adds an error class name to attrs if there is an error
         * @method appendErrorClass
         * @param  array       $divAttrs default div attributes array
         * @param  array       $errors   pass in the form errors
         * @param  string      $name     name of the field
         * @param  string      $class    class name to be applied
         * @return array                 returns an array with an appended class in the div attributes array
      */
      public static function appendErrorClass($attrs,$errors,$name,$class)
      {
            if(array_key_exists($name,$errors))
            {
                if(array_key_exists('class',$attrs))
                {
                    $attrs['class'] .= " " . $class;
                }else{
                    $attrs['class'] = $class;
                }
            }
            return $attrs;
        }
        

      /**
       * Adds a class to the surrounding div array if there are errors. This is used to style the form elements
       * @method blockErrors
       * @param  array       $divAttrs default div attributes array
       * @param  array       $errors   pass in the form errors
       * @param  string      $name     name of the field
       * @return array                 returns an array with an appended class in the div attributes array
       */
      public static function blockErrors($divAttrs,$errors,$name)
      {
          if(array_key_exists($name,$errors))
          {
              if(array_key_exists('class',$divAttrs))
              {
                  $divAttrs['class'] .= " has-error";

              }else{

                  $divAttrs['class'] = "has-error";
              }
          }
          return $divAttrs;
      }

      /**
       * Returns an error message for the input
       * @method errorMsg
       * @param  array    $errors pass in the form errors
       * @param  string   $name   id and name of the input
       * @return string           returns the error message from the form errors
       */
      public static function errorMsg($errors,$name)
      {
          $msg = (array_key_exists($name,$errors))? $errors[$name] : "";
          return $msg;
      }

      /**
       * Turns an associative array into options for a select element
       * @method optionsForSelect
       * @param  array            $options ['option value'=>'Label for option']
       * @return string                    returns html string to be used in select html elements
       */
      public static function optionsForSelect($options)
      {
            $html = "";
            foreach($options as $value => $display){
              $html .= '<option value="'.$value.'">'.$display.'</option>';
            }
            return $html;
      }

}
