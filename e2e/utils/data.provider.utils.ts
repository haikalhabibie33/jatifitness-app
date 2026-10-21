import { LoginDataType, LoginInputType } from '#types/login.type';
import { PersonalDataType } from '#types/personal.type';
import { RequestSubmittedPageDataType } from '#types/page/request-submitted.type';
import { AccessQrPageDataType } from '#types/page/access-qr.type';
import { DuplicatePersonalDataType } from '#types/duplicate.personal.type.js';
import {
  ApplyMembershipCasualFormDataType,
  MembershipFormDataType,
} from '#types/api-form/apply.membership.type.js';
import { CasualPackageDataType, MembershipPackageDataType } from '#types/page/member.type.js';
import { CasualPersonalDataType } from '#types/personal.type.js';
import { MembershipPlanDataType } from '#types/membership-plan.data.type.js';
import { PlanDataType } from '#types/membership-plan.data.type.js';

import loginData from '#data/login.json';
import personalDetailsData from '#data/personal-details.json';
import editedPersonalDetailsData from '#data/edit-personal-details.json';
import requestSubmittedPageData from '#data/page/request-submitted-data.json';
import accessQrPageData from '#data/page/access-qr-data.json';
import duplicatePersonalData from '#data/duplicate-personal-details.json';
import applyMembershipFormDataDetails from '#data/api-form/apply-membership.json';
import membershipPlanData from '#data/membership-plan.json';

import {
  PERSONAL_CATEGORY,
  MEMBER_STUDENT_PACKAGE,
  MEMBER_STUDENT_PRICE,
  DURATIONS,
  MEMBER_REGULAR_PACKAGE,
  MEMBER_REGULAR_PRICE,
  CASUAL_PACKAGE,
  CASUAL_PRICE,
} from '#utils/enum.utils';
import {
  SummaryModalApplicantInformation,
  SummaryModalPackageDetailsType,
  SummaryModalPriceSummaryType,
} from '#types/summary.modal.type.js';

export default class DataProvider {
  public static getLoginData(): LoginDataType {
    return loginData as LoginDataType;
  }

  public static loginValidUser(): LoginInputType {
    return this.getLoginData().validUser;
  }

  public static getPersonalDetails(): PersonalDataType {
    return personalDetailsData as PersonalDataType;
  }

  public static getEditedPersonalDetails(): PersonalDataType {
    return editedPersonalDetailsData as PersonalDataType;
  }

  public static getDuplicatePhoneAndEmailData(): PersonalDataType {
    const data: DuplicatePersonalDataType = duplicatePersonalData;
    return data.phoneAndEmailDuplicate as PersonalDataType;
  }

  public static getDuplicatePhoneOnlyData(): PersonalDataType {
    const data: DuplicatePersonalDataType = duplicatePersonalData;
    return data.phoneOnlyDuplicate as PersonalDataType;
  }

  public static getDuplicateEmailOnlyData(): PersonalDataType {
    const data: DuplicatePersonalDataType = duplicatePersonalData;
    return data.emailOnlyDuplicate as PersonalDataType;
  }

  public static getRequestSubmittedPageData(): RequestSubmittedPageDataType {
    return requestSubmittedPageData as RequestSubmittedPageDataType;
  }

  public static getAccessQrPageData(): AccessQrPageDataType {
    return accessQrPageData as AccessQrPageDataType;
  }

  public static getApplyRegularMembershipFormData(): MembershipFormDataType {
    const data: ApplyMembershipCasualFormDataType = applyMembershipFormDataDetails;
    return data.regular as MembershipFormDataType;
  }

  public static getApplyStudentMembershipFormData(): MembershipFormDataType {
    const data: ApplyMembershipCasualFormDataType = applyMembershipFormDataDetails;
    return data.student as MembershipFormDataType;
  }

  public static studentMemberMonthly(): MembershipPackageDataType {
    return {
      personal_category: PERSONAL_CATEGORY.STUDENT,
      membership_package: MEMBER_STUDENT_PACKAGE.MONTHLY,
      durations: DURATIONS.MONTHLY,
      price: MEMBER_STUDENT_PRICE.MONTHLY,
    } as MembershipPackageDataType;
  }

  public static regularMemberMonthly(): MembershipPackageDataType {
    return {
      personal_category: PERSONAL_CATEGORY.REGULAR,
      membership_package: MEMBER_REGULAR_PACKAGE.MONTHLY,
      durations: DURATIONS.MONTHLY,
      price: MEMBER_REGULAR_PRICE.MONTHLY,
    } as MembershipPackageDataType;
  }

