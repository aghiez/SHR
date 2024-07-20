function logout(){
    firebase.auth().signOut().then(() => {
        window.location.href = 'login.html';
    }).catch((err) => alert(err.message));

    localStorage.clear();
}

// function setCookie(name, value, days){
//     var expires = "";
//     if(days){
//         var date = new Date();
//         date.setTime(date.getTime() + (days * 24 * 60 *60 * 1000));
//         expires = "; expires=" + date.toUTCString();
//     }
//     document.cookie = name + "=" + (value || "") + expires + "; path=/";
// }

// function getCookie(name){
//     var nameEQ = name + "=";
//     var ca  = document.cookie.split(';');
//     for(var i = 0; i < ca.length; i++) {
//         var c = ca[i];
//         while (c.charAt(0) === ' ') c = c.substring(1, c.length);
//         if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
//     }
//     return null;
// }

function formatTgl(dateS){
    const date = new Date(dateS);
    if(!isNaN(date)){
        return date.toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric' });
    }else{
        return '-';
    }
}

// function deleteCookie(name) {
//     document.cookie = name + '=; Max-Age=-99999999; path=/';
// }

function showError(message) {
    var pesanError = document.getElementById('notif');
    pesanError.textContent = message;
    pesanError.style.display = 'block';
    if (pesanError) {
        pesanError.classList.add('show');
        setTimeout(function() {
            pesanError.classList.remove('show');
        }, 3000);
    }
}