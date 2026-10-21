export type ApiMembershipPlanDetailDataType = {
  id: number;
  code: string;
  name: string;
  price: number;
  duration_days: number;
  is_active: number;
  description: string;
  target_member_type: string;
  audience_scope: string;
  daily_limit: number;
  created_at: string;
  updated_at: string;
};

export type ApiMembershipPlanDataType = {
  message: string;
  data: ApiMembershipPlanDetailDataType;
};
