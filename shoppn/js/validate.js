/* Shoppn — client-side form validation
   Task 3: registration form
   Task 4: login form
   Server-side validation is always authoritative. */

(function () {
  'use strict';

  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  var phoneRegex = /^[0-9+\-\s]{7,15}$/;
  var passRegex  = /^(?=.*\d).{8,}$/;

  function showError(fieldId, msg) {
    var el = document.getElementById('err-' + fieldId);
    if (el) el.textContent = msg || '';
  }

  // ---------- Registration form ----------
  var regForm = document.getElementById('register-form');
  if (regForm) {
    regForm.addEventListener('submit', function (e) {
      var ok = true;

      var name    = document.getElementById('customer_name').value.trim();
      var email   = document.getElementById('customer_email').value.trim();
      var pass    = document.getElementById('customer_pass').value;
      var country = document.getElementById('customer_country').value;
      var city    = document.getElementById('customer_city').value.trim();
      var contact = document.getElementById('customer_contact').value.trim();

      if (name.length < 2)               { showError('name', 'Name is too short.'); ok = false; }
      else                               { showError('name', ''); }

      if (!emailRegex.test(email))       { showError('email', 'Invalid email address.'); ok = false; }
      else                               { showError('email', ''); }

      if (!passRegex.test(pass))         { showError('pass', 'Min 8 characters, at least one digit.'); ok = false; }
      else                               { showError('pass', ''); }

      if (country === '')                { showError('country', 'Please select a country.'); ok = false; }
      else                               { showError('country', ''); }

      if (city === '')                   { showError('city', 'City is required.'); ok = false; }
      else                               { showError('city', ''); }

      if (!phoneRegex.test(contact))     { showError('contact', 'Contact must be 7–15 digits.'); ok = false; }
      else                               { showError('contact', ''); }

      if (!ok) {
        e.preventDefault();
        return false;
      }
      var btn = document.getElementById('register-submit');
      if (btn) { btn.disabled = true; btn.textContent = 'Creating account…'; }
    });
  }

  // ---------- Login form ----------
  var loginForm = document.getElementById('login-form');
  if (loginForm) {
    loginForm.addEventListener('submit', function (e) {
      var ok = true;

      var email = document.getElementById('login_email').value.trim();
      var pass  = document.getElementById('login_pass').value;

      if (!emailRegex.test(email))       { showError('login_email', 'Invalid email address.'); ok = false; }
      else                               { showError('login_email', ''); }

      if (pass.length === 0)             { showError('login_pass', 'Password is required.'); ok = false; }
      else                               { showError('login_pass', ''); }

      if (!ok) {
        e.preventDefault();
        return false;
      }
      var btn = document.getElementById('login-submit');
      if (btn) { btn.disabled = true; btn.textContent = 'Logging in…'; }
    });
  }
})();