function Collection()
{
	this.collection = Array();
	this.add = function(obj)
	{
		this.collection[this.collection.length]=obj;
	}
	this.length = function()
	{
		return this.collection.length;
	}
	this.get = function(index)
	{
		return this.collection[index];
	}
	this.getType= function(index)
	{
		return typeof this.collection[index];
	}
	this.getCSVSctring = function()
	{
		return this.collection.join(",");
	}
	this.getIndex = function(name)
	{
		return this.collection.indexOf(name);
	}
	this.remove = function(index)
	{
		this.collection.splice(index, 1);
	}
}

function ContextMenu(id)
{
	this.id = id;
	this.items = null;
	this.setItemObject = function(item)
	{
		this.items = item;
	}
	this.refresh = function()
	{
		$(".contextMenu_"+this.id).contextMenu(this.items,{theme:"vista"}); 
	}
}
function GLOBAL_VARS()
{
	this.keyUpTimestamp=0;
}
GLOBAL = new GLOBAL_VARS();
function DataBox(id)
{
	this.id = id;
	this.string = "";
	this.data = null;
	this.enc = "enc1";
	this.parse = function(str)
	{
		str = str.split(":");
		str = str[1];
		var o = unserialize(Base64.decode(str));
		this.id = o.id;
		this.version=o.version;
		this.data=o.data;
		this.timestamp=o.time;
		this.enc= o.enc;
	}
	this.get = function(key)
	{
		return this.data[key];
	}
	this.add = function(key,val)
	{
		this.data[key]=val;
	}
}
document._oldGetElementById = document.getElementById;
document.getElementById = function(elemIdOrName) 
{
    var result = document._oldGetElementById(elemIdOrName);
	
    if (result) 
	{
		result.rawValue=null;
		result.keystrok=null;
		result.priviusEl=null;
		result.nextEl=null;
		
		result.highlights = function(str)
		{
			var re = new RegExp(str, 'gi');
			var tagList = 'SPAN,DIV';
			if(tagList.indexOf(result.tagName)>-1)
			{
				var obj = null;
				if(result.rawValue)
				{
					obj = result.rawValue;
				}
				else if(result.get("VAL"))
				{
					obj = result.get("VAL");
				}
				result.innerHTML = obj;
				if(result.val().match(re) != null && obj)
				{
					result.innerHTML = obj.replace(re,'<span class="UITextHighlight">'+str+'</span>');;
				}

				
			}
		}
		result.set = function(key,val)
		{
			htmlObjectDataSet.set(result.id,key.toUpperCase(),val);
		}
		result.get = function(key)
		{
			return htmlObjectDataSet.get(result.id,key.toUpperCase());
		}
		result.addInTabIndex = function()
		{
			var tabIndexAllowTag = 'INPUT,TEXTAREA,SELECT,BUTTON';
			if(tabIndexAllowTag.indexOf(result.tagName)>-1)
			{
				var index = htmlObjectDataSet.addObjectOnTabIndex(result.id);
				result.tabIndex = index;
			}
		}
		result.setTabIndex = function(index)
		{
			result.tabIndex = index;
			result.set("tabIndex",index);
		}
		result.val = function()
		{
			if(arguments.length>0)
			{
				result.set("VAL",arguments[0]);
				result.rawValue = arguments[0];
				if(document.getElementById(result.id+"_valueBox")!=null)
				{
					document.getElementById(result.id+"_valueBox").val(arguments[0]);
				}
				switch(result.tagName)
				{
					case "IMG":
						return result.src=arguments[0];
						break;
					case "SPAN":
						result.value=arguments[0];
						return result.innerHTML=arguments[0];
						break;						
					case "A":
						result.value=arguments[0];
						return result.innerHTML=arguments[0];
						break;
					case "BUTTON":
						result.value=arguments[0];
						return result.innerHTML=arguments[0];
						break;
					case "TEXTAREA":
						result.value=arguments[0];
						return result.innerHTML=arguments[0];
						break;
					case "INPUT":
						if(result.type=="checkbox")
						{
							result.value=arguments[0];
							if(arguments[0]=="0")
							{
								arguments[0]=false;
							}
							else
							{
								arguments[0]=true;
							}
							return result.checked=arguments[0];
						}
						else
						{
							return result.value=arguments[0];
						}
						break;
					default:
						return result.value=arguments[0];
						break;
				}
				
			}
			else
			{
				var tagList = 'SPAN,DIV';
				if(tagList.indexOf(result.tagName)>-1)
				{
					if(result.rawValue==null)
					{
						if(result.get("VAL"))
						{
							return result.get("VAL");
						}
						else 
						{
							switch(result.tagName)
							{
								case "IMG":
									return result.src;
									break;
								case "SPAN":
									return result.innerHTML;
									break;				
								case "A":
									return result.innerHTML;
									break;
								case "BUTTON":
									return result.innerHTML;
									break;
								case "TABLE":
									return result.getXml();
									break;
								case "TEXTAREA":
									return result.value;
									break;
								case "INPUT":
									if(result.type=="checkbox")
									{
										return result.checked;
									}
									else
									{
										return result.value;
									}
									break;
								default:
									return result.value;
									break;
							}
						}
					}
					else
					{
						return result.rawValue;
					}
				}
				else
				{
					switch(result.tagName)
					{
						case "IMG":
							return result.src;
							break;
						case "SPAN":
							return result.innerHTML;
							break;				
						case "A":
							return result.innerHTML;
							break;
						case "BUTTON":
							return result.innerHTML;
							break;
						case "TABLE":
							return result.getXml();
							break;
						case "TEXTAREA":
							return result.value;
							break;
						case "INPUT":
							if(result.type=="checkbox")
							{
								return result.checked;
							}
							else
							{
								return result.value;
							}
							break;
						default:
							return result.value;
							break;
					}				
				}
			}
		}
		if(result.tagName=="INPUT" || result.tagName=="SELECT" || result.tagName=="TEXTAREA")
		{
			result.addEventListener("change", function(event)
			{
				var cl = result.className;
				if(cl.indexOf("required")>-1)
				{
					if(result.val()!="")
					{
						result.label("");
					}
					else
					{
						result.label("This is required");
						result.labelColor("#cc0000");
					}
				}
				else
				{
					result.label("");
				}
				
			}, false);
			result.addEventListener("keypress", function(e)
			{
				//tiKeyPressed(e);
				var tmp = new Date().getTime();
				
				evt = e || window.event;
				var charCode = evt.which || evt.keyCode;
				
				
					if(charCode==9 && this.get("nextId") !=null)
					{
						document.getElementById(this.get("nextId")).focus();
						stopEvent(e);
					}
					else if(charCode==16 && this.get("priviusId") !=null)
					{
						document.getElementById(this.get("priviusId")).focus();
						stopEvent(e);
					}
				
				
			 	GLOBAL.keyUpTimestamp = tmp;
				
			},false);
		}
		result.showDescription = function(description)
		{
			addElementDescription(this.id,description);
		}
		result.hideDescription = function()
		{
			removeElementDescription(this.id);
		}
		result.disable = function()
		{
			if(arguments[0]==true || arguments.length==0)
			{
				if(document.getElementById(result.id+"_valueBox")==null)
				{
					result.disabled = true;
					var tmp = document.createElement("input");
					tmp.name = result.name;
					tmp.id = result.id+"_valueBox";
					tmp.value = result.val();
					tmp.type="hidden";
					result.parentNode.appendChild(tmp);
				}
				else
				{
					document.getElementById(result.id+"_valueBox").val(result.val());
				}
			}
			else
			{
				result.enable(true);
			}
		}
		result.enable = function()
		{
			if(arguments[0]==true || arguments.length==0)
			{
				result.disabled = false;
				if(document.getElementById(result.id+"_valueBox")!=null)
				{
					result.parentNode.removeChild(document.getElementById(result.id+"_valueBox"));
				}
			}
			else
			{
				result.disable(true);
			}
		}
		result.readonly = function(val)
		{
			return result.readOnly=val;
		}
		result.isEmail = function()
		{
			var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			var rec = regex.test(result.val());
			if(!rec)
			{
				result.label("Invalid email");
				result.labelColor("#cc0000");
				return false;
			}
			else
			{
				return true;
			}
		}
		result.isNumber = function()
		{
			if(isNaN(Number(result.val())))
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		
		result.label = function()
		{
			if(arguments.length>0)
			{
				var obj = document.getElementById(this.id+"_label");
				if(obj != null)
				{
					obj.innerHTML = arguments[0];
					return obj;
				}
			}
			else
			{
				var obj = document.getElementById(this.id+"_label");
				return obj.innerHTML;
			}
		}
		result.labelColor = function()
		{
			if(arguments.length>0)
			{
				var obj = document.getElementById(this.id+"_label");
				if(obj)
				{
				obj.style.color = arguments[0];
				return obj;
				}
				else
				{return null;}
			}
			else
			{
				var obj = document.getElementById(this.id+"_label");
				if(obj)
				{
				return obj.style.color;
				}
				else
				{return null;}
				
			}
		}
		
		if(result.tagName=="SELECT")
		{
			result.addOption = function(lable,value)
			{
				var obj = document.createElement("option");
				obj.value=value;
				obj.innerHTML=lable;
				result.appendChild(obj);
			}
			result.removeAll = function()
			{
				result.innerHTML="";
			}
		}
		result.nextElementId = function(id)
		{
			this.set("nextId",id);
		}
		
		result.priviusElementId = function(id)
		{
			this.set("priviusId",id);
		}
		result.getNextElementId = function()
		{
			return this.nextEl;
		}
		result.getPriviusElementId = function()
		{
			return this.priviusEl;
		}

    }
	
    return result;
};
function stopEvent(e)
{
				 var evt = e ? e:window.event;
				 if (evt.stopPropagation)    evt.stopPropagation();
				 if (evt.cancelBubble!=null) evt.cancelBubble = true;
				 if (evt.preventDefault) evt.preventDefault();
				 evt.returnValue = false;
				 return false;	
}
function addElementDescription(id,description)
{
	var obj = document.createElement("div");
	var src = document.getElementById(id).parentElement;
	obj.innerHTML=description;
	obj.id = id+"_description";
	obj.className ="elementDescription";
	src.appendChild(obj);
}
function removeElementDescription(id)
{
	var src = document.getElementById(id).parentElement;
	src.removeChild(document.getElementById(id+"_description"));
}
function Group()
{
	this.elements = Array();
	this.add = function(el)
	{
		this.elements[this.elements.length]=el;
	}
	this.val = function()
	{
		for(var i=0;i<this.elements.length;i++)
		{
			if(this.elements[i].checked==true)
			{
				return this.elements[i].value;
			}
		}
		return null;
	}
}function PageContainer(id,target)
{
	this.id = id;
	this.pageSize;
	this.avilableSize = Array();
	this.avilableSize['A4']=Array(200,282,0,0,0,0,12.5,12.5,12.5,12.5);
	this.avilableSize['A5']=Array(135,200,0,0,0,0,12.5,12.5,12.5,12.5);
	this.content = document.getElementById(id).innerHTML;
	
	this.pages = Array();
	this.target=target;
	this.footerDisplay=false;
	this.headerHeight=0;
	this.headerContent = "";
	this.footer = null;
	if(typeof UI == "undefined")
	{
		UI = new UIModule();
	}
	this.ppi = UI.findPPI();
	this.ppm = this.ppi/25.5;
	
	this.showFooter = function()
	{
		this.footerDisplay = true;
	}
	this.pageSize = function(size)
	{
		
		var obj = this.avilableSize[size];
		if(typeof obj == "object")
		{
			this.pageWidth = obj[0];
			this.pageHeight = obj[1];
			
			this.topMargin=obj[2];
			this.bottomMargin=obj[3];
			this.leftMargin=obj[4];
			this.rightMargin=obj[5];
			
			this.topPadding=obj[6];
			this.bottomPadding=obj[7];
			this.leftPadding=obj[8];
			this.rightPadding=obj[9];	
		}
		
		
	}
	this.addPage = function(content)
	{
		var p = new Page(id+"_page"+this.pages.length);
		p.setSize(this.pageWidth,this.pageHeight);
		p.setPadding(this.topPadding,this.bottomPadding,this.leftPadding,this.rightPadding);
		p.setMargin(this.topMargin,this.bottomMargin,this.leftMargin,this.rightMargin);
		p.setContent(content);
		this.pages[this.pages.length] = p;
		
	}
	this.headerCheck = function()
	{
		var obj = document.getElementById(this.id+"_header");
		if(obj)
		{
			this.headerHeight = obj.offsetHeight;
			this.headerContent = obj.innerHTML;
			obj.parentNode.removeChild(obj);
		}
		
	}
	this.footerChekc = function()
	{
		if(this.footerDisplay)
		{
			var obj = document.createElement('div');
			obj.className = "pageFooter";
			obj.style.height = "30px";
			this.footer = obj;
		}
	}
	this.pageBreak = function()
	{
		this.headerCheck();
		this.footerChekc();
		var obj = document.getElementById(this.id);
		var _pageWidth = ((this.pageWidth-(this.leftPadding+this.rightPadding+this.leftMargin+this.rightMargin))*this.ppm);
		var _pageHeight = ((this.pageHeight-(this.topPadding+this.bottomPadding+this.topMargin+this.bottomMargin))*this.ppm);
		
		if(this.headerContent!="")
		{
			_pageHeight = _pageHeight - this.headerHeight;
		}
		
		if(this.footerDisplay==true)
		{
			_pageHeight = _pageHeight - parseInt(this.footer.style.height.replace("px",""));
		}
		obj.style.width = _pageWidth+"px";
		var str=this.headerContent;
		var p=0;
		var bOffset=0;
		for(var i=0;i<obj.childNodes.length;i++)
		{
			var el = obj.childNodes[i];
			var topOffset = el.offsetTop+el.offsetHeight;
			if((topOffset-bOffset)>=_pageHeight-(el.offsetHeight*2))
			{
				if(this.footerDisplay==true)
				{
					this.footer.innerHTML = "<b>Page : "+(p+1)+"</b>";
					str+= this.footer.outerHTML;
				}
				this.addPage(str);
				bOffset = topOffset;
				p++;
				str = this.headerContent;
				str+= el.outerHTML;
			}
			else
			{
				if(el.outerHTML!="undefined")
				{
					str += el.outerHTML+" ";
				}
			}
		}
		if(this.footerDisplay==true)
		{
			this.footer.innerHTML = "<b>Page : "+(p+1)+"</b>";
			str+= this.footer.outerHTML;
		}
		this.addPage(str);
	}
	
	this.rander = function()
	{
		var cont = document.createElement("div");
		cont.id = this.id+"_container";
		
		for(var i=0;i<this.pages.length;i++)
		{
			var obj = this.pages[i].rander();
			cont.appendChild(obj);
		}
		document.getElementById(id).innerHTML="";
		document.getElementById(id).appendChild(cont);
	}
	
}function Page(id)
{
	this.id = id;
	this.width;
	this.height;
	this.content;
	this.topMargin;
	this.bottomMargin;
	this.leftMargin;
	this.rightMargin;
	this.topPadding;
	this.bottomPadding;
	this.leftPadding;
	this.rightPadding;	
	this.setSize = function(w,h)
	{
		this.width = w;
		this.height = h;
		this.topMargin=0;
		this.bottomMargin=0;
		this.leftMargin=0;
		this.rightMargin=0;
	}
	this.setContent = function(cont)
	{
		this.content = cont;
	}
	this.setPadding = function(top,bottom,left,right)
	{
		this.topPadding=top;
		this.bottomPadding=bottom;
		this.leftPadding=left;
		this.rightPadding=right;	
	}
	this.setMargin = function(top,bottom,left,right)
	{
		this.topMargin=top;
		this.bottomMargin=bottom;
		this.leftMargin=left;
		this.rightMargin=right;	
	}	
	this.rander = function()
	{
		this.width = this.width-this.leftPadding-this.rightPadding-this.leftMargin-this.rightMargin;
		this.height = this.height-this.topPadding-this.bottomPadding-this.topMargin-this.bottomMargin;
		var page = document.createElement('div');
		page.id = this.id;
		page.className = 'printPage';
		page.innerHTML = this.content;
		
		page.style.width = this.width+"mm";
		page.style.height = this.height+"mm";
		
		page.style.marginTop = this.topMargin+"mm";
		page.style.marginBottom = this.bottomMargin+"mm";
		page.style.marginLeft = this.leftMargin+"mm";
		page.style.marginRight = this.rightMargin+"mm";
		
		page.style.paddingTop = this.topPadding+"mm";
		page.style.paddingBottom = this.bottomPadding+"mm";
		page.style.paddingLeft = this.leftPadding+"mm";
		page.style.paddingRight = this.rightPadding+"mm";		
		
		page.style.border = "1px solid #DDDDDD";
		return page;
	}
}function Recordset()
{
	this.data = null;
	this.load = function(str)
	{
		this.data = unserialize(str);
	}
}function ScreenControl()
{
	this.screen = null;
	this.silent = false;
	this.width = function()
	{
		return document.body.clientWidth;
	}
	
	this.height = function()
	{
		return document.body.clientHeight;
	}
	
	this.lock = function(msg)
	{
		if(this.screen == null && this.silent==false)
		{
			this.screen  = document.createElement("div");
			this.screen.style.width="100%";
			this.screen.style.height="100%";
			this.screen.style.position="absolute";
			this.screen.style.left="0px";
			this.screen.style.top="0px";
			this.screen.style.zIndex = "10000";
			this.screen.style.backgroundColor = "rgba(0, 0, 0, 0.3)";
			this.screen.style.textAlign="center";
			this.screen.innerHTML = '<span style="font-size:20px;color:#FFF;">'+msg+'</span>';
			this.screen.style.cursor="wait";
			document.body.appendChild(this.screen );
		}
	}
	this.unlock = function()
	{
		if(this.screen)
		{
			document.body.removeChild(this.screen );
			this.screen = null;
		}
	}
}
SC = new ScreenControl();

function TableColumn(id)
{
	this.id = id;
	this.encryption="ASCI";
}
function Table(id)
{
	this.columnCount =0;
	this.column = Array();
	this.rowCount = 0;
	this.id = id;
	this.object = document.getElementById(id);
	this.showHeader=false;
	this.sortStatus=Array();
	this.alternetClass=Array();
	this.rowIndex = Array();
	this.firstRowObject=null;
	this.showSummery = false;
	this.lastRowObject=null;
	this.showRownum =false;
	this.summeryStatus=false;
	this.rowConditionCss=Array();
	this.contentHighlight=true;
	this.setRowCount = function(count)
	{
		this.rowCount = count;
	}
	this.setColumnCount = function (count)
	{
		this.columnCount=count;
	}
	this.addColumn = function(name)
	{
		this.column[this.column.length]= new TableColumn(name);
		this.sortStatus[this.sortStatus.length]=false;
	}
	this.getCellClass = function(column,row)
	{
		return eval(this.id+"_"+column.toUpperCase()+"_"+row);
	}
	this.setColumnEncryption = function(column,enc)
	{
		var cIndex = this.getColumnIndex(column.toUpperCase());
		if(cIndex>-1)
		{
			this.column[cIndex].encryption = enc;
		}
	}
	this.makeIndex = function()
	{
		if(typeof this.object.tBodies =="undefined")
		{
			alert("Table id is invalid or duplicate id exist. ID "+this.id+" is assign to TAG "+this.object.tagName);
			return;
		}
		var tbl = this.object.tBodies[0];
		
		var start = 0;
		var last = tbl.rows.length;
		if(this.showHeader){start=1;}
		this.firstRowObject = jQuery.extend(true,{}, this.object.firstElementChild.children[start]);
		if(this.showSummery){last = tbl.rows.length-1;}
		this.lastRowObject = jQuery.extend(true,{}, this.object.firstElementChild.children[last]);
		this.removeSummeryRow();
		this.rowIndex = jQuery.extend(true,{}, tbl.rows);
		this.showSummeryRow();
	}
	this.removeAll = function()
	{
		var start = 0;
		if(this.showHeader){start=1;}
		var tbl = this.object.firstElementChild;
		var len = parseInt(this.object.firstElementChild.children.length);
		if(this.showSummery && this.summeryStatus){len=len-2;}
		for(var i=start;i<=len;i++)
		{
			if(this.object.firstElementChild.children[start])
			{
				this.object.firstElementChild.children[start].parentNode.removeChild(this.object.firstElementChild.children[start]);
			}
		}
		this.rowCount=0;
	}
	
	this.search = function(query)
	{
		query = query.toLowerCase();
		this.removeSummeryRow();
		this.removeAll();
		var out = Array();
		var start = 0;
		if(this.showHeader){start=1;}
		var len1 = this.rowIndex.length;
		if(this.showSummery){len1=this.rowIndex.length;}
		for(var i =start;i<len1;i++)
		{
			var str="";
			var len = parseInt(this.rowIndex[i].children.length);
			for(var j=1 ; j<len;j++)
			{
				//if(typeof this.rowIndex[i].children[j].firstChild.val == "function")
				//{
					try
					{
					str += this.rowIndex[i].children[j].firstElementChild.val();
					}
					catch(ex)
					{
						//alert(i+":"+j);
					}
				//}
			}
			str = str.toLowerCase();
			if(str.indexOf(query)!=-1)
			{
				out[out.length]=this.rowIndex[i];
			}
		}
		
		
		var tbl = this.object.tBodies[0];

		for(var i=0;i<out.length;i++)
		{
			tbl.appendChild(out[i]);
		}
		this.rowCount=out.length;
		out=null;
		this.showSummeryRow();
		this.resetRownum();
		this.resetAlternetClass();
		this.contentHighlightDrow(query);
	}
	this.getFirstRowCell = function(index)
	{
		var start = 0;
		if(this.showHeader){start=1;}
		return this.firstRowObject.cells[index].firstElementChild;
	}
	this.removeSummeryRow = function()
	{
		if(this.showSummery)
		{
			this.remove(this.rowCount+1);
			//this.object.firstElementChild.children[this.rowCount+1].parentNode.removeChild(this.object.firstElementChild.children[this.rowCount+1]);
			this.summeryStatus = false;
		}
	}
	this.showSummeryRow = function()
	{
		if(this.showSummery)
		{
			var tmp = document.createElement("tr");
			tmp.innerHTML = this.lastRowObject.innerHTML;
			tmp.className = this.lastRowObject.className;
			this.object.tBodies[0].appendChild(tmp);
			this.summeryStatus = true;
			this.rowCount++;
		}
	}
	this.resetRownum = function()
	{
		var start = 0;
		if(this.showHeader){start=1;}
		var tbl = this.object.firstElementChild;
		var len = parseInt(this.object.firstElementChild.children.length);
		if(this.showSummery){len=len-2;}
		for(var i=start;i<len;i++)
		{
			tbl.rows[i].cells[0].firstElementChild.innerHTML=i;
		}
	}
	this.addRow = function()
	{
		//var cl  = this.object.firstElementChild.children[1].className;
		if(this.showSummery)
		{
			var tr = this.object.insertRow(this.object.children[0].children.length-1);
		}
		else
		{
			var tr = this.object.insertRow(this.object.children[0].children.length);
		}
		//tr.className = cl;
		//this.removeSummeryRow();
		//var tr = jQuery.extend(true,{}, this.firstRow);
		for(var i=0;i<this.column.length;i++)
		{
			var td = tr.insertCell(i);
			//var obj =  eval(""+this.id+"_"+this.column[i]+"_"+(this.rowCount));
			var obj = this.getFirstRowCell(i);
			
			var newIndex=this.rowCount;
			var p = jQuery.extend(true,{}, obj.parentElement);
			
			
			var str = p.outerHTML;
			
			if(typeof str == "string")
			{

				str = str.replace(new RegExp(obj.id,"g"),this.id+"_"+this.column[i].id+"_"+(newIndex));
				//str = str.replace(new RegExp(obj.id+"_label","g"),this.id+"_"+this.column[i]+"_"+(this.rowCount+1)+"_label");
				td.outerHTML = str;
				
				eval(""+this.id+"_"+this.column[i].id+"_"+(newIndex)+" = document.getElementById('"+this.id+"_"+this.column[i].id+"_"+(newIndex)+"');");
				var tmepObj = document.getElementById(this.id+"_"+this.column[i].id+"_"+(newIndex)).addInTabIndex();
				if(this.showRownum && i==0)
				{
					//p.children[0].innerHTML=(this.rowCount+1);
					document.getElementById(this.id+"_"+this.column[i].id+"_"+(newIndex)).innerHTML=(newIndex+1);
				}
				
				if(obj.lang=="hindi")
				{
					eval("transliterate('"+this.id+"_"+this.column[i].id+"_"+(newIndex)+"');");
				}
				
			}
		}
		this.rowCount = this.rowCount+1;
		//this.showSummeryRow();
		//this.resetRownum();
		this.makeIndex();
		this.resetAlternetClass();
		var temp = new TableRow(this.id,this.rowCount-1);
		
		temp.setObject(tr);
		
		
		return temp;	
	}
	this.addRowOld = function()
	{
		
		//var cl  = this.object.firstElementChild.children[1].className;
		var tr = this.object.insertRow(-1);
		//tr.className = cl;
		
		//var tr = jQuery.extend(true,{}, this.firstRow);
		for(var i=0;i<this.column.length;i++)
		{
			var td = tr.insertCell(i);
			var obj =  eval(""+this.id+"_"+this.column[i].id+"_"+(this.rowCount));
			//var obj = this.firstRowObject;
			var str = obj.outerHTML;
			if(typeof str == "string")
			{
				str = str.replace(obj.id,this.id+"_"+this.column[i].id+"_"+(this.rowCount+1));
				td.innerHTML=str;
				eval(""+this.id+"_"+this.column[i].id+"_"+(this.rowCount+1)+" = document.getElementById('"+this.id+"_"+this.column[i].id+"_"+(this.rowCount+1)+"');");
				if(obj.lang=="hindi")
				{
					eval("transliterate('"+this.id+"_"+this.column[i].id+"_"+(this.rowCount+1)+"');");
				}
			}
		}
		this.rowCount = this.rowCount+1;
		this.resetAlternetClass();
		var temp = new TableRow(this.id,this.rowCount-1);
		temp.setObject(tr);
		return temp;
	}
	this.getXml = function()
	{
		var xml = '';
		xml += '<'+this.id+">";
		xml += '<object_name>'+this.id+'</object_name>';
		xml += '<columns>';
		for(var j=0;j<this.column.length;j++)
		{
			xml += '<col_'+j+'>';
			xml += this.column[j].id;
			xml += '</col_'+j+'>';
		}
		xml += '</columns>';
		xml += '<rows>';
		for(var i=0;i<this.rowCount;i++)
		{
			xml += '<row_'+i+'>';
			for(var j=0;j<this.column.length;j++)
			{
				var val = document.getElementById(this.id+"_"+this.column[j].id+"_"+(i)).val();
				if(this.column[j].encryption=="BASE64")
				{
					val = Base64.encode(val);
				}
				xml += '<'+this.column[j].id.toUpperCase()+'>"'+encodeURIComponent(val)+'"</'+this.column[j].id.toUpperCase()+'>';
			}
			xml += '</row_'+i+'>';
		}
		xml += '</rows>';
		xml += '</'+this.id+">";
		return xml;
	}
	this.val = function()
	{
		return this.getXml();
	}
	this.getCell = function(id,index)
	{
		return document.getElementById(this.id+"_"+id.toUpperCase()+"_"+index);
	}
	this.remove = function(index)
	{
		if(typeof this.object.firstElementChild.children[index] != "undefined")
		{
			this.rowCount--;
			this.object.firstElementChild.children[index].parentNode.removeChild(this.object.firstElementChild.children[index]);
		}
	}
	this.getColumnIndex = function(fld)
	{
		var field = "";
		for(var i=0;i<this.column.length;i++)
		{
			if(this.column[i].id == fld)
			{
				field = i;
			}
		}
		return field;
	}
	this.sortToggle = function(fld)
	{
		var field = this.getColumnIndex(fld);
		var status = this.sortStatus[field];
		this.sort(fld,status);
		if(status==true)
		{
			this.sortStatus[field]=false;
		}
		else
		{
			this.sortStatus[field]=true;
		}
	}
	this.sort = function(fld,dr)
	{
		if(typeof dr=="undefined")
		{
			dr=false;
		}
		
		if(typeof this.object.tBodies =="undefined")
		{
			alert("Table id is invalid or duplicate id exist. ID "+this.id+" is assign to TAG "+this.object.tagName);
			return;
		}
		var tbl = this.object.tBodies[0];
		var field = this.getColumnIndex(fld);
		var store = [];
		var start = 0;
		if(this.showHeader==true)
		{
			start = 1;
		}
		var colSt = true;
		var len=tbl.rows.length;
		if(this.showSummery && this.summeryStatus)
		{
			len = len - 1;
		}
		for(var i=start;  i<len; i++){
			var row = tbl.rows[i];
			if(typeof row.cells[field].firstElementChild.val == "function")
			{
				var sortnr1 = parseFloat(row.cells[field].firstElementChild.val());
				var sortnr = row.cells[field].firstElementChild.val();
				if(!isNaN(sortnr)) 
				{
					sortnr = sortnr1;
				}
				else
				{
					colSt = false;
				}
				store.push([sortnr, row]);
			}
			else
			{
				//alert("aS");
			}
		}
		if(colSt == false)
		{
			store.sort();
			if(dr==true)
			{
				store.reverse();
			}
		}
		else
		{
			if(dr==true)
			{
				store.sort(function(a,b){return b[0]-a[0]});
			}
			else
			{
				store.sort(function(a,b){return a[0]-b[0]});
			}
		}
		for(var i=0, len=store.length; i<len; i++)
		{
			tbl.appendChild(store[i][1]);
		}
		if(this.showSummery)
		{
			tbl.appendChild(tbl.rows[1]);
		}
		store = null;
		this.resetAlternetClass();
	}
	this.resetAlternetClass = function()
	{
		var tbl = this.object.tBodies[0];
		var start = 0;
		if(this.showHeader==true)
		{
			start = 1;
		}		
		var len = tbl.rows.length;
		if(this.showSummery && this.summeryStatus)
		{
			len--;
		}
		for(var i=start; i<len; i++)
		{ 
			tbl.rows[i].className = this.alternetClass[i%this.alternetClass.length];
		}
		
		for(var k =0;k<this.rowConditionCss.length;k++)
		{
			var p = -1;
			var pt = -1;
			for(var j=0;j<this.column.length;j++)
			{
				if(this.column[j].id == this.rowConditionCss[k].col)
				{
					p=j;
				}
				if(this.column[j].id == this.rowConditionCss[k].target)
				{
					pt=j;
				}
			}
			var sourceColumnIndex = -1;
			if(this.rowConditionCss[k].source=="COLUMN")
			{
				for(var j=0;j<this.column.length;j++)
				{
					if(this.column[j].id==this.rowConditionCss[k].css.toUpperCase())
					{
						sourceColumnIndex=j;
						break;
					}
				}
			}
			if(p>-1 && pt>-1)
			{
				for(var i=start; i<len; i++)
				{ 
					if(typeof tbl.rows[i].cells[p].children[0] == "object" && typeof document.getElementById(tbl.rows[i].cells[p].children[0].id).val().trim() != "undefined")
					{
						
						
						var rowTrue = false;
						var objVal  = document.getElementById(tbl.rows[i].cells[p].children[0].id).val().trim();
						var val = this.rowConditionCss[k].val;
						if(this.rowConditionCss[k].cond=="=")
						{
							if(objVal==val)
							{
								rowTrue=true;
							}
						}
						if(this.rowConditionCss[k].cond=="!=")
						{
							if(objVal!=val)
							{
								rowTrue=true;
							}
						}
						if(this.rowConditionCss[k].cond=="<")
						{
							if(parseFloat(objVal)<parseFloat(val))
							{
								rowTrue=true;
							}
						}
						if(this.rowConditionCss[k].cond=="<=")
						{
							if(parseFloat(objVal)<=parseFloat(val))
							{
								rowTrue=true;
							}
						}
						if(this.rowConditionCss[k].cond==">")
						{
							if(parseFloat(objVal)>parseFloat(val))
							{
								rowTrue=true;
							}
						}
						if(this.rowConditionCss[k].cond==">=")
						{
							if(parseFloat(objVal)>=parseFloat(val))
							{
								rowTrue=true;
							}
						}
						else if(this.rowConditionCss[k].cond=="in")
						{
							val = val.split(",");
							if(val.indexOf(objVal)>-1)
							{
								rowTrue=true;
							}
						}
						else if(this.rowConditionCss[k].cond=="not in")
						{
							val = val.split(",");
							if(val.indexOf(objVal)==-1)
							{
								rowTrue=true;
							}
						}
						else if(this.rowConditionCss[k].cond=="startwith")
						{
							var t = objVal.substr(0,val.length);
							if(t==val)
							{
								rowTrue=true;
							}
						}
						else if(this.rowConditionCss[k].cond=="not startwith")
						{
							var t = objVal.substr(0,val.length);
							if(t!=val)
							{
								rowTrue=true;
							}
						}
						else if(this.rowConditionCss[k].cond=="startwith in")
						{
							val = val.split(",");
							for(var jx = 0;jx < val.length;jx++)
							{
								var t = objVal.substr(0,val[jx].length);
								if(t==val[jx])
								{
									rowTrue=true;
								}
							}
						}
						else if(this.rowConditionCss[k].cond=="startwith not in")
						{
							val = val.split(",");
							for(var jx = 0;jx < val.length;jx++)
							{
								var t = objVal.substr(0,val[jx].length);
								if(t!=val[jx])
								{
									rowTrue=true;
								}
							}
						}
						else if(this.rowConditionCss[k].cond=="endwith")
						{
							var t = objVal.substr(objVal.length-val.length,val.length);
							if(t==val)
							{
								rowTrue=true;
							}
						}
						else if(this.rowConditionCss[k].cond=="not endwith")
						{
							var t = objVal.substr(objVal.length-val.length,val.length);
							if(t!=val)
							{
								rowTrue=true;
							}
						}
						else if(this.rowConditionCss[k].cond=="endwith in")
						{
							val = val.split(",");
							for(var jx = 0;jx < val.length;jx++)
							{
								var t = objVal.substr(objVal.length-val[jx].length,val[jx].length);
								if(t==val[jx])
								{
									rowTrue=true;
								}
							}
						}	
						else if(this.rowConditionCss[k].cond=="endwith not in")
						{
							val = val.split(",");
							for(var jx = 0;jx < val.length;jx++)
							{
								var t = objVal.substr(objVal.length-val[jx].length,val[jx].length);
								if(t!=val[jx])
								{
									rowTrue=true;
								}
							}
						}					
						else if(this.rowConditionCss[k].cond=="like")
						{
							if(objVal.indexOf(val)>-1)
							{
								rowTrue=true;
							}
						}
						else if(this.rowConditionCss[k].cond=="not like")
						{
							if(objVal.indexOf(val)==-1)
							{
								rowTrue=true;
							}
						}
						
						
						if(rowTrue)
						{
							var ev="";
							if(this.rowConditionCss[k].source=="VALUE")
							{
								ev = this.rowConditionCss[k].css;
							}
							else if(this.rowConditionCss[k].source=="COLUMN")
							{
								if(sourceColumnIndex>-1)
								{
									ev = document.getElementById(tbl.rows[i].cells[sourceColumnIndex].children[0].id).val().trim();
								}
							}
							if(this.rowConditionCss[k].cell=="row")
							{
								
								if(this.rowConditionCss[k].type=="css")
								{
									tbl.rows[i].className = ev;
								}
								else if(this.rowConditionCss[k].type=="event")
								{
									
									ev = ev.split("=");
									tbl.rows[i].addEventListener(ev[0],function(){window[ev[1]](this)});
								}
								else if(this.rowConditionCss[k].type=="attrib")
								{
									
									ev = ev.split("=");
									eval(tbl.rows[i].id+"."+ev[0]+"='"+ev[1]+"'");
								}
							}
							else if(this.rowConditionCss[k].cell=="cell")
							{
								if(this.rowConditionCss[k].type=="css")
								{
									tbl.rows[i].cells[pt].className = ev;
								}
								else if(this.rowConditionCss[k].type=="event")
								{
									ev = ev.split("=");
									tbl.rows[i].cells[pt].addEventListener(ev[0],function(){window[ev[1]](this)});
								}
								else if(this.rowConditionCss[k].type=="attrib")
								{
									ev = ev.split("=");
									eval(tbl.rows[i].cells[pt].id+"."+ev[0]+"='"+ev[1]+"'");
								}
								
							}
							else if(this.rowConditionCss[k].cell=="object")
							{
								if(this.rowConditionCss[k].type=="css")
								{
									tbl.rows[i].cells[pt].children[0].className = ev;
								}
								else if(this.rowConditionCss[k].type=="event")
								{
									ev = ev.split("=");
									tbl.rows[i].cells[pt].children[0].addEventListener(ev[0],function(){window[ev[1]](this)});
								}
								else if(this.rowConditionCss[k].type=="attrib")
								{
									ev = ev.split("=");
									eval(tbl.rows[i].cells[pt].children[0].id+"."+ev[0]+"='"+ev[1]+"'");
								}
							}
						}
						
					}
					if(this.contentHighlight)
					{
						document.getElementById(tbl.rows[i].cells[pt].children[0].id).highlights("200");
					}
					//tbl.rows[i].className = this.alternetClass[i%this.alternetClass.length];
				}
			}
		}
	}
	this.contentHighlightDrow = function(str)
	{
		var tbl = this.object.tBodies[0];
		var start = 0;
		if(this.showHeader==true)
		{
			start = 1;
		}		
		var len = tbl.rows.length;
		if(this.showSummery && this.summeryStatus)
		{
			len--;
		}

		var p = -1;
		var pt = -1;
		var jStart =0;
		if(this.showRownum)
		{
			jStart=1;
		}
		for(var j=jStart;j<this.column.length;j++)
		{
			pt=j;
			for(var i=start; i<len; i++)
			{ 
	
				if(this.contentHighlight)
				{
					document.getElementById(tbl.rows[i].cells[pt].children[0].id).highlights(str);
				}
			}
		}
	}
	this.setAlternetClass = function(str)
	{
		str = str.split(",");
		this.alternetClass = str;
	}
	this.getColumnId = function(obj)
	{
		var str = obj.id;
		str = str.split("_");
		if(str[0]==this.id)
		{
			return str[1];
		}
		else
		{
			return null;
		}
	}
	this.getRowId = function(obj)
	{
		var str = obj.id;
		str = str.split("_");

		return parseInt(str[str.length-1]);

	}
	this.getRowPosition = function(obj)
	{
		var colIndex = -1;
		for(var i=0 ;obj.parentNode.parentNode.children.length;i++)
		{
			if(obj.parentNode.parentNode.children[i].children[0].id == obj.id)
			{
				colIndex = i;
				break;
			}
		}
		for(var i=0;i<this.object.children[0].children.length;i++)
		{
			if(this.object.children[0].children[i].children[colIndex].children[0].id == obj.id)
			{
				return i;
			}
		}
		return -1;
	}
	this.getSum = function(id)
	{
		var out=0;
		var start = 0;
		/*
		if(this.showHeader==true)
		{
			start = 1;
		}	
		*/
		for(var i=start;i<this.rowCount;i++)
		{
			var obj = document.getElementById(this.id+"_"+id.toUpperCase()+"_"+i);
			out = out+parseFloat(obj.val());
		}
		return out;
	}
	this.addRowCssCondition = function(col,cond,val,css,cell,target,source)
	{
		var temp = Array();
		temp.col = col.toUpperCase();
		temp.cond = cond;
		temp.val = val;
		temp.css = css;
		temp.cell = cell;
		temp.type = "css";
		temp.source=source;
		if(target=="")
		{
			target=col;
		}
		temp.target = target.toUpperCase();
		this.rowConditionCss[this.rowConditionCss.length]=temp;
	}
	this.addRowEventCondition = function(col,cond,val,css,cell,target,source)
	{
		var temp = Array();
		temp.col = col.toUpperCase();
		temp.cond = cond;
		temp.val = val;
		temp.css = css;
		temp.cell = cell;
		temp.type = "event";
		temp.source=source;
		if(target=="")
		{
			target=col;
		}
		temp.target = target.toUpperCase();
		this.rowConditionCss[this.rowConditionCss.length]=temp;
	}
	this.addRowAttribCondition = function(col,cond,val,css,cell,target,source)
	{
		var temp = Array();
		temp.col = col.toUpperCase();
		temp.cond = cond;
		temp.val = val;
		temp.css = css;
		temp.cell = cell;
		temp.type = "attrib";
		temp.source=source;
		if(target=="")
		{
			target=col;
		}
		temp.target = target.toUpperCase();
		this.rowConditionCss[this.rowConditionCss.length]=temp;
	}	
}

function TableRow(tblId,index)
{
	this.object = null;
	this.tableId = tblId;
	this.rowIndex = index;
	this.setObject = function(obj)
	{
		this.object = obj;
	}
	this.clear = function()
	{
		
	}
	this.getCell = function(name)
	{
		return document.getElementById(this.tableId+"_"+name.toUpperCase()+"_"+this.rowIndex);
	}
}


function UIModule()
{
	this.ppi=0;
	this.findPPI = function()
	{
		var o= document.createElement("div");
		o.style.width="1in";
		o.style.height="1in";
		o.id="uimodulefinppi";
		document.body.appendChild(o);
		o = document.getElementById("uimodulefinppi");
		this.ppi = o.offsetWidth;
		document.body.removeChild(o);
		return this.ppi;
	}
	this.updateTabIndex = function()
	{
		for(var i in htmlObjectDataSet.data)
		{
			var obj = document.getElementById(i);
			obj.setTabIndex(obj.get("tabIndex"));
		}
	}
}


function unserialize (data) 
{
  // http://kevin.vanzonneveld.net
  // +     original by: Arpad Ray (mailto:arpad@php.net)
  // +     improved by: Pedro Tainha (http://www.pedrotainha.com)
  // +     bugfixed by: dptr1988
  // +      revised by: d3x
  // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +        input by: Brett Zamir (http://brett-zamir.me)
  // +     improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +     improved by: Chris
  // +     improved by: James
  // +        input by: Martin (http://www.erlenwiese.de/)
  // +     bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +     improved by: Le Torbi
  // +     input by: kilops
  // +     bugfixed by: Brett Zamir (http://brett-zamir.me)
  // +      input by: Jaroslaw Czarniak
  // %            note: We feel the main purpose of this function should be to ease the transport of data between php & js
  // %            note: Aiming for PHP-compatibility, we have to translate objects to arrays
  // *       example 1: unserialize('a:3:{i:0;s:5:"Kevin";i:1;s:3:"van";i:2;s:9:"Zonneveld";}');
  // *       returns 1: ['Kevin', 'van', 'Zonneveld']
  // *       example 2: unserialize('a:3:{s:9:"firstName";s:5:"Kevin";s:7:"midName";s:3:"van";s:7:"surName";s:9:"Zonneveld";}');
  // *       returns 2: {firstName: 'Kevin', midName: 'van', surName: 'Zonneveld'}
  var that = this,
    utf8Overhead = function (chr) {
      // http://phpjs.org/functions/unserialize:571#comment_95906
      var code = chr.charCodeAt(0);
      if (code < 0x0080) {
        return 0;
      }
      if (code < 0x0800) {
        return 1;
      }
      return 2;
    },
    error = function (type, msg, filename, line) {
      throw new that.window[type](msg, filename, line);
    },
    read_until = function (data, offset, stopchr) {
      var i = 2, buf = [], chr = data.slice(offset, offset + 1);

      while (chr != stopchr) {
        if ((i + offset) > data.length) {
          error('Error', 'Invalid');
        }
        buf.push(chr);
        chr = data.slice(offset + (i - 1), offset + i);
        i += 1;
      }
      return [buf.length, buf.join('')];
    },
    read_chrs = function (data, offset, length) {
      var i, chr, buf;

      buf = [];
      for (i = 0; i < length; i++) {
        chr = data.slice(offset + (i - 1), offset + i);
        buf.push(chr);
        length -= utf8Overhead(chr);
      }
      return [buf.length, buf.join('')];
    },
    _unserialize = function (data, offset) {
      var dtype, dataoffset, keyandchrs, keys,
        readdata, readData, ccount, stringlength,
        i, key, kprops, kchrs, vprops, vchrs, value,
        chrs = 0,
        typeconvert = function (x) {
          return x;
        };

      if (!offset) {
        offset = 0;
      }
      dtype = (data.slice(offset, offset + 1)).toLowerCase();

      dataoffset = offset + 2;

      switch (dtype) {
        case 'i':
          typeconvert = function (x) {
            return parseInt(x, 10);
          };
          readData = read_until(data, dataoffset, ';');
          chrs = readData[0];
          readdata = readData[1];
          dataoffset += chrs + 1;
          break;
        case 'b':
          typeconvert = function (x) {
            return parseInt(x, 10) !== 0;
          };
          readData = read_until(data, dataoffset, ';');
          chrs = readData[0];
          readdata = readData[1];
          dataoffset += chrs + 1;
          break;
        case 'd':
          typeconvert = function (x) {
            return parseFloat(x);
          };
          readData = read_until(data, dataoffset, ';');
          chrs = readData[0];
          readdata = readData[1];
          dataoffset += chrs + 1;
          break;
        case 'n':
          readdata = null;
          break;
        case 's':
          ccount = read_until(data, dataoffset, ':');
          chrs = ccount[0];
          stringlength = ccount[1];
          dataoffset += chrs + 2;

          readData = read_chrs(data, dataoffset + 1, parseInt(stringlength, 10));
          chrs = readData[0];
          readdata = readData[1];
          dataoffset += chrs + 2;
          if (chrs != parseInt(stringlength, 10) && chrs != readdata.length) {
            error('SyntaxError', 'String length mismatch');
          }
          break;
        case 'a':
          readdata = {};

          keyandchrs = read_until(data, dataoffset, ':');
          chrs = keyandchrs[0];
          keys = keyandchrs[1];
          dataoffset += chrs + 2;

          for (i = 0; i < parseInt(keys, 10); i++) {
            kprops = _unserialize(data, dataoffset);
            kchrs = kprops[1];
            key = kprops[2];
            dataoffset += kchrs;

            vprops = _unserialize(data, dataoffset);
            vchrs = vprops[1];
            value = vprops[2];
            dataoffset += vchrs;

            readdata[key] = value;
          }

          dataoffset += 1;
          break;
        default:
          error('SyntaxError', 'Unknown / Unhandled data type(s): ' + dtype);
          break;
      }
      return [dtype, dataoffset - offset, typeconvert(readdata)];
    }
  ;

  return _unserialize((data + ''), 0)[2];
}
function setCookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}
function getCookie(c_name)
{
	var c_value = document.cookie;
	var c_start = c_value.indexOf(" " + c_name + "=");
	if (c_start == -1)
	  {
	  c_start = c_value.indexOf(c_name + "=");
	  }
	if (c_start == -1)
	  {
	  c_value = null;
	  }
	else
	  {
	  c_start = c_value.indexOf("=", c_start) + 1;
	  var c_end = c_value.indexOf(";", c_start);
	  if (c_end == -1)
	  {
	c_end = c_value.length;
	}
	c_value = unescape(c_value.substring(c_start,c_end));
	}
	return c_value;
}
function jsScript(str)
{
	if(document.getElementById("evalScript"))
	{
		var obj = document.getElementById("evalScript");
		document.body.removeChild(obj);
	}

		//$( "body" ).append(resp);
	var obj = document.createElement("script");
	obj.type = "text/javascript";
	obj.id = "evalScript";
	obj.innerHTML = str;
	document.body.appendChild(obj);

}


