<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// Lit et assainit une valeur de cookie ; retourne null si absente.
function bcc_cookie_value( $nom ) {
    if ( ! isset( $_COOKIE[ $nom ] ) ) {
        return null;
    }
    return sanitize_text_field( wp_unslash( $_COOKIE[ $nom ] ) );
}

// Injection du Google Consent Mode V2 (AVANT GTM, très important de garder ce JS ici dans le <head>)
add_action('wp_head', 'bcc_inject_consent_mode', 1);
function bcc_inject_consent_mode() {
    ?>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}

        var bccConsentVersion = document.cookie.match(/(?:^|; )bcc_consent_version=([^;]*)/);
        var bccHasConsent = document.cookie.indexOf('bcc_consent_all=') !== -1
            && bccConsentVersion !== null
            && bccConsentVersion[1] === '<?php echo esc_js( BCC_CONSENT_VERSION ); ?>';
        var bccStats = document.cookie.indexOf('bcc_consent_stats=1') !== -1 ? 'granted' : 'denied';
        var bccMkt = document.cookie.indexOf('bcc_consent_mkt=1') !== -1 ? 'granted' : 'denied';

        gtag('consent', 'default', {
            'ad_storage': bccHasConsent ? bccMkt : 'denied',
            'ad_user_data': bccHasConsent ? bccMkt : 'denied',
            'ad_personalization': bccHasConsent ? bccMkt : 'denied',
            'analytics_storage': bccHasConsent ? bccStats : 'denied',
            // Le délai n'a d'utilité que le temps de laisser un nouveau visiteur
            // répondre à la bannière ; inutile de ralentir GTM pour un visiteur
            // dont le choix est déjà connu.
            'wait_for_update': bccHasConsent ? 0 : 500
        });
    </script>
    <?php
}

// Affichage HTML de la bannière
add_action('wp_footer', 'bcc_afficher_banniere');
function bcc_afficher_banniere() {
    $logo = get_option('bcc_logo_url');
    $texte = get_option('bcc_texte_banniere', bcc_texte_par_defaut());
    $url_politique = get_option('bcc_url_politique', '#');
    $url_mentions = get_option('bcc_url_mentions', '#');
    
    $choix_fait = null !== bcc_cookie_value( 'bcc_consent_all' )
        && bcc_cookie_value( 'bcc_consent_version' ) === BCC_CONSENT_VERSION;
    ?>
    
    <div id="bcc-floating-btn" class="bcc-floating-btn" style="display: <?php echo $choix_fait ? 'flex' : 'none'; ?>;">
        🍪 Préférences
    </div>

    <div id="bcc-banner-card" class="bcc-banner-card" style="display: <?php echo $choix_fait ? 'none' : 'block'; ?>;">
        <?php if (!empty($logo)) : ?><img src="<?php echo esc_url($logo); ?>" alt="Logo" class="bcc-logo" /><?php endif; ?>
        <h3 class="bcc-title">Gérer le consentement</h3>
        <p class="bcc-desc"><?php echo nl2br(esc_html($texte)); ?></p>
        <div class="bcc-links">
            <a href="<?php echo esc_url($url_politique); ?>">Politique de confidentialité</a> | <a href="<?php echo esc_url($url_mentions); ?>">Mentions légales</a>
        </div>
        <div class="bcc-actions">
            <button id="bcc-btn-accepter" class="bcc-btn bcc-btn-accepter">Tout Accepter</button>
            <button id="bcc-btn-refuser" class="bcc-btn bcc-btn-refuser">Tout Refuser</button>
            <button id="bcc-btn-prefs" class="bcc-btn bcc-btn-prefs">Personnaliser</button>
        </div>
    </div>

    <div id="bcc-modal-overlay" class="bcc-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="bcc-modal-title">
        <div class="bcc-modal" tabindex="-1">
            <h3 class="bcc-title" id="bcc-modal-title">Préférences des cookies</h3>
            <div class="bcc-cookie-type">
                <div>
                    <strong>Strictement Nécessaires</strong>
                    <p class="bcc-desc">Requis pour le site (panier, sécurité). Non désactivables.</p>
                </div>
                <input type="checkbox" checked disabled>
            </div>
            <div class="bcc-cookie-type">
                <label for="chk-stats">
                    <strong>Statistiques (Google Analytics)</strong>
                    <p class="bcc-desc">Pour mesurer l'audience de la boutique.</p>
                </label>
                <input type="checkbox" id="chk-stats" <?php echo ( '1' === bcc_cookie_value( 'bcc_consent_stats' ) ) ? 'checked' : ''; ?>>
            </div>
            <div class="bcc-cookie-type">
                <label for="chk-mkt">
                    <strong>Marketing (Pixel Facebook, Google Ads)</strong>
                    <p class="bcc-desc">Pour afficher des publicités ciblées.</p>
                </label>
                <input type="checkbox" id="chk-mkt" <?php echo ( '1' === bcc_cookie_value( 'bcc_consent_mkt' ) ) ? 'checked' : ''; ?>>
            </div>
            <div class="bcc-actions" style="margin-top: 20px;">
                <button id="bcc-btn-save-prefs" class="bcc-btn bcc-btn-accepter">Enregistrer mes choix</button>
                <button id="bcc-btn-close-modal" class="bcc-btn bcc-btn-refuser">Annuler</button>
            </div>
        </div>
    </div>
    <?php
}