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
const isCurrentDateTimeCheckbox = document.querySelector('#is-current-date-time');
const addConsultationForm = document.querySelector("#add-consultation-form");
const filterConsultationForm = document.querySelector('#filter-consultation-form');

viewButton.forEach((viewButton)=> {
    viewButton.addEventListener("click", async ()=> {
        viewConsultationModal.style.display = 'flex';
        const id = viewButton.dataset.id;
        deleteConsultationButton.dataset.id = id;
        editConsultationButton.dataset.id = id;
        try {
            const response = await fetch(`get_consultation.php?id=${id}`);
            const data = await response.json();
            console.log(data);
            document.querySelector('#view-consultation-date').textContent = data.ConsultDate;
            document.querySelector('#view-consultation-time').textContent = data.ConsultTime;
            document.querySelector('#view-patient-first-name').textContent = data.PatientFirstName;
            document.querySelector('#view-patient-middle-initial').textContent = data.PatientMiddleInit;
            document.querySelector('#view-patient-last-name').textContent = data.PatientLastName;
            document.querySelector('#view-patient-age').textContent = data.PatientAge;
            document.querySelector('#view-diagnosis').textContent = data.Diagnosis;
            document.querySelector('#view-prescription').textContent = data.Prescription;
            document.querySelector('#view-doctor-name').textContent = `${data.DocFirstName} ${data.DocMiddleInit}. ${data.DocLastName}`;
            document.querySelector('#view-remarks').textContent = data.Remarks;
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

editConsultationButton.addEventListener("click", async ()=> {
    editConsultationModal.style.display = 'flex';
    viewConsultationModal.style.display = 'none';
    const id = editConsultationButton.dataset.id;
    try {
        const response = await fetch(`get_consultation.php?id=${id}`);
        const data = await response.json();
        document.querySelector('#edit-consultation-date').value = convertToISO(data.ConsultDate); 
        document.querySelector('#edit-consultation-time').value = convertTo24Hour(data.ConsultTime);
        document.querySelector('#edit-patient-name').value = `${data.PatientFirstName} ${data.PatientMiddleInit}. ${data.PatientLastName}`;
        document.querySelector('#edit-diagnosis').value = data.Diagnosis;
        document.querySelector('#edit-prescription').value = data.Prescription;
        document.querySelector('#edit-remarks').value = data.Remarks;
        document.querySelector('#edit-doctor-name').value = `${data.DocFirstName} ${data.DocMiddleInit}. ${data.DocLastName}`;
    }
    catch(error) {
        alert('not work');
    }    
});

deleteConsultationButton.addEventListener("click", ()=> {
    deletionModal.style.display = 'flex';
    viewConsultationModal.style.display = 'none';
});


addConsultationForm.addEventListener("submit", async (e) => {
  // Prevent the default form submission (which would reload the page)
    e.preventDefault();
if (!isCurrentDateTimeCheckbox.checked) {
    const dateValue = document.querySelector('#set-consultation-date').value;
    const timeValue = document.querySelector('#set-consultation-time').value;

    if (!dateValue || !timeValue) {
        document.querySelector('#add-datetime-error-message').textContent = 'Please input your desired date and time.';
        document.querySelector('#add-datetime-error-message').style.display = 'block';
        return; // stop form submission
    }
}
    if (!document.querySelector('#add-patient-name').value.trim()) {
        document.querySelector('#add-patient-error-message').textContent = 'Required.';
        document.querySelector('#add-patient-error-message').style.display = 'block';
        document.querySelector('#add-patient-name').focus();
        return;
    }

    const diagnosis = document.querySelector('#add-diagnosis').value.trim();
    if (!diagnosis) {
        document.querySelector('#add-diagnosis-error-message').textContent = 'Required.';
        document.querySelector('#add-diagnosis-error-message').style.display = 'block';
        document.querySelector('#add-diagnosis').focus();
        return;
    }

    const prescription = document.querySelector('#add-prescription').value.trim();
    if (!prescription) {
        document.querySelector('#add-prescription-error-message').textContent = 'Required.';
        document.querySelector('#add-prescription-error-message').style.display = 'block';
        document.querySelector('#add-prescription').focus();
        return;
    }
    const doctorName = document.querySelector('#add-doctor-name').value.trim();
    if (!doctorName) {
        document.querySelector('#add-doctor-error-message').textContent = 'Required.';
        document.querySelector('#add-doctor-name').focus();
        document.querySelector('#add-doctor-error-message').style.display = 'block';
        return;
    }


    const formData = new FormData(addConsultationForm);

    const response = await fetch('./add_consultation.php', {
        method: 'POST',
        body: formData
    });
    const text = (await response.text()).trim();
    console.log('PHP response:', text);

    if (text === "New record created successfully") {
        addConsultationModal.style.display = 'none';
    } else {
        alert("Error: " + text);
    }
  // You can iterate over the FormData entries and log them
  console.log("Form Data:");
  for (const [key, value] of formData.entries()) {
    console.log(`${key}: ${value}`);
  }

  // Alternatively, if you want a plain object representation:
  const formObject = {};
  formData.forEach((value, key) => {
    formObject[key] = value;
  });
  console.log("Form Data as Object:", formObject);
})

// input event listener
addConsultationForm.addEventListener('input', (() => {
    let timeoutId;
    return (e) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            const field = e.target;
            if (field.name == "ConsultationDate" || field.name == "ConsultationTime") {
                document.querySelector('#add-datetime-error-message').style.display = 'none';
            }
            if (field.name === 'PatientName') {
                if (!field.checkValidity()) {
                    document.querySelector('#add-patient-error-message').textContent = 'Please enter a valid name.';
                    document.querySelector('#add-patient-error-message').style.display = 'block';
                    disableButton(document.querySelector('.action.add'));
                } else {
                    document.querySelector('#add-patient-error-message').style.display = 'none';
                    disableButton(document.querySelector('.action.add'), false);

                }
            }
            if (field.name === 'DoctorName') {
                if (!field.checkValidity()) {
                    document.querySelector('#add-doctor-error-message').textContent = 'Please enter a valid name.';
                    document.querySelector('#add-doctor-error-message').style.display = 'block';
                    disableButton(document.querySelector('.action.add'));
                } else {
                    document.querySelector('#add-doctor-error-message').style.display = 'none';
                    disableButton(document.querySelector('.action.add'), false);

                }
            }
        }, 500); // 500ms debounce delay
    };
})());

isCurrentDateTimeCheckbox.addEventListener("change", ()=> {
    const isChecked = isCurrentDateTimeCheckbox.checked;
    if (isChecked) {
        document.querySelector('#set-consultation-date').setAttribute('disabled', 0);
        document.querySelector('#set-consultation-time').setAttribute('disabled', 0);
    }
    else {
        document.querySelector('#set-consultation-date').removeAttribute('disabled');
        document.querySelector('#set-consultation-time').removeAttribute('disabled');
    }
})

filterConsultationForm.addEventListener('input', (() => {
    let timeoutId;
    const startDateInput = document.querySelector('#filter-start-date');
    const endDateInput = document.querySelector('#filter-end-date');
    //set max possible dates to current
    startDateInput.setAttribute('max', new Date().toISOString().slice(0, 10));
    endDateInput.setAttribute('max', new Date().toISOString().slice(0, 10));
    return (e) => {
        clearTimeout(timeoutId);
        const field = e.target;
        timeoutId = setTimeout(() => {
            //Validation for dates
            if (startDateInput.value && endDateInput.value) {
                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);
                if (endDate < startDate) {
                    document.querySelector('#filter-date-error-message').textContent = "End date must not be earlier than starting date";
                    disableButton(document.querySelector('.action.filter'));
                    document.querySelector('#filter-date-error-message').style.display = "block";
                } else {
                    document.querySelector('#filter-date-error-message').style.display = "none";
                    disableButton(document.querySelector('.action.filter'), false);
                }
            }

            else if (field.name === "PatientName") {
                if (!field.checkValidity()) {
                    document.querySelector('#filter-patient-error-message').textContent = 'Please enter a valid name.';
                    document.querySelector('#filter-patient-error-message').style.display = 'block';
                    disableButton(document.querySelector('.action.filter'));
                } else {
                    document.querySelector('#filter-patient-error-message').style.display = 'none';
                    disableButton(document.querySelector('.action.filter'), false);
                }
            }

            else if (field.name === "DoctorName") {
                if (!field.checkValidity()) {
                    document.querySelector('#filter-doctor-error-message').textContent = 'Please enter a valid name.';
                    document.querySelector('#filter-doctor-error-message').style.display = 'block';
                    disableButton(document.querySelector('.action.filter'));
                } else {
                    document.querySelector('#filter-doctor-error-message').style.display = 'none';
                    disableButton(document.querySelector('.action.filter'), false);
                }
            }

        }, 500);
    };
})());








modalCloseButton.forEach((btn) => {
    btn.addEventListener("click", ()=> {
        deletionModal.style.display = 'none';
        viewConsultationModal.style.display = 'none';
        addConsultationModal.style.display = 'none';
        editConsultationModal.style.display = 'none';
        filterConsultationModal.style.display = 'none';
    })
});


function updateConsultationTable(tableData) {
    const table = document.querySelector('#consultations-table tbody');
    let row = table.insertRow();
}

function clearTableBody(tableId) {
    const table = document.querySelector(tableId);
    if (!table) return;

    let tbody = table.querySelector('tbody');
    if (tbody) {
    table.removeChild(tbody);
    }

    tbody = document.createElement('tbody');
    table.appendChild(tbody);
}

function disableButton(button, isButtonDisabled=true) {
    if (isButtonDisabled) {
        button.setAttribute('disabled', 0);
    }
    else {
        button.removeAttribute('disabled');
    }
}

function convertToISO(dateStr) {
    const date = new Date(dateStr);

    if (isNaN(date)) {
        throw new Error("Invalid date format");
    }
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}


function convertTo24Hour(timeStr) {
    const [time, modifier] = timeStr.split(' '); // e.g., ["11:00", "AM"]
    let [hours, minutes] = time.split(':').map(Number);

    if (modifier.toUpperCase() === 'PM' && hours !== 12) {
        hours += 12;
    } else if (modifier.toUpperCase() === 'AM' && hours === 12) {
        hours = 0;
    }
    const hh = String(hours).padStart(2, '0');
    const mm = String(minutes).padStart(2, '0');

    return `${hh}:${mm}`;
}