var Base64 = {

    // private property
    _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

    // public method for encoding
    encode : function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;

        input = Base64._utf8_encode(input);

        while (i < input.length) {

            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);

            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;

            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }

            output = output +
            this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
            this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

        }

        return output;
    },

    // public method for decoding
    decode : function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;

        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

        while (i < input.length) {

            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;

            output = output + String.fromCharCode(chr1);

            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }

        }

        output = Base64._utf8_decode(output);

        return output;

    },

    // private method for UTF-8 encoding
    _utf8_encode : function (string) {
        string = string.replace(/\r\n/g,"\n");
        var utftext = "";

        for (var n = 0; n < string.length; n++) {

            var c = string.charCodeAt(n);

            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }

        }

        return utftext;
    },

    // private method for UTF-8 decoding
    _utf8_decode : function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;

        while ( i < utftext.length ) {

            c = utftext.charCodeAt(i);

            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }

        }

        return string;
    }

}




/**
 * Trasliteration Tool
 * @author Junaid P V ([[user:Junaidpv]])
 * @date 2010-05-19
 * License: GPLv3, CC-BY-SA 3.0
 */
/**
 * Define your own regular expression rules here. Or include predefined rules before this file.
 * They should be in associative arrays named 'rules' and 'memrules'
 * 'rules' table is for normal rewriting
 * 'memrules' table is for memorised rules
*/

