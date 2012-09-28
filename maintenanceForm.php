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
    private $onSuccessRedirectPage;
    //upd
    private $updIdField;
    private $updIdValue;

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
    function setOnSuccessRedirectPage($page){
        $this->onSuccessRedirectPage=$page;
    }
    function setSuccessMessage($x){
        $this->successMessage=$x;
    }
    //upd
    function setUpdIdField($x){
        $this->updIdField=$x;
    }

    function setUpdIdValue($x){
        $this->updIdValue=$x;
    }
    function setButtonName($x){
        $this->buttonName=$x;
    }

    function getForm(){

       error_reporting(E_ALL ^ E_NOTICE);  // DON'T SHOW NOTICES

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

        $isUpd=false;
        if($this->updIdField && $this->updIdValue){
            
            $fieldsToGet="";
            foreach ($this->fields as $key => $val) {
                $fieldsToGet.=$key.',';
            }
            $fieldsToGet=substr($fieldsToGet, 0,strlen($fieldsToGet)-1);

            $query = "SELECT ".$fieldsToGet." FROM ".$this->tableName." 
                WHERE ".$this->updIdField." ='".$this->updIdValue."'";
            $updData = getWholeRow($query);            
            $isUpd=true;
        }
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
                    foreach ($val as $k => $jValue){
                        if($k=='div-atr')continue;
                        $restOfValues.=$k."='".$jValue."' ";
                    }
                        
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
                case 'select':
                        $values = $val['options'];
                        
                        $field = '';
                       // print_r($values);
                        foreach ($values as $k=>$v){
                            $optionLabel = $v['label'];
                            $optionAtr = $v['attributes'];
                            $field.= '<input type="radio" name ="'.$key.'" id="'.$k.'" value="'.$k.'" '.$optionAtr.'/>
                            <label for = "'.$k.'">'.$optionLabel.'</label>';
                        }
                        // $field.='<select/>';
                        // $res.=$field;
                        break;
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
                    $field = $leftLabel.'<input name="'.$key.'" id="'.$key.'" placeholder="'.$label.'"';
                    $restOfValues=" ";
                    if($isUpd){
                        $field.=' value="'.$updData[$key].'" ';
                    }
                    // print_r($val);
                    foreach ($val as $k => $value){
                        if($k=='div-atr')continue;
                        $restOfValues.=$k."='".$value."' ";
                    }
                    $field .= $restOfValues;
                    $field .= '/>';
                    
                    // $res.=$field;
            }
//            $res = $res.'value="'.$value.'"';
//            $res = $res.'/>';
            // $res .= '<div id="'.$key.'-div">'.$field.$format.'<br/></div>';
            $ditAtr='';
            if(isset($val['div-atr'])){
                $ditAtr=$val['div-atr'];    
            }
            $field='<div id="'.$key.'-div" '.$ditAtr.' >'.$field.$format.'<br/></div>';
            $res.=$field;
            
        }
        $buttonName = 'submit';
        if(!is_null($this->buttonName)){
            $buttonName = $this->buttonName;
        }
        $res.='<input type="hidden" name="__table" value="'.$this->tableName.'"/>';

        if($this->onSuccessRedirectPage){
            $res.='<input type="hidden" name="__redirectpage" value="'.$this->onSuccessRedirectPage.'"/>';    
        }
        if($isUpd){
            $res.='<input type="hidden" name="__operation" value="UPD"/>';
            $res.='<input type="hidden" name="__upd_idfield" value="'.$this->updIdField.'"/>';
            $res.='<input type="hidden" name="__upd_idvalue" value="'.$this->updIdValue.'"/>';
        }
        $res.='<input type="hidden" name="__success_message" value="'.$this->successMessage.'"/>';
        $res.='<input type="hidden" name="__method_to_invoke" value="'.$this->action.'"/>';
        $res.='<div id="button-div" style="text-align:center"><input type="submit" value="'.$buttonName.'" /></div><form/>';
        $res.= "</div>";
        return $res;
    }
}
?>