const deletePatientModal = document.querySelector('#delete-patient-modal');
const deletePatientButton = document.querySelector('.action.delete-ptnt');
const confirmDeletionButton = document.querySelector('.action.confirm-delete-patient');
const modalPatientClose = document.querySelectorAll('.close-btn-patient');

const editPatientModal = document.querySelector('#edit-patient-modal');
const editPatientConfirmModal = document.querySelector('#edit-patient-confirm-modal');
const editPatientButton = document.querySelector('.action.edit-ptnt');
const saveEditsButton = document.querySelector('.action.save-edits');
const editPatientForm = document.getElementById('edit-patient-form');


const addPatientButton = document.querySelector('#add-patient-btn');
const addPatientModal = document.querySelector('#add-patient-modal');
const addPatientForm = document.querySelector("#add-patient-form");

const filterPatientModal = document.querySelector('#filter-patient-modal');
const filterPatientButton = document.querySelector('#filter-patient-btn');
const filterPatientForm = document.querySelector('#filter-patient-form');

const modals = document.querySelectorAll('.modal');

function closeModals() {
    modals.forEach(modal => modal.style.display = 'none');
}

document.querySelectorAll('.close-btn-patient').forEach(btn => {
    btn.addEventListener('click', closeModals);
});

document.querySelectorAll('#add-patient-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        addPatientModal.style.display = 'flex';
    });
});

document.querySelectorAll('#add-patient-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        addPatientModal.style.display = 'flex';
    });
});

document.querySelectorAll('#add-patient-form').forEach(form => {
    form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const firstname = document.querySelector('#add-p-firstname').value.trim();
    if (!firstname) {
        document.querySelector('#add-fname-error-message').textContent = 'Required.';
        document.querySelector('#add-fname-error-message').style.display = 'block';
        document.querySelector('#add-p-firstname').focus();
        return;
    }

    const middleinit = document.querySelector('#add-p-middleinit').value.trim();

    const lastname= document.querySelector('#add-p-lastname').value.trim();
    if (!lastname) {
        document.querySelector('#add-lname-error-message').textContent = 'Required.';
        document.querySelector('#add-lname-error-message').style.display = 'block';
        document.querySelector('#add-p-lastname').focus();
        return;
    }

    const sex = document.querySelector('#add-sex').value.trim();
    if (!sex) {
        document.querySelector('#add-sex-error-message').textContent = 'Required.';
        document.querySelector('#add-sex-error-message').focus();
        document.querySelector('#add-sex').style.display = 'block';
        return;
    }

    const bday = document.querySelector('#add-bday').value.trim();
    if (!bday) {
        document.querySelector('#add-bdayerror-message').textContent = 'Required.';
        document.querySelector('#add-bdayerror-message').focus();
        document.querySelector('#add-bday').style.display = 'block';
        return;
    }

    const contact = document.querySelector('#add-contact').value.trim();
    if (!contact) {
        document.querySelector('#add-contact-error-message').textContent = 'Required.';
        document.querySelector('#add-contact-error-message').focus();
        document.querySelector('#add-contact').style.display = 'block';
        return;
    }


    const formData = new FormData(addPatientForm);

    const response = await fetch('./add_patient.php', {
        method: 'POST',
        body: formData
    });

    const text = (await response.text()).trim();
    console.log('PHP response:', text);

    if (text === "New record created successfully") {
        addPatientModal.style.display = 'none';
    } else {
        alert("Error: " + text);
    }

  const formObject = {};
  formData.forEach((value, key) => {
    formObject[key] = value;
  });
  console.log("Form Data as Object:", formObject);
});});

document.querySelectorAll('#filter-patient-btn').forEach(btn => {
    btn.addEventListener('click', () => {
       filterPatientModal.style.display = 'flex';
    });
});

deletePatientButton.addEventListener("click", ()=> {
    deletePatientModal.style.display = 'flex';
});

confirmDeletionButton.addEventListener("click", async ()=> {
    const id = deletePatientButton.dataset.id;
    const response = await fetch(`delete_patient.php?id=${id}`);
    const text = await response.text();
    if (text.includes("success")) {   
        window.location.href = "patient.php"; 
    } else {
        alert("Failed to delete patient.");
    }
});

editPatientButton.addEventListener("click", async () => {
    editPatientModal.style.display = 'flex';
    const id = editPatientButton.dataset.id; 

    try {
        const response = await fetch(`fetch_patient.php?id=${id}`);
        const data = await response.json();

        document.querySelector('#edit-p-firstname').value = data.PatientFirstName;
        document.querySelector('#edit-p-middleinit').value = data.PatientMiddleInit;
        document.querySelector('#edit-p-lastname').value = data.PatientLastName;
        document.querySelector('#edit-sex').value = data.PatientSex;
        document.querySelector('#edit-bday').value = data.PatientBirthday;  
        document.querySelector('#edit-contact').value = data.PatientContactNo;

    } catch (error) {
        alert("Error loading patient.");
        console.error(error);
    }
});

saveEditsButton.addEventListener('click', function(e) {
    const patientID = this.dataset.id;

    const formData = new FormData(editPatientForm);
    formData.append('PatientID', patientID); // Add patient ID

    fetch('update_patient.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const tableRows = document.querySelectorAll("#patient-information-table tr");
            
            // Name
            const firstName = formData.get('PFirstName') || tableRows[1].children[1].innerText.split(' ')[0];
            const middleInit = formData.get('PMiddleInit') || tableRows[1].children[1].innerText.split(' ')[1] || '';
            const lastName = formData.get('PLastName') || tableRows[1].children[1].innerText.split(' ')[2] || '';
            tableRows[1].children[1].innerText = `${firstName} ${middleInit ? middleInit + '.' : ''} ${lastName}`.trim();

            // Sex
            if (formData.get('Sex')) tableRows[2].children[1].innerText = formData.get('Sex');

            // Birthday
            if (formData.get('Birthday')) tableRows[3].children[1].innerText = formData.get('Birthday');

            // Contact Number
            if (formData.get('ContactNo')) tableRows[4].children[1].innerText = formData.get('ContactNo');

            editPatientModal.style.display = 'none';
            editPatientConfirmModal.style.display = 'flex';
            editPatientForm.reset();
        } else {
            alert(data.message);
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred while updating patient info.');
    });
});




