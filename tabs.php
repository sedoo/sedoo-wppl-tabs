<?php 
/**
* Plugin Name: Tabs
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

			/* Récupération des options enregistrées par l'utilisateur dans les options du plugin */
			$choice 	= get_option('tabs_choice');
			$source 	= get_option('tabs_source');
			$caption 	= get_option('tabs_caption');
			$repeater 	= get_option('tabs_repeater');
			$_img 		= get_option('tabs_image');
			$_content 	= get_option('tabs_content');
			$_link 		= get_option('tabs_link');
			$_title 	= get_option('tabs_title');
			$postType 	= 'tabs';
			
			extract(shortcode_atts(array( 'posts' => 1,'id' => '-1'), $atts) );

			/* récupération des infos du module d'onglets en fonction de l'id spécifié dans le shortcode */
			$billet = get_post($id);
			if ( $billet ) {
				$title = $billet->post_title;
				$contenu = $billet->post_content;
				$contenu = apply_filters('the_content', $contenu);
				$contenu = str_replace(']]>', ']]&gt;', $contenu);
				$choix = get_field( $choice, $id);
			}

			/* Parcours de tous les Posts de type $postType et récupération des IDs */
			$args = array( 'post_type' => $postType, 'fields' => 'ids');

			$loop = new WP_Query( $args );
			$arr = array();
			while ( $loop->have_posts() ) : $loop->the_post();
				$postTypeID = get_the_ID();
				array_push($arr,$postTypeID);
			endwhile;

			wp_reset_postdata();
			
			/* déclaration et initialisation de la chaine de caractère de retour */
			$return_string = ''; 

			if ( ! in_array ($id, $arr) ) {
				// si l'ID spécifié n'existe pas, le module d'onglets n'existe pas
				$return_string .= $id . ' <p>Le module d\'onglets que vous tentez d\'afficher n\'existe pas</p>';
			} else {
				// sinon, le module d'onglets existe et on structure son affichage
				$return_string .= '<h2>'.$title.'</h2>';

				// parcours du repeater qui contient les diapositives 
				// pour afficher les liens qui les contrôlent
				if ( have_rows($repeater, $id) ) :
					while (have_rows($repeater, $id) ) : the_row();
						$image = get_sub_field($_img);
						$titre = get_sub_field($_title);
						$link = get_sub_field($_link);
						$return_string .= '<a href="#" data-img="'.$image['title'].'">'.$titre.'</a>';
					endwhile;
					wp_reset_postdata();
				else :
					$return_string .= 'pas de champ Repeater ';
				endif;

				$return_string .= '</nav><div class="slides">';
				
				// si le Slider est de type "survol", on a besoin d'une image source (d'arrière plan)	
				// if ($choix == "survol") :
				// 	$source = get_field($source, $id);
				// 	$legende = get_field($caption, $id);
				// 	$return_string .= '<figure data-img="tabs-survol-source" id="source"><img src="'.$source['url'].'" alt="tabs-survol-source" >';
				// 	$return_string .= '<figcaption>'.$legende.'</figcaption></figure>';
				// endif;
				// parcours du repeater qui contient les diapositives 
				// pour afficher les images avec leur légende et leurs attributs, 
				// ainsi que les liens vers lesquels chacune d'elles pointe.
				if ( have_rows($repeater, $id) ) :
					while (have_rows($repeater, $id) ) : the_row();
						$image = get_sub_field($_img);
						$content = get_sub_field($_content);
						$link = get_sub_field($_link);
						$return_string .= '<figure class="slide hidden-content" data-img="'.$image['title'].'">';					
						$return_string .= '<img src="'.$image['url'].'" alt="'.$image['alt'].'" /><figcaption>';

						if( $link ):
							$return_string .= '<a href="'.$link.'">';
						endif;
				  
				  		$return_string .= $content;

						if( $link ):
							$return_string .= '</a>';
						endif;
						$return_string .= '</figcaption></figure>';

					endwhile;
					wp_reset_postdata();
				endif;
				$return_string .= '</div></section><article>'.$contenu.'</article>';
			
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

		/* Personnaliser les couleurs */		
		function em_customize_register($wp_customize) 
		{
			$wp_customize->add_section("tabs_color_settings_section", array(
				"title" => __("Custom Slider Button Plugin Colors", "customizer_color_sections"),
				"priority" => 30,
			));

			/* Main Theme Color */
			$wp_customize->add_setting("main_theme_color", array(
				"default" => "#2f4f4f",
				"transport" => "refresh",
				"type" => "option"
			));

			$wp_customize->add_control(new WP_Customize_Color_Control(
				$wp_customize,
				"main_theme_color_control",
				array(
					"label" => __("Main Theme Color", ""),
					"section" => "tabs_color_settings_section",
					"settings" => "main_theme_color",
				)
			));
			/* Secondary Theme Color */
			$wp_customize->add_setting("secondary_theme_color", array(
				"default" => "#eeeeee",
				"transport" => "refresh",
				"type" => "option"
			));

			$wp_customize->add_control(new WP_Customize_Color_Control(
				$wp_customize,
				"secondary_theme_color_control",
				array(
					"label" => __("Secondary Theme Color", ""),
					"section" => "tabs_color_settings_section",
					"settings" => "secondary_theme_color",
				)
			));

			/* Slider Background */
			$wp_customize->add_setting("slider_bg_color", array(
				"default" => "#222222",
				"transport" => "refresh",
				"type" => "option"
			));

			$wp_customize->add_control(new WP_Customize_Color_Control(
				$wp_customize,
				"slider_bg_color_control",
				array(
					"label" => __("Slider Background Color and Nav Links Hover", ""),
					"section" => "tabs_color_settings_section",
					"settings" => "slider_bg_color",
				)
			));
		}
		add_action("customize_register","em_customize_register");

		function em_output_customCSS() { ?>
			<style type="text/css">

				/* Main Color */
				section.slider nav a.active,
				section.survol nav a.active {
					color:<?php echo get_option("main_theme_color");?>;
					border-top-color: <?php echo get_option("main_theme_color");?>;
				}

				/* Secondary Color*/
				section.survol+article ul {
					background-color: <?php echo get_option("secondary_theme_color");?>;
				}
				section.survol,
				section.slider {
					border-color: <?php echo get_option("secondary_theme_color");?>;
				}
				[data-img="source"] figcaption {
					color: <?php echo get_option("secondary_theme_color");?>;
				}

				/* Background Color & Hover Nav Links */
				section.survol .slides,
				section.slider .slides {
					background-color: <?php echo get_option("slider_bg_color");?>;
				}
				section.slider nav a:hover,
				section.survol nav a:hover {
					color: <?php echo get_option("slider_bg_color");?>;
					border-top-color: <?php echo get_option("slider_bg_color");?>;
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


