<?php 

/**
 * @todo Rassembler les variables dans des tableaux
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
	$acf_name 		= $_POST['tabs_acf_name'];
	$title_tab1 	= $_POST['tabs_title_tab1'];
	$content_tab1 	= $_POST['tabs_content_tab1'];
	$title_tab2 	= $_POST['tabs_title_tab2'];
	$content_tab2 	= $_POST['tabs_content_tab2'];
	$title_tab3 	= $_POST['tabs_title_tab3'];
	$content_tab3 	= $_POST['tabs_content_tab3'];
	$title_tab4 	= $_POST['tabs_title_tab4'];
	$content_tab4 	= $_POST['tabs_content_tab4'];
	$title_tab5 	= $_POST['tabs_title_tab5'];
	$content_tab5 	= $_POST['tabs_content_tab5'];
	$title_tab6 	= $_POST['tabs_title_tab6'];
	$content_tab6 	= $_POST['tabs_content_tab6'];


	/*
		On met à jour la table "wp_options" dans la BDD 
		on crée ou met à jour les champs 'tabs_XXX' avec les valeurs $XXX
	*/
	update_option( 'tabs_acf_name'		, $acf_name ); // ($option_name, $option_value)
	update_option( 'tabs_title_tab1'	, $title_tab1 	);
	update_option( 'tabs_content_tab1'	, $content_tab1 );
	update_option( 'tabs_title_tab2'	, $title_tab2 	);
	update_option( 'tabs_content_tab2'	, $content_tab2 );
	update_option( 'tabs_title_tab3'	, $title_tab3 	);
	update_option( 'tabs_content_tab3'	, $content_tab3 );
	update_option( 'tabs_title_tab4'	, $title_tab4 	);
	update_option( 'tabs_content_tab4'	, $content_tab4 );
	update_option( 'tabs_title_tab5'	, $title_tab5 	);
	update_option( 'tabs_content_tab5'	, $content_tab5 );
	update_option( 'tabs_title_tab6'	, $title_tab6 	);
	update_option( 'tabs_content_tab6'	, $content_tab6 );


	// Requête qui retourne tous les posts du Custom Post Type 'tabs'
	$cpt_tabs = new WP_Query( array( 'post_type'=>'tabs' ) );

	if ( $cpt_tabs->have_posts() ) {

		// Le custom post type 'tabs' existe
		while ( $cpt_tabs->have_posts() ) {
			$cpt_tabs->the_post();

			// enregistrement des valeurs pour validation du formulaire
			$acf_title_tab1 = get_field($title_tab1);
			$acf_content_tab1 = get_field($content_tab1);
			$acf_title_tab2 = get_field($title_tab2);
			$acf_content_tab2 = get_field($content_tab2);
			$acf_title_tab3 = get_field($title_tab3);
			$acf_content_tab3 = get_field($content_tab3);	
			$acf_title_tab4 = get_field($title_tab4);
			$acf_content_tab4 = get_field($content_tab4);
			$acf_title_tab5 = get_field($title_tab5);
			$acf_content_tab5 = get_field($content_tab5);
			$acf_title_tab6 = get_field($title_tab6);
			$acf_content_tab6 = get_field($content_tab6);					

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
	$acf_name 		= $_POST['tabs_acf_name'];
	$title_tab1 	= $_POST['tabs_title_tab1'];
	$content_tab1 	= $_POST['tabs_content_tab1'];
	$title_tab2 	= $_POST['tabs_title_tab2'];
	$content_tab2 	= $_POST['tabs_content_tab2'];
	$title_tab3 	= $_POST['tabs_title_tab3'];
	$content_tab3 	= $_POST['tabs_content_tab3'];
	$title_tab4 	= $_POST['tabs_title_tab4'];
	$content_tab4 	= $_POST['tabs_content_tab4'];
	$title_tab5 	= $_POST['tabs_title_tab5'];
	$content_tab5 	= $_POST['tabs_content_tab5'];
	$title_tab6 	= $_POST['tabs_title_tab6'];
	$content_tab6 	= $_POST['tabs_content_tab6'];

	// Requête qui retourne tous les posts du Custom Post Type 'tabs'
	$cpt_tabs = new WP_Query( array( 'post_type'=>'tabs' ) );

	if ( $cpt_tabs->have_posts() ) {

		// Le custom post type 'tabs' existe
		while ( $cpt_tabs->have_posts() ) {
			$cpt_tabs->the_post();

			// enregistrement des valeurs pour validation du formulaire
			$acf_title_tab1 = get_field($title_tab1);
			$acf_content_tab1 = get_field($content_tab1);
			$acf_title_tab2 = get_field($title_tab2);
			$acf_content_tab2 = get_field($content_tab2);
			$acf_title_tab3 = get_field($title_tab3);
			$acf_content_tab3 = get_field($content_tab3);	
			$acf_title_tab4 = get_field($title_tab4);
			$acf_content_tab4 = get_field($content_tab4);
			$acf_title_tab5 = get_field($title_tab5);
			$acf_content_tab5 = get_field($content_tab5);
			$acf_title_tab6 = get_field($title_tab6);
			$acf_content_tab6 = get_field($content_tab6);	
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
        <label for="tabs_title_tab1">Titre onglet n°1</label>
        <input type="text" name="tabs_title_tab1" id="tabs_title_tab1" value="<?php echo $title_tab1; ?>" >
        <span class="notice <?php if ($acf_title_tab1 !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_title_tab1 == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
		<label for="tabs_content_tab1">Contenu onglet n°1</label>
        <input type="text" name="tabs_content_tab1" id="tabs_content_tab1" value="<?php echo $content_tab1; ?>" >
        <span class="notice <?php if ($acf_content_tab1 !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_content_tab1 == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
		<label for="tabs_title_tab2">Titre onglet n°2</label>
        <input type="text" name="tabs_title_tab2" id="tabs_title_tab2" value="<?php echo $title_tab2; ?>" >
        <span class="notice <?php if ($acf_title_tab2 !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_title_tab2 == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
		<label for="tabs_content_tab2">Contenu onglet n°2</label>
        <input type="text" name="tabs_content_tab2" id="tabs_content_tab2" value="<?php echo $content_tab2; ?>" >
        <span class="notice <?php if ($acf_content_tab2 !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_content_tab2 == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
		<label for="tabs_title_tab3">Titre onglet n°3</label>
        <input type="text" name="tabs_title_tab3" id="tabs_title_tab3" value="<?php echo $title_tab3; ?>" >
        <span class="notice <?php if ($acf_title_tab3 !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_title_tab3 == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
		<label for="tabs_content_tab3">Contenu onglet n°3</label>
        <input type="text" name="tabs_content_tab3" id="tabs_content_tab3" value="<?php echo $content_tab3; ?>" >
        <span class="notice <?php if ($acf_content_tab3 !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_content_tab3 == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
		<label for="tabs_title_tab4">Titre onglet n°4</label>
        <input type="text" name="tabs_title_tab4" id="tabs_title_tab4" value="<?php echo $title_tab4; ?>" >
        <span class="notice <?php if ($acf_title_tab4 !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_title_tab4 == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
		<label for="tabs_content_tab4">Contenu onglet n°4</label>
        <input type="text" name="tabs_content_tab4" id="tabs_content_tab4" value="<?php echo $content_tab4; ?>" >
        <span class="notice <?php if ($acf_content_tab4 !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_content_tab4 == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
		<label for="tabs_title_tab5">Titre onglet n°5</label>
        <input type="text" name="tabs_title_tab5" id="tabs_title_tab5" value="<?php echo $title_tab5; ?>" >
        <span class="notice <?php if ($acf_title_tab5 !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_title_tab5 == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
		<label for="tabs_content_tab5">Contenu onglet n°5</label>
        <input type="text" name="tabs_content_tab5" id="tabs_content_tab5" value="<?php echo $content_tab5; ?>" >
        <span class="notice <?php if ($acf_content_tab5 !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_content_tab5 == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
		<label for="tabs_title_tab6">Titre onglet n°6</label>
        <input type="text" name="tabs_title_tab6" id="tabs_title_tab6" value="<?php echo $title_tab6; ?>" >
        <span class="notice <?php if ($acf_title_tab6 !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_title_tab6 == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
        </span>
		<label for="tabs_content_tab6">Contenu onglet n°6</label>
        <input type="text" name="tabs_content_tab6" id="tabs_content_tab6" value="<?php echo $content_tab6; ?>" >
        <span class="notice <?php if ($acf_content_tab6 !== NULL) echo 'input-success'; else echo 'notice-error'; ?> ">
        	<?php if ($acf_content_tab6 == NULL) echo 'Ce nom de champ n\'existe pas dans le groupe ' . $acf_name . '.'; ?>        	
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
	add_action( 'init', 'em_register_tabs' );
}
?>

    	<input class="submit" type="submit" name="Submit" value="<?php _e('Valider', 'tabs_trdom' ) ?>" />

    </form>
</div>

<?php 
