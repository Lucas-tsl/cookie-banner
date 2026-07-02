document.addEventListener("DOMContentLoaded", function() {
    const banner = document.getElementById("bcc-banner-card");
    const modal = document.getElementById("bcc-modal-overlay");
    const floatingBtn = document.getElementById("bcc-floating-btn");

    function setConsent(stats, mkt) {
        const expires = new Date(new Date().getTime() + 365 * 24 * 60 * 60 * 1000).toUTCString();
        // "secure" est ignoré silencieusement par le navigateur en HTTP : ne l'ajouter
        // qu'en HTTPS, sinon le cookie n'est jamais posé (boucle infinie en dev/staging).
        const secureFlag = window.location.protocol === "https:" ? "; secure" : "";
        const consentVersion = (typeof bccConfig !== "undefined" && bccConfig.consentVersion) ? bccConfig.consentVersion : "1";
        document.cookie = `bcc_consent_stats=${stats}; expires=${expires}; path=/; samesite=strict${secureFlag}`;
        document.cookie = `bcc_consent_mkt=${mkt}; expires=${expires}; path=/; samesite=strict${secureFlag}`;
        document.cookie = `bcc_consent_version=${consentVersion}; expires=${expires}; path=/; samesite=strict${secureFlag}`;
        document.cookie = `bcc_consent_all=1; expires=${expires}; path=/; samesite=strict${secureFlag}`;
        
        let statsStatus = stats === 1 ? 'granted' : 'denied';
        let mktStatus = mkt === 1 ? 'granted' : 'denied';
        
        if(typeof gtag === 'function') {
            gtag('consent', 'update', {
                'ad_storage': mktStatus,
                'ad_user_data': mktStatus,
                'ad_personalization': mktStatus,
                'analytics_storage': statsStatus
            });
        }
        
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ 'event': 'cookie_consent_updated' });

        if(banner) banner.style.display = "none";
        if(modal) modal.style.display = "none";
        if(floatingBtn) floatingBtn.style.display = "flex";
    }

    const btnAccepter = document.getElementById("bcc-btn-accepter");
    const btnRefuser = document.getElementById("bcc-btn-refuser");
    const btnPrefs = document.getElementById("bcc-btn-prefs");
    const btnSavePrefs = document.getElementById("bcc-btn-save-prefs");
    const btnCloseModal = document.getElementById("bcc-btn-close-modal");

    if(btnAccepter) btnAccepter.addEventListener("click", () => setConsent(1, 1));
    if(btnRefuser) btnRefuser.addEventListener("click", () => setConsent(0, 0));
    
    if(btnPrefs) btnPrefs.addEventListener("click", () => { 
        banner.style.display = "none"; 
        modal.style.display = "flex"; 
    });
    
    if(floatingBtn) floatingBtn.addEventListener("click", () => { 
        modal.style.display = "flex"; 
    });
    
    if(btnCloseModal) btnCloseModal.addEventListener("click", () => {
        if(!document.cookie.includes('bcc_consent_all')) banner.style.display = "block";
        modal.style.display = "none";
    });
    
    if(btnSavePrefs) btnSavePrefs.addEventListener("click", () => {
        const stats = document.getElementById("chk-stats").checked ? 1 : 0;
        const mkt = document.getElementById("chk-mkt").checked ? 1 : 0;
        setConsent(stats, mkt);
    });
});