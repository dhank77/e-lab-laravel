const loanDialog = document.getElementById('loan-dialog');
const equipmentSelect = document.getElementById('equipment_id');

for (const button of document.querySelectorAll('[data-loan-open]')) {
    button.addEventListener('click', () => {
        equipmentSelect.value = button.dataset.loanOpen;
        loanDialog.showModal();
    });
}

loanDialog.querySelector('[data-loan-close]').addEventListener('click', () => loanDialog.close());

if (loanDialog.dataset.reopen === 'true') {
    loanDialog.showModal();
}
