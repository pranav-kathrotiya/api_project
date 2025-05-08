jQuery('#frm').validate({
    rules: {
        clinicName: "required",
        doctorName: "required",
        startTime: "required",
        endTime: "required",
        contact: {
            required: true,
            minlength: 10
        },
        emergencyContact: {
            required: true,
            minlength: 10
        },
        address1: "required",
        country: "required",
        state: "required",
        city: "required",
        pinCode: "required",
        // logo:{
        //     extension:"jpg|jpeg|png|gif|svg",
        // },
        email: {
            required: true,
            email: true
        },
        password: {
            required: true,
            minlength: 5
        },
    },
    messages: {
        clinicName: "Please Enter Clinic Name",
        doctorName: "Please Enter Doctor Name",
        startTime: "Please Select Start Time",
        endTime: "Please Select End Time",
        contact: {
            required: "Please Enter Contact No",
            minlength: "Please Enter 10 Digits",
        },
        emergencyContact: {
            required: "Please Enter Emergency Contact No",
            minlength: "Please Enter 10 Digits",
        },
        address1: "Please Enter Address",
        country: "Please Select Country",
        state: "Please Select State",
        city: "Please Select City",
        pinCode: "Please Enter Pin Code / Zip Code",
        // logo:{
        //    extension: "Please Upload File In These Format Only (jpg, jpeg, png, svg, gif).",
        // },
        email: {
            required: "Please Enter Email",
            email: "Please Enter Valid Email",
        },
        password: {
            required: "Please enter your password",
            minlength: "Password must be 5 char long"
        },
    },
    submitHandler: function (form) {
        form.submit();
    }
});
