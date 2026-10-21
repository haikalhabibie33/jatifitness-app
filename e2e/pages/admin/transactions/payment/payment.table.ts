import { TableConfig } from '#types/table.config.type.js';

export const PaymentProcessingTableConfig = {
  table: 'payment-processing-table',
  row: 'payment-processing-row',
  emptyState: '',
  columns: {
    access_token: 'access-token-column',
    name: 'name-column',
    phone: 'phone-column',
    plan: 'plan-name-column',
    type: 'member-type-column',
    total: 'price-column',
    status: 'status-column',
  },
  actions: {
    confirm: 'confirm-payment-btn',
    waitingApproval: 'payment-wait-approval',
    viewQrButton: 'payment-view-qr',
    notEligible: 'payment-not-eligible',
  },
  uniqueKeys: ['access_token'] as const,
} satisfies TableConfig;

export type PaymentProcessingTableColumns = {
  access_token: string;
  name: string;
  phone: string;
  plan: string;
  type: string;
  total: string;
  status: string;
};

export type PaymentProcessingTableActions = {
  confirm: 'confirm-payment-btn';
  waitingApproval: 'payment-wait-approval';
  viewQrButton: 'payment-view-qr';
  notEligible: 'payment-not-eligible';
};

export type PaymentProcessingTableUniqueKeys = {
  access_token: string;
};
