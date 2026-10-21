export type ApiCreateMembershipPlanBodyType = {
  code: string;
  name: string;
  target_member_type: string;
  audience_scope: string;
  price: number;
  duration_days: number;
  daily_limit: number;
  description: string;
  is_active: number;
};
