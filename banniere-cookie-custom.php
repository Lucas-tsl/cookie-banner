<?php
/**
 * Plugin Name: Bannière Cookie Custom - Brutal GTM
 * Description: Bannière minimaliste intégrée avec Google Consent Mode V2 et DataLayer GTM.
 * Version: 3.0
 * Author: Troteseil Lucas
 * Author URI:  https://github.com/Lucas-tsl/cookie-banner
 * Text Domain: banniere-cookie-custom
 */


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Sécurité : empêche l'accès direct au fichier
}

// Définition des constantes pour les chemins
define( 'BCC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'BCC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Inclusion des fonctionnalités
require_once BCC_PLUGIN_DIR . 'includes/admin-settings.php';
require_once BCC_PLUGIN_DIR . 'includes/public-display.php';

// Chargement des fichiers CSS et JS
add_action( 'wp_enqueue_scripts', 'bcc_enqueue_assets' );
function bcc_enqueue_assets() {
    // Ne charger les assets que si le cookie complet n'est pas encore posé (optimisation)
    wp_enqueue_style( 'bcc-style', BCC_PLUGIN_URL . 'assets/css/style.css', array(), '3.0' );
    wp_enqueue_script( 'bcc-script', BCC_PLUGIN_URL . 'assets/js/script.js', array(), '3.0', true );
}