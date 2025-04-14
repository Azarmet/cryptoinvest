document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const registerForm = document.querySelector('.register-form');
    
    // Create the criteria container if it doesn't already exist
    let criteriaContainer = document.getElementById('password-criteria');
    if (!criteriaContainer) {
        criteriaContainer = document.createElement('div');
        criteriaContainer.id = 'password-criteria';
        criteriaContainer.className = 'password-criteria';
        passwordInput.parentNode.insertBefore(criteriaContainer, passwordInput.nextSibling);
    }

    // Define the criteria
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

    // Function to update the criteria display
    function updateCriteria() {
        const value = passwordInput.value;
        const confirmValue = confirmPasswordInput.value;
        let allValid = true;
        let criteriaHTML = '<ul>';
        
        // Check the main criteria
        for (let key in criteria) {
            const item = criteria[key];
            if (item.regex.test(value)) {
                criteriaHTML += `<li class="valid">${item.message}</li>`;
            } else {
                criteriaHTML += `<li class="invalid">${item.message}</li>`;
                allValid = false;
            }
        }
        
        // Check password matching
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

    // Update dynamically on input
    passwordInput.addEventListener('input', updateCriteria);
    confirmPasswordInput.addEventListener('input', updateCriteria);

    // Prevent form submission if criteria are not met
    registerForm.addEventListener('submit', function(e) {
        if (!updateCriteria()) {
            e.preventDefault();
            alert("Please fulfill all password security criteria.");
        }
    });
});
