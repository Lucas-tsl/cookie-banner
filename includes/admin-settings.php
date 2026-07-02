<?php
if ( ! defined( 'ABSPATH' ) ) exit;

add_action('admin_menu', 'bcc_ajouter_menu');
function bcc_ajouter_menu() {
    add_options_page('Réglages Bannière Cookie', 'Bannière Cookie', 'manage_options', 'bcc-reglages', 'bcc_page_reglages_html');
}

add_action('admin_init', 'bcc_enregistrer_parametres');
function bcc_enregistrer_parametres() {
    register_setting('bcc_options_group', 'bcc_logo_url', array( 'sanitize_callback' => 'esc_url_raw' ));
    register_setting('bcc_options_group', 'bcc_texte_banniere', array( 'sanitize_callback' => 'sanitize_textarea_field' ));
    register_setting('bcc_options_group', 'bcc_url_politique', array( 'sanitize_callback' => 'esc_url_raw' ));
    register_setting('bcc_options_group', 'bcc_url_mentions', array( 'sanitize_callback' => 'esc_url_raw' ));
}

function bcc_page_reglages_html() {
    $texte_defaut = bcc_texte_par_defaut();
    ?>
    <div class="wrap">
        <h1>Configuration de la Bannière Cookie (GTM Edition)</h1>
        <p><em>Note : Ce plugin communique directement avec Google Tag Manager via le Google Consent Mode V2.</em></p>
        <form method="post" action="options.php">
            <?php settings_fields('bcc_options_group'); ?>
            <table class="form-table">
                <tr valign="top"><th scope="row">URL du Logo</th><td><input type="text" name="bcc_logo_url" value="<?php echo esc_attr(get_option('bcc_logo_url')); ?>" class="regular-text" /></td></tr>
                <tr valign="top"><th scope="row">Texte de la bannière</th><td><textarea name="bcc_texte_banniere" rows="4" cols="60"><?php echo esc_textarea(get_option('bcc_texte_banniere', $texte_defaut)); ?></textarea></td></tr>
                <tr valign="top"><th scope="row">URL Politique de confidentialité</th><td><input type="text" name="bcc_url_politique" value="<?php echo esc_attr(get_option('bcc_url_politique')); ?>" class="regular-text" /></td></tr>
                <tr valign="top"><th scope="row">URL Mentions légales</th><td><input type="text" name="bcc_url_mentions" value="<?php echo esc_attr(get_option('bcc_url_mentions')); ?>" class="regular-text" /></td></tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}