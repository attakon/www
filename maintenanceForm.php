<?php

include_once 'utils/DBUtils.php';
include_once 'utils/StringUtils.php';


class RCMaintenanceForm{
    
    private $fields;
    private $tableTitle;
    private $footer;
    private $tableAtr;
    private $action;
    private $buttonName;
    private $successMessage;

    public function __construct($tableName, $fields, $action, $buttonName, $successMessage, $divAtributes='', $formAtributes='') {
        $this->tableName = $tableName;
        $this->fields = $fields;
        $this->action=$action;
        $this->buttonName=$buttonName;
        $this->successMessage=$successMessage;
        $this->divAtributes=$divAtributes;
        $this->formAtributes=$formAtributes;
    }
    function setTitle($title){
        $this->tableTitle=$title;
    }
    function setFooter($x){
        $this->footer=$x;
    }
    function setTableAtr($x){
        $this->tableAtr=$x;
    }

    function getForm(){

//        error_reporting(E_ALL ^ E_NOTICE);  // DON'T SHOW NOTICES

        if(is_null($this->action)){
            $action = 'maintenanceFormController.php';
        }else{
            if(endsWith($this->action, '.php')){
                $action = $this->action;
            }else{
                $action = $_SERVER['REQUEST_URI'];
            }
        }
        $res = '<div '.$this->divAtributes.' >';
        $res .= '<form action="'.$action.'" METHOD="POST" '.$this->formAtributes.'>';

        foreach ($this->fields as $key => $val) {
            $label = $key;
            $format ='';
            $value = '';
            if(is_array($val)){
                if(isset($val['label']))
                    $label=$val['label'];
                if(isset($val['format']))
                    $format=$val['format'];
            }
            
//            echo $type;
            $type=$val['type'];
            switch($type){
                case 'checkbox': 
                    $leftLabel ='<label>'.$label.'</label>';
                    $field = $leftLabel.'<input placeholder="'.$label.'" type='.$type.' name="'.$key.'" ';
                    $restOfValues=" ";
                    foreach ($val as $jKey => $jValue)
                        $restOfValues.=$jKey."='".$jValue."' ";
                    $field .= $restOfValues;
                    $field .='/>';

                    // $res.=$field;
                    break;
                case 'list': 
//                    $leftLabel =; 
                    $table = $val['list']['table'];
                    $idfield = $val['list']['idField'];
                    $labelField = $val['list']['labelField'];
                    
                    $condition = $val['list']['condition'];
                    $query = 'SELECT '.$idfield.','.$labelField.' FROM '.$table.' '.$condition;
                    
                    $options = getRowsInArray($query);
                    $field = '<label>'.$label.'</label><select name="'.$key.'">';
//                    print_r($options);
                    foreach ($options as $k=>$v){
                        $field.= '<option value="'.$v[$idfield].'">';
                        $field.= $v[$labelField].'</option>';
                    }
                    $field.='<select/>';
                    // $res.=$field;
                    break;
                case 'hard-list': 
//                    $leftLabel =; 
                    $values = $val['values'];
                    
                    $field = '<label>'.$label.'</label>
                        <select name="'.$key.'">';
//                    print_r($options);
                    foreach ($values as $k=>$v){
                        $field.= '<option value="'.$k.'">'.$v.'</option>';
                    }
                    $field.='<select/>';
                    // $res.=$field;
                    break;
                case 'hidden':
                    $field = '<input type="'.$type.'" name="'.$key.'" ';
                    foreach ($val as $jKey => $jValue)
                        $restOfValues.=$jKey.'="'.$jValue.'"';
                    $field = $field.$restOfValues."/>";
                    // $res.=$field;
                    break;
                default:
                    $leftLabel ='<label>'.$label.'</label>';
                    $field = $leftLabel.'<input name="'.$key.'" placeholder="'.$label.'"';
                    $restOfValues=" ";
                    print_r($val);
                    foreach ($val as $key => $value)
                        $restOfValues.=$key."='".$value."' ";
                    $field .= $restOfValues;
                    $field .= '/>';
                    // $res.=$field;
            }
//            $res = $res.'value="'.$value.'"';
//            $res = $res.'/>';
            $res .= '<div>'.$field.$format.'</div><br/>';
            
        }
        $buttonName = 'submit';
        if(!is_null($this->buttonName)){
            $buttonName = $this->buttonName;
        }
        $res.='<input type="hidden" name="__table" value="'.$this->tableName.'"/>';
        $res.='<input type="hidden" name="__success_message" value="'.$this->successMessage.'"/>';
        $res.='<input type="hidden" name="__method_to_invoke" value="'.$this->action.'"/>';
        $res.='<div id="button-div" style="text-align:center"><input type="submit" value="'.$buttonName.'" /></div><form/>';
        $res.= "</div>";
        return $res;
    }
}
?>