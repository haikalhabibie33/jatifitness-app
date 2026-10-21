import { TableConfig } from '#types/table.config.type.js';

export const MembershipDataTableConfig = {
  table: 'membership-data-table',
  row: 'membership-data-table-row',
  emptyState: 'membership-data-table-empty',
  columns: {
    number: 'td-number',
    name: 'td-name',
    type: 'td-memberType',
    phone: 'td-phone',
    email: 'td-email',
    status: 'td-status',
  },
  actions: {
    viewMember: 'view-member-details-btn',
    editMember: 'edit-member-details-btn',
    viewMembership: 'view-membership-details-btn',
    addMembership: 'add-memberships-btn',
  },
  uniqueKeys: ['phone', 'email'] as const,
} satisfies TableConfig;

export type MembershipDataTableColumns = {
  number: string;
  name: string;
  type: string;
  phone: string;
  email: string;
  status: string;
};

export type MembershipDataTableActions = {
  viewMember: string;
  editMember: string;
  viewMembership: string;
  addMembership: string;
};
export type MembershipDataTableUniqueKeys = {
  phone: string;
  email: string;
};
