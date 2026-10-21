document.addEventListener('DOMContentLoaded', function () {

  const data = window.ALL_TRANSACTIONS || [];
  const table = document.getElementById('transactionTable');

  if (!table) {
    return;
  }

  // ========================
  // FORMATTERS
  // ========================

  function formatCurrency(num) {
    return 'Rp ' + Number(num).toLocaleString('id-ID');
  }

  function formatDate(date) {
    if (!date) return '-';
    const d = new Date(date);
    return d.toLocaleString('id-ID');
  }

  function statusBadge(status) {
    let color = 'secondary';

    if (status === 'SUCCESS') color = 'success';
    else if (status === 'FAILED') color = 'danger';
    else if (status === 'REFUNDED') color = 'warning';

    return `<span class="badge bg-${color}">${status}</span>`;
  }

  // ========================
  // RENDER
  // ========================

  function render(rows) {

    if (!rows || !rows.length) {
      table.innerHTML = `
        <tr>
          <td colspan="7" class="text-center p-4 text-muted">
            No transaction data found
          </td>
        </tr>
      `;
      return;
    }

    table.innerHTML = rows.map((t, i) => `
      <tr>
        <td>${i + 1}</td>
        <td class="fw-semibold">${t.name ?? '-'}</td>
        <td>${t.phone ?? '-'}</td>
        <td class="fw-bold">${formatCurrency(t.total_amount)}</td>
        <td><span class="badge bg-secondary">${t.payment_method}</span></td>
        <td>${statusBadge(t.payment_status)}</td>
        <td>${formatDate(t.paid_at)}</td>
      </tr>
    `).join('');
  }

  // ========================
  // FILTER
  // ========================

  function applyFilter() {

    const search = document.querySelector('[name="search"]')?.value?.toLowerCase() || '';
    const method = document.querySelector('[name="method"]')?.value;
    const status = document.querySelector('[name="status"]')?.value;
    const from   = document.querySelector('[name="from"]')?.value;
    const to     = document.querySelector('[name="to"]')?.value;

    const filtered = data.filter(item => {

      const text = (item.name ?? '') + ' ' + (item.phone ?? '');

      if (search && !text.toLowerCase().includes(search)) return false;
      if (method && String(item.payment_method).toUpperCase() !== String(method).toUpperCase()) return false;
      if (String(status && item.payment_status).toUpperCase() !== String(status).toUpperCase()) return false;
      if (from && item.paid_at < from) return false;
      if (to && item.paid_at > to + ' 23:59:59') return false;

      return true;
    });

    render(filtered);
  }

  // ========================
  // DEBOUNCE
  // ========================

  function debounce(fn, delay = 300) {
    let timeout;
    return function () {
      clearTimeout(timeout);
      timeout = setTimeout(fn, delay);
    };
  }

  const debouncedFilter = debounce(applyFilter);

  // ========================
  // EVENTS
  // ========================

  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      applyFilter();
    });
  }

  document.querySelector('[name="search"]')?.addEventListener('input', debouncedFilter);
  document.querySelector('[name="method"]')?.addEventListener('change', applyFilter);
  document.querySelector('[name="status"]')?.addEventListener('change', applyFilter);
  document.querySelector('[name="from"]')?.addEventListener('change', applyFilter);
  document.querySelector('[name="to"]')?.addEventListener('change', applyFilter);

  // ========================
  // INIT
  // ========================

  console.log('DATA:', data);
  render(data);

});