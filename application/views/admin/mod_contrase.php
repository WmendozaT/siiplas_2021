<!DOCTYPE html>
<html lang="en-us" id="lock-page">
    <head>
        <meta charset="utf-8">
        <title><?php echo $this->session->userdata('name')?></title>
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.core.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/themes_alerta/alertify.default.css" id="toggleCSS" />
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url(); ?>assets/css/estilosh.css">
    </head>
    <body>

        <div id="main" role="main">
            <?php echo $formulario; ?>

        </div>
    </body>
    <script>
            if (!window.jQuery) {
                document.write('<script src="<?php echo base_url(); ?>assets/js/libs/jquery-2.0.2.min.js"><\/script>');
            }
        </script>
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="<?php echo base_url(); ?>assets/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
            }
        </script>
        <script src="<?php echo base_url(); ?>assets/lib_alerta/alertify.min.js"></script>
   <script>
        function togglePassword(fieldId) {

            const passwordInput = document.getElementById(fieldId);
            let toggleIconId;
            
            // Determinar el ID del icono basado en el campo
            switch(fieldId) {
                case 'password_anterior':
                    toggleIconId = 'toggleIcon1';
                    break;
                case 'password':
                    toggleIconId = 'toggleIcon2';
                    break;
                case 'password_confirm':
                    toggleIconId = 'toggleIcon3';
                    break;
                default:
                    toggleIconId = 'toggleIcon1';
            }
            
            const toggleIcon = document.getElementById(toggleIconId);
            
            if (passwordInput && toggleIcon) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    toggleIcon.textContent = '🙈';
                } else {
                    passwordInput.type = 'password';
                    toggleIcon.textContent = '👁️';
                }
            }
        }

        function checkPasswordRequirements(password) {
            const requirements = {
                length: password.length >= 12,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@+,:?_.^\/\*&%$]/.test(password) 
            };

            // Actualizar visualización de requisitos
            updateRequirement('lengthReq', requirements.length, '✓ Al menos 8 caracteres', '✗ Al menos 12 caracteres');
            updateRequirement('uppercaseReq', requirements.uppercase, '✓ Al menos una letra mayúscula', '✗ Al menos una letra mayúscula');
            updateRequirement('lowercaseReq', requirements.lowercase, '✓ Al menos una letra minúscula', '✗ Al menos una letra minúscula');
            updateRequirement('numberReq', requirements.number, '✓ Al menos un número', '✗ Al menos un número');
            updateRequirement('specialReq', requirements.special, '✓ Al menos un carácter especial', '✗ Al menos un carácter especial !@+,:?_.^\/\*&%$');

            return Object.values(requirements).every(req => req);
        }

        function updateRequirement(elementId, isValid, validText, invalidText) {
            const element = document.getElementById(elementId);
            element.className = isValid ? 'requirement valid' : 'requirement invalid';
            element.textContent = isValid ? validText : invalidText;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirm').value;
            const isMatch = password === confirmPassword && password.length > 0;
            
            updateRequirement('matchReq', isMatch, '✓ Las contraseñas coinciden', '✗ Las contraseñas coinciden');
            return isMatch;
        }

        function validateForm() {
            const password = document.getElementById('password').value;
            const passwordValid = checkPasswordRequirements(password);
            const passwordsMatch = checkPasswordMatch();
            const currentPassword = document.getElementById('password_anterior').value;
            
            const submitBtn = document.getElementById('submitBtn');
            const isFormValid = passwordValid && passwordsMatch && currentPassword.length > 0;
            
            submitBtn.disabled = !isFormValid;
            
            if (isFormValid) {
                submitBtn.style.background = 'green';
                submitBtn.style.cursor = 'pointer';
            } else {
                submitBtn.style.background = '#ccc';
                submitBtn.style.cursor = 'not-allowed';
            }
        }

        function showMessage(text, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = text;
            messageDiv.className = `message ${type}`;
            messageDiv.style.display = 'block';
            
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 5000);
        }

        // Event listeners
        document.getElementById('password').addEventListener('input', validateForm);
        document.getElementById('password_confirm').addEventListener('input', validateForm);
        document.getElementById('password_anterior').addEventListener('input', validateForm);

        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const currentPassword = document.getElementById('password_anterior').value;
            const newPassword = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirm').value;
            
            // Validaciones finales
            if (!checkPasswordRequirements(newPassword)) {
                showMessage('La nueva contraseña no cumple con todos los requisitos.', 'error');
                return;
            }
            
            if (newPassword !== confirmPassword) {
                showMessage('Las contraseñas no coinciden.', 'error');
                return;
            }
            
            if (currentPassword === newPassword) {
                showMessage('La nueva contraseña debe ser diferente a la actual.', 'error');
                return;
            }
            
            
            alertify.confirm("CONFIRMAR CONTRASEÑA ?", function (a) {
                if (a) {
                  //============= GUARDAR DESPUES DE LA VALIDACION ===============
                  passwordForm.submit();
                  document.getElementById("submitBtn").value = "SUBIENDO ARCHIVO...";
                  document.getElementById("submitBtn").disabled = true;
                  return true; 
                } else {
                  alertify.error("OPCI\u00D3N CANCELADA");
                }
            });


/*            // Simulación de envío exitoso
            showMessage('¡Contraseña cambiada exitosamente!', 'success');
            
            // Limpiar formulario después de éxito
            setTimeout(() => {
                document.getElementById('passwordForm').reset();
                validateForm();
            }, 2000);*/
        });

        // Inicializar validación
        validateForm();
    </script>
</html>
