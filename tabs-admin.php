<?php 

/**
 * @todo validation de formulaire en fonction des données entrées
 */

/* Vérifie que ACF est installé */
if ( !function_exists('get_field') ) return;

/**
 * Gestion des données du formulaire 
 */
if ( isset($_POST['tabs_hidden']) && $_POST['tabs_hidden'] == 'Y' ) {
	
	// données du formulaire envoyées

	/*
		On enregistre la valeur envoyée dans la variable
	*/
	$acf_name 	= $_POST['tabs_acf_name'];
	$choice 	= $_POST['tabs_choice'];
	$source 	= $_POST['tabs_source'];
	$caption 	= $_POST['tabs_caption'];
	$repeater 	= $_POST['tabs_repeater'];
	$_img 		= $_POST['tabs_image'];
	$_content 	= $_POST['tabs_content'];
	$_link 		= $_POST['tabs_link'];
	$_title 	= $_POST['tabs_title'];

	/*
		On met à jour la table "wp_options" dans la BDD 
		on crée ou met à jour les champs 'tabs_XXX' avec les valeurs $XXX
	*/
	update_option( 'tabs_acf_name', $acf_name ); // ($option_name, $option_value)
	update_option( 'tabs_choice'	, $choice 	); 
	update_option( 'tabs_source'	, $source 	);
	update_option( 'tabs_caption'	, $caption 	);
	update_option( 'tabs_repeater', $repeater );
	update_option( 'tabs_image'	, $_img 	);
	update_option( 'tabs_content'	, $_content );
	update_option( 'tabs_link'	, $_link 	);
	update_option( 'tabs_title'	, $_title 	);

	// Requête qui retourne tous les posts du Custom Post Type 'tabs'
	$cpt_tabs = new WP_Query( array( 'post_type'=>'tabs' ) );

	if ( $cpt_tabs->have_posts() ) {

		// Le custom post type 'tabs' existe
		while ( $cpt_tabs->have_posts() ) {
			$cpt_tabs->the_post();

			// enregistrement des valeurs pour validation du formulaire
			$acf_choix = get_field($choice);
			$acf_source = get_field($source);
			$acf_caption = get_field($caption);
			$acf_repeater = get_field($repeater);

			if( have_rows($repeater) ):

			    while( have_rows($repeater) ) : the_row();
			        
					$acf_image = get_sub_field($_img);
					$acf_content = get_sub_field($_content);
					$acf_link = get_sub_field($_link);
					$acf_title = get_sub_field($_title);

			    endwhile;

			endif;
		}
		wp_reset_postdata();
	}


?>
	<!-- Affichage d'une alerte quand les options sont enregistrées -->
	<div class="updated">
		<p><strong><?php _e('Options enregistrées.'); ?></strong></p>
	</div>
<?php
} else {

	// affichage normal de la page

	/*
		On récupère les valeurs des champs 'tabs_XXX'  	
		dans la table 'wp_options' de la BDD 			
	*/
	$acf_name 	= get_option( 'tabs_acf_name'	); // ($option_name)
	$choice 	= get_option( 'tabs_choice'	);
	$source 	= get_option( 'tabs_source'	);
	$caption 	= get_option( 'tabs_caption'	);
	$repeater 	= get_option( 'tabs_repeater'	);
	$_img 		= get_option( 'tabs_image'	);
	$_content 	= get_option( 'tabs_content'	);
	$_link 		= get_option( 'tabs_link'		);
	$_title 	= get_option( 'tabs_title' 	);

	// Requête qui retourne tous les posts du Custom Post Type 'tabs'
	$cpt_tabs = new WP_Query( array( 'post_type'=>'tabs' ) );

	if ( $cpt_tabs->have_posts() ) {

		// Le custom post type 'tabs' existe
		while ( $cpt_tabs->have_posts() ) {
			$cpt_tabs->the_post();

			// enregistrement des valeurs pour validation du formulaire
			$acf_choix = get_field($choice);
			$acf_source = get_field($source);
			$acf_caption = get_field($caption);
			$acf_repeater = get_field($repeater);

			if( have_rows($repeater) ):

			    while( have_rows($repeater) ) : the_row();
			        
					$acf_image = get_sub_field($_img);
					$acf_content = get_sub_field($_content);
					$acf_link = get_sub_field($_link);
					$acf_title = get_sub_field($_title);

			    endwhile;

			endif;
		}
		wp_reset_postdata();
	}
}
?>
<!-- Div de contenu de l'interface admin avec un id unique pour cibler notre plugin -->
<div class="wrap" id="admin-settings">
    <?php    echo "<h2>" . __( 'Tabs Options', 'tabs_trdom' ) . "</h2>"; ?>
     
    <form name="tabsbutton_form" id="tabsbutton_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <!-- champ caché utilisé pour savoir si la page est affichée après que l'utilisateur a cliqué sur le bouton ou pas -->
        <input type="hidden" name="tabs_hidden" value="Y">

        <!-- Select qui permet de choisir le groupe de champs ACF utilisé pour les tabs -->
        <p><label for="tabs_acf_name">Veuillez sélectionner le groupe de champs qui contient le template de Tabs</label></p>
        <select id="tabs_acf_name" name="tabs_acf_name">
