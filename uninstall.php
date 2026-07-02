<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

delete_option( 'bcc_logo_url' );
delete_option( 'bcc_texte_banniere' );
delete_option( 'bcc_url_politique' );
delete_option( 'bcc_url_mentions' );