// defining to store state info
var trasliteration_fields = {};
// memory for previus key sequence
var previous_sequence = {};
// temporary disabling of transliteration
var temp_disable = {};
/**
 * from: http://stackoverflow.com/questions/3053542/how-to-get-the-start-and-end-points-of-selection-in-text-area/3053640#3053640
 */
function GetCaretPosition(el) {
    var start = 0, end = 0, normalizedValue, range,
        textInputRange, len, endRange;

    if (typeof el.selectionStart == "number" && typeof el.selectionEnd == "number") {
        start = el.selectionStart;
        end = el.selectionEnd;
    } else {
        range = document.selection.createRange();
        if (range && range.parentElement() == el) {
            len = el.value.length;
            normalizedValue = el.value.replace(/\r\n/g, "\n");

            // Create a working TextRange that lives only in the input
            textInputRange = el.createTextRange();
            textInputRange.moveToBookmark(range.getBookmark());

            // Check if the start and end of the selection are at the very end
            // of the input, since moveStart/moveEnd doesn't return what we want
            // in those cases
            endRange = el.createTextRange();
            endRange.collapse(false);

            if (textInputRange.compareEndPoints("StartToEnd", endRange) > -1) {
                start = end = len;
            } else {
                start = -textInputRange.moveStart("character", -len);
                start += normalizedValue.slice(0, start).split("\n").length - 1;

                if (textInputRange.compareEndPoints("EndToEnd", endRange) > -1) {
                    end = len;
                } else {
                    end = -textInputRange.moveEnd("character", -len);
                    end += normalizedValue.slice(0, end).split("\n").length - 1;
                }
            }
        }
    }
    return {
        start: start,
        end: end
    };
}

