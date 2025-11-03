console.log('hello w');

const viewButton = document.querySelectorAll('.action.view');
const viewModal = document.querySelector('#consultation-modal');
const viewModalCloseButton = document.querySelector('#close-modal');
const viewModalText = document.querySelector(".modal-content > p");
viewButton.forEach((viewButton)=> {
    viewButton.addEventListener("click", ()=> {
        viewModal.style.display = 'flex';
        viewModalText.textContent = viewButton.dataset.id;
        console.log(viewButton.dataset.id);
    });
});

viewModalCloseButton.addEventListener("click", ()=> {
    viewModal.style.display = 'none';
})