  public static studentCasualPackage(
    quantity: number = 1,
    duration: number = 1,
  ): CasualPackageDataType {
    const price: number = Number(quantity) * Number(CASUAL_PRICE.STUDENT);
    return {
      personal_category: PERSONAL_CATEGORY.STUDENT,
      casual_package: CASUAL_PACKAGE.STUDENT,
      quantity: String(quantity),
      durations: String(duration),
      price: String(price),
    };
  }

  public static regularCasualPackage(
    quantity: number = 1,
    duration: number = 1,
  ): CasualPackageDataType {
    const price: number = Number(quantity) * Number(CASUAL_PRICE.REGULAR);

    return {
      personal_category: PERSONAL_CATEGORY.REGULAR,
      casual_package: CASUAL_PACKAGE.REGULAR,
      quantity: String(quantity),
      durations: String(duration),
      price: String(price),
    };
  }

  public static casualPersonalDetailsData(): CasualPersonalDataType {
    const data = DataProvider.getPersonalDetails();
    return {
      fullname: data.fullname,
      phone: data.phone,
      email: data.email,
    } as CasualPersonalDataType;
  }

  public static summaryMemberRegularPackageDetails(): SummaryModalPackageDetailsType {
    return {
      category: PERSONAL_CATEGORY.REGULAR,
      package: MEMBER_REGULAR_PACKAGE.MONTHLY,
      quantity: '1',
      totalDuration: DURATIONS.MONTHLY,
    } as SummaryModalPackageDetailsType;
  }

  public static summaryCasualRegularPackageDetails(
    quantity: number = 1,
  ): SummaryModalPackageDetailsType {
    return {
      category: PERSONAL_CATEGORY.REGULAR,
      package: CASUAL_PACKAGE.REGULAR,
      quantity: String(quantity),
      totalDuration: String(quantity),
    };
  }

  public static summaryMemberRegularPriceSummary(): SummaryModalPriceSummaryType {
    const price: string = MEMBER_REGULAR_PRICE.MONTHLY;
    const formattedPrice = Number(price)
      .toLocaleString('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      .replace(',', '.');

    return {
      unitPrice: formattedPrice,
      total: formattedPrice,
    } as SummaryModalPriceSummaryType;
  }

  public static summaryCasualRegularPriceSummary(
    quantity: number = 1,
  ): SummaryModalPriceSummaryType {
    const price: string = CASUAL_PRICE.REGULAR;

    const unitPrice = Number(price)
      .toLocaleString('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      .replace(',', '.');

    const totalPrice = Number(price) * quantity;
    const fixPrice = totalPrice
      .toLocaleString('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      .replace(',', '.');

    return {
      unitPrice: unitPrice,
      total: fixPrice,
    };
  }

  public static summaryApplicantInformation(): SummaryModalApplicantInformation {
    const data = DataProvider.getPersonalDetails();
    return {
      name: data.fullname,
      email: data.email,
      phone: data.phone,
    } as SummaryModalApplicantInformation;
  }

  public static summaryMemberStudentPackageDetails(): SummaryModalPackageDetailsType {
    return {
      category: PERSONAL_CATEGORY.STUDENT,
      package: MEMBER_STUDENT_PACKAGE.MONTHLY,
      quantity: '1',
      totalDuration: DURATIONS.MONTHLY,
    } as SummaryModalPackageDetailsType;
  }

  public static summaryCasualStudentPackageDetails(
    quantity: number = 1,
  ): SummaryModalPackageDetailsType {
    return {
      category: PERSONAL_CATEGORY.STUDENT,
      package: CASUAL_PACKAGE.STUDENT,
      quantity: String(quantity),
      totalDuration: String(quantity),
    };
  }

  public static summaryMemberStudentPriceSummary(): SummaryModalPriceSummaryType {
    const price: string = MEMBER_STUDENT_PRICE.MONTHLY;
    const formattedPrice = Number(price)
      .toLocaleString('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      .replace(',', '.');

    return {
      unitPrice: formattedPrice,
      total: formattedPrice,
    } as SummaryModalPriceSummaryType;
  }

  public static summaryCasualStudentPriceSummary(
    quantity: number = 1,
  ): SummaryModalPriceSummaryType {
    const price: string = CASUAL_PRICE.STUDENT;

    const unitPrice = Number(price)
      .toLocaleString('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      .replace(',', '.');

    const totalPrice = Number(price) * quantity;
    const fixPrice = totalPrice
      .toLocaleString('en-US', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })
      .replace(',', '.');

    return {
      unitPrice: unitPrice,
      total: fixPrice,
    };
  }

  public static getAddMembershipPlanData(): PlanDataType {
    const data: MembershipPlanDataType = membershipPlanData;
    return data.addPlan as PlanDataType;
  }

  public static getEditMembershipPlanData(): PlanDataType {
    const data: MembershipPlanDataType = membershipPlanData;
    return data.editPlan as PlanDataType;
  }
}
