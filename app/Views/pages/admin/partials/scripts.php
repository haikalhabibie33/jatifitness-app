<!-- Bootstrap JS -->
<script src="<?= base_url('assets/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<!-- Script kecil admin -->
<script>
  const btnSidebar  = document.getElementById('btnSidebar');
  const sidebar     = document.getElementById('sidebar');
  const backdrop    = document.getElementById('sidebarBackdrop');

  function toggleSidebar(show) {
    if (!sidebar || !backdrop) return;

    sidebar.classList.toggle('show', show);
    backdrop.classList.toggle('show', show);
  }

  // Toggle button (mobile & tablet)
  btnSidebar?.addEventListener('click', () => {
    const isOpen = sidebar.classList.contains('show');
    toggleSidebar(!isOpen);
  });

  // Click backdrop to close
  backdrop?.addEventListener('click', () => toggleSidebar(false));

  // Auto-close when switch to desktop (lg+)
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 992) {
      toggleSidebar(false);
    }
  });
</script>


<script>
  // enable semua tooltip yang ada di halaman saat pertama load
  document.addEventListener('DOMContentLoaded', function () {
    const triggers = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    [...triggers].forEach(el => new bootstrap.Tooltip(el));
  });

  // (opsional) fungsi helper untuk enable tooltip pada elemen yang muncul dinamis (via AJAX)
  window.enableTooltipOn = function (root = document) {
    const triggers = root.querySelectorAll('[data-bs-toggle="tooltip"]:not([data-has-tooltip])');
    [...triggers].forEach(el => {
      new bootstrap.Tooltip(el);
      el.setAttribute('data-has-tooltip', '1'); // penanda agar tidak double init
    });
  };
</script>

<script>
document.querySelectorAll('th[data-sort]').forEach(header => {
  header.addEventListener('click', () => {
    const table = header.closest('table');
    const tbody = table.querySelector('tbody');
    const columnIndex = Array.from(header.parentNode.children).indexOf(header);
    const rows = Array.from(tbody.querySelectorAll('tr'));

    // toggle direction
    const asc = header.classList.toggle('asc');
    header.classList.toggle('desc', !asc);

    // helper: read sortable value safely
    const getValue = (row) => {
      const cell = row.children[columnIndex];
      return cell.dataset.value
        ? cell.dataset.value.trim()
        : cell.innerText.trim();
    };

    rows.sort((a, b) => {
      const A = getValue(a);
      const B = getValue(b);
      return asc
        ? A.localeCompare(B, undefined, { numeric: true, sensitivity: 'base' })
        : B.localeCompare(A, undefined, { numeric: true, sensitivity: 'base' });
    });

    rows.forEach(row => tbody.appendChild(row));
  });
});
</script>

<script>
const contactModal = document.getElementById('contactModal');
if (contactModal) {
  contactModal.addEventListener('show.bs.modal', event => {
  const btn = event.relatedTarget;

  const set = (id, val) =>
    document.getElementById(id).textContent = val ?? '-';

  set('mName', btn.dataset.name);
  set('mEmail', btn.dataset.email);
  set('mSubject', btn.dataset.subject);
  set('mMessage', btn.dataset.message);
})}
</script>

<script>
const addMemberModal = document.getElementById('addMemberModal');

if (addMemberModal) {
  addMemberModal.addEventListener('show.bs.modal', () => {
    const form = addMemberModal.querySelector('form');
    if (form) form.reset();
  });
}
</script>


<script>
const deleteMemberModal = document.getElementById('deleteMemberModal');

if (deleteMemberModal) {
  deleteMemberModal.addEventListener('show.bs.modal', event => {
    const btn = event.relatedTarget;
    if (!btn) return;

    const id   = btn.dataset.id;
    const name = btn.dataset.name;

    // set nama di modal
    document.getElementById('deleteMemberName').textContent = name;

    // set action ke DEACTIVATE
    document.getElementById('deleteMemberForm').action =
      `<?= base_url('admin/member/deactivate') ?>/${id}`;
  });
}
</script>

