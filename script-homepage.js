function animateValue(obj, start, end, duration) {
  let startTimestamp = null;
  const step = (timestamp) => {
    if (!startTimestamp) startTimestamp = timestamp;
    const progress = Math.min((timestamp - startTimestamp) / duration, 1);
    obj.innerHTML = Math.floor(progress * (end - start) + start);
    if (progress < 1) {
      window.requestAnimationFrame(step);
    }
  };
  window.requestAnimationFrame(step);
}

async function getDatabaseStatistics() {
    const response = await fetch('homepage_stats.php');
    const result = await response.json();
    animateValue(document.querySelector('#total-consultations'), 0, result.totalConsultations, 500);
    animateValue(document.querySelector('#total-doctors'), 0, result.totalDoctors, 500);
    animateValue(document.querySelector('#total-patients'), 0, result.totalPatients, 500);
}

getDatabaseStatistics();