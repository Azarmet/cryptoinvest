document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const registerForm = document.querySelector('.register-form');
    
    // Création de la zone de critères si elle n'existe pas déjà
    let criteriaContainer = document.getElementById('password-criteria');
    if (!criteriaContainer) {
        criteriaContainer = document.createElement('div');
        criteriaContainer.id = 'password-criteria';
        criteriaContainer.className = 'password-criteria';
        passwordInput.parentNode.insertBefore(criteriaContainer, passwordInput.nextSibling);
    }

    // Définition des critères
    const criteria = {
        uppercase: {
            regex: /[A-Z]/,
            message: 'Au moins une majuscule'
        },
        lowercase: {
            regex: /[a-z]/,
            message: 'Au moins une minuscule'
        },
        number: {
            regex: /[0-9]/,
            message: 'Au moins un chiffre'
        },
        special: {
            regex: /[\W_]/,
            message: 'Au moins un caractère spécial'
        },
        length: {
            regex: /.{8,}/,
            message: 'Au moins 8 caractères'
        }
    };

    // Fonction qui met à jour l'affichage des critères
    function updateCriteria() {
        const value = passwordInput.value;
        const confirmValue = confirmPasswordInput.value;
        let allValid = true;
        let criteriaHTML = '<ul>';
        
        // Vérification des critères principaux
        for (let key in criteria) {
            const item = criteria[key];
            if (item.regex.test(value)) {
                criteriaHTML += `<li class="valid">${item.message}</li>`;
            } else {
                criteriaHTML += `<li class="invalid">${item.message}</li>`;
                allValid = false;
            }
        }
        
        // Vérification de la correspondance des mots de passe
        if (value === confirmValue && value !== '') {
            criteriaHTML += `<li class="valid">Les deux mots de passe correspondent</li>`;
        } else {
            criteriaHTML += `<li class="invalid">Les deux mots de passe doivent être identiques</li>`;
            allValid = false;
        }
        
        criteriaHTML += '</ul>';
        criteriaContainer.innerHTML = criteriaHTML;
        return allValid;
    }

    // Mise à jour dynamique lors de la saisie
    passwordInput.addEventListener('input', updateCriteria);
    confirmPasswordInput.addEventListener('input', updateCriteria);

    // Empêche l'envoi du formulaire si les critères ne sont pas remplis
    registerForm.addEventListener('submit', function(e) {
        if (!updateCriteria()) {
            e.preventDefault();
            alert("Veuillez remplir tous les critères de sécurité du mot de passe.");
        }
    });
});
