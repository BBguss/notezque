document.addEventListener('DOMContentLoaded', function () {
    const emailOption = document.getElementById('emailOption');
    const smsOption = document.getElementById('smsOption');
    const emailInput = document.getElementById('emailInput');
    const smsInput = document.getElementById('smsInput');

    emailOption.addEventListener('change', function () {
        if (emailOption.checked) {
            emailInput.style.display = 'block';
            smsInput.style.display = 'none';
        }
    });

    smsOption.addEventListener('change', function () {
        if (smsOption.checked) {
            smsInput.style.display = 'block';
            emailInput.style.display = 'none';
        }
    });
});


function email() {
    const email = document.getElementById("email").value;
    if (email) {
        document.getElementById("emeilInput").addEventListener("click", myFunction);
        function myFunction() {
            document.getElementById("emeilInput").innerHTML = `
            <h2>Email Terkirim</h2>
            <p>An email with instructions on how to reset your password has been sent to ${email}. Check your spam or junk folder if you don't see the email in your inbox.</p>
            `;
        }
    } else {
        alert("Email tidak boleh kosong");
    }
};

function sms() {
    const sms = document.getElementById("sms").value;
    if (sms) {
        document.getElementById("emeilInput").addEventListener("click", myFunction);
        function myFunction() {
            document.getElementById("emeilInput").innerHTML = `
            <h2>sms Terkirim</h2>
            <p>An sms with instructions on how to reset your password has been sent to ${sms}. Check your spam or junk folder if you don't see the sms in your inbox.</p>
            `;
        }
    } else {
        alert("sms tidak boleh kosong");
    }
};