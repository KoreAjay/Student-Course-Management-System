function togglePassword(fieldId, iconElement) {
  const field = document.getElementById(fieldId);
  if (field.type === "password") {
    field.type = "text";
    iconElement.textContent = "";
  } else {
    field.type = "password";
    iconElement.textContent = "";
  }
}

function validateLogin() {
  const user = document.getElementById("login-username").value;
  const pass = document.getElementById("login-password").value;

  // Dummy check (for dynamic, integrate with backend/database)
  if (user !== "student@example.com" || pass !== "1234") {
    alert("Login Failed! Please check your username and password.");
    return false;
  }

  alert("Login Successful!");
  return true;
}
function togglePassword(fieldId, iconElement) {
  const field = document.getElementById(fieldId);
  if (field.type === "password") {
    field.type = "text";
    iconElement.textContent = "";
  } else {
    field.type = "password";
    iconElement.textContent = "";
  }
}
const toast = document.getElementById("toast");
toast.textContent = data.message;
toast.style.opacity = 1;
setTimeout(() => { toast.style.opacity = 0; }, 3000);

function validateLogin() {
  const username = document.getElementById("login-username").value;
  const password = document.getElementById("login-password").value;

  // Set fixed valid credentials
  const validUsername = "student123";
  const validPassword = "pass123";

  if (username === validUsername && password === validPassword) {
    // Redirect to home page
    window.location.href = "home.html";
    return false; // Prevent actual form submission
  } else {
    alert("Invalid username or password!");
    return false;
  }
}

function logout() {
  alert("You have been logged out.");
  window.location.href = "index.html";
}
