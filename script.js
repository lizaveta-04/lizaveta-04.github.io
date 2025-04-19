document.addEventListener("DOMContentLoaded", function() {
  const form = document.getElementById("contactForm");
  let formSubmitted = false;
  
  // Инициализация Bootstrap Modal
  const formModal = new bootstrap.Modal(document.getElementById('formModal'));
  
  // Получаем все поля формы
  const nameInput = document.getElementById("name");
  const emailInput = document.getElementById("email");
  const phoneInput = document.getElementById("phone");
  const messageInput = document.getElementById("message");

  // Добавляем обработчики событий
  [nameInput, emailInput, phoneInput, messageInput].forEach(input => {
    if (input) {
      input.addEventListener("blur", function() {
        if (formSubmitted || this.value.trim() !== "") {
          validateField(this);
        }
      });
    }
  });

  if (emailInput) {
    emailInput.addEventListener("input", validateEmailField);
  }

  if (phoneInput) {
    phoneInput.addEventListener("input", validatePhoneField);
  }

  if (form) {
    form.addEventListener("submit", function(e) {
      e.preventDefault();
      formSubmitted = true;
      
      // Валидация всех полей
      const isNameValid = validateField(nameInput);
      const isEmailValid = validateEmailField();
      const isPhoneValid = validatePhoneField();
      const isMessageValid = validateField(messageInput);

      if (isNameValid && isEmailValid && isPhoneValid && isMessageValid) {
        // Все данные корректны
        console.log("Данные формы:", {
          name: nameInput.value.trim(),
          email: emailInput.value.trim(),
          phone: phoneInput.value.trim(),
          message: messageInput.value.trim()
        });

        showModal("✅ Сообщение успешно отправлено!");
        form.reset();
        formSubmitted = false;
      } else {
        showModal("❌ Пожалуйста, исправьте ошибки в форме.");
      }
    });
  }

  function validateField(field) {
    const errorMessage = field.nextElementSibling;
    if (field.value.trim() === "") {
      field.classList.add("is-invalid");
      if (errorMessage) errorMessage.style.display = "block";
      return false;
    } else {
      field.classList.remove("is-invalid");
      if (errorMessage) errorMessage.style.display = "none";
      return true;
    }
  }

  function validateEmailField() {
    const errorMessage = emailInput.nextElementSibling;
    const email = emailInput.value.trim();
    
    if (email === "") {
      emailInput.classList.add("is-invalid");
      if (errorMessage) errorMessage.style.display = "block";
      return false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      emailInput.classList.add("is-invalid");
      if (errorMessage) {
        errorMessage.textContent = "Пожалуйста, введите корректный email";
        errorMessage.style.display = "block";
      }
      return false;
    } else {
      emailInput.classList.remove("is-invalid");
      if (errorMessage) errorMessage.style.display = "none";
      return true;
    }
  }

  function validatePhoneField() {
    const errorMessage = phoneInput.nextElementSibling;
    const phone = phoneInput.value.trim();
    
    if (phone === "") {
      phoneInput.classList.add("is-invalid");
      if (errorMessage) errorMessage.style.display = "block";
      return false;
    } else if (!/^(\+7|8)[\s-]?\(?\d{3}\)?[\s-]?\d{3}[\s-]?\d{2}[\s-]?\d{2}$/.test(phone)) {
      phoneInput.classList.add("is-invalid");
      if (errorMessage) {
        errorMessage.textContent = "Пожалуйста, введите корректный номер телефона";
        errorMessage.style.display = "block";
      }
      return false;
    } else {
      phoneInput.classList.remove("is-invalid");
      if (errorMessage) errorMessage.style.display = "none";
      return true;
    }
  }

  function showModal(message) {
    document.getElementById('modal-text').textContent = message;
    formModal.show();
  }
});