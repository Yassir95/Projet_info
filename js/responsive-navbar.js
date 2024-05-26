function updateVisibility() {
    let toHide = document.getElementsByClassName("max840");
    let toHide2 = document.getElementsByClassName("max420");

    if (window.innerWidth < 840) {
        for (let i = 0; i < toHide.length; i++) {
            toHide[i].style.display = "none";
        }
        if (window.innerWidth < 420) {
            for (let i = 0; i < toHide2.length; i++) {
                toHide2[i].style.display = "none";
            }
        } else {
            for (let i = 0; i < toHide2.length; i++) {
                toHide2[i].style.display = "block";
            }
        }
    } else {
        for (let i = 0; i < toHide.length; i++) {
            toHide[i].style.display = "block";
        }
        for (let i = 0; i < toHide2.length; i++) {
            toHide2[i].style.display = "block";
        }
    }
}

// Exécuter au chargement de la page pour s'assurer que l'état est correct au démarrage
window.addEventListener('load', updateVisibility);

// Ajouter un gestionnaire d'événements pour le redimensionnement de la fenêtre
window.addEventListener('resize', updateVisibility);
