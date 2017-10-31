<?php

class Validate
{
    private $_passed = false,
        $_errors = array(),
        $_db = null;

    public function __construct()
    {
        //$this->_db = DB::getInstance();
        $this->_db = new ADb();

    } // end _constructor

    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                // echo "{$item} {$rule} must be {$rule_value}<br>";
                $value = trim($source[$item]);
                $item = escape($item);
                if ($rule === 'required' && empty($value)) {
                    $this->addError("{$item} is required");
                } else if (!empty($value)) {
                    switch ($rule) {

                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value}");
                            }

                            break;

                        case 'min_number':
                            if ($value < $rule_value) {
                                $this->addError("{$item} must be a minimum of {$rule_value}");
                            }
                            break;

                        case 'max_number':
                            if ($value > $rule_value) {
                                $this->addError("{$item} must be a maximum of {$rule_value}");
                            }
                            break;

                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->addError("{$item} must be a maximum of {$rule_value}");
                            }

                            break;
                        case 'matches':

                            if ($value != $source[$rule_value]) {
                                $this->addError("{$rule_value} must match {$item}");
                            }


                            break;

                        case 'agree';
                            if ($value != "1") {
                                $this->addError("You must agree to our terms and conditions in order to register.");
                            }
                            break;

                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));

                            if ($check->count()) {
                                $this->addError("{$item} already exists.");
                            }
                            break;

                        case 'emailaddress':
                            if ($value != filter_var($value, FILTER_VALIDATE_EMAIL)) {
                                $this->addError("{$item} must be email type.");
                            }
                            break;

                        case "numeric":
                            if (!is_numeric($value)) {
                                $this->addError("{$item} must be numeric");
                            }
                            break;


                    }


                }

            }
        }
        if (empty($this->_errors)) {
            $this->_passed = true;

        }
        return $this;
    } // end function check


    private function addError($error)
    {
        $this->_errors[] = $error;

    }

    public function outSideError($error)
    {
        $this->_errors[] = $error;
    }

    public function errors()
    {
        return $this->_errors;
    }

    public function passed()
    {
        return $this->_passed;
    }


}