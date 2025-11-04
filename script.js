console.log('hello w');

const viewButton = document.querySelectorAll('.action.view');
const viewModal = document.querySelector('#consultation-modal');
const viewModalCloseButton = document.querySelector('#close-modal');
const viewModalText = document.querySelector(".modal-content > p");
viewButton.forEach((viewButton)=> {
    viewButton.addEventListener("click", async ()=> {
        viewModal.style.display = 'flex';
        const id = viewButton.dataset.id;
        viewModalText.textContent = id;

        try {
            const response = await fetch(`get_consultation.php?id=${id}`);
            const data = await response.json();
            console.log(data.PatientLastName);

        } catch(error) {
            alert('not work')
        }
    });
});

viewModalCloseButton.addEventListener("click", ()=> {
    viewModal.style.display = 'none';
})


