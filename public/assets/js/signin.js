// signin.js
// Section: validation for signin form
document.addEventListener('DOMContentLoaded', function(){
    var form = document.getElementById('signinForm');
    if (!form) return;
    form.addEventListener('submit', function(e){
        var email = form.querySelector('input[name="email"]').value.trim();
        var pass = form.querySelector('input[name="password"]').value;
        if (!email || !pass){
            e.preventDefault();
            showToast('Veuillez remplir tous les champs', 'error');
            return false;
        }
        // minimal email pattern check
        if (!/^\S+@\S+\.\S+$/.test(email)){
            e.preventDefault();
            showToast('Email invalide', 'error');
            return false;
        }
    });

    // Section: auto-hide server-rendered toasts after 5s
    var existingToasts = document.querySelectorAll('.toast');
    existingToasts.forEach(function(toast){
        setTimeout(function(){ toast.parentNode && toast.parentNode.removeChild(toast); }, 5000);
    });
});

// Section: toast helper (shared behaviour kept here for signin page)
function showToast(message, type){
    var el = document.createElement('div');
    el.className = 'toast ' + (type || 'success');
    el.textContent = message;
    document.body.appendChild(el);
    setTimeout(function(){ el.classList.add('hide'); el.parentNode && el.parentNode.removeChild(el); }, 5000);
}
