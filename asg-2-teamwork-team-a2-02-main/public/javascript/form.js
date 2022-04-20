const BLANK = 0;
const MIN_PASS = 8;
// Email regex format taken from: https://stackoverflow.com/questions/46155/whats-the-best-way-to-validate-an-email-address-in-javascript
const EMAIL_FORMAT = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

// Eye Icon for password hider inspired from https://www.csestack.org/hide-show-password-eye-icon-html-javascript/

document.addEventListener("DOMContentLoaded", registerFormHandler)

function registerFormHandler() {
  let togglePassword = document.querySelector("#password-hider");
  let toggleConfirm = document.querySelector("#confirm-hider");
  let createButton = document.querySelector(".createAccountButton button");
  let userInput = document.querySelector(".userInput");

  togglePassword.addEventListener("click", passwordEyeHandler);
  toggleConfirm.addEventListener("click", confirmEyeHandler);
  createButton.addEventListener("click", validateHandler);
  userInput.addEventListener("input", inputHandler);

  function inputHandler(e) {
    let label = e.target.parentNode.querySelector("label");

    if (e.target.placeholder === "First Name" || e.target.placeholder === "Last Name" ||
      e.target.placeholder === "City" || e.target.placeholder === "Country") {        
      textInlineValidator();
    }
    else if (e.target.placeholder === "Email") {
      emailInlineValidator();
    }
    else if (e.target.placeholder === "Password") {
      passwordInlineValidator();
    }
    else if (e.target.placeholder === "Confirm Password") {
      confirmInlineValidator();
    }

    function passwordInlineValidator() {
      let confirmPass = document.querySelector("#confirm-input");

      if(e.target.value.length === BLANK) {
        label.textContent = `${e.target.placeholder} is required`;
        label.style = "color: red";
      }
      else if(e.target.value.length < MIN_PASS) {
        label.textContent = `Must be 8 characters long`;
        label.style = "color: red";
      }
      else {
        label.textContent = `Password is valid`;
        label.style = "color: green";
      }

      if(confirmPass.value.length != BLANK) {
        let confirmLabel = confirmPass.parentNode.querySelector("label");

        if (confirmPass.value != e.target.value) {
          confirmLabel.textContent = `Password mismatch`;
          confirmLabel.style = "color: red";
        }
        else {
          if (e.target.value.length < MIN_PASS) {
            confirmLabel.textContent = `Password is invalid`;
            confirmLabel.style = "color: red";
          }
          else {
            confirmLabel.textContent = `Password matches`;
            confirmLabel.style = "color: green";
          }
        }
      }
    }

    function confirmInlineValidator() {
      let passField = document.querySelector("#password-input");

      if(e.target.value.length === BLANK) {
        label.textContent = `Confirm is required`;
        label.style.color = "red";
      }
      else if(e.target.value != passField.value) {
        label.textContent = `Password mismatch`;
        label.style = "color: red";
      }
      else {
        if (passField.value.length < MIN_PASS) {
          label.textContent = `Password is invalid`;
          label.style = "color: red";
        }
        else {
          label.textContent = `Password matches`;
          label.style = "color: green";
        }
      }
    }

    function emailInlineValidator() {
      if(e.target.value.length === BLANK) {
        label.textContent = `${e.target.placeholder} is required`;
        label.style = "color: red";
      }
      else if(!e.target.value.match(EMAIL_FORMAT)) {
        label.textContent = `Must be a proper email`;
        label.style = "color: red";
      }
      else {
        label.textContent = `${e.target.placeholder} is valid`;
        label.style = "color: green";
      }
    }

    function textInlineValidator() {
      if(e.target.value.length == BLANK) {
        label.textContent = `${e.target.placeholder} is required`;
        label.style = "color: red";
      }
      else {
        label.textContent = `${e.target.placeholder} is valid`;
        label.style = "color: green";
      }
    }
  }

  function validateHandler() {
    let fields = document.querySelectorAll(".form__field");
    let emailField = document.querySelector("#email");
    let passField = document.querySelector("#password-input");
    let confirmPass = document.querySelector("#confirm-input");
    let errorFields = [];

    validateFields();
    validateEmailFormat();
    validatePassword();
    confirmPassword();

    if(errorFields.length === BLANK) {
      console.log("Account successfully created.");
    }

    function validateFields() {
      fields.forEach((field) => {
        let label = field.parentNode.querySelector("label");
  
        if(field.value === "") {
          errorFields.push(field);
          label.textContent = `${field.placeholder} is required`;
          label.style = "color: red";
        }
      });
    }

    function validateEmailFormat() {
      if(emailField.value != "") {
        if(!emailField.value.match(EMAIL_FORMAT)) {
          errorFields.push(emailField);
          let label = emailField.parentNode.querySelector("label");
          label.textContent = `Must be a proper email`;
          label.style = "color: red";
        }
      }
    }

    function validatePassword() {
      if(passField.value != "") {
        if(passField.value.length < MIN_PASS){
          errorFields.push(passField);
          let label = passField.parentNode.querySelector("label");
          label.textContent = `Must be 8 characters long`;
          label.style = "color: red";
        }
      }
    }

    function confirmPassword() {
      if(confirmPass.value != "") {
        if(confirmPass.value != passField.value){
          errorFields.push(confirmPass);
          let label = confirmPass.parentNode.querySelector("label");
          label.textContent = `Does not match password`;
          label.style = "color: red";
        }
      }
    }
  }

  function passwordEyeHandler() {
    if(togglePassword.classList.contains("fa-eye-slash") === false) {
      togglePassword.classList.add("fa-eye-slash");
    } else {
      togglePassword.classList.remove("fa-eye-slash");
    }

    let input = document.querySelector("#password-input");
    if (input.type === "password") {
      input.type = "text";
    } else {
      input.type = "password";
    }
  }

  function confirmEyeHandler() {
    if(toggleConfirm.classList.contains("fa-eye-slash") === false) {
      toggleConfirm.classList.add("fa-eye-slash");
    } else {
      toggleConfirm.classList.remove("fa-eye-slash");
    }

    let input = document.querySelector("#confirm-input");
    if (input.type === "password") {
      input.type = "text";
    } else {
      input.type = "password";
    }
  }
}