/**
 * from: http://stackoverflow.com/questions/3274843/get-caret-position-in-textarea-ie
 */
function offsetToRangeCharacterMove(el, offset) {
    return offset - (el.value.slice(0, offset).split("\r\n").length - 1);
}
/**
 * IE part from: http://stackoverflow.com/questions/3274843/get-caret-position-in-textarea-ie
 */
function setCaretPosition (el, iCaretPos)
{
    if (document.selection) // IE
    {
	endOffset = startOffset=iCaretPos;
	var range = el.createTextRange();
	var startCharMove = offsetToRangeCharacterMove(el, startOffset);
	range.collapse(true);
	if (startOffset == endOffset) {
		range.move("character", startCharMove);
	} else {
		range.moveEnd("character", offsetToRangeCharacterMove(el, endOffset));
		range.moveStart("character", startCharMove);
	}
	range.select();
    }
    else if (el.selectionStart || el.selectionStart == '0') // Firefox
    {
        el.setSelectionRange(iCaretPos, iCaretPos)
    }
}

function getLastSixChars(str, caretPosition)
{
	if(caretPosition <= 6 ) return str.substring(0,caretPosition);
	else return str.substring(caretPosition-6,caretPosition);
}

function replaceTransStringAtCaret(control, oldStringLength, newString, selectionRange)
{
	var text = control.value;
	// firefox always scrolls to topmost position,
	// to scroll manually we keep original scroll postion.
	if(control.scrollTop || control.scrollTop=='0') { var scrollTop = control.scrollTop; }
	if(text.length  >= 1) {
		var firstStr = text.substring(0, selectionRange['start'] - oldStringLength + 1);
		var lastStr = text.substring(selectionRange['end'], text.length);
		control.value = firstStr+newString+ lastStr;
		var newCaretPosition = firstStr.length+newString.length;
		setCaretPosition(control,newCaretPosition);
	}
	else { 
		control.value = newString;
		var newCaretPosition = newString.length;
		setCaretPosition(control,newCaretPosition);
	}
	// Manually scrolling in firefox, few tweeks or re-writing may require
	if (navigator.userAgent.indexOf("Firefox")!=-1) {
		var textLength = control.value.length;
		var cols = control.cols;
		if(newCaretPosition > (textLength-cols)) {
			//var height = parseInt(window.getComputedStyle(control,null).getPropertyValue('height'));
			var fontsize = parseInt(window.getComputedStyle(control,null).getPropertyValue('font-size'));
			//var lineheight = height/fontsize;
			control.scrollTop = scrollTop+fontsize;
		} else control.scrollTop = scrollTop;
	}
}

