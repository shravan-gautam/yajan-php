<?php
/**
 * ArrayToXML Package
 * This package allows for the easy setup of web services using only arrays
 * 
 * @package ArrayToXML
 * @version 1
 * 
 * @author Killian McHale (kmchale@gmail.com)
 * @copyright Copyright Killian McHale 2008
 * @license GNU General Public License (GPL)
 */

// Requires PEAR HTTP_Request Package
require_once( "/usr/share/php/HTTP/Request2.php" );

/**
 * ArrayToXML Class
 * This is the base class of the ArrayToXML package. It contains universal variables and methods which can be called from the other classes.
 * 
 * @package ArrayToXML
 */
class XMLDocument
{
    protected $requestXML    = null;
    protected $requestArray  = null;
    protected $responseXML   = null;
    protected $responseArray = null;
    protected $rownum = 0;
    /**
     * First XML element name character
     *
     * @var string
     * @access private
     */
    protected $firstNamingChar = "";
    
    /**
     * Special character replacements
     *
     * @var array
     * @access private
     */
    protected $specialNamingChars = array( ' ' => '_SPC_',
                                           '!' => '_EXC_',
                                           '£' => '_PND_',
                                           '€' => '_EUR_',
                                           '\\' => '_BSL_',
                                           '/' => '_FSL_',
                                           '@' => '_ATS_',
                                           '~' => '_SQG_',
                                           '#' => '_HSH_',
                                           '+' => '_PLS_',
                                           '|' => '_DIV_', 
                                           '\'' => '_SQT_', 
                                           '"' => '_QUT_', 
                                           '?' => '_QUS_',  
                                           '<' => '_GRT_',   
                                           '>' => '_LST_', 
                                           ',' => '_CMA_',
                                           ';' => '_SCL_', 
                                           '=' => '_EQL_',     
                                           '^' => '_UPA_',
                                           '*' => '_STR_',
                                           '(' => '_BRL_',
                                           ')' => '_BRR_',
                                           '[' => '_SQL_',
                                           ']' => '_SQR_',
                                           '{' => '_PAL_',
                                           '}' => '_PAR_',
                                           '&' => '_AMP_',
                                           '%' => '_PER_' );
    
    /**
     * Converts an XML document to a multi-dimensional Array
     *
     * @param string $xml
     * @return array
     */
    function toArray( $xml )
    {
		$this->rownum=0;
        $newXML = new SimpleXMLElement( $xml );
        return $this->getArrayElements( $newXML );
    }
    
    /**
     * Used by toArray( ) - Converts XML elements to Array elements
     *
     * @param SimpleXMLElement $newXML
     * @return array
     * @access private
     */
    private function getArrayElements( $newXML )
    {
        foreach( $newXML->children( ) as $child )
        {
            $name = $child->getName( );
            
            if( count( $child->children( ) ) == NULL )
            {
                $newArray[$name] = (string) $child;
            }
            else
            {
                $newArray[$name] = $this->getArrayElements( $child );
				$this->rownum++;
            }
        }
        
        return $newArray;
    }
    
    /**
     * Converts a multi-dimensional Array to an XML document
     *
     * @param array $array
     * @return string
     */
    function toXML( $array )
    {
        $newXML = new SimpleXMLElement( "<array2XML>\n</array2XML>" );
        return $this->getXMLElements( $newXML, $array )->asXML( );
    }
    
    /**
     * Used by toXML( ) - Converts Array elements to XML elements
     *
     * @param SimpleXMLElement $newXML
     * @param array $array
     * @return SimpleXMLElement
     * @access private
     */
    private function getXMLElements( $newXML, $array )
    {
        foreach( $array as $key=>$value )
        {
			if(gettype($key)!="string")
			{
				$key = "A".$key;
			}
            if( is_array( $value ) )
            {
                $child = $newXML->addChild( $key );
                $this->getXMLElements( $child, $value );
            }
            else
            {
                $newXML->addChild( $key, $value );
            }
        }
        
        return $newXML;
    }
    
    /**
     * Removes/Replaces invalid XML element name characters
     *
     * @param string $string
     * @return string
     * @access private
     */
    private function specialChars( $string )
    {
		if($string!="")
		{
			return "";
		}
        $searchArray = array_keys( $this->specialNamingChars );
        $replaceArray = array_values( $this->specialNamingChars );
		
        if( substr_count( $string, $this->firstNamingChar ) )
        {
            $string = str_replace( $this->firstNamingChar, "", $string );
            $string = str_replace( $replaceArray, $searchArray, $string );
        }
        else
        {
            $string = $this->firstNamingChar . $string;
            $string = str_replace( $searchArray, $replaceArray, $string );
        }
        
        return $string;
    }
    
    /**
     * Returns request Array
     *
     * @return array
     */
    public function getRequestArray( )
    {
        return $this->requestArray;
    }
    
    /**
     * Returns request XML
     *
     * @return string
     */
    public function getRequestXML( )
    {
        return $this->requestXML;
    }
    
    /**
     * Returns response Array
     *
     * @return array
     */
    public function getResponseArray( )
    {
        return $this->responseArray;
    }
    
    /**
     * Returns response XML
     *
     * @return string
     */
    public function getResponseXML( )
    {
        return $this->responseXML;
    }
}

/**
 * ArrayToXMLServer Class
 * This is the class which is called on the server side. It extends from ArrayToXML.
 * 
 * @package ArrayToXML
 *
 */
