import { ParamGetServiceRequestType } from "@/types/apiRequestParams";

const userToken: string = "c8c69a475a9715c2f2c6194bc1974fae:tenant"

/** 
* Fetches all the user's Gate Passes
* @param {ParamGetServiceRequestDetailsType} params - This is a json object that has accountCode, queryCondition, and resultLimit
* @return {Promise<Response>} Returns a promise of a Response object.
*/
export async function getServiceRequestDetails(params: ParamGetServiceRequestType, table: string, token: string = "c8c69a475a9715c2f2c6194bc1974fae:tenant"): Promise<Response>{
    const url: string = `${process.env.API_URL}/tenant/get-list-sr`;
    const method: string = 'POST';
    const body: string = JSON.stringify({
        accountcode: params.accountcode,
        table: table === 'personnel' ? 'gatepass_personnel' : `vw_${table}`,
        condition: table === 'personnel' ? null : `name_id=${params.userId}`,
        limit: params.limit,
    });
    const headers = {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
    };
    try {
        const response: Response = await fetch(url, {
            method: method,
            headers: headers,
            body: body,
            referrerPolicy: "unsafe-url"
        });
        if (!response.ok) {
            //Need to fix this error handling here so that I can pass the error message to the screen instead of just here
            throw new Error(`HTTP error! Status: ${response.status}, Response: ${JSON.stringify(await response.json())}`);
        }
        return response;
        
    } catch (error: any) {
        return error.message ? error.message : "Something went wrong";
    }
}