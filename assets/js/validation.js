document.addEventListener("DOMContentLoaded", function () {
  function showError(input, message) {
    clearError(input);
    let error = document.createElement("div");
    error.className = "fieldError";
    error.textContent = message;
    input.insertAdjacentElement("afterend", error);
    input.classList.add("invalidField");
  }

  function clearError(input) {
    input.classList.remove("invalidField");
    let next = input.nextElementSibling;
    if (next && next.classList.contains("fieldError")) {
      next.remove();
    }
  }

  function isValidEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  function validateRegisterForm(form) {
    let valid = true;
    let name = form.querySelector("#name");
    let email = form.querySelector("#email");
    let password = form.querySelector("#password");
    let confirmPassword = form.querySelector("#confirm_password");

    if (name.value.trim() === "") {
      showError(name, "Name is required.");
      valid = false;
    } else {
      clearError(name);
    }

    if (!isValidEmail(email.value.trim())) {
      showError(email, "Enter a valid email address.");
      valid = false;
    } else {
      clearError(email);
    }

    if (password.value.length < 8) {
      showError(password, "Password must be at least 8 characters.");
      valid = false;
    } else {
      clearError(password);
    }

    if (confirmPassword.value !== password.value) {
      showError(confirmPassword, "Passwords do not match.");
      valid = false;
    } else {
      clearError(confirmPassword);
    }

    return valid;
  }

  function validateLoginForm(form) {
    let valid = true;
    let email = form.querySelector("#email");
    let password = form.querySelector("#password");

    if (!isValidEmail(email.value.trim())) {
      showError(email, "Enter a valid email address.");
      valid = false;
    } else {
      clearError(email);
    }

    if (password.value.trim() === "") {
      showError(password, "Password is required.");
      valid = false;
    } else {
      clearError(password);
    }

    return valid;
  }

  function validateContactForm(form) {
    let valid = true;
    let name = form.querySelector("#name");
    let email = form.querySelector("#email");
    let message = form.querySelector("#message");

    if (name.value.trim() === "") {
      showError(name, "Name is required.");
      valid = false;
    } else {
      clearError(name);
    }

    if (!isValidEmail(email.value.trim())) {
      showError(email, "Enter a valid email address.");
      valid = false;
    } else {
      clearError(email);
    }

    if (message.value.trim() === "") {
      showError(message, "Message is required.");
      valid = false;
    } else {
      clearError(message);
    }

    return valid;
  }

  function validateProductForm(form) {
    let valid = true;
    let name = form.querySelector("#name");
    let price = form.querySelector("#price");
    let stock = form.querySelector("#stock");

    if (name.value.trim() === "") {
      showError(name, "Name is required.");
      valid = false;
    } else {
      clearError(name);
    }

    if (price.value.trim() === "" || isNaN(price.value) || Number(price.value) < 0) {
      showError(price, "Price must be a positive number.");
      valid = false;
    } else {
      clearError(price);
    }

    if (stock.value.trim() === "" || !/^\d+$/.test(stock.value.trim())) {
      showError(stock, "Stock must be a whole number.");
      valid = false;
    } else {
      clearError(stock);
    }

    return valid;
  }

  let validators = {
    registerForm: validateRegisterForm,
    loginForm: validateLoginForm,
    contactForm: validateContactForm,
    productForm: validateProductForm,
  };

  Object.keys(validators).forEach(function (formId) {
    let form = document.getElementById(formId);
    if (!form) {
      return;
    }

    form.addEventListener("submit", function (event) {
      let isValid = validators[formId](form);
      if (!isValid) {
        event.preventDefault();
      }
    });
  });
});
