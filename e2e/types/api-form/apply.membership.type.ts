export type MembershipFormDataType = {
  membership_plan: string;
  target_type: string;
  name: string;
  phone: string;
  email: string;
  gender: string;
  date_of_birth: string;
  address: string;
};

export type ApplyMembershipCasualFormDataType = {
  regular: MembershipFormDataType;
  student: MembershipFormDataType;
};
