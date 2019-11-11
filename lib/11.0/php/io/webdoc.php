<?php
class XMLSerializer {

    // functions adopted from http://www.sean-barton.co.uk/2009/03/turning-an-array-or-object-into-xml-using-php/

    public function generateValidXmlFromObj($obj, $node_block='nodes', $node_name='node') {
        $arr = get_object_vars($obj);
        return $this->generateValidXmlFromArray($arr, $node_block, $node_name);
    }

    public function generateValidXmlFromArray($array, $node_block='nodes', $node_name='node') {
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>';

        $xml .= '<' . $node_block . '>';
        $xml .= $this->generateXmlFromArray($array, $node_name);
        $xml .= '</' . $node_block . '>';

        return $xml;
    }

    private function generateXmlFromArray($array, $node_name) {
        $xml = '';

        if (is_array($array) || is_object($array)) {
            foreach ($array as $key=>$value) {
                if (is_numeric($key)) {
                    $key = $node_name;
                }

                $xml .= '<' . $key . '>' . $this->generateXmlFromArray($value, $node_name) . '</' . $key . '>';
            }
        } else {
            $xml = htmlspecialchars($array, ENT_QUOTES);
        }

        return $xml;
    }

}
class WebDocumentTag
{
	var $id;
	var $tag;
	var $info;
	function __construct($tag,$info="")
	{
		$this->info = $info;
		$this->id = uniqid();
		$this->tag = $tag;
	}
}
class WebDocumentArticleTopic
{
	var $title;
	var $text;
	var $type;
	var $id;
	function __construct($title,$type,$text)
	{
		$this->id = uniqid("topic",true);
		$this->title = $title;
		$this->type = $type;
		$this->text = $text;
	}
}
class WebDocumentArticle
{
	var $property;
	var $id;
	var $tags;
	var $topic;
	function __construct($title)
	{
		$this->id = uniqid();
		$this->addProperty("id",$id);
		$this->addProperty("title",$title);
		$this->topic = array();
		$this->tags=array();
	}
	function addTag($name,$info="")
	{
		$this->tags[count($this->tags)]=new WebDocumentTag($name,$info);
	}
	function updateTags($tags)
	{
		$list = explode(" ",$tags);
		$this->tags=array();
		for($i=0;$i<count($list);$i++)
		{
			$this->addTag($list[$i]);
		}
		echo $this->getTagsAsString();
	}
	function getTagsAsString()
	{
		$str = "";
		
		for($i=0;$i<count($this->tags);$i++)
		{
			$str = $str." ".$this->tags[$i]->tag;
		}
		return trim($str);
	}
	function addProperty($name,$val)
	{
			$this->property[$name]=$val;
	}
	function getProperty($name)
	{
		if(isset($this->property[$name]))
		{
			return $this->property[$name];
		}
	}
	function addTopic($title,$type,$text)
	{
		$this->topic[count($this->topic)]=new WebDocumentArticleTopic($title,$type,$text);
	}
	function setTopic($topic)
	{
		for($i=0;$i<count($this->topic);$i++)
		{
			if($this->topic[$i]->id==$topic->id)
			{
				return $this->topic[$i]=$topic;
			}
		}
	}
	function getTopic($id)
	{
		for($i=0;$i<count($this->topic);$i++)
		{
			if($this->topic[$i]->id==$id)
			{
				return $this->topic[$i];
			}
			
		}
		return null;
	}
	function getTopicList()
	{
		$r = new Recordset();
		$r->addColumns("id","title","type");
		for($i=0;$i<count($this->topic);$i++)
		{
			$r->add($this->topic[$i]->id,$this->topic[$i]->title,$this->topic[$i]->type);
		}
		return $r;
	}

}
class WebDocumentFile
{
	var $id;
	var $name;
	var $data;
	var $filename;
	var $tags;
	var $property;
	var $base64;
	function __construct($name,$file)
	{
		$this->base64=false;
		$this->property = array();
		$this->id = uniqid();
		$this->name=$name;
		$this->filename;
		$this->filename = basename($file);
		$this->data = file_get_contents($file);
	}
	function base64_encode()
	{
		$this->base64=true;
		$this->data = base64_encode($this->data);
	}
	function base64_decode()
	{
		$this->base64=false;
		$this->data = base64_decode($this->data);
	}
	function addTag($name,$info="")
	{
		$this->tags[count($this->tags)]=new WebDocumentTag($name,$info);
	}
	function addProperty($name,$val)
	{
		$this->property[$name]=$val;
	}
	function getData()
	{
		return $this->data;
	}
}
class WebDocumentComment
{
	var $id;
	var $property;
	function __construct($name,$email,$text)
	{
		$this->property = array();
		$this->id = uniqid();
		$this->addProperty("name",$name);
		$this->addProperty("email",$email);
		$this->addProperty("text",$text);
	}
	function addProperty($name,$val)
	{
		$this->property[$name]=$val;
	}
}
class WebDocument
{
	var $validProperty;
	var $pages;
	var $files;
	var $filename;
	var $openStatus;
	var $tags;
	var $comments;
	function __construct($filename="")
	{
		$this->filename=$filename;
		$this->openStatus=false;
		$this->pages= array();
		$this->files = array();
		
	}
	function createDataBox()
	{
		$dx = new DataBox1_2("webdoc");
		$dx->enc = "asci";
		$dx->base64=false;
		$dx->compressionLevel=9;
		return $dx;
	}
	function getDataBox()
	{
		$dx = $this->createDataBox();
		
		$dx->add("property",$this->property);
		$dx->add("pages",$this->pages);
		$dx->add("files",$this->files);
		$dx->add("comments",$this->comments);
		$dx->add("tags",$this->tags);
		$dx->add("version","1.2");
		return $dx;
	}
	function getPageList()
	{
		$r = new Recordset();
		$r->addColumns("title","id");
		for($i=0;$i<count($this->pages);$i++)
		{
			
			$r->add($this->pages[$i]->getProperty("title"),$this->pages[$i]->id);
		}

		return $r;
	}
	function getFileByName($name)
	{
		$id = $this->getFileIdByName($name);
		if($id!="")
		{
			return $this->getFile($id);
		}
		else
		{
			return null;
		}
	}
	function getFileIdByName($name)
	{
		$r = $this->getFileList();
		for($i=0;$i<$r->count;$i++)
		{
			if($r->data[$i]["NAME"]==$name)
			{
				$id = $r->data[$i]["ID"];
				unset($r);
				return $id;
			}
		}
		unset($r);
		return "";
	}
	function getFileList()
	{
		$r = new Recordset();
		$r->addColumns("name","id","filename");
		for($i=0;$i<count($this->files);$i++)
		{
			$r->add($this->files[$i]->name,$this->files[$i]->id,$this->files[$i]->filename);
		}
		return $r;
	}
	function base64_encode()
	{
		for($i=0;$i<count($this->files);$i++)
		{
			$this->files[$i]->base64_encode();
		}		
	}
	function base64_decode()
	{
		for($i=0;$i<count($this->files);$i++)
		{
			$this->files[$i]->base64_decode();
		}		
	}
	function loadDataBox(DataBox1_2 $dx)
	{
		
		$this->openStatus=true;
		$this->property = $dx->getObject("property");
		$this->pages = $dx->getObject("pages");
		$this->files = $dx->getObject("files");
		$this->tags = $dx->getObject("tags");
		$this->comments = $dx->getObject("comments");
		unset($dx);
	}
	function toFile($filename="")
	{
		if($this->filename=="" && $filename!="")
		{
			$this->filename=$filename;
		}
		$dx = $this->getDataBox();
		file_put_contents($this->filename,$dx->toString());
		unset($dx);
	}
	function savePhpDoc($filename="")
	{
		$this->toFile($filename);
	}
	function saveXmlDoc($filename="")
	{
                if($this->filename=="" && $filename!="")
                {
                        $this->filename=$filename;
                }
//                $dx = $this->getDataBox();
                $xs=new XMLSerializer();
//                echo $xs->generateValidXmlFromObj($this);

                file_put_contents($this->filename,$xs->generateValidXmlFromObj($this));
                unset($dx);

	}
	function toSession()
	{
		$dx = $this->getDataBox();
		$dx->toSession();
		unset($dx);
	}
	function fromSession()
	{
		$dx = $this->createDataBox();
		
		if($dx->fromSession())
		{
			$this->loadDataBox($dx);
			return true;
		}
		return false;
	}
	function fromFile($filename)
	{
		$str = "";
		if($this->filename=="" && $filename!="")
		{
			$this->filename=$filename;
		}
		if(file_exists($this->filename))
		{
			$dx = $this->createDataBox();	
			
			if($dx->parse(file_get_contents($this->filename)))
			{
				$this->loadDataBox($dx);
				return true;
			}
			else
			{
				unset($dx);
				$this->message="invalid file";
				return false;
			}
		}
		else
		{
			$this->message="file not found";
		}
	}
	function addProperty($name,$val)
	{

			$this->property[$name]=$val;
			return true;
	}
	function addPage($title,$text)
	{
		$p = new WebDocumentArticle($title);
		$p->addProperty("text",$text);
		$this->pages[count($this->pages)]=$p;
		return $p;
	}
	function addTag($tag)
	{
		$t = new WebDocumentTag($tag);
		$this->tags[count($this->tags)]=$t;
		return $t;
	}
	function addComment($name,$email,$comment)
	{
		$c = new WebDocumentComment($name,$email,$comment);
		$this->comments[count($this->comments)]=$c;
		return $c;
	}
	function addFile($name,$file,$filename="")
	{
		if(file_exists($file))
		{
			$f = new WebDocumentFile($name,$file);
			$this->files[count($this->files)]=$f;
			return $f;
		}
		else
		{
			$this->message="file not found.";
			return false;
		}
	}
	function addUploadedFile($input,$name="")
	{
		$fn = $filename = $_FILES[$input]["name"][0];
		$filename = $_FILES[$input]["tmp_name"][0];
		$this->addFile($_FILES[$input]["name"][0],$_FILES[$input]["tmp_name"][0],$_FILES[$input]["name"][0]);
	}
	function getProperty($name)
	{
		if(isset($this->property[$name]))
		{
			return $this->property[$name];
		}
	}
	function getTag($id)
	{
		for($i=0;$i<count($this->tags);$i++)
		{
			if($this->tags[$i]->id==$id)
			{
				return $this->tags[$i];
			}
		}		
	}
	function getComment($id)
	{
		for($i=0;$i<count($this->comments);$i++)
		{
			if($this->comments[$i]->id==$id)
			{
				return $this->comments[$i];
			}
		}				
	}
	function getPage($id)
	{
		for($i=0;$i<count($this->pages);$i++)
		{
			if($this->pages[$i]->id==$id)
			{
				return $this->pages[$i];
			}
		}
	}
	function setPage(WebDocumentArticle $page)
	{
		for($i=0;$i<count($this->pages);$i++)
		{
			if($this->pages[$i]->id==$page->id)
			{
				$this->pages[$i] = $page;
			}
		}		
	}
	function setFile(WebDocumentFile $file)
	{
		for($i=0;$i<count($this->files);$i++)
		{
			if($this->files[$i]->id==$page->id)
			{
				$this->files[$i] = $page;
			}
		}				
	}
	function getFile($id)
	{
		for($i=0;$i<count($this->files);$i++)
		{
			if($this->files[$i]->id==$id)
			{
				return $this->files[$i];
			}
		}		
	}
	function removePage($id)
	{
		$f = array();
		for($i=0;$i<count($this->pages);$i++)
		{
			if($this->pages[$i]->id!=$id)
			{
				$f[count($f)] = $this->pages[$i];
			}
		}
		$this->pages = $f;
	}
	
