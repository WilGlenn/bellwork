// Minimal JS for attendance checkboxes and totals
document.addEventListener('DOMContentLoaded', function() {
	// Three-state attendance toggles
    document.querySelectorAll('.att-toggle').forEach(function(cell) {
        cell.addEventListener('click', function() {
            const states = ['□', '✔', 'A'];
            const classes = ['', 'att-present', 'att-absent'];
            let current = 0;
            if (this.classList.contains('att-present')) current = 1;
            else if (this.classList.contains('att-absent')) current = 2;
            // Next state
            let next = (current + 1) % 3;
            this.textContent = states[next];
            this.classList.remove('att-present', 'att-absent');
            if (classes[next]) this.classList.add(classes[next]);
            // Send to backend
            const classId = this.dataset.classId;
            const studentId = this.dataset.studentId;
            const day = this.dataset.day;
            let value = '';
            if (next === 1) value = 1;
            else if (next === 2) value = 2;
            fetch('/api/attendance/toggle', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ classId, studentId, day, checked: value })
            }).then(r => r.json()).then(resp => {
                if (!resp.success) alert(resp.error || 'Error');
            });
            // Update total for the row
            updateTotal(this.closest('tr'));
        });
    });
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
		row.querySelectorAll('.att-toggle').forEach(tg => {
            if (tg.classList.contains('att-present')) total++;
        });
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
// Drag-and-drop student reordering on attendance page using SortableJS
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.querySelector('#attendance-table tbody');
    if (tbody && window.Sortable) {
        new Sortable(tbody, {
            handle: '.drag-handle',
            animation: 150,
            onEnd: function () {
                console.log('Attendance table reordered');
                //const classId = document.querySelector('input.att-checkbox')?.dataset.classId;
				const classId = document.querySelector('span.att-toggle')?.dataset.classId;
                if (!classId) return;
				console.log("class id worked")
                const order = Array.from(tbody.querySelectorAll('tr[data-student]')).map(tr => tr.getAttribute('data-student'));
                fetch('api/students/reorder', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ classId, order })
                });
            }
        });
        console.log('Sortable initialized for attendance table');
    }
});
