document.addEventListener('DOMContentLoaded', function () {
    const membershipModal = document.getElementById('viewMembershipModal');

    if (!membershipModal) {
        return;
    }

    membershipModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        if (!button) return;

        const memberName = button.getAttribute('data-name') || '-';
        const memberType = button.getAttribute('data-member-type') || '-';
        const membershipJson = button.getAttribute('data-membership') || '[]';

        let memberships = [];

        try {
            memberships = JSON.parse(membershipJson);
        } catch (error) {
            memberships = [];
        }

        const vmMemberName = document.getElementById('vmMemberName');
        const vmMemberType = document.getElementById('vmMemberType');
        const tableBody = document.getElementById('vmMembershipTableBody');

        if (vmMemberName) vmMemberName.textContent = memberName;
        if (vmMemberType) vmMemberType.textContent = memberType;

        if (!tableBody) return;

        if (!Array.isArray(memberships) || memberships.length === 0) {
            tableBody.innerHTML = `
                <tr data-testid="view-membership-table-empty">
                    <td colspan="5" class="text-center text-muted">
                        No membership data available
                    </td>
                </tr>
            `;
            return;
        }

        tableBody.innerHTML = memberships.map((item, index) => `
            <tr data-testid="view-membership-member-row">
                <td data-testid="td-number">${index + 1}</td>
                <td data-testid="td-plan-name">${item.plan_name_snapshot ?? '-'}</td>
                <td data-testid="td-status">${item.status ?? '-'}</td>
                <td data-testid="td-start-date">${item.start_date ?? '-'}</td>
                <td data-testid="td-end-date">${item.end_date ?? '-'}</td>
            </tr>
        `).join('');
    });
});
