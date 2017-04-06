**sample usage**
```php
use RequestVariableValidation\RequestVariableValidation;
require_once '/vendor/autoload.php'; // composer autoloader

$APIRequestVariableValidation = RequestVariableValidation::getInstance();

$rules = array(
    "lang" => array("type"=>"letters","max_length" =>2, "min_length"=>2),
    "offset" => array("type"=>"numbers","max_length" =>2, "min_length"=>1),
    "count" => array("type"=>"numbers","max_length" =>2, "min_length"=>1)
);

$result = $APIRequestVariableValidation->checkVariableIfExists($_GET, $rules);
```

**returns**
```php
$result['status'] = 0;  // 0: Error, 1: Success
$result['message'] = "" // If an error occured
```
