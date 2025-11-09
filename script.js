console.log('hello w');

const viewButton = document.querySelectorAll('.action.view');
const viewConsultationModal = document.querySelector('#consultation-modal');
const addConsultationModal = document.querySelector('#add-consultation-modal');
const deletionModal = document.querySelector('#delete-confirmation-modal');
const filterConsultationModal = document.querySelector('#filter-consultation-modal');
const editConsultationModal = document.querySelector('#edit-consultation-modal');
const modalCloseButton = document.querySelectorAll('.close-btn');
const deleteConsultationButton = document.querySelector('.action.delete');
const filterConsultationButton = document.querySelector('#filter-consultation-btn');
const editConsultationButton = document.querySelector('.action.edit');
const confirmDeletionButton = document.querySelector('.action.confirm-delete');
const addConsultationButton = document.querySelector('#add-consultation-btn');
const consultationSearchBox = document.querySelector('#consultation-searchbox');


viewButton.forEach((viewButton)=> {
    viewButton.addEventListener("click", async ()=> {
        viewConsultationModal.style.display = 'flex';
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

addConsultationButton.addEventListener("click", () => {
    addConsultationModal.style.display = 'flex';
})

filterConsultationButton.addEventListener("click", () => {
    filterConsultationModal.style.display = 'flex';
});

editConsultationButton.addEventListener("click", ()=> {
    editConsultationModal.style.display = 'flex';
    viewConsultationModal.style.display = 'none';
});

deleteConsultationButton.addEventListener("click", ()=> {
    deletionModal.style.display = 'flex';
    viewConsultationModal.style.display = 'none';
});

consultationSearchBox.addEventListener("input", ()=> {
    console.log(consultationSearchBox.value);
});

modalCloseButton.forEach((btn) => {
    btn.addEventListener("click", ()=> {
        deletionModal.style.display = 'none';
        viewConsultationModal.style.display = 'none';
        addConsultationModal.style.display = 'none';
        editConsultationModal.style.display = 'none';
        filterConsultationModal.style.display = 'none';
    })
})


