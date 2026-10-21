export type RequestsType = {
  id: string;
  corrected_from_request_id: null;
  access_token: string;
  request_type: string;
  target_member_type: string;
  membership_plan_id: string;
  plan_name_snapshot: string;
  plan_code_snapshot: string;
  price_snapshot: string;
  total_price_snapshot: string;
  daily_limit_snapshot: string;
  duration_snapshot: string;
  quantity: string;
  payment_source: string;
  payment_reference: string;
  paid_at: string;
  name: string;
  gender: string;
  date_of_birth: string;
  phone: string;
  email: string;
  address: string;
  status: string;
  approval_required: string;
  created_at: string;
  updated_at: string;
};

export type ApiMembershipRequestType = {
  message: string;
  requests: RequestsType[];
};
