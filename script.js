const viewButtons = document.querySelectorAll(".action.view");
const dialog = document.getElementById("consultationDialog");
const dialogBody = dialog.querySelector(".dialog-body");
const closeDialog = document.getElementById("closeDialog");

viewButtons.forEach((btn) => {
    btn.addEventListener("click", async (e) => {
        e.preventDefault();
        const id = btn.dataset.id;

        try {
            const response = await fetch(`get_consultation.php?id=${id}`);
            if (!response.ok) throw new Error("Network response was not ok");
            const data = await response.json();

            dialogBody.innerHTML = `
                <p><strong>Date:</strong> ${new Date(data.ConsultDateTime).toLocaleString()}</p>
                <p><strong>Patient:</strong> ${data.PatientFirstName} ${data.PatientMiddleInit}. ${data.PatientLastName}</p>
                <p><strong>Doctor:</strong> ${data.DocFirstName} ${data.DocMiddleInit}. ${data.DocLastName}</p>
                <p><strong>Diagnosis:</strong> ${data.Diagnosis || "N/A"}</p>
                <p><strong>Prescription:</strong> ${data.Prescription || "N/A"}</p>
            `;
            dialog.showModal();
        } catch (error) {
            console.error(error);
            alert("Failed to fetch consultation details");
        }
    });
});

closeDialog.addEventListener("click", () => dialog.close());
