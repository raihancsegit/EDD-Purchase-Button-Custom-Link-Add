<?php
/*
Plugin Name: EDD-Purchase Button
Plugin URL: http://wordpress.org
Description: Add an "Add to cart text" option for your downloads to change the text on the "Add to cart" button for that particular download.
Version: 1.0.2
Author: Raihan Islam
Author URI: https://www.raihanislamcse.me/
*/

/**
 * Add to Cart button Text meta field
 *
 * Adds field do the EDD Downloads meta box for specifying the "Add to cart text"
 *
 * @since 1.0.0
 * @param integer $post_id Download (Post) ID
 */


function edd_atc_new_tab_render_field( $post_id ) {
	$edd_atc_new_tab = get_post_meta( $post_id, '_edd_atc_new_tab', true );
?>
	<p><strong><?php _e( 'Custom Button Add ?:', 'edd-atc-text' ); ?></strong></p>
	<label for="edd_atc_new_tab">
		<input type="checkbox" name="_edd_atc_new_tab" id="edd_atc_new_tab" <?php checked(1, $edd_atc_new_tab) ?> value='1' Add to cart"/>
		<?php _e( 'Add Custom Button Text and Link For Download?', 'edd-atc-text' ); ?>
<?php

}

add_action( 'edd_meta_box_fields', 'edd_atc_new_tab_render_field', 91 );

function edd_atc_text_render_field( $post_id ) {
	$edd_atc_text = get_post_meta( $post_id, '_edd_atc_text', true );
?>
	<p><strong><?php _e( 'Custom Button Text:', 'edd-atc-text' ); ?></strong></p>
	<label for="edd_atc_text">
		<input type="text" name="_edd_atc_text" id="edd_atc_text" value="<?php echo esc_attr( $edd_atc_text ); ?>" size="80" Add to cart"/>
		<br/><?php _e( 'Change the Add to cart button text for this product, leave blank to use default text instead.', 'edd-atc-text' ); ?>
	</label>
<?php
}

add_action( 'edd_meta_box_fields', 'edd_atc_text_render_field', 92 );

function edd_atc_link_render_field( $post_id ) {
	$edd_atc_link = get_post_meta( $post_id, '_edd_atc_link', true );
?>
	<p><strong><?php _e( 'Custom Button link:', 'edd-atc-link' ); ?></strong></p>
	<label for="edd_atc_text">
		<input type="text" name="_edd_atc_link" id="_edd_atc_link" value="<?php echo esc_attr( $edd_atc_link ); ?>" size="80" Add to cart"/>
		<br/><?php _e( 'Change the Add to cart button Link for this product, leave blank to use default text instead.', 'edd-atc-text' ); ?>
	</label>
<?php
}

add_action( 'edd_meta_box_fields', 'edd_atc_link_render_field', 93 );




/**
 * Add the _edd_atc_text field to the list of saved product fields
 *
 * @since  1.0.0
 *
 * @param  array $fields The default product fields list
 * @return array         The updated product fields list
 */
function edd_atc_text_save( $fields ) {

	// Add our field
	$fields[] = '_edd_atc_text';
	$fields[] = '_edd_atc_link';
	$fields[] = '_edd_atc_new_tab';

	// Return the fields array
	return $fields;
}

add_filter( 'edd_metabox_fields_save', 'edd_atc_text_save' );

/**
 * Sanitize metabox field
 *
 * @since 1.0.0
*/
function edd_atc_text_metabox_save( $new ) {

	// sanitize the field before saving into wp_postmeta table
	$new = esc_attr( $_POST[ '_edd_atc_text' ] );
	$link = esc_url( $_POST[ '_edd_atc_link' ] );

	// Return Title
	return $new;
	return $link;

}

add_filter( 'edd_metabox_save__edd_atc_text', 'edd_atc_text_metabox_save' );

function edd_atc_new_tab_metabox_save( $new ) {

	// sanitize the field before saving into wp_postmeta table
	$new = esc_attr( $_POST[ '_edd_atc_new_tab' ] );

	// Return Title
	return $new;
}

add_filter( 'edd_metabox_save__edd_atc_new_tab', 'edd_atc_new_tab_metabox_save' );



// define the edd_product_details_widget_purchase_button callback 
function filter_edd_product_details_widget_purchase_button( $edd_get_purchase_link, $download_id ) { 
	// make filter magic happen here...
	$edd_atc_text = get_post_meta( $download_id, '_edd_atc_text', true ) ? get_post_meta( $download_id, '_edd_atc_text', true ) : '';
	$edd_atc_link = get_post_meta( $download_id, '_edd_atc_link', true ) ? get_post_meta( $download_id, '_edd_atc_link', true ) : '';
	$edd_atc_new_tab = get_post_meta( $download_id, '_edd_atc_new_tab', true ) ? get_post_meta( $download_id, '_edd_atc_new_tab', true ) : '';
	if($edd_atc_new_tab){	
		$edd_get_purchase_link = edd_purchase_variable_pricing( $download_id, $args );
		$edd_get_purchase_link .= edd_price( $download_id );
		$edd_get_purchase_link .= '<a href="'.$edd_atc_link.'" class="button blue edd-submit " style="color:#FFF">'.$edd_atc_text.'</a>';
	}else {
		$edd_get_purchase_link ;
	}
	return $edd_get_purchase_link; 
	
}; 
         
// add the filter 
add_filter( 'edd_product_details_widget_purchase_button', 'filter_edd_product_details_widget_purchase_button', 10, 2 );


