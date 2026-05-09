document.addEventListener('DOMContentLoaded', () => {
    const panels = Array.from(document.querySelectorAll('[data-profile-panel]'));
    const switchButtons = Array.from(document.querySelectorAll('[data-profile-target]'));
    const actionButtons = Array.from(document.querySelectorAll('[data-profile-action="edit"]'));
    const form = document.getElementById('profileForm');
    const editBtn = document.getElementById('editBtn');
    const saveBtn = document.getElementById('saveBtn');
    const cancelBtn = document.getElementById('cancelBtn');

    const editableFields = form ? Array.from(form.querySelectorAll('input[name], select[name], textarea[name]')) : [];
    const snapshot = new Map();

    editableFields.forEach((field) => {
        snapshot.set(field.name, field.value);
    });

    const setPanel = (panelName) => {
        panels.forEach((panel) => {
            panel.classList.toggle('is-active', panel.dataset.profilePanel === panelName);
        });

        switchButtons.forEach((button) => {
            button.classList.toggle('is-active', button.dataset.profileTarget === panelName);
        });

        if (panelName === 'edit') {
            editableFields.forEach((field) => {
                field.disabled = false;
            });
            if (editBtn) editBtn.classList.add('is-hidden');
            if (saveBtn) saveBtn.classList.remove('is-hidden');
            if (cancelBtn) cancelBtn.classList.remove('is-hidden');

            const firstField = form?.querySelector('input:not([type="hidden"]), select, textarea');
            if (firstField) firstField.focus();
            return;
        }

        editableFields.forEach((field) => {
            field.disabled = true;
        });

        if (editBtn) editBtn.classList.remove('is-hidden');
        if (saveBtn) saveBtn.classList.add('is-hidden');
        if (cancelBtn) cancelBtn.classList.add('is-hidden');
    };

    const resetForm = () => {
        editableFields.forEach((field) => {
            if (snapshot.has(field.name)) {
                field.value = snapshot.get(field.name);
            }
        });
    };

    switchButtons.forEach((button) => {
        button.addEventListener('click', () => {
            const target = button.dataset.profileTarget || 'overview';
            if (target === 'overview') {
                resetForm();
            }
            setPanel(target);
        });
    });

    actionButtons.forEach((button) => {
        button.addEventListener('click', () => {
            setPanel('edit');
        });
    });

    form.addEventListener('submit', () => {
        editableFields.forEach((field) => {
            field.disabled = false;
        });
    });

    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => {
            resetForm();
            setPanel('overview');
        });
    }

    setPanel('overview');
});
