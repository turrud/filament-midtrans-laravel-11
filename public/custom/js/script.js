// modal form booking
function openModal() {
    const modal = document.getElementById('bookingModal');
    const modalContent = document.getElementById('modalContent');
    modal.classList.remove('hidden');
    // Trigger reflow
    void modal.offsetWidth;
    modal.classList.add('opacity-100');
    modalContent.classList.add('show');
}

function closeModal() {
    const modal = document.getElementById('bookingModal');
    const modalContent = document.getElementById('modalContent');
    modal.classList.remove('opacity-100');
    modalContent.classList.remove('show');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('bookingModal');
    if (event.target === modal) {
        closeModal();
    }
}

// modal form booking end
