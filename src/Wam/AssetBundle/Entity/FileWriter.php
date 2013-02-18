<?php
namespace Wam\AssetBundle\Entity;

class FileWriter
{

	/**
	 * fileName
	 * @var string
	 **/
	private $fileName;

	/**
	 * className
	 * @var string
	 **/
	private $className;

	/**
	 * destination
	 * @var string
	 **/
	private $destination;

	/**
	 * Properties
	 * @var array
	 **/
	private $properties = array();

	/**
	 * Content of file
	 * @var string
	 **/
	private $content;


	/**
	 * Constructor
	 * @param string $name
	 * @return FileWriter
	 **/
	public function __construct($name = '')
	{
		$this->setClassName($name);
		return $this;
	}

	/**
	 * setClassName
	 * @param string $class_name
	 * @return FileWriter
	 **/
	public function setClassName($class_name)
	{
		$this->className = $class_name;
		return $this;
	}

	/**
	 * setFileName
	 * @param string $file_name
	 * @return FileWriter
	 **/
	public function setFileName($file_name)
	{
		$this->fileName = $file_name;
		return $this;
	}

	/**
	 * setDestination
	 * @param string $destination
	 * @return FileWriter
	 **/
	public function setDestination($destination)
	{
		$this->destination = $destination;
		return $this;
	}

	/**
	 * addProperty
	 * @param string $key
	 * @param mixed $value
	 * @return FileWriter
	 **/
	public function addProperty($key, $value)
	{
		$this->properties[$key] = $value;
		return $this;
	}

	/**
	 * setContent
	 * @param string $content
	 * @param mixed $value
	 * @return FileWriter
	 **/
	public function setContent($content)
	{
		$this->content = $content;
		return $this;
	}

	/**
	 * getFilePath
	 * @return string
	 **/
	public function getFilePath()
	{
		return $this->destination . '/' . $this->fileName;
	}

	/**
	 * write
	 * @return void
	 **/
	public function write()
	{
		$template = $this->getTemplate();
		$template = $this->hydrateTemplate($template);

		$handle = fopen($this->getFilePath(), 'w');
		fwrite($handle, $template);
		fclose($handle);
	}

	/**
	 * getTemplate
	 * @return string
	 **/
	private function getTemplate()
	{
		return file_get_contents(__DIR__ . '/../Resources/Templates/WamEntity.txt');
	}

	/**
	 * hydrateTemplate
	 * @param string $template
	 * @return string
	 **/
	private function hydrateTemplate($template)
	{
		$properties = $this->createPropertyString();
		
		$template = str_replace('{{properties}}', $properties, $template);
		$template = str_replace('{{classname}}', $this->className, $template);
		$template = str_replace('{{namespace}}', 'namespace ' . $this->getNamespace() . ';', $template);
		$template = str_replace('{{use}}', "use Wam\AssetBundle\Entity\AbstractEntity;\nuse Wam\AssetBundle\Entity\Base\AssetDefinition;", $template);

		return $template;
	}

	/**
	 * createPropertyString
	 * @return string
	 **/
	private function createPropertyString()
	{
		$string = '';

		foreach($this->properties as $key => $value) {
			$type = gettype($value);
			$string .= <<<EOL
/**
	 * $key
	 * @var $type
	 */

EOL;
			if(is_array($value)) {
				$string .= '	protected $' . $key . ' = array(' . "\n";
				foreach($value as $k => $item) {
					$string .= "		'" . $item . "'";
					if(isset($value[$k+1])) {
						$string .=  ",\n";
					} else {
						$string .= "\n";
					}
				}
				$string .= '	);';
			} else {
				$string .= '	protected $' . $key . ' = ' . $value;
			}

			$string .= "\n\n";
		}

		return $string;
	}

	/**
	 * getNamespace
	 * @param boolean full
	 * @return string
	 **/
	public function getNamespace($full = false)
	{
		$arr = explode('src/', $this->destination);
		$return = str_replace('/', '\\', $arr[1]);

		return ($full) ? $return . '\\' . $this->className : $return;
	}
	
	

	

}