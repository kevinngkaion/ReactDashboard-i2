import Soa from "@/components/pages/soa/Soa";
import { ParamGetSoaDetailsType, ParamGetSoaType, SoaDetailsType, SoaType } from "@/types/models";
import api from "@/utils/api";
import authorizeUser from "@/utils/authorizeUser";
import mapObject from "@/utils/mapObject";

export async function getServerSideProps(context: any) {
  // Need to update this function to address cases where the user is not authenticated therefor authorizeUser(jwt) returns null
  //Fetch all the soa objects.
  let response;
  const accountCode: string = process.env.TEST_ACCOUNT_CODE as string;
  const jwt = context.req.cookies.token;
  const user = authorizeUser(jwt);
  const token = `${user?.token}:tenant`;
  const soaParams: ParamGetSoaType = {
    accountcode: accountCode,
    userId: user?.tenantId as number,
  }

  response = await api.soa.getSoa(soaParams, token);  // get all soas
  const soas = mapObject(await response?.json());
  const currentSoa = soas.shift();
  const paidSoas = soas.filter((soa: SoaType) => 
    soa.status === "Paid" && soa.id != currentSoa.id
    );
  const unpaidSoas = soas.filter((soa: SoaType) =>
    (soa.status === "Unpaid" || soa.status === "Partially Paid") && soa.id != currentSoa.id
    );
  
  const soaDetailsParams: ParamGetSoaDetailsType = {
    accountcode: accountCode,
    soaId: currentSoa.id,
  }
  response = await api.soa.getSoaDetails(soaDetailsParams, token);  // get soa details (transactions for this soa)
  const soaDetails: SoaDetailsType[] = mapObject(await response?.json()) as SoaDetailsType[];

  return {
    props: {
      currentSoa,
      paidSoas,
      unpaidSoas,
      soaDetails,
    }
  }
}

export default Soa