	function removeFile($id)
	{
		$f = array();
		for($i=0;$i<count($this->files);$i++)
		{
			if($this->files[$i]->id!=$id)
			{
				$f[count($f)] = $this->files[$i];
			}
		}
		$this->files = $f;
	}
	function downloadPhpDoc()
	{
		echo serialize($this);
	}
	function downloadJsdoc()
	{
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.$this->getProperty("title").'.jsdoc"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		
		echo json_encode($this);
		
		
	}
	function download()
	{
		
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.$this->getProperty("title").'.webdoc"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		/**/
		$dx= $this->getDataBox();
		echo $dx;
		unset($dx);
	}
	function upload($input)
	{
		$fn = $filename = $_FILES[$input]["name"][0];
		$fn = explode(".",$fn);
		if(count($fn)>1)
		{
			if($fn[count($fn)-1]!="webdoc")
			{
				echo "Invalid file. Only webdoc file support";
				return;
			}
		}
		
		$filename = $_FILES[$input]["tmp_name"][0];
		$data = file_get_contents($filename);
		$dx =$this->createDataBox();
		
		if($dx->parse($data))
		{
			$this->loadDataBox($dx);
		}
		else
		{
			echo "Error in file opening. invalid file.";
		}
	}
	function downloadXmlDoc()
	{
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.$this->getProperty("title").'.xmldoc"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		$xs=new XMLSerializer();
		echo $xs->generateValidXmlFromObj($this);
	}
	function downloadEpub()
	{
		
		$epub = new TPEpubCreator();
		$epub->temp_folder = 'temp_folder/';
		$epub->epub_file = $this->getProperty("title").'.epub';
		$epub->title = $this->getProperty("title");
		$epub->creator = $this->getProperty("writer");
		$epub->language = 'hi';
		$epub->rights = 'All World Gayatri Pariwar';
		$epub->publisher = 'http://www.awgp.org/';
		$epub->css = '*, *:before, *:after {
			-moz-box-sizing: border-box;
			-webkit-box-sizing: border-box; 
			box-sizing: border-box; 
		}
		body {
			margin: 0;
			padding: 0;
			line-height: 1.5;
			text-align: justify;
		}
		img {
			display: block !important;
			margin: 0.5em auto !important;
			max-width: 100% !important;
			height: auto !important;
		}
		pre {
			white-space: pre-wrap;
			display: block;
			margin: 0.5em;
		}
		h1, h2, h3, h4, h5, h6 {
			text-align: center;
		}
		p , table{
			margin: 0.3em;
		}
		table td, table th {
			border-bottom: 1px solid #ccc;
			font-size: 80%;
			color: #444;
			text-align: left;
		}
		.indexList{
			list-style:none;
			padding:0px;
		}
		.indexList li{
			margin:20px;
			border-bottom:1px solid #DDD;
			padding-bottom:15px;
		}
		';
		
