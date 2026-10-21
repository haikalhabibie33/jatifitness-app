const editMemberModal = document.getElementById('editMemberModal');

if (editMemberModal) {
  editMemberModal.addEventListener('show.bs.modal', event => {
    const btn = event.relatedTarget;
    if (!btn) return;

    const id = btn.dataset.id;
    const name = btn.dataset.name;
    const dob = btn.dataset.dob;
    const gender = btn.dataset.gender;
    const target = btn.dataset.target;
    const status = btn.dataset.status;
    const phone = btn.dataset.phone;
    const email = btn.dataset.email;
    const address = btn.dataset.address;
    const active = btn.dataset.active;

    const form = editMemberModal.querySelector('form');
    form.action = `${window.memberUpdateBaseUrl}/${id}`;

    document.getElementById('eMemberId').value = id;
    document.getElementById('eName').value = name ?? '';
    document.getElementById('eGender').value = gender ?? '';
    document.getElementById('eDob').value = dob ?? '';
    document.getElementById('eMemberType').value = target ?? 'REGULAR';
    document.getElementById('ePhone').value = phone ?? '';
    document.getElementById('eEmail').value = email ?? '';
    document.getElementById('eAddress').value = address ?? '';
    document.getElementById('eIsActive').checked = active === '1';
  });
}