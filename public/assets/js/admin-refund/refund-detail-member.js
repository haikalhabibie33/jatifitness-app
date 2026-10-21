document.addEventListener('DOMContentLoaded', function () {
    const detailModal = document.getElementById('memberRefundDetailModal');
    const refundModal = document.getElementById('memberRefundModal');
    const openRefundBtn = document.getElementById('openMemberRefundFromDetailBtn');

    if (!detailModal) {
        return;
    }

    let selectedRefundData = {
        id: '',
        name: '-',
        phone: '-',
        email: '-',
        plan: '-',
        total: '-'
    };

    detailModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        const id = button.getAttribute('data-id') || '';
        const name = button.getAttribute('data-name') || '-';
        const phone = button.getAttribute('data-phone') || '-';
        const email = button.getAttribute('data-email') || '-';
        const plan = button.getAttribute('data-plan') || '-';
        const method = button.getAttribute('data-method') || '-';
        const total = button.getAttribute('data-total') || '-';
        const paidAt = button.getAttribute('data-paid-at') || '-';
        const type = button.getAttribute('data-type') || '-';
        const target = button.getAttribute('data-target') || '-';
        const status = button.getAttribute('data-status') || '-';

        const detailName = document.getElementById('memberDetailName');
        const detailPhone = document.getElementById('memberDetailPhone');
        const detailEmail = document.getElementById('memberDetailEmail');
        const detailPurchaseType = document.getElementById('memberDetailPurchaseType');
        const detailTargetType = document.getElementById('memberDetailTargetType');
        const detailPlan = document.getElementById('memberDetailPlan');
        const detailMethod = document.getElementById('memberDetailMethod');
        const detailStatus = document.getElementById('memberDetailStatus');
        const detailPaidAt = document.getElementById('memberDetailPaidAt');
        const detailTotal = document.getElementById('memberDetailTotal');

        if (detailName) detailName.value = name;
        if (detailPhone) detailPhone.value = phone;
        if (detailEmail) detailEmail.value = email;
        if (detailPurchaseType) detailPurchaseType.value = type;
        if (detailTargetType) detailTargetType.value = target;
        if (detailPlan) detailPlan.value = plan;
        if (detailMethod) detailMethod.value = method;
        if (detailStatus) detailStatus.value = status;
        if (detailPaidAt) detailPaidAt.value = paidAt;
        if (detailTotal) detailTotal.value = 'Rp ' + total;

        selectedRefundData = {
            id,
            name,
            phone,
            email,
            plan,
            total
        };
    });

    if (openRefundBtn && refundModal) {
        openRefundBtn.addEventListener('click', function () {
            const transactionIdField = document.getElementById('memberRefundTransactionIdField');
            const customerName = document.getElementById('memberRefundCustomerName');
            const customerPhone = document.getElementById('memberRefundCustomerPhone');
            const planName = document.getElementById('memberRefundPlanName');
            const totalAmount = document.getElementById('memberRefundTotalAmount');
            const refundForm = document.getElementById('memberRefundForm');

            if (transactionIdField) transactionIdField.value = selectedRefundData.id || '';
            if (customerName) customerName.textContent = selectedRefundData.name || '-';
            if (customerPhone) customerPhone.textContent = selectedRefundData.phone || '-';
            if (planName) planName.textContent = selectedRefundData.plan || '-';
            if (totalAmount) totalAmount.textContent = 'Rp ' + (selectedRefundData.total || '-');

            if (refundForm && window.refundMemberBaseUrl) {
                refundForm.action = window.refundMemberBaseUrl.replace(/\/$/, '') + '/' + selectedRefundData.id;
            }

            const detailModalInstance = bootstrap.Modal.getInstance(detailModal);
            if (detailModalInstance) {
                detailModalInstance.hide();
            }

            const refundModalInstance = new bootstrap.Modal(refundModal);
            refundModalInstance.show();
        });
    }
});