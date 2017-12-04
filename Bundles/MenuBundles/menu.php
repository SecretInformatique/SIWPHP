<?php
if(!defined('ABSPATH')) exit;

class SI_MENU extends SI_APP
{
    private $options = array();

    /**
	* Method: Constructeur
	* @param $app obj: Options
	* @return void
	*/
    public function __construct(SI_WPHP $app)
    {
        parent::__construct($app);

        // Init Options
		if ($this->app()->getOptions('menu') && is_array($this->app()->getOptions('menu')))
        	$this->options = array_merge($this->options, $this->app()->getOptions('menu'));

        add_theme_support('menus');
        add_action( 'after_setup_theme', [$this, 'si_register_nav_menu']);
    }

    /**
    * Method: Enregistrer un custum menu
    * @return void
    */
    public function si_register_nav_menu()
    {
        foreach($this->options as $key => $value)
        {
            if (is_numeric($key) && is_string($value))
                register_nav_menu($value, $value);
                    else if (is_string($key) && is_string($value))
                        register_nav_menu($key, $value);
        }
    }

    /**
    * Method: Retourne un custum menu
    * @param $args array|string: Options
    * @return objet|false|void
    */
    public function get($args)
    {

        $options = [
            'container' => 'nav',
            'fallback_cb' => false,
            'echo' => false
        ];

        if (is_string($args))
            $options['theme_location'] = $args;
        if (is_array($args))
            $options = wp_parse_args ($args, $options);

        return has_nav_menu($options['theme_location']) ? wp_nav_menu($options) : false;
    }
}
