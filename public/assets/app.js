// Global delete confirmation for all delete forms
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('form.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });
});
// Minimal JS for attendance checkboxes and totals
// ...existing code...
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.att-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            const { classId, studentId, day } = this.dataset;
            fetch('/api/attendance/toggle', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ classId, studentId, day, checked: this.checked ? 1 : 0 })
            }).then(r => r.json()).then(resp => {
                if (!resp.success) alert(resp.error || 'Error');
            });
            updateTotal(this.closest('tr'));
        });
    });

    // Clear table when teacher dropdown changes
    const teacherSelect = document.querySelector('select[name="teacher"]');
    if (teacherSelect) {
        teacherSelect.addEventListener('change', function() {
            // Find the students table and clear its body
            const table = document.querySelector('table.table');
            if (table) {
                const tbody = table.querySelector('tbody');
                if (tbody) tbody.innerHTML = '';
            }
        });
    }
    function updateTotal(row) {
        let total = 0;
        row.querySelectorAll('.att-checkbox').forEach(cb => { if (cb.checked) total++; });
        row.querySelector('.att-total').textContent = total;
    }
    document.querySelectorAll('tr[data-student]').forEach(row => updateTotal(row));
    // Reset week
    const resetBtn = document.getElementById('reset-week');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (!confirm('Reset attendance for this week?')) return;
            const classId = this.dataset.classId;
            fetch('/api/attendance/reset', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ classId })
            }).then(r => r.json()).then(resp => {
                if (resp.success) location.reload();
                else alert(resp.error || 'Error');
            });
        });
    }
});
