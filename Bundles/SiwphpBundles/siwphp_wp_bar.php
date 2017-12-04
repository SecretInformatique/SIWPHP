<?php
if(!defined('ABSPATH')) exit;

class SI_SIWPHP_WP_BAR extends SI_APP
{

    private $template;

    public function __construct()
    {
        if ( current_user_can('administrator') )
        {
			add_action('template_include', [$this, 'si_define_current_theme_file'], 1000);
			add_action('admin_bar_menu', [$this, 'si_create_node_toolbar'], 999);
		}
	}

    /**
    * Method: Défini le nom du fichier du template courant
    * @param $template string: Locale du fichier
    * @return string: Nom du fichier
    */
    public function si_define_current_theme_file($template)
    {
        $this->template = basename($template);
        return $template;
    }

    /**
    * Method: Met dans la toolbar un menu de debug
    * @param $wp_admin_bar array: Options de la toolbar
    * @return void
    */
    public function si_create_node_toolbar($wp_admin_bar)
    {

        $wp_admin_bar->add_node([
            'id' => 'debug_info',
            'title' => '<div class="wp-menu-image dashicons-before dashicons-admin-tools ab-icon" style="display:inline-block;float:none;vertical-align:middle;width:20px;padding:0;height:auto;line-height:1;"></div>Debug toolbar',
            'parent' => false
        ]);

        if ($this->template)
            $wp_admin_bar->add_node([
                'id' => 'current_file',
                'title' => 'Fichier : ' . $this->template,
                'parent' => 'debug_info'
            ]);

        if ($postType = get_post_type())
            $wp_admin_bar->add_node([
                'id' => 'current_post_type',
                'title' => 'Type : ' . $postType,
                'parent' => 'debug_info'
            ]);

        $wp_admin_bar->add_node([
            'id' => 'num_queries',
            'title' => get_num_queries().' requêtes en '. timer_stop(0) .' secondes',
            'parent' => 'debug_info'
        ]);


        $wp_admin_bar->add_node([
            'id' => 'author',
            'href' => 'http://siwphp.secretinformatique.net',
            'meta'   => ['target'=>'_blank'],
            'title' => 'Développé par SI WPHP',
            'parent' => 'debug_info'
        ]);
    }
}
