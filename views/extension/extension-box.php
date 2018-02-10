<?php

$ext = $this->get_var( 'extension' );

// fragment forked from wp-admin/includes/class-wp-plugin-install-list-table.php
if ( isset( $ext['wporg'] ) && ! is_wp_error( $ext['wporg'] ) && ( current_user_can( 'install_plugins' ) || current_user_can( 'update_plugins' ) ) ) {
	$status = install_plugin_install_status( $ext['wporg'] );

	switch ( $status['status'] ) {
		case 'install':
			if ( $status['url'] ) {
				/* translators: 1: Plugin name and version. */
				$action_button = '<a class="install-now button" data-slug="' . esc_attr( $ext['slug'] ) . '" href="' . esc_url( $status['url'] ) . '" aria-label="' . esc_attr( sprintf( __( 'Install %s now' ), $ext['name'] ) ) . '" data-name="' . esc_attr( $ext['name'] ) . '">' . __( 'Install Now' ) . '</a>';
			}
			break;

		case 'update_available':
			if ( $status['url'] ) {
				/* translators: 1: Plugin name and version */
				$action_button = '<a class="update-now button aria-button-if-js" data-plugin="' . esc_attr( $status['file'] ) . '" data-slug="' . esc_attr( $ext['slug'] ) . '" href="' . esc_url( $status['url'] ) . '" aria-label="' . esc_attr( sprintf( __( 'Update %s now' ), $ext['name'] ) ) . '" data-name="' . esc_attr( $ext['name'] ) . '">' . __( 'Update Now' ) . '</a>';
			}
			break;

		case 'latest_installed':
		case 'newer_installed':
			if ( is_plugin_active( $status['file'] ) ) {
				$action_button = '<button type="button" class="button button-disabled" disabled="disabled">' . _x( 'Active', 'plugin' ) . '</button>';
			} elseif ( current_user_can( 'activate_plugins' ) ) {
				$button_text  = __( 'Activate' );
				/* translators: %s: Plugin name */
				$button_label = _x( 'Activate %s', 'plugin' );
				$activate_url = add_query_arg( array(
					'_wpnonce'    => wp_create_nonce( 'activate-plugin_' . $status['file'] ),
					'action'      => 'activate',
					'plugin'      => $status['file'],
				), network_admin_url( 'plugins.php' ) );

				if ( is_network_admin() ) {
					$button_text  = __( 'Network Activate' );
					/* translators: %s: Plugin name */
					$button_label = _x( 'Network Activate %s', 'plugin' );
					$activate_url = add_query_arg( array( 'networkwide' => 1 ), $activate_url );
				}

				$action_button = sprintf(
					'<a href="%1$s" class="button activate-now" aria-label="%2$s">%3$s</a>',
					esc_url( $activate_url ),
					esc_attr( sprintf( $button_label, $ext['name'] ) ),
					$button_text
				);
			} else {
				$action_button = '<button type="button" class="button button-disabled" disabled="disabled">' . _x( 'Installed', 'plugin' ) . '</button>';
			}
			break;
	}
} else {

	$action_button = '<a href="' . esc_url( $ext['url'] ) . '" class="button" target="_blank">' . __( 'More Details' ) . '</a>';

}

?>

<div class="plugin-card plugin-card-<?php echo $ext['slug']; ?>">
	<div class="plugin-card-top">
		<div class="name column-name">
			<h3>
				<?php echo esc_html( $ext['name'] ); ?>
				<img src="<?php echo esc_attr( $ext['icon'] ) ?>" class="plugin-icon" alt="<?php echo esc_attr( $ext['name'] ); ?>">
			</h3>
		</div>
		<div class="action-links">
			<ul class="plugin-action-buttons">
				<li><?php echo $action_button; ?></li>
				<?php if ( $ext['official'] ): ?>
					<li><span class="official"><?php _e( 'Official', 'notification' ); ?></span></li>
				<?php endif ?>
			</ul>
		</div>
		<div class="desc column-description">
			<p><?php echo mb_strimwidth( $ext['desc'], 0, 117, '...' ); ?></p>
			<p class="authors"><?php esc_html_e( 'Author', 'notification' ); ?>: <?php echo esc_html( $ext['author'] ); ?></p>
		</div>
	</div>
</div>
