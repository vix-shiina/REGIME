// signup.js
// Section: validation for signup form
document.addEventListener('DOMContentLoaded', function(){
    var form = document.getElementById('signupForm');
    if (!form) return;
    form.addEventListener('submit', function(e){
        var nom = form.querySelector('input[name="nom"]').value.trim();
        var prenom = form.querySelector('input[name="prenom"]').value.trim();
        var email = form.querySelector('input[name="email"]').value.trim();
        var pass = form.querySelector('input[name="password"]').value;
        var genre = form.querySelector('select[name="genre_id"]').value;
        if (!nom || !prenom || !email || !pass || !genre){
            e.preventDefault();
            showToast('Veuillez remplir tous les champs', 'error');
            return false;
        }
        if (nom.length < 2 || prenom.length < 2){
            e.preventDefault();
            showToast('Nom/Prénom trop court', 'error');
            return false;
        }
        if (!/^\S+@\S+\.\S+$/.test(email)){
            e.preventDefault();
            showToast('Email invalide', 'error');
            return false;
        }
        if (pass.length < 6){
            e.preventDefault();
            showToast('Le mot de passe doit avoir au moins 6 caractères', 'error');
            return false;
        }
    });

    // Section: auto-hide server-rendered toasts after 5s
    var existingToasts = document.querySelectorAll('.toast');
    existingToasts.forEach(function(toast){
        setTimeout(function(){ toast.parentNode && toast.parentNode.removeChild(toast); }, 5000);
    });
});

// Section: toast helper (kept in this file for separation)
function showToast(message, type){
    var el = document.createElement('div');
    el.className = 'toast ' + (type || 'success');
    el.textContent = message;
    document.body.appendChild(el);
    setTimeout(function(){ el.classList.add('hide'); el.parentNode && el.parentNode.removeChild(el); }, 5000);
}