<!-- CONTACT MESSAGE DATA -->
<script>
const deleteContactModal = document.getElementById('deleteContact');
if (deleteContactModal){
  deleteContactModal.addEventListener('show.bs.modal', event => {
    const btn = event.relatedTarget;
  
    const id   = btn.dataset.id;
    const name = btn.dataset.name;
  
    // set nama di UI
    document.getElementById('deleteMemberName').textContent = name;
  
    // set action form (POST)
    document.getElementById('deleteMemberForm').action =
      `<?= base_url('admin/contact/delete') ?>/${id}`;
  })}
</script>

<?php if (session()->getFlashdata('success')): ?>
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1100">
  <div id="successToast" 
       class="toast text-bg-success border-0 fs-6 py-2 px-3 shadow" 
       role="alert">
    <div class="d-flex">
      <div class="toast-body">
        <i class="bi bi-check-circle me-2 fs-5"></i>
        <?= esc(session()->getFlashdata('success')) ?>
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto"
              data-bs-dismiss="toast"></button>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="toast-container position-fixed top-0 end-0 p-3 mt-5" style="z-index: 1100;">
  <div id="errorToast"
       class="toast align-items-center text-bg-danger border-0 shadow fs-6 py-2 px-3"
       role="alert"
       aria-live="assertive"
       aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        <i class="bi bi-x-circle-fill me-2 fs-5"></i>
        <?= esc(session()->getFlashdata('error')) ?>
      </div>
      <button type="button"
              class="btn-close btn-close-white me-2 m-auto"
              data-bs-dismiss="toast"
              aria-label="Close"></button>
    </div>
  </div>
</div>
<?php endif; ?>



<script>
  document.addEventListener('DOMContentLoaded', function () {
    const toastEl = document.getElementById('successToast');
    if (toastEl) {
      new bootstrap.Toast(toastEl, { delay: 10000 }).show();
    }
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const errorToast = document.getElementById('errorToast');
    if (errorToast) {
      new bootstrap.Toast(errorToast, { delay: 10000 }).show();
    }
  });
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {

  // Sync default open sections
  document.querySelectorAll('.collapsible-section.active').forEach(section => {
    const content = section.querySelector('.collapsible-content');
    const inner   = section.querySelector('.collapsible-inner');
    content.style.height = inner.scrollHeight + 'px';
  });

  document.querySelectorAll('.collapsible-header').forEach(header => {
    header.addEventListener('click', () => {
      const section = header.closest('.collapsible-section');
      const content = section.querySelector('.collapsible-content');
      const inner   = section.querySelector('.collapsible-inner');
      const isOpen  = section.classList.contains('active');

      // Accordion behavior
      document.querySelectorAll('.collapsible-section').forEach(s => {
        if (s !== section && s.classList.contains('active')) {
          const c = s.querySelector('.collapsible-content');
          c.style.height = c.scrollHeight + 'px';
          requestAnimationFrame(() => {
            c.style.height = '0px';
          });
          s.classList.remove('active');
        }
      });

      if (isOpen) {
        // CLOSE (smooth)
        content.style.height = content.scrollHeight + 'px';
        requestAnimationFrame(() => {
          content.style.height = '0px';
        });
        section.classList.remove('active');

      } else {
        // OPEN (smooth)
        content.style.height = '0px';
        section.classList.add('active');
        requestAnimationFrame(() => {
          content.style.height = inner.scrollHeight + 'px';
        });
      }
    });
  });

});
</script>

<!-- search on Membership -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('SearchMember');
  if (!searchInput) return;
  const tableRows = document.querySelectorAll('table tbody tr');

  searchInput.addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();

    tableRows.forEach(row => {
      const rowText = row.textContent.toLowerCase();
      row.style.display = rowText.includes(keyword) ? '' : 'none';
    });
  });
});
</script>