		for($i=0;$i<count($this->pages);$i++)
		{
			
				$page = $this->pages[$i];
				for($j=0;$j<count($page->topic);$j++)
				{
					$topic = $page->topic[$j];
					//print_r($topic);
					$text =  $topic->text;
					$text = str_replace('<br>','<br />',$text);
					$text = '<h2>'.$topic->title.'</h2><hr />'.$text;
					$epub->AddPage( $text ,false, $topic->title );
					//echo $topic->text;
				}
			
		}
		if(!is_dir("var/image"))
		{
			mkdir("var/image");
		}
		for($i=0;$i<count($this->files);$i++)
		{
			$file = $this->files[$i];
			$name = $file->name;
			$data = $file->data;
			$fileName = "var/image/".($i+1).".jpg";
			file_put_contents($fileName,$data);
			
			if($name=="cover")
			{
				$epub->AddImage( $fileName, false, true );
			
			}
			
		}
		
		if(!$epub->error)
		{
			$epub->CreateEPUB();
			if(!$epub->error)
			{
				//echo "AS";
				$epub->download();
				
				$epub->delete();
				
				if(is_dir("var/image"))
				{
					
					exec("rm -r var/image",$out);
					
				}
			}
			else
			{
				
				echo $epub->error;
			}
		}
		else
		{
			echo $epub->error;
		}
		
		//print_r($this);
		//print_r($epub);
		
	}
}
?>
