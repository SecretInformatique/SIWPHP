<?php
if(!defined('ABSPATH')) exit;

class SI_FORM extends SI_APP
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
		if ($this->app()->getOptions('form') && is_array($this->app()->getOptions('form')))
        	$this->options = array_merge($this->options, $this->app()->getOptions('form'));

        if ( is_admin() )
        {
            // On retire les création des meta boxes manuel
            add_action('init',  [$this, 'si_remove_custom_field_meta_boxes']);
            // création des meta boxes
            add_action( 'add_meta_boxes', [&$this, 'si_add_meta_box']);
            // Sauvegarde des meta boxes
            add_action( 'save_post', [&$this, 'si_save_post'] );
        }

    }

    /**
    * Method: Désactiver les meta boxes manuel
    * @return void
    */
    public function si_remove_custom_field_meta_boxes() {

        remove_post_type_support('post', 'custom-fields');
        remove_post_type_support('page', 'custom-fields');
        if ($postType_add = get_post_types( ['public' => true , '_builtin' => false], 'names', 'and' ))
        {
            foreach ($postType_add as $value)
            {
                remove_post_type_support($value, 'custom-fields');
            }
        }
    }

    /**
	* Method: Ajout de meta box
	* @param $options array: Options
	* @return void
	*/
    public function si_add_meta_box()
    {
        foreach ($this->options as $key => $value) {

            if ( !isset($value['title']) && !isset($value['postType']) && !isset($value['form']) )
                die('<b>SI-WPHP&nbsp;&nbsp;&raquo;</b>&nbsp;&nbsp;&nbsp; Form: les données title, postType, champ sont obligatoires !');

            $value['context'] = isset($value['context']) ? $value['context'] : 'normal';
            $value['priority'] = isset($value['priority']) ? $value['priority'] : 'high';
            $value['champ']['id_meta_box'] = 'si_'.$key;

            add_meta_box($value['champ']['id_meta_box'], $value['title'], array(&$this, 'si_render'), $value['postType'], $value['context'], $value['priority'], $value['champ']);
        }
    }

    /**
	* Method: Render des meta boxes
	* @param $post array: POST
    * @param $post array: callback
	* @return string: Html
	*/
    public function si_render($post, $callback)
    {

        if ($champ = $callback['args'])
        {

            $id_meta_box = $champ['id_meta_box'];
            unset($champ['id_meta_box']);

            foreach ($champ as $value)
            {
                $value['id_meta_box'] = $id_meta_box;
                $value['name'] = 'si_'. $value['name'];
                if (!isset($value['options'])) $value['options'] = array();
                if ($meta_value = get_post_meta($post->ID, $value['name'], true)) $value['options']['value'] = $meta_value;

                $path = dirname(__FILE__).'/views/'.$value['type'].'.php';
                extract($value);

                if (file_exists($path))
                {
                    include $path;
                }
                else
				{
                    die('<b>SI-WPHP&nbsp;&nbsp;&raquo;</b>&nbsp;&nbsp;&nbsp; Form: Le fichier '.$path.' n\'existe pas !');
				}
            }
        }
    }

    /**
	* Method: Enregistrement des meta boxes
	* @param $post_id int: Id du post
	* @return void
	*/
	public function si_save_post($post_id)
    {

        // Protection
        if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)
			return $post_id;

		if (isset($_POST['post_type']) && 'page' == $_POST['post_type'])
        {
			if (!current_user_can('edit_page', $post_id))
				return $post_id;
		}
        else
        {
			if (!current_user_can( 'edit_post', $post_id))
				return $post_id;
		}


        if (isset($this->options) && !empty($this->options))
        {
            foreach ($this->options as $value)
            {
                if (isset($value['champ']) && !empty($value['champ']))
                {
                    foreach ($value['champ'] as $value)
                    {
                        $value['name'] = 'si_'. $value['name'];
                        if (isset($_POST[$value['name']]))
                        {
                            update_post_meta($post_id, $value['name'], $_POST[$value['name']]);
                        }
                    }
                }
            }
        }
    }

    /**
    * Method: Retourne un custum menu
    * @param $arg string: options
    * @param $default string: default
    * @return objet|false|void
    */
    public function get($arg, $default='')
    {
        if (isset($arg) && is_string($arg)) {
            $return = get_post_meta(get_the_ID(), 'si_'. $arg, true);
            $return = $return ? $return : $default;
        }
        return $return;
    }
}
