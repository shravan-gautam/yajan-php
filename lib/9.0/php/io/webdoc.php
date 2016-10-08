<?php
class WebDocumentTag
{
	var $id;
	var $tag;
	var $info;
	function WebDocumentTag($tag,$info="")
	{
		$this->info = $info;
		$this->id = uniqid();
		$this->tag = $tag;
	}
}

class WebDocumentArticle
{
	var $property;
	var $id;
	var $tags;
	function WebDocumentArticle($title)
	{
		$this->id = uniqid();
		$this->addProperty("id",$id);
		$this->addProperty("title",$title);
	}
	function addTag($name,$info="")
	{
		$this->tags[count($this->tags)]=new WebDocumentTag($name,$info);
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

}
class WebDocumentFile
{
	var $id;
	var $name;
	var $data;
	var $filename;
	var $tags;
	var $property;
	function WebDocumentFile($name,$file)
	{
		$this->property = array();
		$this->id = uniqid();
		$this->name=$name;
		$this->filename;
		$this->filename = basename($file);
		$this->data = file_get_contents($file);
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
	function save($path)
	{
		$path=rtrim($path,"/");
		if(!is_dir($path))
		{
			mkdir($path);
		}
		file_put_contents("$path/$this->filename",$this->data);
		return "$path/$this->filename";
	}
}
class WebDocumentComment
{
	var $id;
	var $property;
	function WebDocumentComment($name,$email,$text)
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
	function WebDocument($filename="")
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
	function getPageCount()
	{
		return count($this->pages);
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
				return false;
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
}
?>