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
const isCurrentDateTimeCheckbox = document.querySelector('#is-current-date-time');
const addConsultationForm = document.querySelector("#add-consultation-form");

viewButton.forEach((viewButton)=> {
    viewButton.addEventListener("click", async ()=> {
        viewConsultationModal.style.display = 'flex';
        const id = viewButton.dataset.id;
        deleteConsultationButton.dataset.id = id;
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

editConsultationButton.addEventListener("click", ()=> {
    editConsultationModal.style.display = 'flex';
    viewConsultationModal.style.display = 'none';
});

deleteConsultationButton.addEventListener("click", ()=> {
    deletionModal.style.display = 'flex';
    viewConsultationModal.style.display = 'none';
});

// Add an event listener for form submission
addConsultationForm.addEventListener("submit", (event) => {
  // Prevent the default form submission (which would reload the page)
  event.preventDefault();

  // Create a FormData object from the form
  const formData = new FormData(addConsultationForm);

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

addConsultationForm.addEventListener('input', (() => {
    let timeoutId;
    return (e) => {
        clearTimeout(timeoutId); // reset timer on each input
        timeoutId = setTimeout(() => {
            const field = e.target;

            if (field.name === 'PatientName') {
                if (!field.checkValidity()) {
                    document.querySelector('#add-patient-error-message').textContent = 'Please enter a valid name.';
                    document.querySelector('#add-patient-error-message').style.display = 'block';
                    disableButton(document.querySelector('.action.add'));
                } else {
                    document.querySelector('#add-patient-error-message').style.display = 'none';
                    disableButton(document.querySelector('.action.add'));

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

modalCloseButton.forEach((btn) => {
    btn.addEventListener("click", ()=> {
        console.log('hello worl');
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

