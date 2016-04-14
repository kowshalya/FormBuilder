<?PHP

class ValidatorObj {

    var $variable_name;
    var $validator_string;
    var $error_string;

}

/** Default error messages */
define("E_VAL_REQUIRED_VALUE", "Please enter the value for %s");
define("E_VAL_MAXLEN_EXCEEDED", "Maximum length exceeded for %s.");
define("E_VAL_MINLEN_CHECK_FAILED", "Please enter input with length more than %d for %s");
define("E_VAL_ALNUM_CHECK_FAILED", "Please provide an alpha-numeric input for %s");
define("E_VAL_NUM_CHECK_FAILED", "Please provide numeric input for %s");
define("E_VAL_ALPHA_CHECK_FAILED", "Please provide alphabetic input for %s");
define("E_VAL_EMAIL_CHECK_FAILED", "Please provide a valida email address");
define("E_VAL_EQELMNT_CHECK_FAILED", "Value of %s should be same as that of %s");
define("E_VAL_SHOULD_SEL_CHECK_FAILED", "You must select %s");

/**
 * FormValidator: The main class that does all the form validations
 * */
class FormValidator {

    var $validator_array;
    var $error_hash;            
    var $num_errors = 0;

    function FormValidator() {
        $this->validator_array = array();
        $this->error_hash = array();
         $this->num_errors = count($this->error_hash);
            
    }

    function addValidation($variable, $validator, $error) {
        $validator_obj = new ValidatorObj();
        $validator_obj->variable_name = $variable;
        $validator_obj->validator_string = $validator;
        $validator_obj->error_string = $error;
        array_push($this->validator_array, $validator_obj);
    }

    function GetErrors() {
        return $this->error_hash;
    }

    function GetFlashMessages() {
        $this->flash_message = $this->error_hash;
        return $this->error_hash;
    }

    function ValidateForm() {
        $bret = true;

        $error_string = "";
        $error_to_display = "";


        if (strcmp($_SERVER['REQUEST_METHOD'], 'POST') == 0) {
            $form_variables = $_POST;
        } else {
            $form_variables = $_GET;
        }

        $vcount = count($this->validator_array);


        foreach ($this->validator_array as $val_obj) {
            if (!$this->ValidateObject($val_obj, $form_variables, $error_string)) {
                $bret = false;
                $this->error_hash[$val_obj->variable_name] = $error_string;
            }
        }


        return $bret;
    }

    function ValidateObject($validatorobj, $formvariables, &$error_string) {
        $bret = true;

        $splitted = explode("=", $validatorobj->validator_string);
        $command = $splitted[0];
        $command_value = '';

        if (isset($splitted[1]) && strlen($splitted[1]) > 0) {
            $command_value = $splitted[1];
        }

        $default_error_message = "";

        $input_value = "";

        if (isset($formvariables[$validatorobj->variable_name])) {
            $input_value = $formvariables[$validatorobj->variable_name];
        }

        $bret = $this->ValidateCommand($command, $command_value, $input_value, $default_error_message, $validatorobj->variable_name, $formvariables);


        if (false == $bret) {
            if (isset($validatorobj->error_string) &&
                    strlen($validatorobj->error_string) > 0) {
                $error_string = $validatorobj->error_string;
            } else {
                $error_string = $default_error_message;
            }
        }//if
        return $bret;
    }

    function validate_req($input_value, &$default_error_message, $variable_name) {
        $bret = true;
        if (!isset($input_value) ||
                strlen($input_value) <= 0) {
            $bret = false;
            $default_error_message = sprintf(E_VAL_REQUIRED_VALUE, $variable_name);
        }
        return $bret;
    }

    function validate_maxlen($input_value, $max_len, $variable_name, &$default_error_message) {
        $bret = true;
        if (isset($input_value)) {
            $input_length = strlen($input_value);
            if ($input_length > $max_len) {
                $bret = false;
                $default_error_message = sprintf(E_VAL_MAXLEN_EXCEEDED, $variable_name);
            }
        }
        return $bret;
    }

    function validate_minlen($input_value, $min_len, $variable_name, &$default_error_message) {
        $bret = true;
        if (isset($input_value)) {
            $input_length = strlen($input_value);
            if ($input_length < $min_len) {
                $bret = false;
                $default_error_message = sprintf(E_VAL_MINLEN_CHECK_FAILED, $min_len, $variable_name);
            }
        }
        return $bret;
    }

    function test_datatype($input_value, $reg_exp) {
        if (ereg($reg_exp, $input_value)) {
            return false;
        }
        return true;
    }

    function validate_email($email) {
        return eregi("^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$", $email);
    }

    function ValidateCommand($command, $command_value, $input_value, &$default_error_message, $variable_name, $formvariables) {
        $bret = true;
        switch ($command) {
            case 'req': {
                    $bret = $this->validate_req($input_value, $default_error_message, $variable_name);
                    break;
                }

            case 'maxlen': {
                    $max_len = intval($command_value);
                    $bret = $this->validate_maxlen($input_value, $max_len, $variable_name, $default_error_message);
                    break;
                }

            case 'minlen': {
                    $min_len = intval($command_value);
                    $bret = $this->validate_minlen($input_value, $min_len, $variable_name, $default_error_message);
                    break;
                }

            case 'alnum': {
                    $bret = $this->test_datatype($input_value, "[^A-Za-z0-9]");
                    if (false == $bret) {
                        $default_error_message = sprintf(E_VAL_ALNUM_CHECK_FAILED, $variable_name);
                    }
                    break;
                }

            case 'numeric': {
                    $bret = $this->test_datatype($input_value, "[^0-9]");
                    if (false == $bret) {
                        $default_error_message = sprintf(E_VAL_NUM_CHECK_FAILED, $variable_name);
                    }
                    break;
                }

            case 'alpha': {
                    $bret = $this->test_datatype($input_value, "[^A-Za-z]");
                    if (false == $bret) {
                        $default_error_message = sprintf(E_VAL_ALPHA_CHECK_FAILED, $variable_name);
                    }
                    break;
                }

            case 'email': {
                    if (isset($input_value) && strlen($input_value) > 0) {
                        $bret = $this->validate_email($input_value);
                        if (false == $bret) {
                            $default_error_message = E_VAL_EMAIL_CHECK_FAILED;
                        }
                    }
                    break;
                }




            case "eqelmnt": {

                    if (isset($formvariables[$command_value]) &&
                            strcmp($input_value, $formvariables[$command_value]) == 0) {
                        $bret = true;
                    } else {
                        $bret = false;
                        $default_error_message = sprintf(E_VAL_EQELMNT_CHECK_FAILED, $variable_name, $command_value);
                    }
                    break;
                }
        }//switch
        return $bret;
    }

//validdate command
}

?> 