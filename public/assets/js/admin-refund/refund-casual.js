document.addEventListener('DOMContentLoaded', function () {
    const refundModal = document.getElementById('refundModal');
    const refundForm = document.getElementById('refundForm');

    if (!refundModal || !refundForm) {
        return;
    }

    refundModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        if (!button) {
            return;
        }

        const id = button.dataset.id || '';
        const name = button.dataset.name || '-';
        const phone = button.dataset.phone || '-';
        const plan = button.dataset.plan || '-';
        const total = button.dataset.total || '0';

        const refundName = document.getElementById('refundName');
        const refundPhone = document.getElementById('refundPhone');
        const refundPlan = document.getElementById('refundPlan');
        const refundTotal = document.getElementById('refundTotal');

        if (refundName) refundName.textContent = name;
        if (refundPhone) refundPhone.textContent = phone;
        if (refundPlan) refundPlan.textContent = plan;
        if (refundTotal) refundTotal.textContent = total;

        refundForm.action = window.refundCasualBaseUrl + '/' + id;
    });
});