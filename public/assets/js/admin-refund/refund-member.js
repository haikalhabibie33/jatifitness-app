document.addEventListener('DOMContentLoaded', function () {
    const refundModal = document.getElementById('memberRefundModal');
    const refundForm = document.getElementById('memberRefundForm');

    if (!refundModal || !refundForm) {
        return;
    }

    refundModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        const id = button.getAttribute('data-id') || '';
        const name = button.getAttribute('data-name') || '-';
        const phone = button.getAttribute('data-phone') || '-';
        const plan = button.getAttribute('data-plan') || '-';
        const total = button.getAttribute('data-total') || '-';

        const transactionIdField = document.getElementById('memberRefundTransactionIdField');
        const customerName = document.getElementById('memberRefundCustomerName');
        const customerPhone = document.getElementById('memberRefundCustomerPhone');
        const planName = document.getElementById('memberRefundPlanName');
        const totalAmount = document.getElementById('memberRefundTotalAmount');

        if (transactionIdField) transactionIdField.value = id;
        if (customerName) customerName.textContent = name;
        if (customerPhone) customerPhone.textContent = phone;
        if (planName) planName.textContent = plan;
        if (totalAmount) totalAmount.textContent = 'Rp ' + total;

        if (window.refundMemberBaseUrl) {
            refundForm.action = window.refundMemberBaseUrl.replace(/\/$/, '') + '/' + id;
        }
    });
});