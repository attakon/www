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

    public function __construct($tableName, $fields, $action, $buttonName, $successMessage) {
        $this->tableName = $tableName;
        $this->fields = $fields;
        $this->action=$action;
        $this->buttonName=$buttonName;
        $this->successMessage=$successMessage;

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
        $res = '<form action="'.$action.'" METHOD="POST">';

        foreach ($this->fields as $key => $val) {
            $type=$val;
            $label = $key;
            $format ='';
            $value = '';
            if(is_array($val)){
                if(isset($val['label']))
                    $label=$val['label'];
                $type=$val['type'];
                if(isset($val['format']))
                    $format=$val['format'];
                if(isset($val['value']))
                    $val=$val['value'];
            }
//            echo $type;
            switch($type){
                case 'checkbox': 
                    $leftLabel ='<label>'.$label.'<label/>';
                    $res = $res.$leftLabel.'<input placeholder="'.$label.'" type='.$type.' name="'.$key.'" ';
                    $res = $res.'value="'.$value.'"';
                    $res = $res.'/>';
                    break;
                case 'list': 
//                    $leftLabel =; 
                    $table = $val['list']['table'];
                    $idfield = $val['list']['idField'];
                    $labelField = $val['list']['labelField'];
                    
                    $condition = $val['list']['condition'];
                    $query = 'SELECT '.$idfield.','.$labelField.' FROM '.$table.' '.$condition;
                    
                    $options = getRowsInArray($query);
                    $res = $res.'<label>'.$label.'<label/><select name="'.$key.'">';
//                    print_r($options);
                    foreach ($options as $k=>$v){
                        $res.= '<option value="'.$v[$idfield].'">';
                        $res.= $v[$labelField].'</option>';
                    }
                    $res.='<select/>';
                    break;
                 case 'hidden':
                    $res = $res.'<input type="'.$type.'" name="'.$key.'" ';
                    $res = $res.'value="'.$value.'" />';
                    break;
                  default:
                    $leftLabel ='<label>'.$label.'<label/>';
                    $res = $res.$leftLabel.'<input placeholder="'.$label.'" type="'.$type.'" name="'.$key.'" ';
                    $res = $res.'value="'.$value.'"';
                    $res = $res.'/>';
            }
//            $res = $res.'value="'.$value.'"';
//            $res = $res.'/>';
            $res = $res.$format.'<br/>';
            
        }
        $buttonName = 'submit';
        if(!is_null($this->buttonName)){
            $buttonName = $this->buttonName;
        }
        $res.='<input type="hidden" name="__table" value="'.$this->tableName.'"/>';
        $res.='<input type="hidden" name="__success_message" value="'.$this->successMessage.'"/>';
        $res.='<input type="hidden" name="__method_to_invoke" value="'.$this->action.'"/>';
        $res=$res.'<input type="submit" value="'.$buttonName.'" /><form/>';
        return $res;
    }
}
?>