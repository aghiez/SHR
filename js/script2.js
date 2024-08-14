firebase.auth().onAuthStateChanged((user) => {
    if (user) {
        document.getElementById('username').innerText = localStorage.getItem('user');
    } else {
        window.location.href = 'login.html';
    }
});

function input(){
    window.location.href = 'input.html';
}

function cekCustomer(){
    const nasabahe = localStorage.getItem('nasabahe');

    if(nasabahe){
        document.getElementById('customer').value = nasabahe;
        document.getElementById('customer').dispatchEvent(new Event('change'));
    }
}


db.collection('customer').get()
    .then((snaps) => {
        snaps.docs.forEach(doc => {
            renderCust(doc);
        });
        cekCustomer();
    })
    .catch((err) => {
        console.error(`Error fetch dokumen : `, err);
    });

const pilih = document.getElementById('customer');

function renderCust(doc){
    const dataNasabah = doc.data();
    const option = document.createElement('option');
    option.value = doc.id;
    option.textContent = dataNasabah.nama_customer;
    pilih.appendChild(option);
}

document.addEventListener('DOMContentLoaded', function(){

document.getElementById('customer').addEventListener('change', function() {
    const customerID = this.value;
    const card = document.getElementById('card');

    card.innerHTML = '';

    db.collection('customer').doc(customerID).collection('minggu')
    .orderBy('minggu')
    .get()
    .then((snaps) => {
        snaps.docs.forEach(mingguDoc => {
            const dataminggu = document.createElement('div');
            dataminggu.classList.add('mingguke');
            dataminggu.innerHTML = `
            <div class="box">
                <span class="teks">Besar : </span>
                <div class="boxk"><span class="teks1" id="besar">Rp. 100.000,-</span></div>

                <span class="teks">Tanggal : </span>
                <div class="boxk"><span class="teks1" id="tanggal">${formatTgl(mingguDoc.data().tanggal.toDate())}</span></div>

                <span class="teks">Minggu Ke : </span>
                <div class="boxk"><span class="teks2" id="minggu">${mingguDoc.id}</span></div>

                <span class="teks">Untuk : </span>
                <div class="boxk1"><span class="teks3" id="jmt">Jum'at, ${formatTgl(mingguDoc.data().jumat.toDate())}</span></div>

                <span class="teks">Saldo : </span>
                <div class="boxk"><span class="teks1" id="saldo">Rp. ${mingguDoc.data().saldo.toLocaleString('id-ID')},-</span></div>
                <a href="javascript:void(0)" class="yedit" onclick="edit('${customerID}','${mingguDoc.id}')">Edit</a>
            </div>
            `;
            card.appendChild(dataminggu);
        });
    })
    .catch((err) => {
        console.error(`Ada error pada nasabah ${customerID}: `, err);
    });
});
});

function edit(nasabahID, mingguID){
    localStorage.setItem('terpilihNasabah', nasabahID);
    localStorage.setItem('terpilihMinggu', mingguID);

    window.location.href = 'input.html';
}
