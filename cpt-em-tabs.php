<?php
/**
 * Code pour enregistrer un Custom Post Type (CPT) `em_tabs`
 * @package em_tabs
 */
add_action( 'init', 'em_tabs_cpt' );
/**
 * Enregistrer un CPT public
 */
function em_tabs_cpt() {
	// Bonne pratique : le type de Post doit être préfixé, au singulier, et ne devrait pas dépasser 20 caractères
	register_post_type( 
		'tabs', 							
		array(
			'label' => 'Modules d\'onglets', 			
			'labels' => array(    			
				'name' => 'Modules d\'onglets',
				'singular-name' => 'Module d\'onglets',
				'all_items' => 'Tous les modules d\'onglets',
				'add_new_item' => 'Ajouter un module d\'onglets',
				'edit_item' => 'Editer le module d\'onglets',
				'new_item' => 'Nouveau module d\'onglets',
				'view_item' => 'Voir le module d\'onglets',
				'search_item' => 'Rechercher parmis les modules d\'onglets',
				'not_found' => 'Pas de module d\'onglets trouvé',
				'not_found_in_trash' => 'Pas de module d\'onglets dans la corbeille'
			),
			'public' => true, 				
			'show_in_rest' => true,         
			'capability_type' => 'post',	
			'supports' => array(),
			'has_archive' => true, 
			// Url vers une icone ou à choisir parmi celles de WP : https://developer.wordpress.org/resource/dashicons/.
			'menu_icon'   => 'dashicons-index-card'
		) 
	);
}