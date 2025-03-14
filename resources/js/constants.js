export const AIHSTATUS = [
    { id: "A", label: "Active" },
    { id: "I", label: "Inactive" },
    { id: "H", label: "On Hold" }
];
export const AISTATUS = [
    { id: "A", label: "Active" },
    { id: "I", label: "Inactive" }
];
export const IECATEGORY = [
    { id: "internal", label: "Internal" },
    { id: "external", label: "External" }
];
export const USERTYPES = [
    { id: "admin", label: "Administrator" },
    { id: "standard", label: "Standard" },
    { id: "guest", label: "Guest" },
  ];
export const ORDERSTATUS = [
    { id: "O", label: "Open" },
    { id: "C", label: "Closed" },
    { id: "H", label: "Hold" },
    { id: "X", label: "Cancelled" },
];

export const ADDRESS = {
    streetAddress:{value:'',label:'Street address'},
    streetAddressL2:{value:'',label:'Street address line 2'},
    city:{value:'',label:'City/Town'},
    province:{value:'',label:'Province'},
    postalCode:{value:'',label:'Postal code'},
    poBox:{value:'',label:'PO Box'},
    country:{value:'',label:'Country'},
    contactPerson:{value:'',label:'Contact Person'},
    contactPhone:{value:'',label:'Phone'},
}

export const CUSTOMERCATEGORY = IECATEGORY
export const SUPPLIERCATEGORY = IECATEGORY
export const TRUCKERCATEGORY = IECATEGORY
export const CUSTOMERSTATUS = AIHSTATUS
export const SUPPLIERSTATUS = AIHSTATUS
export const TRUCKERSTATUS = AISTATUS
export const WAREHOUSESTATUS = AISTATUS
