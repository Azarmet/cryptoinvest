document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const registerForm = document.querySelector('.register-form');
    
    // Crée le conteneur des critères s'il n'existe pas déjà
    let criteriaContainer = document.getElementById('password-criteria');
    if (!criteriaContainer) {
        criteriaContainer = document.createElement('div');
        criteriaContainer.id = 'password-criteria';
        criteriaContainer.className = 'password-criteria';
        passwordInput.parentNode.insertBefore(criteriaContainer, passwordInput.nextSibling);
    }

    // Définit les critères
    const criteria = {
        uppercase: {
            regex: /[A-Z]/,
            message: 'At least one uppercase letter'
        },
        lowercase: {
            regex: /[a-z]/,
            message: 'At least one lowercase letter'
        },
        number: {
            regex: /[0-9]/,
            message: 'At least one number'
        },
        special: {
            regex: /[\W_]/,
            message: 'At least one special character'
        },
        length: {
            regex: /.{8,}/,
            message: 'At least 8 characters'
        }
    };

    // Fonction de mise à jour de l'affichage des critères
    function updateCriteria() {
        const value = passwordInput.value;
        const confirmValue = confirmPasswordInput.value;
        let allValid = true;
        let criteriaHTML = '<ul>';
        
        // Vérifie les critères principaux
        for (let key in criteria) {
            const item = criteria[key];
            if (item.regex.test(value)) {
                criteriaHTML += `<li class="valid">${item.message}</li>`;
            } else {
                criteriaHTML += `<li class="invalid">${item.message}</li>`;
                allValid = false;
            }
        }
        
        // Vérifie la correspondance des mots de passe
        if (value === confirmValue && value !== '') {
            criteriaHTML += `<li class="valid">Passwords match</li>`;
        } else {
            criteriaHTML += `<li class="invalid">Passwords must match</li>`;
            allValid = false;
        }
        
        criteriaHTML += '</ul>';
        criteriaContainer.innerHTML = criteriaHTML;
        return allValid;
    }

    // Met à jour dynamiquement à la saisie
    passwordInput.addEventListener('input', updateCriteria);
    confirmPasswordInput.addEventListener('input', updateCriteria);

    // Empêche la soumission du formulaire si les critères ne sont pas remplis
    registerForm.addEventListener('submit', function(e) {
        if (!updateCriteria()) {
            e.preventDefault();
            alert("Please fulfill all password security criteria.");
        }
    });
});
