import { TableConfig } from '#types/table.config.type.js';

export const MembershipRequestTableConfig = {
  table: 'membership-request-table',
  row: 'membership-request-rows',
  emptyState: 'membership-request-empty',
  columns: {
    token: 'token-column',
    name: 'name-column',
    phone: 'phone-column',
    requestType: 'request-type-column',
    memberType: 'member-type-column',
    planName: 'plan-name-column',
    planCodeDuration: 'plan-code-duration-column',
    status: 'status-column',
    date: 'date-column',
    time: 'time-column',
  },
  actions: {
    detailRequest: 'detail-request-btn',
    approveRequest: 'approve-request-btn',
    rejectRequest: 'reject-request-btn',
    disabledAction: 'disabled-action',
  },
  uniqueKeys: ['token'] as const,
} satisfies TableConfig;

export type MembershipRequestTableColumns = {
  token: string;
  name: string;
  phone: string;
  requestType: string;
  memberType: string;
  planName: string;
  planCodeDuration: string;
  status: string;
  date: string;
  time: string;
};

export type MembershipRequestTableActions = {
  detailRequest: string;
  approveRequest: string;
  rejectRequest: string;
  disabledAction: string;
};
