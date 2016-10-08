<?php
class JQChart
{
	var $id;
	var $width;
	var $height;
	var $value;
	var $format;
	var $fill;
	var $stackSeries;
	var $animate;
	var $keys;
	var $smooth;
	var $directon;
	var $fillToZero;
	var $xLable;
	var $yLable;
	var $marker;
	var $axisFontSize;
	var $axisAngal;
	var $edit;
	var $cursor;
	var $recordet;
	var $keyColumn;
	var $showDataLabels;
	var $xFormatString;
	var $yFormatString;
	var $series;
	var $legend;
	var $legendLocation;
	var $xAngle;
	var $yAngle;
	var $zoom;
	var $showXLable;
	var $showYLable;
	function JQChart($id)
	{
		$this->showYLable=true;
		$this->showXLable=true;
		$this->id = $id;
		$this->width = "400px";
		$this->height = "300px";
		$this->value = array();
		$this->format = "line";
		$this->legendLocation="nw";
		$this->fill=false;
		$this->stackSeries=false;
		$this->animate = true;
		$this->keys=array();
		$this->smooth=true;
		$this->direction = 'vertical';
		$this->fillToZero=true;
		$this->xLable="";
		$this->yLable="";
		$this->marker = false;
		$this->axisFontSize="12px";
		$this->axisAngle  = 0;
		$this->edit = false;
		$this->cursor=false;
		$this->setRecordset = null;
		$this->keyColumn = null;
		$this->showDataLabels = false;
		$this->xFormatString='';
		$this->yFormatString='%.2f';
		$this->legend =true;
		$this->series = array();
		$this->xAngle=-30;
		$this->yAngle=0;
		$this->zoom=false;
	}
	function setXValueFormat($f)
	{
		if($f=="string")
		{
			$this->xFormatString='';
		}
		else if($f=="int")
		{
			$this->xFormatString='%d';
		}
		else if($f=="float")
		{
			$this->xFormatString='%.2f';
		}
	}
	function setYValueFormat($f)
	{
		if($f=="string")
		{
			$this->yFormatString='%s';
		}
		else if($f=="int")
		{
			$this->yFormatString='%d';
		}
		else if($f=="float")
		{
			$this->yFormatString='%.2f';
		}
	}
	function showDataLabels($v)
	{
		$this->showDataLabels = $v;
	}
	function setRecordset($r)
	{
		$this->recordset = $r;
		for($i=0;$i<count($r->columns);$i++)
		{
			$this->series[$i]=$r->columns[$i]->name;
		}
		
	}
	function keyColumn($col)
	{
		$this->keyColumn = strtoupper($col);
	}
	function cursor($v)
	{
		$this->cursor = $v;
	}
	function edit($v)
	{
		$this->edit = $v;
	}
	function axisAngle($v)
	{
		$this->axisAngle = $v;
	}
	function axisFontSize($v)
	{
		$this->axisFontSize = $v;
	}
	function marker($v)
	{
		$this->marker = $v;
	}
	function xLable($lable)
	{
		$this->xLable = $lable;
	}
	function yLable($lable)
	{
		$this->yLable = $lable;	
	}
	function fillToZero($v)
	{
		$this->fillToZero = $v;
	}
	function direction($d)
	{
		$this->direction = $d;
	}
	function smooth($v)
	{
		$this->smooth=$v;
	}
	function keys($k)
	{
		$this->keys=$k;
	}
	function animate($v)
	{
		$this->animate = $v;
	}
	function stack($v)
	{
		$this->stackSeries=$v;
	}
	function fill($v)
	{
		$this->fill=$v;
	}
	function format($f)
	{
		$this->format = $f;
	}
	function showYLable($v)
	{
		$this->showYLable=$v;
	}
	function showXLable($v)
	{
		if($v==true)
		{
			$this->showXLable="true";
		}
		else
		{
			$this->showXLable="false";
		}
	}	
	function addValues($v)
	{
		
		$temp = array();
		$c = count($this->value);
		$temp['name']=$this->id."_V".$c;
		$temp['value']=$v;
		$this->value[$c]=$temp;
	}
	function width($w)
	{
		$this->width = $w;
	}
	function height($h)
	{
		$this->height = $h;
	}
	