/**
 * This function will take a string to check against regular expression rules in the rules array.
 * It will return a two memeber array, having given string as first member and replacement string as
 * second memeber. If corresponding replacement could not be found then second string will be too given string
*/
function trans(lastpart,e)
{
	var len = lastpart.length;
	var i=0;
	var part1 = lastpart;
	var part2 = lastpart;
	var found = false;
outerloop1:
	for(i=0; i< len; i++)
	{
		var toTrans = lastpart.substring(i, len);
		for(var key in memrules)
		{
			if((new RegExp(key)).test(toTrans) && (new RegExp(memrules[key][0])).test(previous_sequence[(e.currentTarget || e.srcElement).id ]))
			{
				part1 = toTrans;
				part2 = toTrans.replace(RegExp(key), memrules[key][1]);
				found = true;
				break outerloop1;
			}
		}
	}
	if(!found)
	{
	outerloop2:
		for(i=0; i< len; i++)
		{
			var toTrans = lastpart.substring(i, len);
			for(var key in rules)
			{
				if((new RegExp(key)).test(toTrans))
				{
					part1 = toTrans;
					part2 = toTrans.replace(RegExp(key), rules[key]);
					break outerloop2;
				}
			}
		}
	}
	var pair = new Array(part1, part2);
	return pair;
}
/**
 * from: http://www.javascripter.net/faq/settinga.htm
 */