<!-- search contact message -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('SearchContactMessage');
  if (!searchInput) return;
  const tableRows = document.querySelectorAll('table tbody tr');

  searchInput.addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();

    tableRows.forEach(row => {
      const rowText = row.textContent.toLowerCase();
      row.style.display = rowText.includes(keyword) ? '' : 'none';
    });
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const table = document.querySelector('table');
    const exportBtn = document.getElementById('exportToCsvBtn_membership');
    if (!exportBtn) return;

    exportBtn.addEventListener('click', function () {
      let csv = [];
      
    // ===== HEADER (skip Gender & Actions) =====
    const headers = [];
    table.querySelectorAll('thead th').forEach((th, index, ths) => {
      // skip Gender (index 2) dan Actions (kolom terakhir)
      if (index === 2 || index === ths.length - 1) return;

      headers.push(`"${th.textContent.trim()}"`);
    });
    csv.push(headers.join(','))


    // ===== BODY =====
    
    table.querySelectorAll('tbody tr').forEach(row => {
      if (row.style.display === 'none') return;

      let rowData = [];
      row.querySelectorAll('td').forEach((td, index, tds) => {

        // skip Gender (index 2) & Actions (last)
        if (index === 2 || index === tds.length - 1) return;

        let cellText = td.textContent.trim();

        // Phone column (index 3 sebelum gender di-skip)
        // karena Gender di-skip, index Phone tetap 3 di tabel asli
        if (index === 3) {
          cellText = `="${cellText}"`; // jaga awalan 0
        } else {
          cellText = `"${cellText}"`;
        }

        rowData.push(cellText);
      });

      csv.push(rowData.join(','));
    });


    // ===== DOWNLOAD =====
    const csvBlob = new Blob([csv.join('\n')], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(csvBlob);

    const a = document.createElement('a');
    a.href = url;
    a.download = `memberships_${new Date().toISOString().slice(0,10)}.csv`;
    document.body.appendChild(a);
    a.click();

    document.body.removeChild(a);
    URL.revokeObjectURL(url);
  });

});
</script>

<!-- MEMBERSHIP PLAN -->
 <!-- PREFILL EDIT PLAN MODAL -->
 <script>
document.addEventListener('DOMContentLoaded', () => {
  const editModal = document.getElementById('editPlanModal');

  if (!editModal) return;

  editModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    if (!button) return;

    // ambil data dari button
    const id       = button.dataset.id;
    const code     = button.dataset.code;
    const name     = button.dataset.name;
    const duration = button.dataset.duration;
    const target = button.dataset.target;
    const audience = button.dataset.audience;
    const price    = button.dataset.price;
    const description = button.dataset.description;
    const limit = button.dataset.limit;
    const active   = button.dataset.active;

    // set form action (kalau pakai edit endpoint)
    const form = editModal.querySelector('form');
    if (form) {
      form.action = `<?= base_url('admin/membership-package/update') ?>/${id}`;
    }

    // isi field
    editModal.querySelector('#eCode').value = code;
    editModal.querySelector('#eName').value = name;
    editModal.querySelector('#eDuration').value = duration;
    editModal.querySelector('#eTarget').value = target;
    editModal.querySelector('#eAudience').value = audience;
    editModal.querySelector('#ePrice').value = price;
    editModal.querySelector('#eLimit').value = limit;
    editModal.querySelector('#eDescription').value = description ?? '';
    editModal.querySelector('#eIsActive').checked = active === '1';

    toggleMaxCheckin(editModal.querySelector('#eAccessType'));
  });
});
</script>


<script>
function toggleMaxCheckin(selectEl) {
  const wrapper = selectEl
    .closest('.row')
    .querySelector('[data-max-checkin-wrapper]');

  const input = wrapper.querySelector('input');

  if (selectEl.value === 'unlimited') {
    input.value = '';
    input.disabled = true;
    wrapper.classList.add('opacity-50');
  } else {
    input.disabled = false;
    wrapper.classList.remove('opacity-50');
  }
}

document.addEventListener('change', e => {
  if (e.target.matches('.access-type-select')) {
    toggleMaxCheckin(e.target);
  }
});

// default on modal open
document.addEventListener('show.bs.modal', e => {
  if (e.target.id === 'addPlanModal') {
    const select = e.target.querySelector('.access-type-select');
    toggleMaxCheckin(select);
  }
});
</script>

<script src="<?= base_url('assets/js/transaction.js') ?>"></script>


<script>
document.addEventListener('DOMContentLoaded', function () {
  const sidebar = document.getElementById('sidebar');
  if (!sidebar) return;

  // cari menu aktif
  const activeLink = sidebar.querySelector('.nav-link.active');
  if (!activeLink) return;

  // tunggu layout selesai lalu scroll ke item aktif
  requestAnimationFrame(() => {
    activeLink.scrollIntoView({
      block: 'center',
      behavior: 'auto'
    });
  });
});
</script>
</body>
</html>