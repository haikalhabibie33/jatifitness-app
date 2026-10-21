import { TableConfig } from '#types/table.config.type.js';

export const ViewMembershipTableConfig = {
  table: 'view-membership-member-table',
  row: 'view-membership-member-row',
  emptyState: 'view-membership-table-empty',
  columns: {
    number: 'td-number',
    planName: 'td-plan-name',
    status: 'td-status',
    startDate: 'td-start-date',
    endDate: 'td-end-date',
  },
  actions: {},
  uniqueKeys: ['number'] as const,
} satisfies TableConfig;

export type ViewMembershipDataTableColumns = {
  number: string;
  planName: string;
  status: string;
  startDate: string;
  endDate: string;
};