<?php 

// Requête qui retourne tous les posts du Custom Post Type 'tabs'
$cpt_tabs = new WP_Query( array( 'post_type'=>'tabs' ) );

if ( $cpt_tabs->have_posts() ) {

	// Le custom post type 'tabs' existe

	// Requête qui retourne tous les posts du Custom Post Type 'acf-field-group' (groupes de champs)
	$cpt_acf_field_group = new WP_Query( array( 'post_type'=>'acf-field-group' ) );
	// si des groupes de champs existent
	if ( $cpt_acf_field_group->have_posts() ) {
 
		while ( $cpt_acf_field_group->have_posts() ) {
			$cpt_acf_field_group->the_post();
			$acf_id = $cpt_acf_field_group->get_the_ID();
?>
			<!-- on fait en sorte de pouvoir les sélectionner -->
			<option value="<?php echo get_the_excerpt(); ?>" <?php if (get_the_excerpt() === $acf_name) echo 'selected'; ?>><?php echo get_the_title(); ?></option>
<?php
		}
		wp_reset_postdata(); 
?>
		</select>

<?php

	} else {
?>
		<!-- il n'y a pas de groupe de champs à afficher -->
		<option value="not_found" selected disabled>Pas de champ ACF trouvé.</option>
		</select>
		<div class="updated">Veuillez créer un groupe de champs avec ACF.</div>
<?php 
	}

?>
		<!-- inputs qui permettent de lier les noms des champs au plugin -->
        <label for="tabs_choice">choice</label>
        <input type="text" name="tabs_choice" id="tabs_choice" value="<?php echo $choice; ?>" >
        <span class="notice <?php if ($acf_choix !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_choix == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
        <label for="tabs_source">source</label>
        <input type="text" name="tabs_source"  id="tabs_source" value="<?php echo $source; ?>" >
        <span class="notice <?php if ($acf_source !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_source == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
        <label for="tabs_caption">caption</label>
        <input type="text" name="tabs_caption"  id="tabs_caption" value="<?php echo $caption; ?>" >
        <span class="notice <?php if ($acf_caption !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_caption == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
        <label for="tabs_repeater">repeater</label>
        <input type="text" name="tabs_repeater"  id="tabs_repeater" value="<?php echo $repeater; ?>" >
        <span class="notice <?php if ($acf_repeater !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_repeater == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
        <label for="tabs_image">image</label>
        <input type="text" name="tabs_image"  id="tabs_image" value="<?php echo $_img; ?>" >
        <span class="notice <?php if ( isset($acf_image) && is_array($acf_image) ) echo 'input-success'; else echo 'notice-error'; ?> ">
	        <?php 
		        if ( ! isset($acf_image) ) echo 'Le nom du repeater n\'est pas valide'; 
		        else if ( ! is_array($acf_image) ) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; 
	        ?>	
        </span>
        <label for="tabs_content">content</label>
        <input type="text" name="tabs_content"  id="tabs_content" value="<?php echo $_content; ?>" >
        <span class="notice <?php if ( isset($acf_content) && $acf_content !== false ) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php 
        		if ( ! isset($acf_content) ) echo 'Le nom du repeater n\'est pas valide';
        		else if ( $acf_content === false ) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; 
        	?>
        </span>
        <label for="tabs_link">link</label>
        <input type="text" name="tabs_link"  id="tabs_link" value="<?php echo $_link; ?>" >
        <span class="notice <?php if ( isset($acf_link) && $acf_link !== false ) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php 
        		if ( ! isset($acf_link) ) echo 'Le nom du repeater n\'est pas valide';
        		else if ( $acf_link === false ) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; 
        	?>
        </span>
        <label for="tabs_title">title</label>
        <input type="text" name="tabs_title"  id="tabs_title" value="<?php echo $_title; ?>" >
        <span class="notice <?php if ( isset($acf_title) && $acf_title !== false ) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php 
        		
        		if ( ! isset($acf_title) ) echo 'Le nom du repeater n\'est pas valide';
        		else if ( $acf_title === false) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; 
        	?>
        </span>

		<script>
        	
        	/* Indication sur les données envoyées */
			var inputs = document.querySelectorAll('input[type="text"]'); 
			var spans = document.querySelectorAll('input+span');

			for (var i = 0 ; i < spans.length ; i++) {
				if (spans[i].hasAttribute('class')) {
					var notice = spans[i];

					var inputSibling = notice.previousSibling.previousSibling;
					var noticeError = notice.className.match(/\bnotice-error\b/);
					var noticeSuccess = notice.className.match(/\binput-success\b/);

					if ( noticeError ) {
						inputSibling.style.borderColor = "red";
					} else if ( noticeSuccess ) {
						notice.style.display = "none";
						inputSibling.style.borderColor = "green";
					}					
				}
			}

        </script>
<?php
} else {
	// Le CPT 'tabs' n'existe pas
	/**
	 * Fonction qui crée le CPT 'tabs' 
	 */
	function em_register_tabs() {		
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
	add_action( 'init', 'em_register_tabs' );
}
?>

    	<input class="submit" type="submit" name="Submit" value="<?php _e('Valider', 'tabs_trdom' ) ?>" />

    </form>
</div>

<?php 
