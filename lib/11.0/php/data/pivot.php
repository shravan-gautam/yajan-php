<?php

class Pivot
{
    var $recordset;
    var $id;
    var $dataColumn;
    var $keyColumn;
    var $valueColumn;
    function __construct(Recordset $r)
    {
        $this->recordset = $r;
    }

    function addDataColumn()
    {
    	$arguments = func_get_args();
    	foreach ($arguments as $key => $value) {
    		# code...
    		$this->dataColumn[count($this->dataColumn)]=strtoupper($value);
    	}
        
    }
    function setKeyColumn($column)
    {
        $this->keyColumn= strtoupper($column);
    }
    function setValueColumn($column)
    {
        $this->valueColumn = strtoupper($column);
    }


    function toRecordset()
    {
        $d = $this->recordset->data;
        $row = array();
        for ($i=0; $i <count($d) ; $i++) { 
            $t = array();
            for($j=0;$j<count($this->dataColumn);$j++)
            {
                $t[$this->dataColumn[$j]] = $d[$i][$this->dataColumn[$j]];
            }
            $row[count($row)]=$t;
            # code...
        }
        
        //$row = array_unique($row);
        $row = array_map("unserialize", array_unique(array_map("serialize", $row)));
        $row = array_values($row);

        $col = array();
        for ($i=0; $i <count($d) ; $i++) { 
            $col[count($col)]=$d[$i][$this->keyColumn];
            # code...
        }
        //$col = array_unique($col);
        $col = array_map("unserialize", array_unique(array_map("serialize", $col)));
        $col = array_values($col);

        $f = new MoneyFormat();
        $sum= array();


        $out = new Recordset();
        for($y = 0;$y<count($this->dataColumn);$y++)
        {
            $out->addColumns($this->dataColumn[$y]);
        }

        for($j=0;$j<count($col);$j++)
		{
			$out->addColumns($col[$j]);
		}
		$out->addColumns("total");

		for($i=0;$i<count($row);$i++)
        {
        	$ar = array();
        	$rowTotal  = 0;
        	for($y = 0;$y<count($this->dataColumn);$y++)
            {
                $ar[$this->dataColumn[$y]]=$row[$i][$this->dataColumn[$y]];
            }
            for($j=0;$j<count($col);$j++)
            {
            	$cv = false;
            	for($x =0 ;$x<count($d);$x++)
				{
					if($d[$x][$this->dataColumn[0]]==$row[$i][$this->dataColumn[0]] && $d[$x][$this->keyColumn]==$col[$j])
					{
						$ar[$col[$j]]=$d[$x][$this->valueColumn];
						$rowTotal  = $rowTotal+$d[$x][$this->valueColumn];
						$cv = true;
					}
				}
				if(!$cv)
				{
					$ar[$col[$j]] = 0;
				}
            }
            $ar["TOTAL"] = $rowTotal;
            $out->add($ar);
        }
        return $out;
    }
}    
?>