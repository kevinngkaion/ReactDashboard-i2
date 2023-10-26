import { CreateGatepassFormType, CreateVisitorPassFormDataType, GatepassTypeType, GuestDataType, InputProps } from "@/types/models";
import InputGroup from "../../../inputGroup/InputGroup";
import styles from './CreateVisitorPassForm.module.css';
import { Dispatch, SetStateAction, useEffect, useState } from "react";
import ItemizedDetailsSection from "../../../section/ItemizedDetailsSection";
import Button from "@/components/general/button/Button";
import api from "@/utils/api";

const emptyFormData = {
    requestorName: '',
    unit: '',
    contactNumber: '',
    arrivalDate: '',
    arrivalTime: '',
    departureDate: '',
    departureTime: '',
    guests: []
}

export default function CreateGatepassForm({closeDropdown, handleInput, formData, setFormData, onSubmit}: {
    closeDropdown: any,
    handleInput: Function,
    formData: CreateVisitorPassFormDataType,
    setFormData: Dispatch<SetStateAction<CreateVisitorPassFormDataType>>,
    onSubmit: Function,
    }) {
    const [status, setStatus] = useState<string>('');



    const baseFields: InputProps[] = [
        {
            name: 'requestorName',
            label: 'Requestor Name',
            type: 'text',
            onChange: handleInput,
            value: formData.requestorName,
            disabled: true,
            required: true,
        },
        {
            name: 'unit',
            label: 'Unit #',
            type: 'text',
            onChange: handleInput,
            value: formData.unit,
            required: true,
            disabled: true,
        },
        {
            name: 'contactNumber',
            label: 'Contact Number',
            type: 'text',
            onChange: handleInput,
            value: formData.contactNumber,
            required: true,
        },
        {
            name: 'arrivalDate',
            label: 'Date of Arrival',
            type: 'date',
            onChange: handleInput,
            value: formData.arrivalDate,
            required: true,
        },
        {
            name: 'arrivalTime',
            label: 'Time of Arrival',
            type: 'time',
            onChange: handleInput,
            value: formData.arrivalTime,
            required: true,
        },
        {
            name: 'departureDate',
            label: 'Date of Departure',
            type: 'date',
            onChange: handleInput,
            value: formData.departureDate,
            required: true,
        },
        {
            name: 'departureTime',
            label: 'Time of Departure',
            type: 'time',
            onChange: handleInput,
            value: formData.departureTime,
            required: true,
        },
    ]
    
    const clearForm = () => {
        setFormData({...emptyFormData})
        setStatus('');
    }

    const handleCancel = (event: any) => {
        event.preventDefault();
        window.scrollTo(0,0);
        closeDropdown();
        clearForm();
    }

    // 
    const handleSubmit = async (event: any) => {
        event.preventDefault();
        setStatus('submitting');
        const response = await onSubmit(event);
        setFormData(emptyFormData);
        response?.success ? setStatus('success') : setStatus('error');
    }

    return status === 'submitting' ? 
        (
            <div>Submitting your request...</div>
        ) : status === 'success' ?
        (
            <div>
                <div>Your request has successfully been submitted</div>
                <button onClick={clearForm}>Submit another request</button>
            </div>
        ) : status === 'error' ?
        (
            <div>
                <div>There was an error submitting your request. Please try again</div>
                <button onClick={clearForm}>Try again</button>
            </div>
        ) :
        (
            <form action="" onSubmit={handleSubmit} className={styles.form} id="createGatepassForm">
                {baseFields.map((field, index) => (<InputGroup key={index} props={field}/>))}
                
                {/* Need to implement this part of it for creating visitor pass */}
                <ItemizedDetailsSection type='Item Details' formData={formData} setFormData={setFormData}/>

                <div className={styles.footer}>
                    <Button type='submit' onClick={null}/>
                    <Button type='cancel' onClick={handleCancel}/>
                </div>
            </form>
        )
}