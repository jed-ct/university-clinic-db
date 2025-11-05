console.log('hello w');

const viewButton = document.querySelectorAll('.action.view');
const viewModal = document.querySelector('#consultation-modal');
const deletionModal = document.querySelector('#delete-confirmation-modal');
const viewModalCloseButton = document.querySelectorAll('.close-btn');
const deleteConsultationButton = document.querySelector('.action.delete');
const confirmDeletionButton = document.querySelector('.action.confirm-delete');
viewButton.forEach((viewButton)=> {
    viewButton.addEventListener("click", async ()=> {
        viewModal.style.display = 'flex';
        const id = viewButton.dataset.id;
        deleteConsultationButton.dataset.id = id;
        try {
            const response = await fetch(`get_consultation.php?id=${id}`);
            const data = await response.json();
            console.log(data);
            document.querySelector('#consultation-date').textContent = data.ConsultDate;
            document.querySelector('#consultation-time').textContent = data.ConsultTime;
            document.querySelector('#patient-first-name').textContent = data.PatientFirstName;
            document.querySelector('#patient-middle-initial').textContent = data.PatientMiddleInit;
            document.querySelector('#patient-last-name').textContent = data.PatientLastName;
            document.querySelector('#patient-age').textContent = data.PatientAge;
            document.querySelector('#diagnosis').textContent = data.Diagnosis;
            document.querySelector('#prescription').textContent = data.Prescription;
            document.querySelector('#doctor-name').textContent = `${data.DocFirstName} ${data.DocMiddleInit}. ${data.DocLastName}`;
            document.querySelector('#remarks').textContent = data.Remarks;
        } catch(error) {
            alert('not work')
        }
    });
});

confirmDeletionButton.addEventListener("click",async ()=> {
    const id = deleteConsultationButton.dataset.id;
    const response = await fetch(`delete_consultation.php?id=${id}`);
    window.location.reload(); 
    deletionModal.style.display = 'none';
})

deleteConsultationButton.addEventListener("click", ()=> {
    deletionModal.style.display = 'flex';
    viewModal.style.display = 'none';
});

viewModalCloseButton.forEach((btn) => {
    btn.addEventListener("click", ()=> {
        deletionModal.style.display = 'none';
        viewModal.style.display = 'none';
    })
})


