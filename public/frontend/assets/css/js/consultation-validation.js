document.addEventListener("DOMContentLoaded", function () {

    let form = document.getElementById("consultForm");

    if (!form) return;

    form.addEventListener("submit", function(e) {

        e.preventDefault(); // STOP default

        console.log("Submit triggered");

        let name = form.querySelector('input[name="name"]').value.trim();
        let email = form.querySelector('input[name="email"]').value.trim();
        let phone = form.querySelector('input[name="phone_no"]').value.trim();

        let namePattern = /^[A-Za-z\s]+$/;
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        let phonePattern = /^[0-9]{10,15}$/;

        document.querySelectorAll(".error-msg").forEach(el => el.remove());

        let isValid = true;

        if (name === "") {
            showError(form, 'input[name="name"]', "Name is required");
            isValid = false;
        } else if (!namePattern.test(name)) {
            showError(form, 'input[name="name"]', "Name should not contain numbers");
            isValid = false;
        }
        
        if (email === "") {
            showError(form, 'input[name="email"]', "Email is required");
            isValid = false;
        } else if (!emailPattern.test(email)) {
            showError(form, 'input[name="email"]', "Enter valid email");
            isValid = false;
        }
        
        if (phone === "") {
            showError(form, 'input[name="phone_no"]', "Phone number is required");
            isValid = false;
        } else if (!phonePattern.test(phone)) {
            showError(form, 'input[name="phone_no"]', "Phone must be 10 to 15 digits only");
            isValid = false;
        }
        
        
        // ✅ CAPTCHA validation
        let captchaResponse = grecaptcha.getResponse();
        
        if (captchaResponse.length === 0) {

            let error = document.createElement("span");
            error.className = "error-msg";
            error.style.color = "#b30000"; 
            error.style.fontSize = "14px";
            error.style.display = "block";       // 🔥 important
            error.style.textAlign = "center";    // 🔥 center text
            error.style.marginTop = "5px";       // spacing
            error.innerText = "Please verify captcha";
        
            let captchaDiv = form.querySelector('.g-recaptcha');
            let captchaContainer = captchaDiv.closest('.form-group');
        
            captchaContainer.appendChild(error);
        
            isValid = false;
        }
        
        if (isValid) {
            form.submit();
        }

    });

});

// ✅ KEEP THIS OUTSIDE (GLOBAL FUNCTION)
function showError(form, selector, message) {
    let input = form.querySelector(selector);

    if (!input) return;

    let error = document.createElement("span");
    error.className = "error-msg";
    error.style.color = "#b30000"; 
    error.style.fontSize = "14px";
    error.innerText = message;

    input.parentNode.appendChild(error);
}