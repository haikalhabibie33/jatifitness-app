export type TableColumns = Record<string, string>;
export type TableActions = Record<string, string>;

export type TableConfig<
  TColumns extends TableColumns = TableColumns,
  TActions extends TableActions = TableActions,
> = {
  table: string;
  row: string;
  emptyState?: string;
  columns: TColumns;
  actions?: TActions;
  uniqueKeys?: ReadonlyArray<keyof TColumns & string>;
};

export type RowCriteria<TColumns extends TableColumns> = Partial<
  Record<keyof TColumns & string, string>
>;
