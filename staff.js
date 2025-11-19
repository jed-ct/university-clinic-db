document.addEventListener('DOMContentLoaded', function() {
    // ELEMENTS
    const addModal = document.getElementById('addStaffModal');
    const viewModal = document.getElementById('viewStaffModal');
    const editModal = document.getElementById('editStaffModal');
    const addBtn = document.getElementById('add-doctor-modal-btn');

    // --- ADD MODAL ---
    if(addBtn) {
        addBtn.addEventListener('click', () => {
            addModal.style.display = 'flex'; // Changes inline style from 'none' to 'flex'
        });
    }

    // Functions attached to window so they can be called by onclick="" attributes in PHP if needed
    window.closeAddStaffModal = function() {
        addModal.style.display = 'none';
        document.getElementById('addStaffForm').reset();
    }

    // --- VIEW MODAL ---
    document.querySelectorAll('.view-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const d = btn.dataset;
            document.getElementById('view-fullname').innerText = d.firstname + " " + d.middle + " "+ d.lastname;
            document.getElementById('view-specialty').innerText = d.specialtyname;
            document.getElementById('view-email').innerText = d.email;
            document.getElementById('view-contact').innerText = d.contact;
            document.getElementById('view-address').innerText = d.address;
            
            viewModal.style.display = 'flex';
        });
    });

    window.closeViewModal = function() { 
        viewModal.style.display = 'none'; 
    }

    // --- EDIT MODAL ---
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const d = btn.dataset;
            document.getElementById('edit-id').value = d.id;
            document.getElementById('edit-firstname').value = d.firstname;
            document.getElementById('edit-lastname').value = d.lastname;
            document.getElementById('edit-middle').value = d.middle;
            document.getElementById('edit-specialty').value = d.specialtyid;
            document.getElementById('edit-email').value = d.email;
            document.getElementById('edit-address').value = d.address;
            document.getElementById('edit-contact').value = d.contact;
            document.getElementById('edit-dob').value = d.dob;
            
            editModal.style.display = 'flex';
        });
    });

    window.closeEditModal = function() { 
        editModal.style.display = 'none'; 
    }

    // --- CLOSE ON OUTSIDE CLICK ---
    window.onclick = function(e) {
        if (e.target == addModal) closeAddStaffModal();
        if (e.target == viewModal) closeViewModal();
        if (e.target == editModal) closeEditModal();
    }

    // --- SEARCH REDIRECT ---
    const searchInput = document.getElementById('doctor-search-input');
    if (searchInput) {
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                window.location.href = "staff.php?search=${encodeURIComponent(this.value)}";
            }
        });
    }
});