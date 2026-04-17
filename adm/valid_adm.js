
function formValidation() {
    
    // Получение элементов из формы
    var ulogin = document.register.login;
    var password1 = document.register.password;
    var admin_code = document.register.admin_code;
    
    console.log("Логин:", ulogin ? ulogin.value : "не найден");
    
    // Проверка на наличие admin_code (для админской регистрации)
    if (admin_code) {
        if (ulogin_validation(ulogin, 3, 50)) {
            if (password1_validation(password1, 6, 50)) {
                if (admin_code_validation(admin_code)) {
                    alert("Администратор успешно зарегистрирован!");
                    return true;
                }
            }
        }
        return false;
    }
    
    return false;
}

// Функция для проверки логина
function ulogin_validation(ulogin, mx, my) {
    if (!ulogin) {
        console.error("Поле login не найдено");
        return false;
    }
    var ulogin_len = ulogin.value.length;
    if (ulogin_len == 0) {
        alert("Логин не может быть пустым");
        ulogin.focus();
        return false;
    }
    if (ulogin_len < mx) {
        alert("Логин должен быть не менее " + mx + " символов");
        ulogin.focus();
        return false;
    }
    if (ulogin_len > my) {
        alert("Логин не должен превышать " + my + " символов");
        ulogin.focus();
        return false;
    }
    return true;
}

// Функция для проверки пароля
function password1_validation(password1, mx, my) {
    if (!password1) {
        console.error("Поле password не найдено");
        return false;
    }
    var pass_len = password1.value.length;
    if (pass_len == 0) {
        alert("Пароль не может быть пустым");
        password1.focus();
        return false;
    }
    if (pass_len < mx) {
        alert("Пароль должен быть не менее " + mx + " символов");
        password1.focus();
        return false;
    }
    if (pass_len > my) {
        alert("Пароль не должен превышать " + my + " символов");
        password1.focus();
        return false;
    }
    return true;
}

// Функция для проверки кода администратора
function admin_code_validation(admin_code) {
    if (!admin_code) {
        console.error("Поле admin_code не найдено");
        return false;
    }
    var code = admin_code.value;
    if (code == "") {
        alert("Код администратора не может быть пустым");
        admin_code.focus();
        return false;
    }
    if (code != "vet_admin") {
        alert("Неверный код администратора");
        admin_code.focus();
        return false;
    }
    return true;
}