# Bannière Cookie Custom - Brutal GTM 🍪

Un plugin WordPress e-commerce léger, minimaliste et orienté performances. Il affiche une bannière de consentement aux cookies au design "brutaliste" et s'intègre nativement avec **Google Tag Manager** via le **Google Consent Mode V2**.

## 🚀 Fonctionnalités
* **Design Brutaliste :** Noir et blanc, bordures strictes, pas de `border-radius`.
* **Performances Extrêmes :** Pas de dépendances (Vanilla JS, CSS pur), pas d'appels à la base de données en front-end.
* **Intégration GTM :** Envoie les signaux `granted` ou `denied` au DataLayer sans recharger la page.
* **Conformité RGPD :**
  * Granularité (Statistiques vs Marketing).
  * Bouton de rétractation permanent flottant.
  * Blocage par défaut en amont.
* **Administration :** Page d'options native WordPress pour configurer les textes, le logo et les liens légaux.

## 📁 Structure du projet
* `banniere-cookie-custom.php` : Déclaration du plugin et point d'entrée.
* `includes/admin-settings.php` : Gestion du menu et des options dans le Back-Office WP.
* `includes/public-display.php` : Injection du Consent Mode et HTML Front-End.
* `assets/css/style.css` : Styles de la bannière et de la modale.
* `assets/js/script.js` : Écouteurs d'événements et API Google gtag.

## 🛠️ Installation
1. Uploadez le dossier `banniere-cookie-custom` dans le répertoire `/wp-content/plugins/` de votre site.
2. Activez l'extension via le menu **Extensions** de WordPress.
3. Rendez-vous dans **Réglages > Bannière Cookie** pour configurer vos URLs.

## ⚙️ Configuration Google Tag Manager (Requis)
Ce plugin est conçu pour fonctionner en symbiose avec GTM.
1. Activez la **Vue d'ensemble du consentement** dans GTM.
2. Assurez-vous que vos balises nécessitent le consentement (`ad_storage` ou `analytics_storage`).
3. Remplacez le déclencheur `All Pages` par un événement personnalisé nommé `cookie_consent_updated` pour le déclenchement asynchrone post-consentement.