/**
 * from: http://www.javascripter.net/faq/readinga.htm
 */


function enableTrasliteration(controlID, enable) {
	if(enable==undefined) { enable = true; }
	var cookieValue;
	if(enable) {
		trasliteration_fields[controlID] = true;
		temp_disable[controlID] = false;
		cookieValue = 1;
	}
	else {
		trasliteration_fields[controlID] = false;
		cookieValue = 0;
	}
	var checkbox = document.getElementById(controlID+'cb');
	if(checkbox) { checkbox.checked = enable; }

}

// event listener for trasliterattion textfield
// also listen for Ctrl+M combination to disable and enable trasliteration
var keyboardStatus=false;
function changeKetboard()
{
	if(keyboardStatus)
	{
		keyboardStatus=false;
	}
	else
	{
		keyboardStatus=true;
	}
}
function tiKeyPressed(event) {
	if(keyboardStatus)
	{
		var e = event || window.event;
		var code = e.charCode || e.keyCode;
		var targetElement = (e.currentTarget || e.srcElement);
		if (code == 8 ) { previous_sequence[targetElement.id] = ''; return true; } // Backspace
		// If this keystroke is a function key of any kind, do not filter it
		if (e.charCode == 0 || e.which ==0 ) return true;       // Function key (Firefox and Opera), e.charCode for Firefox and e.which for Opera
		if (e.ctrlKey || e.altKey) // Ctrl or Alt held down
		{
			if (e.ctrlKey && (e.keyCode == 13 || e.which == 109)) // pressed Ctrl+M
			{
				enableTrasliteration(targetElement.id, !trasliteration_fields[targetElement.id]);
				return false;
			}
			return true;
		}
		if (code < 32) return true;             // ASCII control character
		if(trasliteration_fields[targetElement.id])
		{
			
			var c = String.fromCharCode(code);
			var selectionRange = GetCaretPosition(targetElement);
			var lastSevenChars = getLastSixChars(targetElement.value, selectionRange['start']);
			
			if(code ==62 && previous_sequence[targetElement.id ].substring(previous_sequence[targetElement.id ].length-1)=="<") 
			{
				var oldString = "<>";
				var newString = "";
				temp_disable[targetElement.id] = !temp_disable[targetElement.id];
			}
			else {
				if(!temp_disable[targetElement.id])
				{
					var transPair = trans(lastSevenChars+c, e);
					var oldString = transPair[0];
					var newString = transPair[1];
				}
				else 
				{
					var oldString = c;
					var newString = c;
				}
			}
			replaceTransStringAtCaret(targetElement, oldString.length, newString , selectionRange);
			previous_sequence[targetElement.id ] += c;
			if(previous_sequence[targetElement.id ].length > 6 ) previous_sequence[targetElement.id ] = previous_sequence[targetElement.id ].substring(previous_sequence[targetElement.id ].length-6);
			if(event.preventDefault) event.preventDefault();
			else if(event.cancelBubble) { event.cancelBubble = true; }
			return false;
		}
		return true;
	}
}
/**
 * This is the function to which call during window load event for trasliterating textfields.
 * The funtion will accept any number of HTML tag IDs of textfields.
*/
function transliterate(id) {

	var len = arguments.length;
	for(var i=0;i<len; i++)
	{
		
		var element = document.getElementById(arguments[i]);
		if(element)
		{
			trasliteration_fields[arguments[i]] = true;
			previous_sequence[arguments[i]] = '';
			//element.onkeypress = tiKeyPressed;
			if (element.addEventListener){
				element.addEventListener('keypress', tiKeyPressed, false);
			} else if (element.attachEvent){  
				element.attachEvent("onkeypress", tiKeyPressed);  
			}  
		}
		
	}
	
}

