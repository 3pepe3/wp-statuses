<?php
/**
 * WP Statuses Admin Class.
 *
 * @package WP Statuses\admin\classes
 *
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * The Status Object
 *
 * @since  1.0.0
 */
class WP_Statuses_Core_Status {

	/**
	 * The label of the status.
	 *
	 * @var bool|string
	 */
	public  $label = false;

	/**
	 * The Post Types' List Table views label
	 *
	 * @var bool|string
	 */
	public  $label_count = false;

	/**
	 * The status labels.
	 *
	 * To customize dropdowns.
	 *
	 * @var array
	 */
	public  $labels = array();


	/**
	 * Whether to exclude posts with this post status from search.
	 *
	 * @var null|bool
	 */
	public  $exclude_from_search = null;

	/**
	 * Whether it's a WordPress built-in status.
	 *
	 * @var bool
	 */
	private $_builtin = false;

	/**
	 * Whether posts of this status should be shown
	 * in the front end of the site.
	 *
	 * @var null|bool
	 */
	public  $public = null;

	/**
	 * Whether the status is for internal use only.
	 *
	 * @var null|bool
	 */
	public	$internal = null;

	/**
	 * Whether posts with this status should be protected.
	 *
	 * @var null|bool
	 */
	public	$protected = null;

	/**
	 * Whether posts with this status should be private.
	 *
	 * @var null|bool
	 */
	public  $private = null;

	/**
	 * Whether posts with this status should be publicly-queryable.
	 *
	 * @var null|bool
	 */
	public	$publicly_queryable = null;

	/**
	 * The list of post types the status applies to.
	 *
	 * @var array
	 */
	public  $post_type = array();

	/**
	 * Whether to include posts in the edit listing for their post type
	 *
	 * @var null|bool
	 */
	public  $show_in_admin_status_list = null;

	/**
	 * Show in the list of statuses with post counts at the top of the edit
	 * listings.
	 *
	 * @var null|bool
	 */
	public	$show_in_admin_all_list = null;

	/**
	 * Whether to use the status in WordPress's Publishing metabox.
	 *
	 * @var null|bool
	 */
	public  $show_in_metabox_dropdown = null;

	/**
	 * Whether to use the status in WordPress's List Table inline/bulk edit actions.
	 *
	 * @var null|bool
	 */
	public  $show_in_inline_dropdown = null;

	/**
	 * The dashicon to use for the status.
	 *
	 * @var string
	 */
	public  $dashicon = 'dashicons-post-status';

	/**
	 * Constructor.
	 *
	 * @since  1.0.0
	 *
	 * @param WP_Statuses_Core_Status|object $status Status object.
	 */
	public function __construct( $status ) {
		foreach ( get_object_vars( $status ) as $key => $value ) {
			$this->{$key} = $value;
		}

		$status_data = $this->get_initial_types_data( $status->name );

		if ( $status_data ) {
			$this->labels   = $status_data['labels'];
			$this->dashicon = $status_data['dashicon'];

			if ( ! isset( $status->post_type )  ) {
				$this->post_type = wp_statuses_get_registered_post_types();
			}

			if ( ! isset( $status->show_in_metabox_dropdown ) ) {
				$this->show_in_metabox_dropdown = true;
			}

			if ( ! isset( $status->show_in_inline_dropdown ) ) {
				$this->show_in_inline_dropdown = true;
			}
		}

		$this->labels = array_merge( $this->labels, array(
			'label'       => $this->label,
			'label_count' => $this->label_count,
		) );

		if ( ! isset( $this->labels['metabox_dropdown'] ) ) {
			$this->labels['metabox_dropdown'] = $this->labels['label'];
		}

		if ( $this->show_in_inline_dropdown && ! isset( $this->labels['inline_dropdown'] ) ) {
			$this->labels['inline_dropdown'] = $this->labels['label'];
		}
	}

	/**
	 * Get the additional properties for the WordPress built-in statuses.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $name The name of the status.
	 * @return array        The additional properties matching the name of the status.
	 */
	public function get_initial_types_data( $name = '' ) {
		$labels = array(
			'publish'    => array(
				'labels' => array(
					'metabox_dropdown' => __( 'Publicly published', 'wp-statuses' ),
					'inline_dropdown'  => __( 'Published', 'wp-statuses' ),
				),
				'dashicon' => 'dashicons-visibility',
			),
			'private'    => array(
				'labels' => array(
					'metabox_dropdown' => __( 'Privately Published', 'wp-statuses' ),
					'inline_dropdown'  => __( 'Private', 'wp-statuses' ),
				),
				'dashicon' => 'dashicons-hidden',
			),
			'pending'    => array(
				'labels' => array(
					'metabox_dropdown' => __( 'Pending Review', 'wp-statuses' ),
				),
				'dashicon' => 'dashicons-flag',
			),
			'draft'      => array(
				'labels' => array(
					'metabox_dropdown' => __( 'Draft', 'wp-statuses' ),
				),
				'dashicon' => 'dashicons-edit',
			),
		);

		if ( ! isset( $labels[ $name ] ) ) {
			return null;
		}

		return $labels[ $name ];
	}
}
