<?php

/*
Plugin Name: RetaxMaster Slider
Description: Plugin para registrar nuevos tipos de post
Version: 1.0
Author: RetaxMaster
License: GPLv2
*/

// Registra el custom post type para el slider
function rm_slider_post_type() {

	$labels = array(
		'name'                  => 'Sliders',
		'singular_name'         => 'Slider',
		'menu_name'             => 'Sliders',
		'name_admin_bar'        => 'Admin Bar Name',
		'archives'              => 'Item Archives',
		'attributes'            => 'Item Attributes',
		'parent_item_colon'     => 'Parent Item:',
		'all_items'             => 'Todos los slides',
		'add_new_item'          => 'Añadir nuevo slide',
		'add_new'               => 'Añadir nuevo',
		'new_item'              => 'Nuevo slide',
		'edit_item'             => 'Editar slide',
		'update_item'           => 'Actualizar slide',
		'view_item'             => 'Ver slide',
		'view_items'            => 'Ver sliders creados',
		'search_items'          => 'Buscar slide',
		'not_found'             => 'No hay slides aún',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Imagen',
		'set_featured_image'    => 'Establecer imagen del slide',
		'remove_featured_image' => 'Remover imagen',
		'use_featured_image'    => 'Usr como imagen del slide',
		'insert_into_item'      => 'Insert into item',
		'uploaded_to_this_item' => 'Uploaded to this item',
		'items_list'            => 'Items list',
		'items_list_navigation' => 'Items list navigation',
		'filter_items_list'     => 'Filter items list',
	);
	$args = array(
		'label'                 => 'Slider',
		'description'           => 'Tipo de publicación para guardar los sliders de la página',
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail' ),
		'taxonomies'            => array( 'index' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-slides',
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'rewrite'               => false,
		'capability_type'       => 'post',
		'show_in_rest'          => true,
	);
    
    register_post_type( 'rm_slider', $args );

}

// Crea los inputs personalizados para rellenar el texto del slider
function rm_register_custom_fields($post) {

	/* echo "<pre>";
	var_dump($post); //<- FIXME: Trying to get property of a non object pero por alguna razón si funciona
	echo "</pre>"; */

	$screens = ['rm_slider'];
	$values = get_post_custom($post->ID);
	$firstTitle = isset( $values['first-title'] ) ? esc_attr($values['first-title'][0]) : "";
	$secondTitle = isset( $values['second-title'] ) ? esc_attr($values['second-title'][0]) : "";

	add_meta_box("slide_text", "Texto a mostrar en los slides", function() use ($firstTitle, $secondTitle) {
		
		wp_nonce_field('send_slider_info', 'rm_metabox_nonce' ); ?>
		<div class="row" style="display: flex;justify-content: center;align-items: center;text-align: center;margin-bottom: 10px;">
			<label for="first-title" class="col-6" style="width: 50%; font-weight: bold;">Primer texto</label>
			<input type="text" name="first-title" id="first-title" class="col-6" style="width: 50%;" value="<?= esc_html($firstTitle) ?>">
		</div>
		<div class="row" style="display: flex;justify-content: center;align-items: center;text-align: center;">
			<label for="second-title" class="col-6" style="width: 50%; font-weight: bold;">Segundo texto</label>
			<input type="text" name="second-title" id="second-title" class="col-6" style="width: 50%;" value="<?= esc_html($secondTitle) ?>">
		</div>

	<?php }, $screens);

    
}

// Registra la taxonomía "posiciones" para que se pueda indicar en dónde se desea tal slider
function rm_slider_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Posiciones', 'Taxonomy General Name', 'text_domain' ),
		'singular_name'              => _x( 'Posición', 'Taxonomy Singular Name', 'text_domain' ),
		'menu_name'                  => __( 'Posición', 'text_domain' ),
		'all_items'                  => __( 'Todas las posiciones', 'text_domain' ),
		'parent_item'                => __( 'Por defecto', 'text_domain' ),
		'parent_item_colon'          => __( 'Parent Item:', 'text_domain' ),
		'new_item_name'              => __( 'Nueva posición', 'text_domain' ),
		'add_new_item'               => __( 'Añadir nueva posición', 'text_domain' ),
		'edit_item'                  => __( 'Editar posición', 'text_domain' ),
		'update_item'                => __( 'Actualizar posición', 'text_domain' ),
		'view_item'                  => __( 'Ver posición', 'text_domain' ),
		'separate_items_with_commas' => __( 'Separa las posiciones con comas', 'text_domain' ),
		'add_or_remove_items'        => __( 'Añadir o eliminar posiciones', 'text_domain' ),
		'choose_from_most_used'      => __( 'Elegir de las más usadas', 'text_domain' ),
		'popular_items'              => __( 'Posiciones populares', 'text_domain' ),
		'search_items'               => __( 'Buscar posiciones', 'text_domain' ),
		'not_found'                  => __( 'No encontrado', 'text_domain' ),
		'no_terms'                   => __( 'No hay posiciones', 'text_domain' ),
		'items_list'                 => __( 'Lista de posiciones', 'text_domain' ),
		'items_list_navigation'      => __( 'Items list navigation', 'text_domain' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => false,
		'show_in_rest'               => true,
	);
    
	register_taxonomy( 'rm_slider_position', ['rm_slider'], $args );

}