function transOptionOnClick(event)
{
	var e = event || window.event;
	var checkbox =  (e.currentTarget || e.srcElement);
	if(checkbox.checked)
	{
		enableTrasliteration(checkbox.value,true);
	}
	else
	{
		enableTrasliteration(checkbox.value,false);
	}
}
// change this value to "after" or "before" to position transliteration option check box
var TO_POSITION = "after";
// check box message
var CHECKBOX_TEXT = "To Write Malayalam (Ctrl+M)";
// call this function to add checkbox to enable/disable transliteration
function addTransliterationOption()
{
	var len = arguments.length;
	for(var i=0;i<len; i++)
	{
		var element = document.getElementById(arguments[i]);
		if(element)
		{
			var checkbox = document.createElement('input');
			checkbox.id = arguments[i]+'cb';
			checkbox.type = 'checkbox';
			checkbox.value = arguments[i];
			checkbox.onclick = transOptionOnClick;
			checkbox.checked = true;
			var para = document.createElement('p');
			para.appendChild(checkbox);
			var text = document.createTextNode(CHECKBOX_TEXT);
			para.appendChild(text);
			if(TO_POSITION=="after") element.parentNode.insertBefore(para, element.nextSibling);
			else if(TO_POSITION=="before") element.parentNode.insertBefore(para, element);
		}
	}
}

/**
 * This functions is to synchronize state transliteration state to fields from cookies
 */
function translitStateSynWithCookie() {
	var len = arguments.length;
	for(var i=0;i<len; i++)
	{
		var element = document.getElementById(arguments[i]);
		if(element)
		{
			var state = readCookie("tr"+arguments[i]);
			var enable = true;
			if(parseInt(state) == 0) { enable=false; }
			enableTrasliteration(arguments[i],enable);
		}
	}
}
var memrules = {

};





/**
 * Trasliteration regular expression rules table for Devanagari
 * @authors: Junaid P V ([[user:Junaidpv]]), Mayur Kumar ([[user:mayur]])
 * @date 2010-09-29
 * License: GPLv3, CC-BY-SA 3.0
 */
 // Normal rules
 var rules = {
 '^([क-ह])ृओo$':'$1ॄ',
 '^([क-ह])ॢओo$':'$1ॣ',
 
'^([क-ह])्a$':'$1',
'^([क-ह]़?)a$':'$1ा',
'^([क-ह])्M$':'$1ं',
'^([क-ह])((़)|्)i$':'$1$3ि',
'^ड़्i$':'ड़ि',
'^([क-ह])((़)|्)I$':'$1$3ी',
'^ड़्I$':'ड़ी',
'^([क-ह]़?)िi$':'$1ी',
'^([क-ह]़?)ेe$':'$1ी',
'^([क-ह])((़)|्)u$':'$1$3ु',
'^ड़्u$':'ड़ु',
'^([क-ह])((़)|्)U$':'$1$3ू',
'^ड़्U$':'ड़ू',
'^([क-ह]़?)ुu$':'$1ू',
'^([क-ह]़?)ोo$':'$1ू',
'^([क-ह])((़)|्)R$':'$1$3ृ',
'^ड़्R$':'ड़ृ',
'^([क-ह]़?)ृR$':'$1ॄ',
'^([क-ह])((़)|्)e$':'$1$3े',
'^ड़्e$':'ड़े',
'^([क-ह])((़)|्)E$':'$1$3ॅ',
'^ड़्E$':'ड़ॅ',
'^([क-ह]़?)i$':'$1ै',
'^([क-ह])i$':'$1ै',
'^([क-ह])((़)|्)o$':'$1$3ो',
'^ड़्o$':'ड़ो',
'^([क-ह])((़)|्)O$':'$1$3ॉ',
'^ड़्O$':'ड़ॉ',
'^([क-ह]़?)u$':'$1ौ',
'^([क-ह]़?)ृl$':'$1ॢ',
'^([क-ह]़?)ॢU$':'$1ॣ',
'^([क-ह])((़)|्)H$':'$1$3ः',
'^ड़्H$':'ड़ः',
'^([क-ह]़?)ृU$':'$1ॄ',
'^फ्रJ$':'फ़्र',

'^च्ह्h$':'छ्',
'^ह्u$':'हु',

'^अंM$':'अँ',
'^आंM$':'आँ',
'^ऋओo$':'ॠ',

'^क्h$':'ख्',
'^ग्h$':'घ्',
'^त्h$':'थ्',
'^ज्h$':'झ्',
'^ट्h$':'ठ्',
'^ड्h$':'ढ्',
'^त्h$':'थ्',
'^द्h$':'ध्',
'^स्h$':'श्',
'^श्h$':'ष्',
'^प्h$':'फ्',
'^ब्h$':'भ्',
'^़n$':'ज्ञ्',
'^ङ्y$':'ज्ञ्',
'^ड्D$':'ड़्',
'^ांM':'ाँ',
'^ड़्a$':'ड़',

'^अa$':'आ',
'^अi$':'ऐ',
'^एe$':'ऐ',
'^इi$':'ई',
'^उu$':'ऊ',
'^अu$':'औ',
'^ओo$':'औ',
'^ऑm$':'ॐ',
'^ंM$':'ँ',
'^ऋU$':'ॠ',
'^ऋl$':'ॡ',
'^\u0951q$':'\u0952',
'^\u0953Q$':'\u0954',
'^।z$':'॥',

'^अ~$':'ऽ',
'^्\u0020$':'\u0020',
'^a$':'अ',
'^b$':'ब्',
'^c$':'च्',
'^d$':'द्',
'^e$':'ए',
'^f$':'फ्',
'^g$':'ग्',
'^h$':'ह्',
'^i$':'इ',
'^j$':'ज्',
'^k$':'क्',
'^l$':'ल्',
'^m$':'म्',
'^n$':'न्',
'^o$':'ओ',
'^p$':'प्',
'^q$':'\u0951',
'^r$':'र्',
'^s$':'स्',
'^t$':'त्',
'^u$':'उ',
'^v$':'व्',
'^w$':'व्',
'^[xX]$':'क्ष्',
'^y$':'य्',
'^z$':'।',
'^A$':'आ',
'^B$':'ब्',
'^C$':'॰',
'^D$':'ड्',
'^E$':'ऍ',
'^F$':'फ्',
'^G$':'ङ्',
'^H$':'ः',
'^I$':'ई',
'^J$':'़',
'^K$':'ख्',
'^L$':'ळ्',
'^M$':'ं',
'^N$':'ण्',
'^O$':'ऑ',
'^P$':'प्',
'^Q$':'\u0953',
'^R$':'ऋ',
'^S$':'श्',
'^T$':'ट्',
'^U$':'ऊ',
'^V$':'व्',
'^W$':'व्',
'^Y$':'ञ्',
'^Z$':'झ्',
'^\\.$':'।',
'^0$':'०',
'^1$':'१',
'^2$':'२',
'^3$':'३',
'^4$':'४',
'^5$':'५',
'^6$':'६',
'^7$':'७',
'^8$':'८',
'^9$':'९'
};
// Memorised rules
var memrules = {

};

