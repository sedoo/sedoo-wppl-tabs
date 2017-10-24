<?php 
/**
* Plugin Name: sedoo-wppl-tabs
* Description: Plugin permettant l'ajout d'un module à onglets dans le contenu d'un post ou d'une page via un shortcode
* Author: Esteban
* Version: 1.0.0
* GitHub Plugin URI: sedoo/sedoo-wppl-tabs
* GitHub Branch: master
*/


function em_plugin_init(){

	/* Gestion de la dépendance de ACF */
	if ( ! function_exists('get_field') && current_user_can( 'activate_plugins' ) ) {

		add_action( 'admin_init', 'em_plugin_deactivate');
		add_action( 'admin_notices', 'em_plugin_admin_notice');

		//Désactiver le plugin
		function em_plugin_deactivate () {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
		
		// Alerter pour expliquer pourquoi il ne s'est pas activé
		function em_plugin_admin_notice () {
			
			echo '<div class="error">Le plugin "Tabs" requiert ACF Pro pour fonctionner <br><strong>Activez ACF Pro ci-dessous</strong> ou <a href=https://wordpress.org/plugins/advanced-custom-fields/> Téléchargez ACF Pro &raquo;</a><br></div>';

			if ( isset( $_GET['activate'] ) ) 
				unset( $_GET['activate'] );	
		}

	} else {
		// Le plugin est activé 

		require_once 'cpt-em-tabs.php';

		/* Appelée au clic sur l'élément du menu */
		function em_admin ( ) {

			// page qui gère l'affichage des réglages du plugin	dans l'admin
			// et la sauvegarde des données entrées dans les inputs
			include('tabs-admin.php'); 	
		}

		/* Custom fonctions chargées au chargement du menu de l'admin */
		function em_admin_actions () {
		    // ajoute un élément 'Tabs Button Settings' dans le menu des réglages
		    // em_admin est le nom de la fonction appelée au clic sur l'élément du menu
		    // add_options_page( 'Tabs Button', 'Tabs Button Settings', 'edit_pages', 'tabs-button-admin', 'em_admin');

		    // ajoute l'élément au menu simple plutôt que dans les réglages 
		    add_menu_page( 'Tabs Button', 'Tabs Settings', 'edit_pages', 'tabs-button-admin', 'em_admin', 'dashicons-index-card');
		}
		add_action('admin_menu', 'em_admin_actions');

		/**
		 * Fonction qui crée le shortcode avec l'API Shortcodes de WordPress 
		 */
		function em_shortcode ( $atts ) {

			/**
			 * @todo Gestion du postType autrement que en dur?
			 */
			$postType = 'tabs';
		
			extract(shortcode_atts(array( 'posts' => 1,'id' => '-1'), $atts) );

			/* Parcours de tous les Posts de type $postType et récupération des IDs */
			$args = array( 'post_type' => $postType, 'fields' => 'ids');

			$cpt_ids = new WP_Query( $args );
			$arr = array();
			while ( $cpt_ids->have_posts() ) : $cpt_ids->the_post();
				$postTypeID = get_the_ID();
				array_push($arr,$postTypeID);
			endwhile;

			wp_reset_postdata();
			
			/* déclaration et initialisation de la chaine de caractère de retour */
			$return_string = ''; 

			if ( ! in_array ($id, $arr) ) {
				// si l'ID spécifié n'existe pas, le module d'onglets n'existe pas
				$return_string .= "<p>Le module d'onglets que vous tentez d'afficher n'existe pas. (id = $id)</p>";
			} else {
				// sinon, le module d'onglets existe et on structure son affichage
				
				// Contrôler si ACF est actif
				if ( !function_exists('get_field') ) return;

				$return_string .= '<section class="worko-tabs">';

				$title_tabs = [];
				$content_tabs = [];
				for($i=1; $i<7; $i++){ 
					$title_tabs[$i] = get_field('title-tab'.$i.'-custom_tabs', $id);
					$content_tabs[$i] = get_field('content-tab'.$i.'-custom_tabs', $id);
				}

				// insérer les input radio
				for($i=1; $i<7; $i++){ 
					if($content_tabs[$i] !== "" && $title_tabs[$i] !== "") {
						$return_string .= '<input class="state" type="radio" name="tabs-state"'; 
						if($i == 1){
							$return_string .= 'checked="checked" ';
						}
						$return_string .= 'id="tab-'.$i.'"/>';
					}
				}
				$return_string .= '<div class="tabs flex-tabs">';

				// Afficher les titres des onglets dans des <label>
				for($i=1; $i<7; $i++){
					if($content_tabs[$i] !== "" && $title_tabs[$i] !== "") {
						$return_string .= '<label class="tab" id="tab-'.$i.'-label" for="tab-'.$i.'" >'.$title_tabs[$i].'</label>';
					}
				} 

				// Afficher le contenu des onglets
				for($i=1; $i<7; $i++){ 
					if($content_tabs[$i] !== "" && $title_tabs[$i] !== "") {
						$return_string .= '<div id="tab-'.$i.'-panel" class="panel';
						if($i == 1){ 
							$return_string .= ' active';
						} 
						$return_string .= '">' . $content_tabs[$i] .'</div>';
					}
				}			
				$return_string .= '</div></section>';	

			}					
			return $return_string; //retour de la chaîne de caractère concaténée
		}

		/* Enregistrement de la feuille de style principale du plugin */
		function em_register_assets () {
			wp_register_style('tabs-global', plugin_dir_url( __FILE__ ) . 'css/tabs-global.css', array(), 1.0 );
			wp_enqueue_style( 'tabs-global' );
		}
		add_action('init', 'em_register_assets');

		/* Enregistrement du shortcode */
		function em_register_shortcode () {
			add_shortcode('tabs', 'em_shortcode');
		}
		add_action('init', 'em_register_shortcode');
		add_filter('widget_text', 'do_shortcode');

		/* Enregistrement du plugin pour le bouton */
		function em_add_plugin( $plugin_array ) {
			$plugin_array['tabsbutton'] = plugin_dir_url( __FILE__ ) . 'js/tabsbutton.js';	
			return $plugin_array;
		}

		/* Enregistrement du bouton qui génère le shortcode */
		function em_register_button ( $buttons ) {
			// on n'affiche le bouton que si ACF Pro est activé
			if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) )	array_push( $buttons, "|", "tabsbutton" );
			return $buttons;
		}
		/* Gestion du plugin du bouton */
		function em_plugin_button () {

			// sort de la fonction si l'utilisateur n'a pas les droits d'édition 
			// ou s'il n'est pas en mode WYSIWYG
			if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;

			// s'il a les droits
			if ( get_user_option( 'rich_editing' ) == 'true' ) {
				global $typenow;
				if (empty($typenow) && !empty($_GET['post'])) {
					$post = get_post($_GET['post']);
					$typenow = $post->post_type;
				}
				if ("page" == $typenow || "post" == $typenow) {
					add_filter( 'mce_external_plugins', 'em_add_plugin' );
					add_filter( 'mce_buttons', 'em_register_button' );
				}
			}
		}
		add_action('init', 'em_plugin_button');

		function em_output_customCSS() { 
			
			if (get_theme_mod('theme_aeris_main_color') == "custom" ) {
				$code_color = get_theme_mod( 'theme_aeris_color_code' );
			}
			else {
				$code_color	= get_theme_mod( 'theme_aeris_main_color' );
			}

			// Récupération de la couleur principale du thème
			?>
			<style type="text/css">
			
				[for^="tab-"] {
					border-bottom:3px solid  <?php echo $code_color;?>;
					color:<?php echo $code_color;?>;
				}

				#tab-1:checked ~ .tabs #tab-1-label,
				#tab-2:checked ~ .tabs #tab-2-label,
				#tab-3:checked ~ .tabs #tab-3-label,
				#tab-4:checked ~ .tabs #tab-4-label,
				#tab-5:checked ~ .tabs #tab-5-label,
				#tab-6:checked ~ .tabs #tab-6-label  {
					background-color: <?php echo $code_color;?>;
					color:#fff;
					cursor: default;
				}

			</style>
		<?php
		}
		add_action('wp_head', 'em_output_customCSS');

		/**
		* Enlève l'editor dans l'édition des tabs
		*/
		add_action( 'init', function() {
			remove_post_type_support( 'tabs', 'editor' );
		}, 99);

	}
}
add_action('plugins_loaded', 'em_plugin_init');