// Inserta la taxonomía por defecto para la localización de index_top
function insert_index_top() {
	$taxonomy = 'rm_slider_position';
	
	$terms = array (
		'0' => array (
			'name'          => 'Slider del index',
			'slug'          => 'index-top',
			'description'   => 'Slider de la página principal',
		)
	);  

	foreach ($terms as $term) {

		if (term_exists($term["name"], $taxonomy) == null) {
			wp_insert_term(
				$term['name'],
				$taxonomy, 
				array(
					'description'   => $term['description'],
					'slug'          => $term['slug'],
				)
			);
		}

		unset( $term ); 
	}
}

// Guarda el slide
function rm_save_slide($post_id) {

    // Ignoramos los auto guardados.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Si no está el nonce declarado antes o no podemos verificarlo no seguimos.
    if (!isset( $_POST['rm_metabox_nonce']) || !wp_verify_nonce($_POST['rm_metabox_nonce'], 'send_slider_info')) {
        return;
    }

    // Si el usuario actual no puede editar entradas no debería estar aquí.
    if (!current_user_can('edit_posts')) {
        return;
    }

    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['first-title'])) {
        update_post_meta($post_id, 'first-title', wp_kses($_POST['first-title'], []));
    }

    // Nos aseguramos de que hay información que guardar.
    if (isset($_POST['second-title'])) {
        update_post_meta($post_id, 'second-title', wp_kses($_POST['second-title'], []));
    }
}

// Crea el shortcode para usar el slider
function rm_add_shortcode( $atts ) {

	$args = array(
	    'post_type' => 'rm_slider',
	    'orderby'   => 'date',
	    'order' => 'ASC',
	    'rm_slider_position' => $atts["position"]
	);
	
	$loop = new WP_Query( $args );
    $have_posts = $loop->have_posts();
    
    if ($have_posts) { ?>
        <div id="main-carousel" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <?php for($x = 0; $x < $loop->post_count; $x++): ?>
                    <li data-target="#main-carousel" data-slide-to="<?= $x ?>" class="<?= ($x == 0) ? "active" : "" ?>"></li>
                <?php endfor; ?>
            </ol>
            <div class="carousel-inner">

                <?php 
                
                $active = "active";
                while ( $loop->have_posts() ) : $loop->the_post();
                $firstTitle = get_post_meta( get_the_ID(), 'first-title', true );
                $secondTitle = get_post_meta( get_the_ID(), 'second-title', true );
                
                ?>

                <div class="carousel-item <?= $active ?>">
                    <?php if(has_post_thumbnail()) the_post_thumbnail("slider_thumbs", ["class" => "d-block w-100"]) ?>
                    <div class="carousel-caption">
                        <span><b><?= $firstTitle ?></b></span>
                        <span><?= $secondTitle ?></span>
                    </div>
                </div>

                <?php $active = ""; endwhile; ?>

            </div>
            <a class="carousel-control-prev" href="#main-carousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#main-carousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    <?php }

}

add_action('init', 'rm_slider_post_type', 0);
add_action('add_meta_boxes', 'rm_register_custom_fields', 1);
add_action('init', 'rm_slider_taxonomy', 2);
add_action('save_post', 'rm_save_slide');
add_shortcode('slider', 'rm_add_shortcode');
add_action('init', 'insert_index_top');

?>