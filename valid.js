function formValidation() {
    console.log("formValidation вызвана");
    
    // Получение элементов из формы (имена полей из HTML)
    var ulogin = document.register.login;
    var uemail = document.register.email;
    var uphone = document.register.phone;
    var ufullname = document.register.fullname;
    var password1 = document.register.password1;
    var password2 = document.register.password2;
    
    console.log("Логин:", ulogin ? ulogin.value : "не найден");
    console.log("Email:", uemail ? uemail.value : "не найден");
    
    // Вызов функций валидации
    if (ulogin_validation(ulogin, 3, 50)) {
        if (ValidateEmail(uemail)) {
            if (allLetter(ufullname)) {
                if (phone_validation(uphone)) {
                    if (password1_validation(password1, 6, 50)) {
                        if (password_match(password1, password2)) {
                            alert("Регистрация успешна!");
                            return true;
                        }
                    }
                }
            }
        }
    }
    return false;
}

// Функция для проверки логина
function ulogin_validation(ulogin, mx, my) {
    if (!ulogin) {
        console.error("Поле login не найдено");
        return false;
    }
    var ulogin_len = ulogin.value.length;  // ИСПРАВЛЕНО: .value.length
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

// Функция для проверки почты
function ValidateEmail(uemail) {
    if (!uemail) {
        console.error("Поле email не найдено");
        return false;
    }
    if (uemail.value == "") {
        alert("Email не может быть пустым");
        uemail.focus();
        return false;
    }
    // ИСПРАВЛЕНО: добавлены обратные слеши
    var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
    if (uemail.value.match(mailformat)) {
        return true;
    } else {
        alert("Вы ввели неверный адрес электронной почты");
        uemail.focus();
        return false;
    }
}

// Функция для проверки номера телефона (необязательное поле)
function phone_validation(uphone) {
    if (!uphone) {
        console.error("Поле phone не найдено");
        return false;
    }
    // Если поле пустое - пропускаем проверку
    if (uphone.value == "") {
        return true;
    }
    // Проверка формата телефона (российский номер)
    var phonePattern = /^(\+7|7|8)?[\s\-]?\(?[0-9]{3}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/;
    if (phonePattern.test(uphone.value)) {
        return true;
    } else {
        alert("Введите корректный номер телефона (например: +7 999 123-45-67)");
        uphone.focus();
        return false;
    }
}

// Функция для проверки ФИО (только буквы)
function allLetter(ufullname) {
    if (!ufullname) {
        console.error("Поле fullname не найдено");
        return false;
    }
    // Если поле пустое - пропускаем проверку
    if (ufullname.value == "") {
        return true;
    }
    // ИСПРАВЛЕНО: добавлены русские буквы и пробелы
    var letters = /^[A-Za-zА-Яа-я\s]+$/;
    if (ufullname.value.match(letters)) {
        return true;
    } else {
        alert("Имя должно содержать только буквы");
        ufullname.focus();
        return false;
    }
}

// Функция для проверки пароля
function password1_validation(password1, mx, my) {
    if (!password1) {
        console.error("Поле password1 не найдено");
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

// Функция для проверки совпадения паролей
function password_match(password1, password2) {
    if (!password2) {
        console.error("Поле password2 не найдено");
        return false;
    }
    if (password1.value != password2.value) {
        alert("Пароли не совпадают!");
        password2.focus();
        return false;
    }
    return true;
}