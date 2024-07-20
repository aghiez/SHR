firebase.auth().onAuthStateChanged((user) => {
    if (user) {
        // document.getElementById('username').innerText = user.email;
        window.location.href = 'index.html';
    } else {

    }
});

function isValidEmail(email) {
    // Regular expression for validating an Email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function validateInputs(username, password) {
    if (username === "" || password === "") {
        showError('Email/Password tidak boleh kosong !');
        return false;
    }
    if (!isValidEmail(username)) {
        showError('Format email tidak valid !');
        return false;
    }
    return true;
}

function register() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('katasandi').value;

    if (!validateInputs(username, password)) {
        return;
    }

    firebase.auth().createUserWithEmailAndPassword(username, password)
        .then((cred) => {
            showError('Berhasil membuat akun ' + cred.user.email);
        })
        .catch((err) => {
            showError('Gagal membuat akun, ' + err.message);
        });
}

function login(){
    const username = document.getElementById('username').value;
    const password = document.getElementById('katasandi').value;

    if(!validateInputs(username, password)){ return; }

    firebase.auth().signInWithEmailAndPassword(username, password)
        .then((cred) => {
            showError('Berhasil Login');
            localStorage.setItem('user', cred.user.email);
            window.location.href = 'index.html';
        })
        .catch((err) => {
            showError('Gagal login, Email/Password salah');
        });
}