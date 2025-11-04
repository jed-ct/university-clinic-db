console.log('hello w');

const viewButton = document.querySelectorAll('.action.view');
const viewModal = document.querySelector('#consultation-modal');
const viewModalCloseButton = document.querySelector('#close-modal');
viewButton.forEach((viewButton)=> {
    viewButton.addEventListener("click", async ()=> {
        viewModal.style.display = 'flex';
        const id = viewButton.dataset.id;
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

        } catch(error) {
            alert('not work')
        }
    });
});

viewModalCloseButton.addEventListener("click", ()=> {
    viewModal.style.display = 'none';
})


