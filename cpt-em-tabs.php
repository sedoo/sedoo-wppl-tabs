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
			'label' => 'Tabs', 			
			'labels' => array(    			
				'name' => 'Tabs',
				'singular-name' => 'Tab',
				'all_items' => 'Tous les Tabs',
				'add_new_item' => 'Ajouter un tabs',
				'edit_item' => 'Editer le tabs',
				'new_item' => 'Nouveau tabs',
				'view_item' => 'Voir le tabs',
				'search_item' => 'Rechercher parmis les tabs',
				'not_found' => 'Pas de tabs trouvé',
				'not_found_in_trash' => 'Pas de tabs dans la corbeille'
			),
			'public' => true, 				
			'show_in_rest' => true,         
			'capability_type' => 'post',	
			'supports' => array(			
				'title',
				'thumbnail',
				'editor'	
			),
			'has_archive' => true, 
			// Url vers une icone ou à choisir parmi celles de WP : https://developer.wordpress.org/resource/dashicons/.
			'menu_icon'   => 'dashicons-index-card'
		) 
	);
}