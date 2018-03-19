<?php
/**
 * Created by PhpStorm.
 * User: alper
 * Date: 05/04/2017
 * Time: 20:05
 */

namespace RequestVariableValidation;

class RequestVariableValidation
{
    protected static $instance;

    /**
     * @return RequestVariableValidation
     */
    public static function getInstance()
    {
        return (self::$instance ?: self::$instance = new self);
    }

    /**
     * RequestVariableValidation constructor.
     */
    protected function __construct()
    {
    }

    public function checkVariableIfExists($req_variables, $variables)
    {
        $success = true;
        $result['status'] = 1;
        $missingParameters = array();
        foreach ($variables as $key => $value) {

            if ( (array) $value !== $value ) {
                $parameter = $value;
            } else {
                $parameter = $key;
                foreach($value as $validation => $rule) {
                    if(isset($req_variables[$key]))
                    {
                        $validate = self::$validation($req_variables[$key],$rule,$key);
                        if($validate['status'] != 1) {
                            $result = $validate;
                        }
                    }
                }
            }

            if (@!array_key_exists(@$parameter, @$req_variables)) {
                $success = false;
                array_push($missingParameters, $parameter);
                $result['status'] = 0;
                $result['message'] = 'missing parameters: '.implode(", ", $missingParameters);
            }
        }
        return $result;
    }

    public static function convertToResponse($validationResult)
    {
        $response['meta']['status'] = $validationResult['status'];
        $response['meta']['message'] = $validationResult['message'];
        return $response;
    }

    private static function max_length($item, $max_length,$key) {
        if(strlen($item) <= $max_length) {
            $result['status'] = 1;
        } else {
            $result['status'] = 0;
            $result['message'] = $key." can contain maximum".$max_length." characters.";
        }
        return $result;
    }

    private static function min_length($item, $min_length,$key) {
        if(strlen($item) >= $min_length) {
            $result['status'] = 1;
        } else {
            $result['status'] = 0;
            $result['message'] = $key." should contain ".$min_length." characters at minimum.";
        }
        return $result;
    }

    private static function type($item, $type,$key) {
        switch ($type) {
            case "letters":
                if(is_string($item) || !is_numeric($item)) {
                    $result['status'] = 1;
                } else {
                    $result['status'] = 0;
                    $result['message'] = $key." cannot contain any characters except ".$type.".";
                }
                break;
            case "numbers":
                if(is_numeric($item)) {
                    $result['status'] = 1;
                } else {
                    $result['status'] = 0;
                    $result['message'] = $key." cannot contain any characters except ".$type.".";
                }
                break;
            case "alphanum":
                if(ctype_alnum($item)) {
                    $result['status'] = 1;
                } else {
                    $result['status'] = 0;
                    $result['message'] = $key." should be ".$type." type.";
                }
                break;
            case "email":
                if (filter_var($item, FILTER_VALIDATE_EMAIL)) {
                    $result['status'] = 1;
                } else {
                    $result['status'] = 0;
                    $result['message'] = $key.":" . $item. " is not a valid ".$type;
                }
                break;
            case "text":
                $result['status'] = 1;
                break;

            default:
                $result['status'] = 0;
                $result['message'] = $item." can not contain any characters except ".$type.".";
                break;
        }
        return $result;
    }

    private static function validateType($item, $type) {
        if ( (string) $item !== $item ) {
            return false;
        } else{
            return true;
        }
    }
}