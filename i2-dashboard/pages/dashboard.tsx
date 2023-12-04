import Layout from "@/components/layouts/layout";
import { SoaPaymentsType, SoaType, SystemInfoType, NewsAnnouncementsType, ServiceRequestType } from "@/types/models";
import { ParamGetSoaType, ParamGetSoaDetailsType,ParamGetSystemInfoType,ParamGetServiceRequestsParamsType } from "@/types/apiRequestParams";
import authorizeUser from "@/utils/authorizeUser";
import Section from "@/components/general/section/Section";
import api from "@/utils/api";
import Dashboard from "@/components/pages/dashboard/Dashboard";
import encryptData from "@/utils/encryptData";

export async function getServerSideProps(context: any) {
  const accountCode: string = process.env.TEST_ACCOUNT_CODE as string;
  const jwt = context.req.cookies.token;
  const user = authorizeUser(jwt);
  if (!user) {
    return {redirect : {destination: '/?error=accessDenied', permanent: false}};
  }
  const token = `${user?.token}:tenant`;
  const soaParams: ParamGetSoaType = {
    accountcode: accountCode,
    userId: user?.id,
    limit: 1,
  }
  const systeminfoParams: ParamGetSystemInfoType = {
    accountcode: accountCode
  }

  const serviceRequestsParams: ParamGetServiceRequestsParamsType = {
    accountcode: accountCode,
    userId: 1,
  }
  
  const getSoaResponse = await api.soa.getSoa(soaParams, token);
  const soas = getSoaResponse.success ? getSoaResponse.data as SoaType[]: undefined;
  const currentSoa = soas?.shift() as SoaType;
  const soaDetailsParams: ParamGetSoaDetailsType = {
    accountcode: accountCode,
    soaId: parseInt(currentSoa.id),
  }

  const getNewsAnnouncementsResponse = await api.newsAnnouncements.getNewsAnnouncements(soaDetailsParams, token);
  const newsAnnouncements = getNewsAnnouncementsResponse.success ? getNewsAnnouncementsResponse.data as NewsAnnouncementsType[] : undefined;

  const getSystemInfoResponse = await api.systeminfo.getSysteminfo(systeminfoParams,token)
  const systemInfo = getSystemInfoResponse.success ? getSystemInfoResponse.data as SystemInfoType : undefined;

  const getServiceRequestResponse = await api.requests.getServiceRequests(serviceRequestsParams, token, context);
  const serviceRequests = getServiceRequestResponse.success ? getServiceRequestResponse.data as ServiceRequestType[] : null;

  console.log(serviceRequests)

  const getSoaPaymentsResponse = await api.soa.getSoaPayments(soaDetailsParams, token);  // get soa details (transactions for this soa)
  const soaDetails = getSoaPaymentsResponse.success ? getSoaPaymentsResponse.data as SoaPaymentsType[] : null;
  return {props: {authorizedUser: user, currentSoa, soaDetails, systemInfo, newsAnnouncements, serviceRequests}};
}

export default Dashboard