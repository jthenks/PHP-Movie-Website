// Eye Icon for password hider inspired from https://www.csestack.org/hide-show-password-eye-icon-html-javascript/

document.addEventListener("DOMContentLoaded", registerPasswordHandler)

function registerPasswordHandler() {
  let togglePassword = document.querySelector("#password-hider");

  togglePassword.addEventListener("click", passwordEyeHandler);
  
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
}
