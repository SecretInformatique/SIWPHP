<?php
if(!defined('ABSPATH')) exit;

class SI_WPHP
{
	protected $authorization = [
				'siwphp',
				'menu',
				'image',
				'form'
			],
			$instance = [ 'siwphp' ],
			$options = array();

	private static $_instance;

	/**
	* Method: Constructeur
	* @param $options array: Options
	* @return void
	*/
	public function __construct(array $options = array())
	{
		if (!empty($options)) $this->hydrate($options);
	}

	/**
	* Method: Hydratation des options
	* @param $options array: Options
	* @return void
	*/
	private function hydrate(array $options)
	{

		foreach ($options as $key => $value)
		{
			if (in_array($key, $this->authorization))
			{
				if (!in_array($key, $this->instance)) array_push($this->instance, $key);
				$this->options = array_merge($this->options, [ $key => $value ]);
			}

		}
	}

	/**
	* Method: Ajout d'options
	* @param $options array: Options
	* @return void
	*/
	public function add(array $options)
	{
		$this->hydrate($options);
	}

	/**
	* Method: Get Options
	* @param $arguments string: Arguments
	* @return array options
	*/
	public function getOptions($argument = null)
	{
		if (!empty($argument))
		{
			return isset($this->options[$argument]) ? $this->options[$argument] : 'null';
		}

		return $this->options;
	}

	/**
	* Method: Fonction magique
	* @param $nom string: Méthode
	* @param $arguments string: Arguments
	* @return obj: Instance
	*/
	public function __call($nom, $arguments='null')
	{
		if (isset(self::$_instance[$nom]))
		{
			if (!empty($arguments) && count($arguments) == 1)
			{
				if (isset(self::$_instance[$nom]))
				{
					$instance = self::$_instance[$nom];
					return $instance->get($arguments[0]);
				}
			}
			else
			{
				return self::$_instance[$nom];
			}
		}

		die('<b>SI-WPHP&nbsp;&nbsp;&raquo;</b>&nbsp;&nbsp;&nbsp; La méthode : "'. $nom .'" n\'existe pas !');
	}

	/**
	* Method: Lancement de SI-WPHP
	* @return void
	*/
	public function send()
	{
		foreach ($this->instance as $value)
		{
			if (!isset(self::$_instance[$value]))
			{
				$path = $this->path($value .':'. $value);
				if (file_exists($path))
				{
					include_once $path;
					$class = 'SI_'. strtoupper($value);
					self::$_instance[$value] = new $class($this);
				}
				else
				{
					die('<b>SI-WPHP&nbsp;&nbsp;&raquo;</b>&nbsp;&nbsp;&nbsp; Le fichier : "'. $path .'" n\'existe pas !');
				}
			}
		}
	}

	/**
	* Method: Chemin utile pour le script
	* @param $var string: Variable
	* @return string: Chemin
	*/
	public function path($var)
	{

		switch ($var)
		{
			case 'site':
				$path = get_bloginfo('url');
			break;

			case 'url':
				$path = get_bloginfo('wpurl');
			break;

			case 'theme':
				$path = get_bloginfo('template_directory');
			break;

			case 'upload':
				$path = get_bloginfo('url').'/wp-content/uploads';
			break;

			default:
				$var = explode (':', $var);
				if (is_array($var) && count($var) == 2)
					$path = str_replace('index.php','',__FILE__).'Bundles/'. ucfirst($var[0]) .'Bundles/'. strtolower($var[1]) .'.php';
		}

		return isset($path) ? $path : false;
	}

	/**
	* Method: Debug rapide
	* @param $var all: Variable à analyser
	* @param $attr string: Index de repérage
	* @return string: Debug
	*/
	public function debug($var, $attr = '')
	{
		$debug = debug_backtrace();
		$attr = !empty($attr) ? ' | '. $attr : '' ;
		echo 	'<div style="margin:30px 5px; background:#000; color:#fff; padding:10px; position:relative; font-size:14px; font-family:"Helvetica Neue", Helvetica, Arial, sans-serif;">
					<p># <strong> '.$debug[0]['file'].'</strong> - l. '.$debug[0]['line'] . $attr .' </p>
					<pre style="font-size:12px; width:100%; font-family:Menlo,Monaco,Consolas,"Courier New",monospace;">';
						if (is_string($var) || is_numeric($var))
							var_dump($var);
						else
							print_r($var);
					echo '</pre>
				</div>';
	}
}


abstract class SI_APP
{
	private $app;

	/**
	* Method: Constructeur SI_APP
	* @param $app Objet : SI_WPHP
	* @return void
	*/
	public function __construct(SI_WPHP $app)
	{
		$this->app = $app;
	}

	/**
	* Method: Notre application
	* @return obj: SI_WPHP
	*/
	public function app()
	{
		return $this->app;
	}
}
