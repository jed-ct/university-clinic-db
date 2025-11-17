const deletePatientModal = document.querySelector('#delete-patient-modal');
const deletePatientButton = document.querySelector('.action.delete-ptnt');
const confirmDeletionButton = document.querySelector('.action.confirm-delete');

deletePatientButton.addEventListener("click", ()=> {
    deletePatientModal.style.display = 'flex';
});
