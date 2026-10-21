const memberModal = document.getElementById('memberModal');
if (memberModal) {
  memberModal.addEventListener('show.bs.modal', event => {
    const btn = event.relatedTarget;
  
    const set = (id, val) =>
      document.getElementById(id).textContent = val ?? '-';
  
    set('mName', btn.dataset.name);
    set('mDob', btn.dataset.dob);
    set('mGender', btn.dataset.gender);
    set('mTarget', btn.dataset.target);
    set('mPhone', btn.dataset.phone);
    set('mEmail', btn.dataset.email);
    set('mAddress', btn.dataset.address);
    set('mStatus', btn.dataset.status);
  })
}
