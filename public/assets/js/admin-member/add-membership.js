document.addEventListener('DOMContentLoaded', () => {

  const addMembershipModal = document.getElementById('addMembershipModal');
  if (!addMembershipModal) return;

  const mPlan          = document.getElementById('mPlan');
  const mMemberId      = document.getElementById('mMemberId');
  const mMemberName    = document.getElementById('mMemberName');
  const mMemberType    = document.getElementById('mTargetType');
  const mPaymentMethod = document.getElementById('mPaymentMethod');

  const normalize = v => (v || '').trim().toUpperCase();

  addMembershipModal.addEventListener('show.bs.modal', e => {
    const btn = e.relatedTarget;
    if (!btn) return;

    const memberType = normalize(btn.dataset.type);
    const audienceScope = normalize(btn.dataset.audience);

    mMemberId.value   = btn.dataset.id;
    mMemberName.value = btn.dataset.name;
    mMemberType.value = btn.dataset.type;


    Array.from(mPlan.options).forEach(opt => {
      if (!opt.value) return;

      const target = normalize(opt.dataset.target);
      const audience = normalize(opt.dataset.audience);

      opt.hidden = !(target === 'ALL' || target === memberType && audience === 'MEMBER_ONLY' || audience === 'BOTH');
    });

    const addMembershipForm = document.getElementById('addMembershipForm');
    const id = btn.dataset.id;
    addMembershipForm.action = window.addMembershipBaseUrl + '/' + id;
  });
});