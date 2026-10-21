document.addEventListener('DOMContentLoaded', function () {
    const refundDetailModal = document.getElementById('refundDetailModal');
    const refundModal = document.getElementById('refundModal');
    const openRefundFromDetailBtn = document.getElementById('openRefundFromDetailBtn');

    if (!refundDetailModal) {
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

    refundDetailModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        if (!button) {
            return;
        }

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
        const eligibility = button.getAttribute('data-eligibility') || '-';

        const detailName = document.getElementById('detailName');
        const detailPhone = document.getElementById('detailPhone');
        const detailEmail = document.getElementById('detailEmail');
        const detailPurchaseType = document.getElementById('detailPurchaseType');
        const detailTargetType = document.getElementById('detailTargetType');
        const detailPlan = document.getElementById('detailPlan');
        const detailMethod = document.getElementById('detailMethod');
        const detailStatus = document.getElementById('detailStatus');
        const detailPaidAt = document.getElementById('detailPaidAt');
        const detailTotal = document.getElementById('detailTotal');
        const detailEligibility = document.getElementById('detailEligibility');

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
        if (detailEligibility) detailEligibility.value = eligibility;

        selectedRefundData = {
            id: id,
            name: name,
            phone: phone,
            email: email,
            plan: plan,
            total: total
        };
    });

    if (openRefundFromDetailBtn && refundModal) {
        openRefundFromDetailBtn.addEventListener('click', function () {
            const refundTransactionId = document.getElementById('refundTransactionId');
            const refundCustomerName = document.getElementById('refundCustomerName');
            const refundCustomerPhone = document.getElementById('refundCustomerPhone');
            const refundPlanName = document.getElementById('refundPlanName');
            const refundTotalAmount = document.getElementById('refundTotalAmount');

            if (refundTransactionId) refundTransactionId.value = selectedRefundData.id || '';
            if (refundCustomerName) refundCustomerName.textContent = selectedRefundData.name || '-';
            if (refundCustomerPhone) refundCustomerPhone.textContent = selectedRefundData.phone || '-';
            if (refundPlanName) refundPlanName.textContent = selectedRefundData.plan || '-';
            if (refundTotalAmount) refundTotalAmount.textContent = 'Rp ' + (selectedRefundData.total || '-');

            const detailModalInstance = bootstrap.Modal.getInstance(refundDetailModal);
            if (detailModalInstance) {
                detailModalInstance.hide();
            }

            const refundModalInstance = new bootstrap.Modal(refundModal);
            refundModalInstance.show();
        });
    }
});