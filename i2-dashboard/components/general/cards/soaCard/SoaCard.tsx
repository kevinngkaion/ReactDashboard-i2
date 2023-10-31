import styles from './SoaCard.module.css'
import Link from 'next/link'
import getDateString from '@/utils/getDateString';
import { SoaDetailsType, SoaType } from '@/types/models';
import formatCurrency from '@/utils/formatCurrency';
const SoaCard = ({ currentSoa, currentSoaDetails }: { currentSoa: SoaType, currentSoaDetails: SoaDetailsType[] }) => {
    const status = currentSoa.status;
    const statementDate = getDateString(null, parseInt(currentSoa.monthOf), parseInt(currentSoa.yearOf));
    const dueDate = getDateString(currentSoa?.dueDate);
    const statementAmount = parseFloat(currentSoa.amountDue);
    const credits = currentSoaDetails
        .filter((detail) => detail.particular.includes('SOA Payment') && detail.status !== 'Invalid')
        .map((detail) => detail.amount);
    const totalCredits = credits.reduce((total, amount) => total + parseFloat(amount), 0);
    const amountDue = formatCurrency(statementAmount - totalCredits);
    const statusClass = status === 'Paid' ? `${styles.status} ${styles.paid}` : `${styles.status} ${styles.unpaid}`;

console.log(currentSoa)
    return (
        <div className={styles.container}>
            <div className={styles.desciption}>
                <div>
                    <p className={statusClass}>{status}</p>
                    <p className={styles.date}>{statementDate}</p>
                    <p className={styles.date}><b>Due Date: </b>{dueDate}</p>
                </div>
                <div>
                    <p className={styles.amountDue}>Total Amount Due</p>
                    <p className={styles.amount}>{amountDue}</p>
                </div>
            </div>
            <Link className={styles.viewDetails} href={'/'}>
                <p>View Details</p>
            </Link>
            <div className={styles.btnContainer}>
                <button className={styles.btnPdf}>SOA PDF</button>
                <button className={styles.payNow}>PAY NOW</button>
            </div>
        </div>
    )
}

export default SoaCard