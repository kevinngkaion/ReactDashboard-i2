import { z } from "zod";
import { ApiResponse } from "./responseWrapper";

export type UserType = {
    token: string;
    tenantName: string;
    tenantId: number;
    email: string;
    mobile: number;
    db: string;
    tmp?: null | string;
    isAuthorized: boolean
}

export type UserTokenPayload = {
    token: string;
    tenant_name: string;
    tenant_id: number;
    email: string;
    mobile: number;
    db: string;
    tmp?: null | string;
    iat?: number;
    exp?: number;
    isAuthorized?: boolean;
}

export type LoginSchema = {
    email: string;
    password: string;
    accountcode: string;
}

interface ChangeHistory {
    createdBy: string,
    createdOn: string,
    deletedBy: string,
    deletedOn: string
    encId: string,
}

export interface SoaType extends ChangeHistory {
    id: string,
    residentId: string,
    monthOf: string,
    yearOf: string,
    balance: string,
    chargeAmount: string,
    electricity: string,
    water: string,
    cusa: string,
    assoDues: string,
    currentCharges: string,
    amountDue: string,
    notes: string | null,
    status: string,
    posted: string,
    dueDate: string,
    residentName: string,
    companyName: string,
    unitName: string | null,
    locationName: string | null,
    floorArea: string | null | number,
    locationType: string | null,
}

export interface SoaDetailsType extends ChangeHistory {
    id: string,
    soaId: string,
    paymentType: string,
    particular: string,
    amount: string,
    checkNo: number | string | null,
    checkDate: string | null,
    checkAmount: number | string | null,
    status: string,
    transactionDate: string,
}

export interface GatepassPersonnelType extends ChangeHistory {
    id: string,
    gatepassId: string,
    personnelName: string,
    companyName: string,
    personnelDescription: string | null,
    personnelNo: string,
}

export interface GatepassType extends ChangeHistory {
    id: string,
    gpType: string,
    gpPeronnelId: string | null,
    gpDate: string,
    gpTime: string,
    nameId: string,
    unitId: string,
    contactNo: string,
    approveId: string,
    status: string,
    dateUpload: string,
    firstName: string,
    fullName: string,
    unit: string,
    type: string,
    personnel: GatepassPersonnelType | GatepassPersonnelType[] | null,
}

export interface GuestType extends ChangeHistory {
    guestName: string,
    guestNo: string,
    guestPurpose: string,
    guestId: string,
}

export interface VisitorsPassType extends ChangeHistory {
    id: string,
    nameId: string,
    firstName: string,
    lastName: string,
    unitId: string,
    contactNo: string,
    departureDate: string,
    arrivalDate: string,
    arrivalTime: string,
    departureTime: string,
    dateUpload: string,
    approveId: string,
    status: string,
    locationName: string,
    guests: GuestType[] | GuestType | null,
}

export interface ServiceIssueType extends ChangeHistory {
    id: string,
    issueId: string,
    nameId: string,
    issueName: string,
    firstName: string,
    lastName: string,
    fullName: string,
    status: string,
    contactNo: string,
    unitId: string,
    locationName: string | null,
    description: string,
    statusId: string,
    attachments: string | null,
    dateUpload: string,
}

export interface WorkDetailType extends ChangeHistory {
    id: string,
    nameContractor: string,
    scopeWork: string,
    personCharge: string,
    contactNumber: string,
}

export interface WorkPermitType extends ChangeHistory {
    id: string,
    locationName: string,
    categoryName: string,
    firstName: string,
    lastName: string,
    status: string,
    fullName: string,
    unitId: string,
    contactNo: string,
    startDate: string,
    endDate: string,
    statusId: string,
    workpermitcategoryId: string,
    workDetailsId: string,
    dateUpload: string,
    nameId: string,
    workDetail: WorkDetailType | WorkDetailType[] | null,
}

export type ServiceRequestDataType = WorkPermitType | ServiceIssueType | GatepassType | VisitorsPassType | null;

export interface ServiceRequestType {
    id: string,
    dateUpload: string,
    type: string,
    table: string,
    data: ServiceRequestDataType,
}

export type ServiceRequestsType = ServiceRequestType[];

export interface MyRequestDataType {
    gatepasses?: GatepassType[] | string,
    personnel?: GatepassPersonnelType[] | string,
    serviceIssues?: ServiceIssueType[] | string,
    workPermits?: WorkPermitType[] | string,
    workDetails?: WorkDetailType[] | string,
    visitorPasses?: VisitorsPassType[] | string,
    guests?: GuestType[] | string,
  }

  export interface RequestType extends ChangeHistory {
    id: string,
    date_upload: string,
    type: string,
    table: string,
}
export interface ServiceRequestsThumbnailData {
    title: string,
    img: string,
    link: string,
}

export type TypeOfInput = 'text' | 'password' | 'email' | 'date' | 'select' | 'number' | 'time' | 'textArea'

export interface InputProps {
    name: string,
    label: string,
    type: TypeOfInput,
    onChange: any,
    value: any,
    required?: boolean,
    disabled?: boolean,
    options?: any[],
}

export interface PersonnelDetailsType {
    courier: string | null,
    courierName: string | null,
    courierContact: string | null,
}

export interface GatepassItemType {
    itemName: string | null,
    itemQuantity: number | null,
    itemDescription: string | null,
}

export interface CreateGatepassFormType {
    requestorName: string | null,
    gatepassType: string | null,
    forDate: string | null,
    time: string | null,
    unit: string | null,
    contactNumber: string | null,
    items: GatepassItemType[] | null,
    personnel: PersonnelDetailsType | null,
}

export interface GuestDataType {
    guestName: string | null,
    guestContact: string | null,
    guestPurpose: string | null,
}

export interface CreateVisitorPassFormDataType {
    requestorName: string | null,
    unit: string | null,
    contactNumber: string | null,
    arrivalDate: string | null,
    arrivalTime: string | null,
    departureDate: string | null,
    departureTime: string | null,
    guests: GuestDataType[],
}

export interface GatepassTypeType extends ChangeHistory{
    id: string,
    categoryName: string,
}

export interface GatepassDisplayType {
    gatepassId?: number,
    status?: string,
    date?: string,
    type?: string,
    personnel?: string,
    unit?: string,
}

export interface SaveGatePassDataType {
    personnelId?: number,
    itemIds?: number[],
    gatepass?: GatepassType
}

export interface SaveGatePassResponseType {
    success: boolean,
    data: SaveGatePassDataType, 
    errors?: any,
}
