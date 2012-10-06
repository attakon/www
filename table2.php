<?php
/**
     *
     * @param <type> $connexion
     * @param <type> $tableName
     * @param <type> $limit
     * @param <type> $arrayColumns an array of arrays with the form <br/>
     * <b>array(<br/>
     *      array(colName, colTitle, colWidth, dt.className, [OPT|flag]))</b>
     * where flag may be either the form of [linked i type]
     * (where i is the index of the linked source and <type> the type exapmle:
     * linked 0 user, or [img imgpath ext] where imgpath is the folder where the images
     * are placed and ext the extension of the images(ie gif jpg).
     * @param <type> $condition
     */
     include_once 'CustomTags.php';
class RCTable{
    /**
     *
     * @var <type> XD
     */
    private $tableName;
    private $condition;
    private $connexion;
    private $arrayColumns;
    private $tableTitle;
    private $footer;
    private $tableAtr;
    private $showLineBreaks;

    public function __construct($connexion, $tableName, $arrayColumns, $condition) {
        $this->connexion = $connexion;
        $this->tableName = $tableName;
        $this->condition = $condition;
        $this->arrayColumns = $arrayColumns;
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
    function showLineBreaks($showLineBreaks){
       $this->showLineBreaks=$showLineBreaks;
    }

    function getTable(){

        // error_reporting(E_ALL ^ E_NOTICE);  // DON'T SHOW NOTICES

        $query = "SELECT ";
        foreach ($this->arrayColumns as $col => $keys) {
            $query .= $this->arrayColumns[$col][0].", ";
        }
        $query = substr($query, 0, strlen($query)-2);
        $query.=" FROM ".$this->tableName." ".$this->condition;


        // echo $query;        
        $rsTop = fetchResultSet($this->connexion,$query);
        echo mysql_error($this->connexion); //DEBUG

        $cl = 'class= "tr_Par"';
        $cl2 = 'class= "tr_Impar"';

        $clRC ='class = "tr_RightCorner"';
        $clHeaderTN = 'class = "tr_headerTN"';
        
        $clTN='class = "tr_TeamName"';

        $table = "
            <table class='rCTable' $this->tableAtr align =\"center\" title ='$this->tableTitle' border=\"0\" style=\"border-collapse: collapse\" $cl>";
        // $header = '
        //         <tr>
        //             <td '.$classLeftCorner.'></td>';
        $header = "<tr $clHeaderTN>";
        $nroColumnsToShow=0;
        $totalWidth=0;
        foreach ($this->arrayColumns as $key=>$keys) {
            switch ($this->arrayColumns[$key][2]) {
                case -2:
                     $header .= "
                    <td $clHeaderTN >".$this->arrayColumns[$key][1]."</td>";
                    $nroColumnsToShow++;
                    // $totalWidth+=$this->arrayColumns[$key][2];
                    break;
                case -1: //hidden

                    break;
                default: //valid 
                     $header .= "
                    <td $clHeaderTN width =".$this->arrayColumns[$key][2].">".$this->arrayColumns[$key][1]."</td>";
                    $nroColumnsToShow++;
                    $totalWidth+=$this->arrayColumns[$key][2];
                    break;
            }
            // if($this->arrayColumns[$key][2]!=-1){ // don't show hidden Columns
               
            // }else if($this->arrayColumns[$key][2]!=-1){ // don't show hidden Columns
            //     $header .= "
            //         <td $clHeaderTN width =".$this->arrayColumns[$key][2].">".$this->arrayColumns[$key][1]."</td>";
            //     $nroColumnsToShow++;
            //     $totalWidth+=$this->arrayColumns[$key][2];
            // }
        }
        // $header.="
        //             <td class = 'right'></td>
        //         </tr>";
        $header.="</tr>";
        // $title= "
        //         <tr>
        //             <td $classLeftCorner ></td>
        //             <td $clHeaderTN colspan='$nroColumnsToShow'>$this->tableTitle </td>
        //             <td class = \"right\"></td>
        //         </tr>";

        $title= "
                <tr>
                    <td $clHeaderTN colspan='$nroColumnsToShow'>$this->tableTitle </td>
                </tr>";

        if($this->tableTitle!=null){
            $table.=$title;
        }else{
            $table.=$header;
        }
        $col=1;
        while($data = mysql_fetch_row($rsTop)){
            $table .="
                <tr ".(($col%2==0)?$cl:$cl2).">";
                    // <td $clLeft></td>";

            foreach ($data as $i=>$keys) {
                $atr = $this->arrayColumns[$i][3];
                $atr = "";
                if(isset($this->arrayColumns[$i]['td_atr'])){
                    $atr = $this->arrayColumns[$i]['td_atr'];
                }
                if(isset($this->arrayColumns[$i]['type']) && 
                    $this->arrayColumns[$i]['type']!=null && $this->arrayColumns[$i]['type']!=""){
                    $splited = explode(" ", $this->arrayColumns[$i]['type']);
                    $kind = $splited [0];
                    $field="";
                    if($kind=='replacement'){
                        $linkExpression = $this->arrayColumns[$i]['value'];
                        
                        $result = '';
                        for($i=0; $i<strlen($linkExpression);$i++){
                            if($i>0 && $linkExpression[$i]=='#' && $linkExpression[$i+1]=='{'){
                                $i++;
                                $number = '';
                                while($linkExpression[++$i]!='}'){
                                    $number .= $linkExpression[$i];
                                }                                
                                $result .= $data[$number];
                            }else 
                                $result .= $linkExpression[$i];
                        }

                        // preg_match("{(?P<digit>\d+)}", $linkExpression, $matches);                        
                        $field = $result;
                    }else if($kind=='linked'){
                        $id = $splited [1];
                        $type = $splited [2];
                        $username = $data[$i];
                        $userId=$data[$id];
                        $field = rCLink(".", $userId, $username , $type);
                    
                    }else if ($kind =='img'){
                        $path = $splited [1];
                        $ext = $splited [2];
                        $img = $path.'/'.$data[$i].'.'.$ext;
                        if(file_exists($img ))
                            $field = "<img src='$img'>";
                    }else if($kind=='date'){
                        $spl = explode('-', $data[$i]);
                        $year = $spl[0];
                        $month= $spl[1];
                        $day= $spl[2];
                        $field = getSpanishDateShort($day, $month, $year);
                    }else if($kind=='time'){
                        $spl = explode(':', $data[$i]);
                        $hou= $spl[0];
                        $min= $spl[1];                        
                        $field = $hou.":".$min;
                    }
                    // $table.= "<td $atr width=\"".$this->arrayColumns[$i][2]."\">$field</td>";
                    $table.= "<td $atr >$field</td>";
                }else {
                    if($this->arrayColumns[$i][2]!=-1){                        
                        $dataF=str_replace("<", "&lt;",$data[$i]);
                        $dataF=str_replace(">", "&gt;",$dataF);
                        if($this->showLineBreaks==true){
                            $dataF=str_replace("\n", "<br/>", $dataF);
                        }
                        $table.= "<td $atr > ".$dataF."</td>";
                    }
                }
            }
            $table.="</tr>";
            $col++;
        }
        $table.="
                <tr style='border-bottom-width: 1px'></tr>
                <tr>
                    <td class='tr_footerTN'  align=\"right\" colspan=\"".($nroColumnsToShow+2)."\"> ".$this->footer."
                    </td>
                </tr>
            </table>";
//        mysql_close($this->connexion);
        return $table;
    }
}

?>
