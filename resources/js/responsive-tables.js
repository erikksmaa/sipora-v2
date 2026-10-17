// Reuse each table's actual headings; keep the same rows, forms and pagination.
export function prepareMobileTables(root = document) {
    root.querySelectorAll('main table:not([data-keep-table])').forEach((table) => {
        if (table.classList.contains('mobile-card-table') || !table.tHead || table.tHead.rows.length !== 1) return;
        const headings = [...table.tHead.rows[0].cells].map((cell) => cell.textContent.trim());
        if (!headings.length) return;
        table.setAttribute('role', 'table');
        table.tHead.setAttribute('role', 'rowgroup');
        table.tHead.rows[0].setAttribute('role', 'row');
        [...table.tHead.rows[0].cells].forEach((cell) => cell.setAttribute('role', 'columnheader'));
        [...table.tBodies].forEach((body) => {
            body.setAttribute('role', 'rowgroup');
            [...body.rows].forEach((row) => {
                row.setAttribute('role', 'row');
                let column = 0;
                [...row.cells].forEach((cell) => {
                    cell.setAttribute('role', cell.tagName === 'TH' ? 'rowheader' : 'cell');
                    if (cell.colSpan === 1) {
                        const label = document.createElement('span');
                        label.className = 'mobile-cell-label';
                        label.setAttribute('aria-hidden', 'true');
                        label.textContent = headings[column] || '';
                        cell.prepend(label);
                    } else {
                        cell.classList.add('mobile-full-cell');
                    }
                    column += cell.colSpan;
                });
            });
        });
        table.classList.add('mobile-card-table');
        table.closest('.overflow-x-auto, .stats-table-wrap, .eco-map-table-wrap')?.classList.add('mobile-table-wrap');
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => prepareMobileTables(), {once: true});
} else {
    prepareMobileTables();
}
