import { TableConfig } from '#types/table.config.type.js';

export const MembershipPlanTableConfig = {
  table: 'membership-plan-table',
  row: 'membership-plan-table-row',
  emptyState: 'membership-plan-empty-state',
  columns: {
    number: 'td-number',
    code: 'td-plan-code',
    name: 'td-plan-name',
    target: 'td-target-type',
    scope: 'td-audience-scope',
    duration: 'td-duration',
    price: 'td-price',
    dailyLimit: 'td-daily-limit',
    status: 'td-plan-status',
  },
  actions: {
    editPlan: 'edit-plan-btn',
  },
  uniqueKeys: ['code'] as const,
} satisfies TableConfig;

export type MembershipPlanTableColumns = {
  number: string;
  code: string;
  name: string;
  target: string;
  scope: string;
  duration: string;
  price: string;
  dailyLimit: string;
  status: string;
};

export type MembershipPlanTableActions = {
  editPlan: string;
};

export type MembershipPlanUniqueKey = {
  code: string;
};
