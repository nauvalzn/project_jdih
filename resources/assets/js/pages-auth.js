/**
 *  Pages Authentication
 */
'use strict';

document.addEventListener('DOMContentLoaded', function () {
  (() => {
    const formAuthentication = document.querySelector('#formAuthentication');

    // Form validation untuk login / register
    if (formAuthentication && typeof FormValidation !== 'undefined') {
      FormValidation.formValidation(formAuthentication, {
        fields: {
          username: {
            validators: {
              notEmpty: {
                message: 'Silakan masukkan nama pengguna'
              },
              stringLength: {
                min: 6,
                message: 'Nama pengguna minimal 6 karakter'
              }
            }
          },
          email: {
            validators: {
              notEmpty: {
                message: 'Silakan masukkan email Anda'
              },
              emailAddress: {
                message: 'Silakan masukkan alamat email yang valid'
              }
            }
          },
          'email-username': {
            validators: {
              notEmpty: {
                message: 'Silakan masukkan email atau nama pengguna'
              },
              stringLength: {
                min: 6,
                message: 'Nama pengguna minimal 6 karakter'
              }
            }
          },
          password: {
            validators: {
              notEmpty: {
                message: 'Silakan masukkan kata sandi'
              },
              stringLength: {
                min: 6,
                message: 'Kata sandi minimal 6 karakter'
              }
            }
          },
          'confirm-password': {
            validators: {
              notEmpty: {
                message: 'Silakan konfirmasi kata sandi'
              },
              identical: {
                compare: () => formAuthentication.querySelector('[name="password"]').value,
                message: 'Konfirmasi kata sandi tidak cocok'
              },
              stringLength: {
                min: 6,
                message: 'Kata sandi minimal 6 karakter'
              }
            }
          },
          terms: {
            validators: {
              notEmpty: {
                message: 'Anda harus menyetujui syarat & ketentuan'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap5: new FormValidation.plugins.Bootstrap5({
            eleValidClass: '',
            rowSelector: '.form-control-validation'
          }),
          submitButton: new FormValidation.plugins.SubmitButton(),
          defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
          autoFocus: new FormValidation.plugins.AutoFocus()
        },
        init: instance => {
          instance.on('plugins.message.placed', e => {
            if (e.element.parentElement.classList.contains('input-group')) {
              e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
            }
          });
        }
      });
    }

    // Two Steps Verification untuk input angka
    const numeralMaskElements = document.querySelectorAll('.numeral-mask');

    const formatNumeral = value => value.replace(/\D/g, ''); // hanya angka

    if (numeralMaskElements.length > 0) {
      numeralMaskElements.forEach(numeralMaskEl => {
        numeralMaskEl.addEventListener('input', event => {
          numeralMaskEl.value = formatNumeral(event.target.value);
        });
      });
    }
  })();
});
