export type PlanDataType = {
  code: string;
  planName: string;
  duration: string;
  price: string;
  dailyLimit: string;
  description: string;
};
export type MembershipPlanDataType = {
  addPlan: PlanDataType;
  editPlan: PlanDataType;
};
