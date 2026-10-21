type MemberPersonalDataType = {
  fullname: string;
  gender: string;
  dob: string;
  phone: string;
  email: string;
  address: string;
};
export type DuplicatePersonalDataType = {
  phoneAndEmailDuplicate: MemberPersonalDataType;
  phoneOnlyDuplicate: MemberPersonalDataType;
  emailOnlyDuplicate: MemberPersonalDataType;
};
