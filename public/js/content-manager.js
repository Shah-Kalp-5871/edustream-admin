/**
 * Simple modal handler
 */
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('show');
        // Focus first input if any
        const firstInput = modal.querySelector('input, select, textarea');
        if (firstInput) setTimeout(() => firstInput.focus(), 100);
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('show');
}

/**
 * Dropdown handler
 */
function toggleDropdown(event, btn) {
    event.stopPropagation();
    const dropdown = btn.closest('.dropdown');
    // Close other dropdowns
    document.querySelectorAll('.dropdown.show').forEach(d => {
        if (d !== dropdown) d.classList.remove('show');
    });
    dropdown.classList.toggle('show');
}

/**
 * Global click listener to close dropdowns and modals
 */
window.addEventListener('click', function(e) {
    // Close dropdowns when clicking outside
    if (!e.target.closest('.dropdown')) {
        document.querySelectorAll('.dropdown.show').forEach(d => d.classList.remove('show'));
    }
    
    // Close modals when clicking on the backdrop
    if (e.target.classList.contains('modal-backdrop')) {
        e.target.classList.remove('show');
    }
});

/**
 * Standard selection logic
 */
function updateSelectedCounter(countId, count) {
    const el = document.getElementById(countId);
    if (el) el.textContent = count;
}
