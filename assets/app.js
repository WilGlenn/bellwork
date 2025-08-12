// Minimal JS for attendance checkboxes and totals
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
