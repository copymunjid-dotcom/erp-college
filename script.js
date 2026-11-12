
document.addEventListener("DOMContentLoaded", function () {

    // Get the form
    const form = document.querySelector("form");

    // Run when the form is submitted
    form.addEventListener("submit", function (event) {

        // Get all values
        let studentName = document.getElementsByName("student_name")[0].value.trim();

        let registerNumber = document.getElementsByName("register_number")[0].value.trim();

        let email = document.getElementsByName("email")[0].value.trim();

        let phone = document.getElementsByName("phone")[0].value.trim();

        let password = document.getElementsByName("password")[0].value;

        let confirmPassword = document.getElementsByName("confirm_password")[0].value;

        // Student Name Validation
        if(studentName === ""){

            alert("Please enter your name.");

            event.preventDefault();

            return;

        }

        // Register Number Validation
        if(registerNumber === ""){

            alert("Please enter your register number.");

            event.preventDefault();

            return;

        }

        // Phone Validation
        if(phone.length !== 10){

            alert("Phone number must contain 10 digits.");

            event.preventDefault();

            return;

        }

        // Password Validation
        if(password.length < 6){

            alert("Password must be at least 6 characters.");

            event.preventDefault();

            return;

        }

        // Confirm Password
        if(password !== confirmPassword){

            alert("Passwords do not match.");

            event.preventDefault();

            return;

        }

        // Success
        alert("Validation Successful.");

    });

});
// Password

const togglePassword = document.getElementById("togglePassword");
const password = document.getElementById("password");

if (togglePassword && password) {

    togglePassword.addEventListener("click", function(){

        if(password.type === "password"){

            password.type = "text";
            this.classList.replace("fa-eye","fa-eye-slash");

        }else{

            password.type = "password";
            this.classList.replace("fa-eye-slash","fa-eye");

        }

    });

}

// Confirm Password

const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
const confirmPassword = document.getElementById("confirm_password");

if (toggleConfirmPassword && confirmPassword) {

    toggleConfirmPassword.addEventListener("click", function(){

        if(confirmPassword.type === "password"){

            confirmPassword.type = "text";
            this.classList.replace("fa-eye","fa-eye-slash");

        }else{

            confirmPassword.type = "password";
            this.classList.replace("fa-eye-slash","fa-eye");

        }

    });

}
// ==========================
// SAME AS PERMANENT ADDRESS
// ==========================

// ==========================
// SAME AS PERMANENT ADDRESS
// ==========================

const sameAddress = document.getElementById("sameAddress");
const permanentAddress = document.getElementById("address");
const temporaryAddress = document.getElementById("temporary_address");

if (sameAddress && permanentAddress && temporaryAddress) {

    sameAddress.addEventListener("change", function(){

        if(this.checked){

            temporaryAddress.value = permanentAddress.value;
            temporaryAddress.readOnly = true;

        }else{

            temporaryAddress.readOnly = false;
            temporaryAddress.value = "";

        }

    });

    permanentAddress.addEventListener("input", function(){

        if(sameAddress.checked){

            temporaryAddress.value = this.value;

        }

    });

}
// ==========================
// SIBLING DETAILS
// ==========================

const siblingsYes = document.getElementById("siblings_yes");
const siblingsNo = document.getElementById("siblings_no");
const siblingsContainer = document.getElementById("siblings_container");

if (siblingsYes && siblingsNo && siblingsContainer) {

    siblingsYes.addEventListener("change", function () {

        siblingsContainer.style.display = "block";

        if (document.querySelectorAll("#siblingsBody tr:not(#siblingTemplate)").length === 0) {

            addSiblingRow();

        }

    });

    siblingsNo.addEventListener("change", function () {

        siblingsContainer.style.display = "none";

        document.querySelectorAll("#siblingsBody tr:not(#siblingTemplate)").forEach(function(row){

            row.remove();

        });

    });

}

function addSiblingRow() {

    let template = document.getElementById("siblingTemplate");

    let newRow = template.cloneNode(true);

    newRow.removeAttribute("id");

    newRow.style.display = "";

    document.getElementById("siblingsBody").appendChild(newRow);

}

function removeSiblingRow(button) {

    button.closest("tr").remove();

}