function Form(id,formObjectId)
{
	this.id = id;
	this.object = document.getElementById(formObjectId);
	this.object.onsubmit=function(){return false;}
	this.submitStatus = false;
	this.validateStatus = false;
	this.isPosted=false;
	this.callBackname=null;
	this.postSubmitFunction=null;
	this.object.onkeypress= function(event)
	{
		var evt  = (evt) ? evt : ((event) ? event : null);
		var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
		if ((evt.keyCode == 13) && (node.type!="textarea")) 
		{ 
			evt.preventDefault();
		}
	}
	this.reset = function()
	{
		this.object.reset();
	}
	this.clear = function()
	{
		this.reset();
	}
	this.setPostSubmit = function(fun)
	{
		if(typeof fun == "function")
		{
			this.postSubmitFunction = fun;
			//fun(this.object);
		}
	}	
	this.ajaxSubmitComplite = function(callback)
	{
		SC.unlock();
		if(callback!="")
		{
			var resp = $("#"+this.id+"_ifream").contents().find("body").html();
			if(typeof callback =="function")
			{
				
				if(this.isPosted==true)
				{
					callback(resp);
				}
				this.isPosted=false;
			}
			else
			{
				
			}
		}
	}	

	this.validateForm = function()
	{
		var obj = $("#"+this.id+' .validateEMAIL');
		var temp=false;
		for(var i=0;i<obj.length;i++)
		{
			if(!obj[i].isEmail())
			{

				if(!temp)
				{
					obj[i].focus();
				}
				temp = true;
			}
		}
		if(!temp)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	this.test = function(obj,e)
	{
		alert("AS");
	}
	this.submit = function(obj,e)
	{

		this.object.onsubmit=null;
		if(!this.blanckRequired())
		{
			if(this.validateForm())
			{
				this.isPosted=true;
				if(typeof this.postSubmitFunction =="function")
				{
					var resp = this.postSubmitFunction(this.object);
					if(resp==false)
					{
						this.isPosted = false;
						this.object.onsubmit=function(){return false;}
					}
				}
				if(this.isPosted==true)
				{
					SC.lock("Loading");
					this.object.submit();
					this.object.onsubmit=function(){return false;}
				}
			}
		}
		else
		{
			this.object.onsubmit=function(){return false;}
		}
	}

	
	this.blanckRequired = function()
	{
		this.submitErr=false;
		var obj = $("#"+this.id+"_form .required");
		for(var i=0;i<obj.length;i++)
		{
			var _obj = document.getElementById(obj[i].id);
			if(_obj.val()=="")
			{
				if(!this.submitErr)
				{
					_obj.focus();
				}
				_obj.label("This is required");
				_obj.labelColor("#cc0000");
				this.submitErr = true;
			}
		}
		if(!this.submitErr)
		{
			return false;
		}
		else
		{
			return true;
		
		}
	}
}

function ObjectData()
{
	this.data = Array();
	this.lastTabIndex = 1;
	this.set = function(id,key,val)
	{
		if(typeof this.data[id]=="object")
		{
			var temp =this.data[id];
		}
		else
		{
			var temp = Array();
		}
		
		temp[key]=val;
		this.data[id]=temp;
	}
	this.get = function(id,key)
	{
		if(typeof this.data[id] != "undefined")
		{
			return this.data[id][key];		
		}
		else
		{
			return null;
		}
	}
	this.addObjectOnTabIndex = function(id)
	{
		this.set(id,'TABINDEX',this.lastTabIndex);
		return this.lastTabIndex++;
	}
}
htmlObjectDataSet = new ObjectData();

function Cookies()
{
	this.set  = function(cname,cvalue,exdays,mode)
	{
		var d = new Date();
		if(mode=="D")
		{
			d.setTime(d.getTime()+(exdays*24*60*60*1000));
		}
		else if(mode=="H")
		{
			d.setTime(d.getTime()+(exdays*60*60*1000));
		}
		else if(mode=="M")
		{
			d.setTime(d.getTime()+(exdays*60*1000));
		}
		else if(mode=="S")
		{
			d.setTime(d.getTime()+(exdays*1000));
		}
		var expires = "expires="+d.toGMTString();
		document.cookie = cname + "=" + cvalue + "; " + expires;
	} 
	this.get = function (cname)
	{
		var name = cname + "=";
		var ca = document.cookie.split(';');
		for(var i=0; i<ca.length; i++)
		{
			var c = ca[i].trim();
			if (c.indexOf(name)==0) return c.substring(name.length,c.length);
		}
		return "";
	} 	
}
var COOKIES = new Cookies();


/*******************   Add Visibility Events on HTML Element **************************/

var EventListener = function(element, callback) {
    this._el = element;
    this._cb = callback;
    this._at = false;
    this._hasBeenVisible = false;
    this._hasBeenInvisible = true;
    var  _me = this;

    window.onscroll = function() {
        for (q in EventListener.queue.onvisible) {
            EventListener.queue.onvisible[q].call();
        }
        for (q in EventListener.queue.oninvisible) {
            EventListener.queue.oninvisible[q].call();
        }
    };

    return {
        onvisible: function() {
            EventListener.queue.onvisible.push(function() {
                if (!_me._at && _me._hasBeenInvisible && (window.pageYOffset + window.innerHeight) > _me._el.offsetTop && window.pageYOffset < (_me._el.offsetTop + _me._el.scrollHeight)) {
                    _me._cb.call();
                    _me._at = true;
                    _me._hasBeenVisible = true;
                }
            });
            EventListener.queue.oninvisible.push(function() {
                if (_me._hasBeenVisible && ((window.pageYOffset + window.innerHeight) < _me._el.offsetTop || window.pageYOffset > (_me._el.offsetTop + _me._el.scrollHeight))) {
                    _me._hasBeenInvisible = true;
                    _me._hasBeenVisible   = false;
                    _me._at = false;
                }
            });
        },
        oninvisible: function() {
            EventListener.queue.oninvisible.push(function() {
                if (!_me._at && _me._hasBeenVisible && ((window.pageYOffset + window.innerHeight) < _me._el.offsetTop || window.pageYOffset > (_me._el.offsetTop + _me._el.scrollHeight))) {
                    _me._cb.call();
                    _me._at = true;
                    _me._hasBeenInvisible = true;
                }
            });
            EventListener.queue.onvisible.push(function() {
                if (_me._hasBeenInvisible && (window.pageYOffset + window.innerHeight) > _me._el.offsetTop && window.pageYOffset < (_me._el.offsetTop + _me._el.scrollHeight)) {
                    _me._hasBeenVisible = true;
                    _me._hasBeenInvisible = false;
                    _me._at = false;
                }
            });
        }
    };
}
EventListener.queue = {
    onvisible:   [],
    oninvisible: []
};

function addListener(element, event, fn) {
    if (typeof element == 'string')
        element = document.getElementById(element);

    var listener = new EventListener(element, fn);

    if (listener['on' + event.toLowerCase()])
        return listener['on' + event.toLowerCase()].call();
}
function scrollIntoView(element, container) 
{
  var containerTop = $(container).scrollTop()+element.scrollHeight; 
  //var containerBottom = containerTop + $(container).height(); 
  var containerBottom =  $(container).height() - 20;
  var elemTop = element.offsetTop;
  var elemBottom = elemTop + $(element).height(); 
  if (elemTop < containerTop) {
    $(container).scrollTop(elemTop);
	//$(container).scrollTop(elemTop+(containerTop-elemTop));
  } else if (elemBottom > containerBottom) {
    //$(container).scrollTop(elemBottom - $(container).height());
	$(container).scrollTop(elemBottom-containerBottom);
  }
}
function ListOfValue(id)
{
	this.id = id;
	this.selectedRow=0;
	this.selectedOldRow=null;
	this.getPosition=0;
	this.keyPressTime=null;
	this.url;
	this.maxRecord;
	this.input = document.getElementById(this.id+"_input");
	this.returnColumn = Array();
	this.data = null;
	this.callingMode = null;
	this.regulerKey = false;
	this.contentTable = document.getElementById(this.id+"_contentTable");
	this.contenor = document.getElementById(this.id+"_content");
	this.exitFocus=null;
	this.openerObject = this.id+"_list";
	this.returnTo = function(dbColumn,elementId)
	{
		this.returnColumn[this.returnColumn.length] = Array(dbColumn,elementId);
	}
	this.setRagulerKey  = function(value)
	{
		this.regulerKey = value;
	}
	this.setMaxRow = function(maxRow)
	{
		this.maxRecord = maxRow;
	}
	
	this.exitFocusElement = function(id)
	{
		this.exitFocus = id;
	}
	this.setUrl = function(url)
	{
		this.url = url;
	}
	this.setData = function(data)
	{
		this.data = data;
	}
	this.updateRowColor = function(mode)
	{
		this.contentTable = document.getElementById(this.id+"_contentTable");
		this.input.style.width = (this.contenor.clientWidth-7)+"px";
		if(this.contentTable.clientWidth < this.contenor.clientWidth)
		{
			this.contentTable.style.width="100%";
		}
		if(mode=="new")
		{
			this.selectedOldRow = this.contentTable.children[0].children[this.selectedRow].className;
			this.contentTable.children[0].children[this.selectedRow].className="selectedRow";
			scrollIntoView(this.contentTable.children[0].children[this.selectedRow],this.contenor);
		}
		else
		{
			if(typeof this.contentTable.children[0].children[this.selectedRow] != "undefined")
			{
				this.contentTable.children[0].children[this.selectedRow].className=this.selectedOldRow;
			}
		}
	}
	this.updateReturnValue = function()
	{
		if(this.data != null)
		{
			for(var i=0;i<this.returnColumn.length;i++)
			{
				var obj = document.getElementById(this.returnColumn[i][1]);
				if(this.callingMode=="popup")
				{
					if(window.opener.document.getElementById(this.returnColumn[i][1])!=null)
					{
						window.opener.document.getElementById(this.returnColumn[i][1]).val(this.data.data[this.selectedRow][this.returnColumn[i][0].toUpperCase()]);
					}
				}
				else
				{
					obj.val(this.data.data[this.selectedRow][this.returnColumn[i][0].toUpperCase()]);
				}
				
			}
		}
		this.closeWindow();
	}
	this.updateData = function()
	{
		var inst = this;
		ajax(this.url,"str="+this.input.val(),
		function(resp)
		{
			$("#"+inst.id+"_content").html(resp);
			inst.selectedRow=0;
			inst.updateRowColor("new");
		},true);
	}
	this.updateDataThred = function()
	{
		var end  = +new Date();
		if(end-this.keyPressTime>300)
		{
			this.updateData();
		}
	}
	
	this.onKeyUp = function(obj,evt)
	{
		evt = evt || window.event;
		var charCode = evt.which || evt.keyCode;
		var charStr = String.fromCharCode(charCode);
		

		if(charCode==40)
		{
			
			if(this.selectedRow < this.data.count - 1)
			{
				this.updateRowColor("old");
				this.selectedRow++;
				this.updateRowColor("new");
			}
		}
		else if(charCode==38)
		{
			
			if(this.selectedRow>0)
			{
				this.updateRowColor("old");
				this.selectedRow--;
				this.updateRowColor("new");	
			}	
					
		}
		else if(charCode==27)
		{
			this.closeWindow();
		}
		else if(charCode==13)
		{
			this.updateReturnValue();
		}
		else
		{
			if(this.regulerKey)
			{
				this.updateData();
			}
			else
			{
				this.keyPressTime  = +new Date();
				setTimeout(this.id+".updateDataThred()",300);
			}
		}
	}
	
	this.onMouseClickRow = function(obj,id)
	{
		this.updateRowColor("old");
		this.selectedRow = id;
		this.updateRowColor("new");
	}
	this.onDblClick = function(obj)
	{
		this.updateReturnValue();
	}
	this.closeWindow = function()
	{
		if(this.onCloseCallback != null)
		{
			window.opener.selectedListofValue = this;
			var str = "window.opener."+this.onCloseCallback+"()";
			eval(str);
		}				
		if(this.callingMode=="popup")
		{
			window.close();
		}
		else
		{
			$.colorbox.close();
		}

		if(this.exitFocus!=null)
		{
			
			setTimeout("document.getElementById('"+this.exitFocus+"').focus()",500);
		}
		
	}
	this.setCallingMode = function(mode)
	{
		this.callingMode = mode;
	}
}
function callPhpMathod()
{
	var url = arguments[0];
	var parm = url;
	var callback = arguments[1];
	
	for (var i = 2; i < arguments.length; i++) 
	{
		var name=arguments[i];
		i++;
		var varname=arguments[i];
		i++;
		var value = arguments[i];
		if(value=="")
		{
			parm += "&"+varname+"="+eval(name+".val()");
		}
		else
		{
			parm += "&"+varname+"="+value+"";
		}
	}
	if(AJAX_LISTNER_STATUS==true)
	{
		url = applicationPath;
	}
	
	ajax(url,parm,
	function(resp)
	{
		var f = eval(callback);
		if(typeof f=="function")
		{
			f(resp);
		}
		else if(typeof f =="object")
		{
			$("#"+callback).html(resp);
		}
	});
	
}
