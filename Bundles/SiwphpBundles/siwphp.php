<?php
if(!defined('ABSPATH')) exit;

class SI_SIWPHP extends SI_APP
{

    private $options = [
        'visibility' => 1
    ];

    public function __construct(SI_WPHP $app) {
		parent::__construct($app);

        // Init Options
		if ($this->app()->getOptions('siwphp') && is_array($this->app()->getOptions('siwphp')))
        	$this->options = array_merge($this->options, $this->app()->getOptions('siwphp'));

        // Texte du footer en admin
        add_filter('admin_footer_text', [$this, 'si_admin_footer_text']);

        // Visibilité dans le menu
        if ($this->options['visibility'])
        {
            add_action('admin_menu', [$this, 'si_add_admin_menu']);
        }

        // wp_bar
        $path = dirname(__FILE__).'/siwphp_wp_bar.php';
        if (file_exists($path))
        {
            include_once $path;
            new SI_SIWPHP_WP_BAR();
        }

	}

    public function si_admin_footer_text ()
    {
		echo 'Merci de faire de <a href="http://fr.wordpress.org/" target="_blank">WordPress</a> & <a href="http://siwphp.secretinformatique.net" target="_blank">SI WPHP</a> votre outil de création.';
	}

    /**
	* Method: Ajout des menus du Framework
	* @return void
	*/
	public function si_add_admin_menu() {
		add_menu_page( 'SI-WPHP', 'SI-WPHP', 'manage_options', 'siwphp', function() {
            echo
                '<div class="wrap">

                	<h2>Version</h2>
                	<p>
                		SI-WPHP est un Framework pour Wordpress.<br/>
                		Vous disposez de la version : <strong style="color:#0074A2;">Beta 2.1</strong>
                	</p>

                </div>';
        },  $this->app()->path('theme').'/siwphp/Bundles/SiwphpBundles/images/icon.jpg', 100 );
		add_submenu_page('siwphp', 'Documentation', 'Documentation', 'manage_options', 'siwphp-version', function() {
            echo
                '<div class="wrap">
                	<iframe id="iframe-siwphp" style="width:100%;min-height:400px;" src="http://siwphp.secretinformatique.net/doc/beta/"></iframe>
                </div>

                <script type="text/javascript">
                	var addEvent = function(e, type, eventHandle) {
                		if (e == null || typeof(e) == "undefined") return;
                		if ( e.addEventListener ) {
                			e.addEventListener( type, eventHandle, false );
                		} else if ( e.attachEvent ) {
                			e.attachEvent( "on" + type, eventHandle );
                		} else {
                			e["on"+type]=eventHandle;
                		}
                	};

                	function resize () {
                		var height = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
                		obj = document.getElementById("iframe-siwphp");
                		obj.style.height = (height - 100) + "px";
                	}

                	addEvent(window, "resize", resize);
                	resize();
                </script>';
        });
	}

}
