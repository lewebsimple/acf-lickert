<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'acf_lickert_field' ) ) {

	class acf_lickert_field extends acf_field {

		public $settings;

		function __construct( $settings ) {
			$this->name     = 'lickert';
			$this->label    = __( "Lickert Scale", 'acf-lickert' );
			$this->category = 'basic';
			$this->defaults = array(
				'resolution'    => '5',
				'return_format' => 'array',
			);
			$this->settings = $settings;
			parent::__construct();
		}

		/**
		 * Render lickert field settings
		 *
		 * @param $field (array) the $field being edited
		 */
		function render_field_settings( $field ) {
			// Resolution
			acf_render_field_setting( $field, array(
				'label'        => __( "Scale Resolution", 'acf-lickert' ),
				'instructions' => __( "The number of choices in the scale.", 'acf-lickert' ),
				'type'         => 'number',
				'min'          => 2,
				'max'          => 10,
				'step'         => 1,
				'name'         => 'resolution',
			) );
			// Return_format
			acf_render_field_setting( $field, array(
				'label'        => __( "Return format", 'acf-lickert' ),
				'instructions' => __( "Specify the return format used in the templates.", 'acf-lickert' ),
				'type'         => 'select',
				'name'         => 'return_format',
				'choices'      => array(
					'formatted' => __( "Formatted", 'acf-lickert' ),
					'array'     => __( "Values (array)", 'acf-lickert' ),
				),
			) );
		}

		/**
		 * Enqueue input scripts and styles
		 */
		function input_admin_enqueue_scripts() {
			$url     = $this->settings['url'];
			$version = $this->settings['version'];
			$options = array();

			wp_register_script( 'acf-lickert', "{$url}assets/js/acf-lickert.js", array( 'acf-input' ), $version );
			wp_enqueue_script( 'acf-lickert' );
			wp_localize_script( 'acf-lickert', 'acfLickertOptions', $options );

			wp_register_style( 'acf-lickert', "{$url}assets/css/acf-lickert.css", array( 'acf-input' ), $version );
			wp_enqueue_style( 'acf-lickert' );
		}

		/**
		 * Render lickert field input
		 *
		 * @param $field (array) the $field being rendered
		 */
		function render_field( $field ) {
			$name  = $field['name'];
			$value = wp_parse_args( $field['value'], array(
				'score'   => '',
				'comment' => '',
			) );
			?>
			<div class="acf-input-wrap acf-lickert">
				<div class="form-group score">
					<?php for ( $i = 1; $i <= $field['resolution']; $i ++ ): ?>
						<div class="form-check form-check-inline custom-control custom-radio custom-control-inline">
							<input type="radio" id="<?= $name ?>-score-<?= $i ?>" name="<?= $name ?>[score]" value="<?= $i ?>" class="form-check-input custom-control-input" <?php checked( $value['score'], $i ) ?>/>
							<label class="form-check-label custom-control-label" for="<?= $name ?>-score-<?= $i ?>">
								<?= $i ?>
							</label>
						</div>
					<?php endfor; ?>
				</div>
				<div class="form-group comment">
					<label for="<?= $name ?>[comment]"><?= __( "Comment", 'acf-lickert' ) ?></label>
					<textarea name="<?= $name ?>[comment]" rows="2"><?= $value['comment'] ?></textarea>
				</div>
			</div>
			<?php
		}

		/**
		 * Validate lickert value
		 *
		 * @param $valid (boolean) validation status based on the value and the field's required setting
		 * @param $value (mixed) the $_POST value
		 * @param $field (array) the field array holding all the field options
		 * @param $input (string) the corresponding input name for $_POST value
		 *
		 * @return mixed
		 */
		function validate_value( $valid, $value, $field, $input ) {
			if ( empty( $value['score'] ) ) {
				return $field['required'] ? false : $valid;
			}
			if ( ! is_numeric( $value['score'] ) || (int) $value['score'] < 1 || (int) $value['score'] > (int) $field['resolution'] ) {
				return __( "Invalid score", 'acf-lickert' );
			}

			return $valid;
		}

		/**
		 * Update lickert value
		 *
		 * @param $value (mixed) the value to be updated in the database
		 * @param $post_id (mixed) the $post_id from which the value was loaded
		 * @param $field (array) the field array holding all the field options         *
		 *
		 * @return mixed
		 */
		function update_value( $value, $post_id, $field ) {
			return $value;
		}

		/**
		 * Format lickert value
		 *
		 * @param $value (mixed) the value which was loaded from the database
		 * @param $post_id (mixed) the $post_id from which the value was loaded
		 * @param $field (array) the $field array holding the options
		 *
		 * @return $value (mixed) the formatted value
		 */
		function format_value( $value, $post_id, $field ) {
			return acf_lickert_plugin::format_value( $value, $field['return_format'] );
		}

	}

	new acf_lickert_field( $this->settings );

}
