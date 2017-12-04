<?php
if(!defined('ABSPATH')) exit;

class SI_IMAGE extends SI_APP
{
    private $options = [
        'thumbnail' => 'all'
    ];

    /**
    * Method: Constructeur
    * @param $app obj: Options
    * @return void
    */
    public function __construct(SI_WPHP $app)
    {
        parent::__construct($app);

        // Init Options
		if ($this->app()->getOptions('image') && is_array($this->app()->getOptions('image')))
        	$this->options = array_merge($this->options, $this->app()->getOptions('image'));

        // création des post thumbnails
        add_action( 'after_setup_theme', [$this, 'si_add_image_size']);
        add_filter( 'image_size_names_choose', [$this, 'custom_image_sizes_choose']);
    }

    /**
	* Method: Mise en place du post thumbnails & de nouvelle taille de thumbnails
	* @param $options array: Options
	* @return void
	*/
    public function si_add_image_size()
    {

        // Mise en place du post thumbnails
        if (isset($this->options['thumbnail']) && is_array($this->options['thumbnail'] ))
            add_theme_support('post-thumbnails', $this->options['thumbnail']);
                else
                    add_theme_support('post-thumbnails');

        // Création de nouvelle taille de thumbnails
        if (isset($this->options['size']) && is_array($this->options['size']))
        {
            foreach ($this->options['size'] as $key => $value)
            {
                if (!isset($value[1])) $value[1] = $value[0];
                if (!isset($value[2])) $value[2] = [ 'center', 'center' ];
                add_image_size( $key, (int)$value[0], (int)$value[1], $value[2] );
            }
        }

    }

    /**
    * Method: Mise en place de nouvelle taille de thumbnails dans l'admin
    * @param $sizes array: taille & nom des thumbnails
    * @return void
    */
    public function custom_image_sizes_choose( $sizes )
    {

        // Création de nouvelle taille de thumbnails
        if (isset($this->options['size']) && is_array($this->options['size']))
        {
            foreach ($this->options['size'] as $key => $value)
            {
                $custom_sizes[$key] = ucfirst(implode(' ', explode('_', $key)));
            }
        }

        return array_merge( $sizes, $custom_sizes );
    }
}
