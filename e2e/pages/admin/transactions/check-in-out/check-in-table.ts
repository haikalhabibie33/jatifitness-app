import { TableConfig } from "#types/table.config.type.js";

export const CheckInTableConfig = {
    table:'',
    row:'',
    emptyState:'',
    columns: {
        id: '',
        name:'',
        phone:'',
        type:'',
        status:''
    },
    actions: {
        checkInOut:''
    },
    uniqueKeys: ['id'] as const,
} satisfies TableConfig;

export type CheckInTableColumns = {
    id: string,
    name: string,
    phone: string,
    type: string,
    status: string
}

export type CheckInTableActions = {
    checkInOut: string
}