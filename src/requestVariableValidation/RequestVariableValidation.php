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
     * RequestVariableValidatiın constructor.
     */
    protected function __construct()
    {
    }

    public function checkVariableIfExists($req_variables, $variables)
    {

        $success = true;
        $missingParameters = array();
        foreach ($variables as $key => $value) {
            if (@!array_key_exists(@$value, @$req_variables)) {
                $success = false;
                array_push($missingParameters, $value);
            }
        }
        if($success) {
            $result['status'] = 1;
        }
        else {
            $result['status'] = 0;
            $result['message'] = 'missing parameters: '.implode(", ", $missingParameters);
        }
        return $result;
    }

    public function convertToResponse($validationResult)
    {
        $response['meta']['status'] = $validationResult['status'];
        $response['meta']['message'] = $validationResult['message'];
        return $response;
    }
}