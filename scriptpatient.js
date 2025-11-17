const deletePatientModal = document.querySelector('#delete-patient-modal');
const deletePatientButton = document.querySelector('.action.delete-ptnt');
const confirmDeletionButton = document.querySelector('.action.confirm-delete');
const modalPatientClose = document.querySelectorAll('.close-btn-patient');
const modals = document.querySelectorAll('.modal');

function closeModals() {
    modals.forEach(modal => modal.style.display = 'none');
}

document.querySelectorAll('.close-btn-patient').forEach(btn => {
    btn.addEventListener('click', closeModals);
});

deletePatientButton.addEventListener("click", ()=> {
    deletePatientModal.style.display = 'flex';
});

/*
confirmDeletionButton.addEventListener("click", async ()=> {
    const id = deletePatientButton.dataset.id;
    const response = await fetch(`delete_patient.php?id=${id}`);
    const text = await response.text();
    if (text.includes("success")) {   
        window.location.href = "patients.php"; 
    } else {
        alert("Failed to delete patient.");
    }
});
*/

