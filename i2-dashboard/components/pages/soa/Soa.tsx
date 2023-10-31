import Layout from "@/components/layouts/layout"
import style from "./Soa.module.css"
import { SoaDetailsType, SoaType } from "@/types/models"
import Section from "@/components/general/section/Section"

const Soa = ({currentSoa, paymentTransactions} : {
    currentSoa: SoaType,
    paymentTransactions: SoaDetailsType[]
}) => {
    const soaDetails = paymentTransactions.filter((transaction: SoaDetailsType) => {
        return transaction.soaId === currentSoa.id;
    })
    const soaProps = {
        title: 'SOA',
        headerAction: null,
        data: {currentSoa, soaDetails},
    }
    const paymentTransactionsProps = {
        title: 'Payment Transactions',
        headerAction: 'Show All',
        data: paymentTransactions,
    }
    return (
        <Layout title="i2 - SOA">
            <Section props={soaProps}/>
            <Section props={paymentTransactionsProps}/>
        </Layout>
    )
}

export default Soa