	function rander()
	{
		global $LIB_PATH,$FREAMWORK_PATH,$libVersion,$ONLINE_LIB_PATH;
		$jsLibPath = "/$FREAMWORK_PATH/lib$libVersion";
		$jsLibPath = "/$ONLINE_LIB_PATH";
		
		if($this->recordset!=null && $this->keyColumn!=null)
		{
			$col = array();
			for($i=0;$i<$this->recordset->countColumns;$i++)
			{
				$val = array();
				for($j=0;$j<$this->recordset->count;$j++)
				{
					if($this->recordset->columns[$i]->getName()==$this->keyColumn)
					{
						$col[$j]=$this->recordset->data[$j][$this->keyColumn];
					}
					else
					{
						if($this->format!="circle")
						{
							$val[$j]=$this->recordset->data[$j][$this->recordset->columns[$i]->getName()];
						}
						else
						{
							$val[$this->recordset->data[$j][$this->keyColumn]]=$this->recordset->data[$j][$this->recordset->columns[$i]->getName()];
						}
					}
				}
				
				if(count($val )>0)
				{
					$this->addValues($val);
				}
			}
			$this->keys($col);
		}
		
		
		$format = $this->format;
	echo '    <script class="include" type="text/javascript" src="'.$jsLibPath.'/js/jqplot/jquery.jqplot.min.js"></script>
    <script type="text/javascript" src="'.$jsLibPath.'/js/syntaxhighlighter/scripts/shCore.min.js"></script>
    <script type="text/javascript" src="'.$jsLibPath.'/js/syntaxhighlighter/scripts/shBrushJScript.min.js"></script>
    <script type="text/javascript" src="'.$jsLibPath.'/js/syntaxhighlighter/scripts/shBrushXml.min.js"></script>
<!-- Additional plugins go here -->

  <script class="include" type="text/javascript" src="'.$jsLibPath.'/js/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
  <script class="include" type="text/javascript" src="'.$jsLibPath.'/js/jqplot/plugins/jqplot.highlighter.min.js"></script>
  <script class="include" type="text/javascript" src="'.$jsLibPath.'/js/jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
  <script class="include" type="text/javascript" src="'.$jsLibPath.'/js/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
  <script class="include" type="text/javascript" src="'.$jsLibPath.'/js/jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>  
  
  <script class="include" type="text/javascript" src="'.$jsLibPath.'/js/jqplot/plugins/jqplot.cursor.min.js"></script>  
  <script class="include" type="text/javascript" src="'.$jsLibPath.'/js/jqplot/plugins/jqplot.highlighter.min.js"></script>  
  <script class="include" type="text/javascript" src="'.$jsLibPath.'/js/jqplot/plugins/jqplot.dragable.min.js"></script>  
  <script class="include" type="text/javascript" src="'.$jsLibPath.'/js/jqplot/plugins/jqplot.trendline.min.js"></script>  
  
  <link rel="stylesheet" type="text/css" href="'.$jsLibPath.'/css/jqplot/jquery.jqplot.min.css" />
	<link rel="stylesheet" type="text/css" href="'.$jsLibPath.'/css/jqplot/jq1.css" />
  ';
  echo '<script class="include" type="text/javascript" src="'.$jsLibPath.'/js/jqplot/plugins/jqplot.barRenderer.min.js"></script>';
  
		if($format=="circle")
		{
			echo '<script class="include" type="text/javascript" src="'.$jsLibPath.'/js/jqplot/plugins/jqplot.pieRenderer.min.js"></script>';
		}
		else if($format=="bar")
		{
			echo '<script class="include" type="text/javascript" src="'.$jsLibPath.'/js/jqplot/plugins/jqplot.pieRenderer.min.js"></script>';
			echo '<script class="include" type="text/javascript" src="'.$jsLibPath.'/js/jqplot/plugins/jqplot.pointLabels.min.js"></script>';
		}
		echo "
		<div id='$this->id' style='width:$this->width;height:$this->height;'></div>
		<script  type='text/javascript'>
	";	
		

	$fld = "";
	if($format=="line" || $format=="bar")
	{
		for($i=0;$i<count($this->value);$i++)
		{
			$str = $this->value[$i]['value'];
			$str = join(",",$str);
			echo "var ".$this->value[$i]['name']." = [$str];";
			$fld .= $this->value[$i]['name'].",";
		}
	}
	else if($format=="circle")
	{
		$str = "[";

		foreach($this->value[0]['value'] as $k => $v)
		{
			$str .= "['$k',$v],";
		}
		
		$str = rtrim($str,",");
		$str .= "]";
		$fld = $this->id."_V0";
		echo "var $fld = $str;";
		
	}
	$keyList = "";
	
	for($i=0;$i<count($this->keys);$i++)
	{

			$keyList .= "'".$this->keys[$i]."',";

	}
	
	
	
	$keyList=rtrim($keyList,",");
	$fld = rtrim($fld,",");
    
 
	 if($this->stackSeries==true)
	 {
		$stackSeries="true";
	 }
	 else
	 {
		$stackSeries="false";
	 }
	 $animate="false";
	 if($this->animate)
	 {
		$animate="true";
	 }
	 $fill="false";
	 if($this->fill)
	 {
		$fill="true";
	 }
	 $smooth="false";
	 if($this->smooth)
	 {
		$smooth="true";
	 }
	 
	 
	 $randerer = "";
	 if($format == "circle")
	 {
		$randerer =',renderer:$.jqplot.PieRenderer';
	 }
	 else if($format=="bar")
	 {
		$randerer =',renderer:$.jqplot.BarRenderer';
	 }
	 
	 $direction = '';
	 if($this->direction !="vertical")
	 {
		$direction = "barDirection: '$this->direction',";
	 }
	 $fillToZero = 'false';
	 if($this->fillToZero)
	 {
		$fillToZero = 'true';
	 }
	 $marker = "false";
	 if($this->marker)
	 {
		$marker="true";
	 }
	 
	 $edit="false";
	 if($this->edit)
	 {
		$edit="true";
	 }
	 
	 $cursor="false";
	 if($this->cursor)
	 {
		$cursor = "true";
	 }
	 
	 $showDataLabels = "false";
	 if($this->showDataLabels)
	 {
		$showDataLabels = "true";
	 }
	 $series = "";
	 if(count($this->series)>0)
	 {
		for($i=0;$i<count($this->series);$i++)
		{
			if(strtoupper($this->keyColumn)!=strtoupper($this->series[$i]))
			{
				$series .= "{label:'".$this->series[$i]."'},";
			}
		}

			
	 }
	 $zoom="false";
	 if($this->zoom)
	 {
		 $zoom="true";
	 }
	 $legend = "";
	 if($this->legend)
	 {
		$legend = "legend: {
                show: true,
				location:'$this->legendLocation',
				placement: 'outsideGrid'
            },";
	 }
     echo "
	 $(document).ready(function(){
	 $.jqplot.config.enablePlugins = $edit;
    var plot1b = $.jqplot('$this->id',[$fld],{
       stackSeries: $stackSeries,
       
	   animateReplot: true,
	   animate:$animate,
	   gridPadding: {top:0, bottom:38, left:0, right:0},
       seriesDefaults: {
           fill: $fill,
		   showMarker: $marker,
		   rendererOptions: {
				smooth: $smooth,
				trendline:{ show:false }, 
				showDataLabels: $showDataLabels,
				$direction
				fillToZero: $fillToZero,
			}$randerer,
			pointLabels: { show: $showDataLabels }
       },
	    series:[
            $series
        ],
		axes: {
				
				   xaxis: {
							label:'$this->xLable',
							labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
							labelOptions:{
									fontSize:'$this->axisFontSize',
								},
							tickRenderer:$.jqplot.CanvasAxisTickRenderer,
							tickOptions:{
								show:$this->showXLable,
								
								formatString:'$this->xFormatString',
								angle:$this->xAngle,
							},
						";
						if($format!="circle" && $this->direction=="vertical")
						{
							echo "ticks: [$keyList],
							renderer: $.jqplot.CategoryAxisRenderer,";
						}
						echo"
				   },
				   
				   yaxis: {
							label:'$this->yLable',
							labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
							tickRenderer:$.jqplot.CanvasAxisTickRenderer,
							labelOptions:{
									fontSize:'$this->axisFontSize',
							},
							tickOptions:{
								show:$this->showYLable,
								labelPosition: 'middle',
								formatString:'$this->yFormatString',
								angle:$this->yAngle,
							},
						";
						if($format!="circle" && $this->direction=="horizontal")
						{
							echo "ticks: [$keyList],
							renderer: $.jqplot.CategoryAxisRenderer,";
						}
						echo "
				   }
				 
		},
		
		";
		
	   echo "
	   $legend
	    
     highlighter: {
	     show:true,
		 showMarker:true
     },
     cursor: {
         show: $cursor,
		 zoom:$zoom,
         looseZoom: $zoom
     }
	  
    });
     
   
});
		
	";
	
	
	
	echo '</script>';
	}
}
?>