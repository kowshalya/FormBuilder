<?php

class formbuilder {

    private $method = "post"; //The form Method type by default
    private $attributes = array(); //form attributes	    
    private $formName = "form";

    //setting up global elements wile form class to be called
    function __construct($method = "post", $attributes = null, $formName = NULL) {
        $this->method = $method;
        $this->formName = ($formName != NULL) ? $formName : $this->formName;
        if (is_array($attributes)) {
            $this->attributes = $attributes;
        } else {
            
        }
        $this->startForm();
    }

    private function startForm() {
        echo"<div class='$this->formName'>";
        echo"<form method=" . $this->method . " ";
        foreach ($this->attributes as $key => $value) {
            echo $key . "=\"" . $value . "\"";
        }
        echo " >" . PHP_EOL;
    }

    private function createAttributes($iattributes) {
        $el = '';
        foreach ($iattributes as $key => $value) {
            $el .=$key . "=\"" . $value . "\" ";
        }
        return $el;
    }

    private function has_spaces($text) {
        if (preg_match("/ /", $text)) {
            return(true);
        }
        return(false);
    }

    function input($label, $iattributes) {
        $id = (isset($iattributes['name'])) ? $iattributes['name'] : $label;
        $el = "<div class='form-group'>" . PHP_EOL;
        if (isset($label))
            $el .="<label for='" . $id . "'>$label</label> " . PHP_EOL; //label for the field
        $el .="<input id='" . $this->formName . "-" . $this->trimlabel($id) . "'";
        if (isset($iattributes)) {
            if (is_array($iattributes)) {
                $el .= $this->createAttributes($iattributes);
            }
        }
        $el .="/>" . PHP_EOL;
        $el .="</div>" . PHP_EOL;

        return $el;
    }

    function select($label = 'Select', $iattributes = null, $options = null, $opt = false) {
        $id = (isset($iattributes['name'])) ? $iattributes['name'] : $label;
        $el = "<div class='form-group'>" . PHP_EOL;
        $el .="<label for='" . $id . "'>$label</label> " . PHP_EOL;
        $el .="<select id='" . $this->formName . "-" . $this->trimlabel($id) . "'";
        if (isset($iattributes)) {
            if (is_array($iattributes)) {
                $el .= $this->createAttributes($iattributes);
            }
        }
        $el .=">" . PHP_EOL;
        if (isset($options)) {//options for the select
            if (is_array($options)) {
                foreach ($options as $key => $value) {
                    if ($opt) {
                        if (is_array($value)) {

                            $el .="<optgroup label='$key'>";
                            foreach ($value as $key1 => $val1) {
                                $el .="<option ";
                                if (is_array($val1)) {
                                    $el .= $this->createAttributes($val1);
                                } else {
                                    $el .=" value=\"" . $val1 . "\" ";
                                }
                                $el .=" >";
                                $el .= $key1;
                                $el .="</option>" . PHP_EOL;
                            }
                            $el .="</optgroup>";
                        }
                    } else {
                        $el .="<option ";
                        if (is_array($value)) {
                            $el .=$this->createAttributes($value);
                        } else {
                            $el .=" value=\"" . $value . "\" ";
                        }
                        $el .=" >";
                        $el .= $key;
                        $el .="</option>" . PHP_EOL;
                    }
                }
            }
        }
        $el .="</select>" . PHP_EOL;
        $el .="</div>" . PHP_EOL;

        return $el;
    }

    function radio($label = "radio", $options, $iattributes = null) {
        $id = (isset($iattributes['name'])) ? $iattributes['name'] : $label;
        $el = "<div class='form-group'>" . PHP_EOL;
        $el .="<label for='$id'>$label</label>" . PHP_EOL;

        if (isset($options)) {
            if (is_array($options)) {
                foreach ($options as $key => $value) {
                    $el .= "<label for='$this->formName-$id' class='radio'>";
                    PHP_EOL;
                    $el .="<input id='$this->formName-$id' type='radio' value='" . $value . "'";
                    if (isset($iattributes)) {
                        if (is_array($iattributes)) {
                            $el .=$this->createAttributes($iattributes);
                        }
                    }
                    $el .= "/>" . (is_numeric($key) ? $value : $key) . "</label>" . PHP_EOL;
                }
            } else {
                trigger_error("you must pass radio options arguments as array");
                return;
            }
        }
        $el .="</div>" . PHP_EOL;
        return $el;
    }

    function check($label = "checkbox", $options, $iattributes = null) {

        $id = (isset($iattributes['name'])) ? $iattributes['name'] : $label;
        $el = "<div class='form-group'>" . PHP_EOL;
        $el .="<label for='$id'>$label</label>" . PHP_EOL;

        if (isset($options)) {
            if (is_array($options)) {
                foreach ($options as $key => $value) {
                    $el .="<div class='" . (strlen($key) > 12 && $this->has_spaces($key) ? "checks" : "checks1") . "'>";
                    $el .= "<label  for='$this->formName-$id' class='checkbox'>";
                    $el .="<input id='$this->formName-$id' type='checkbox' value='" . $value . "'";
                    if (isset($iattributes)) {
                        if (is_array($iattributes)) {
                            $el .= $this->createAttributes($iattributes);
                        }
                    }
                    $el .= "/>" . (is_numeric($key) ? $value : $key) . "</label>" . PHP_EOL;

                    PHP_EOL;
                    $el .="</div>" . PHP_EOL;
                }
            } else {
                trigger_error("you must pass radio options arguments as array");
                return;
            }
        }
        $el .="</div>" . PHP_EOL;
        return $el;
    }

    function textarea($label = 'Textarea', $iattributes = null) {
        $id = (isset($iattributes['name'])) ? $iattributes['name'] : $label;
        $el = "<div class='form-group'>" . PHP_EOL;
        $el .="<label for='" . $id . "'>$label</label> " . PHP_EOL; //label for the field         
        $el .="<textarea id='" . $this->formName . "-" . $this->trimlabel($id) . "'";
        if (isset($iattributes)) {
            if (is_array($iattributes)) {
                $el .=$this->createAttributes($iattributes);
            }
        }
        $el .=">";
        $el .="</textarea>" . PHP_EOL;
        $el .="</div>" . PHP_EOL;

        return $el;
    }

    function submit($label, $iattributes = null) {
        $id = (isset($iattributes['name'])) ? $iattributes['name'] : $label;
        $el = "<input  value='$label' type='submit' id='$this->formName-$id'";
        if (isset($iattributes)) {
            if (is_array($iattributes)) {
                $el .= $this->createAttributes($iattributes);
            }
        }
        $el .="/>";
        return $el;
    }

    function trimlabel($label) {
        return strtolower($label) ? strtolower(str_replace(' ', '-', $label)) : false;
    }

    function end() {
        echo"</form>" . PHP_EOL;
        echo"</div>" . PHP_EOL;
    }

}

?>