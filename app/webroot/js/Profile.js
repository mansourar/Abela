function SaveProfileAction() {
    try {
        var FirstName = document.getElementById('FirstName');
        var MiddleName = document.getElementById('MiddleName');
        var LastName = document.getElementById('LastName');
        var Address = document.getElementById('Address');
        var Phone = document.getElementById('Phone');
        var Mobile = document.getElementById('Mobile');
        var Fax = document.getElementById('Fax');
        var Email = document.getElementById('UserEmail');
        var PhoneRegex = new RegExp("^[0-9]{8}$");
        if (FirstName.value.trim() === "") {
            FirstName.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'First Name Input Is Required !',
                type: 'error',
                delay: 1500
            });
            return false;
        }
        if (MiddleName.value.trim() === "") {
            MiddleName.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Middle Name Input Is Required !',
                type: 'error',
                delay: 1500
            });
            return false;
        }
        if (LastName.value.trim() === "") {
            LastName.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Last Name Input Is Required !',
                type: 'error',
                delay: 1500
            });
            return false;
        }
        if (Address.value.trim() === "") {
            Address.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Address Input Is Required !',
                type: 'error',
                delay: 1500
            });
            return false;
        }
        if (Mobile.value.trim() === "") {
            Mobile.focus();
            new PNotify({
                title: 'Invalid Input. ',
                text: 'Mobile Input Is Required !',
                type: 'error',
                delay: 1500
            });
            return false;
        }

        if (!PhoneRegex.test(Mobile.value)) {
            Mobile.focus();
            Mobile.value = "";
            new PNotify({
                title: 'Invalid Mobile Number. ',
                text: 'Only 8 Digits Are Allowed !',
                type: 'error',
                delay: 1500
            });
            return false;
        }
        if (Phone.value.trim() !== "") {
            if (!PhoneRegex.test(Phone.value)) {
                Phone.focus();
                Phone.value = "";
                new PNotify({
                    title: 'Invalid Phone Number. ',
                    text: 'Only 8 Digits Are Allowed !',
                    type: 'error',
                    delay: 1500
                });
                return false;
            }
        }
        if (Fax.value.trim() !== "") {
            if (!PhoneRegex.test(Fax.value)) {
                Fax.focus();
                Fax.value = "";
                new PNotify({
                    title: 'Invalid Fax Number. ',
                    text: 'Only 8 Digits Are Allowed !',
                    type: 'error',
                    delay: 1500
                });
                return false;
            }
        }
        if (Email.value.trim() !== "") {
            var EmailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (!EmailRegex.test(Email.value)) {
                Email.focus();
                Email.value = "";
                new PNotify({
                    title: 'Invalid Email Address. ',
                    text: 'Please Enter A Valid Email Address!',
                    type: 'error',
                    delay: 1500
                });
                return false;
            }
        }

        $("#ProfileForm").submit();
    }
    catch (e) {
        console.log("Profile.js - SaveProfileAction" + e);
    }
}