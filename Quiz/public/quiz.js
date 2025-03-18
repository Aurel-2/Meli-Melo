// Validation pour le formulaire de connexion
function validateLoginForm() {
    const username = document.querySelector('input[name="username"]');
    const password = document.querySelector('input[name="password"]');
    let isValid = true;

    clearErrors();
    const errors = [];

    if (username.value.trim() === '') {
        errors.push('Le nom d\'utilisateur est requis');
        isValid = false;
    }

    if (password.value.trim() === '') {
        errors.push('Le mot de passe est requis');
        isValid = false;
    }

    if (errors.length > 0) {
        showErrors(errors);
    }

    return isValid;
}

// Validation pour le formulaire d'ajout de question
function validateQuestionForm() {
    const question = document.querySelector('input[name="question"]');
    const options = document.querySelectorAll('input[name="options[]"]');
    const answers = document.querySelectorAll('input[name="answer[]"]');
    let isValid = true;

    clearErrors();
    const errors = [];

    if (question.value.trim() === '') {
        errors.push('La question est requise');
        isValid = false;
    }

    let hasEmptyOption = false;
    options.forEach((option, index) => {
        if (option.value.trim() === '') {
            errors.push(`L'option ${index + 1} ne peut pas être vide`);
            hasEmptyOption = true;
        }
    });

    let checkedCount = 0;
    answers.forEach(answer => {
        if (answer.checked) {
            checkedCount++;
        }
    });

    if (checkedCount === 0) {
        errors.push('Veuillez sélectionner une bonne réponse');
        isValid = false;
    } else if (checkedCount > 1) {
        errors.push('Une seule bonne réponse est autorisée');
        isValid = false;
    }

    if (hasEmptyOption) {
        isValid = false;
    }

    if (errors.length > 0) {
        showErrors(errors);
    }

    return isValid;
}

// Validation pour le formulaire de quiz
function validateQuizForm() {
    const questions = document.querySelectorAll('input[type="radio"]');
    const questionGroups = {};

    clearErrors();
    const errors = [];

    questions.forEach(radio => {
        const name = radio.name;
        if (!questionGroups[name]) {
            questionGroups[name] = [];
        }
        questionGroups[name].push(radio);
    });

    let questionIndex = 1;
    for (let name in questionGroups) {
        const radios = questionGroups[name];
        const isChecked = radios.some(radio => radio.checked);
        if (!isChecked) {
            errors.push(`Veuillez sélectionner une réponse pour la question ${questionIndex}`);
        }
        questionIndex++;
    }

    if (errors.length > 0) {
        showErrors(errors);
        return false;
    }
    return true;
}

// Fonction pour afficher les erreurs dans le conteneur
function showErrors(errorMessages) {
    const container = document.getElementById('error-container') || document.createElement('div'); // Fallback si pas trouvé
    container.className = 'error-container';
    container.innerHTML = ''; // Nettoyer le contenu existant

    errorMessages.forEach(message => {
        const error = document.createElement('span');
        error.className = 'error-message';
        error.style.color = 'red';
        error.style.display = 'block';
        error.style.marginTop = '5px';
        error.style.fontSize = '14px';
        error.textContent = message;
        container.appendChild(error);
    });

    if (!document.getElementById('error-container')) {
        const form = document.querySelector('form');
        form.appendChild(container);
    }
}

// Fonction pour nettoyer les messages d'erreur
function clearErrors() {
    const container = document.getElementById('error-container');
    if (container) {
        container.innerHTML = '';
    }
}

// Ajout des options
function add_options() {
    const container = document.getElementById('options-container');
    const optionCount = container.querySelectorAll('.option-item').length;
    const div = document.createElement('div');
    div.className = 'option-item';
    div.innerHTML = `
        <input type="text" name="options[]" placeholder="Option">
        <input type="checkbox" name="answer[]" value="${optionCount}">
        <label>Bonne réponse</label>
    `;
    container.appendChild(div);
}

// Gestion des soumissions de formulaires
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const formType = this.querySelector('input[name="form_type"]').value;

            let isValid = false;
            switch (formType) {
                case 'login':
                    isValid = validateLoginForm();
                    break;
                case 'add_question':
                    isValid = validateQuestionForm();
                    break;
                case 'submit_quiz':
                    isValid = validateQuizForm();
                    break;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    });
});