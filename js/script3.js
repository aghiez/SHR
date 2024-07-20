firebase.auth().onAuthStateChanged((user) => {
    if (user) {
        document.getElementById('username').innerText = localStorage.getItem('user');
    } else {
        window.location.href = 'login.html';
    }
});

function bali(){
    window.location.href = 'index.html';
}

function setDropwodn(){
    const terpilihNasabah = localStorage.getItem('terpilihNasabah');
    const terpilihMinggu = localStorage.getItem('terpilihMinggu');
    
    if(terpilihNasabah){
        document.getElementById('customer').value = terpilihNasabah;
        document.getElementById('customer').dispatchEvent(new Event('change'));
    }

    if(terpilihMinggu){
        document.getElementById('week').value = terpilihMinggu;
        document.getElementById('week').dispatchEvent(new Event('change'));
    }
}

//mulai DOM untuk cek pas loading dan proses refresh
document.addEventListener('DOMContentLoaded', function(){


    const pilih = document.getElementById('customer');
    const week = document.getElementById('week');

    db.collection('customer').get()
    .then((snaps) => {
        snaps.docs.forEach(doc => {
            renderCust(doc);
        });
        setDropwodn();
    })
    .catch((err) => {
        // console.error(`Error fetch dokumen : `, err);
        showError('Tidak ada data' + err.message);
    });


function renderCust(doc){
    const dataNasabah = doc.data();
    const option = document.createElement('option');
    option.value = doc.id;
    option.textContent = dataNasabah.nama_customer;
    pilih.appendChild(option);
}

pilih.addEventListener('change', function() {
    week.innerHTML = '';

    const opt = document.createElement('option');
    opt.value = '';
    opt.textContent = 'Pilih Minggu';
    week.appendChild(opt);
    for(let i=1; i <= 45; i++){
        const option = document.createElement('option');
        option.value = i;
        option.textContent = 'Minggu ke-' + i;
        week.appendChild(option);
        }
        const terpilihMinggu = localStorage.getItem('terpilihMinggu');
        if('terpilihMinggu'){
            week.value = terpilihMinggu;
        }
    });

    if(document.getElementById('customer').value){
        document.getElementById('customer').dispatchEvent(new Event('change'));
    }
    
week.addEventListener('change', function(){
    db.collection('customer').doc(pilih.value).collection('minggu').doc(week.value).get()
    .then((snaps) => {
        if(snaps.exists){
            document.getElementById('besar').value = 'Rp. 100.000,-';
            document.getElementById('tanggal').value = snaps.data().tanggal.toDate().toISOString().split('T')[0]; 
            document.getElementById('tanggalDisplay').textContent = formatTgl(document.getElementById('tanggal').value);
            document.getElementById('saldo').value = 'Rp. ' + snaps.data().saldo.toLocaleString('id-ID') + ',-';
            document.getElementById('jmt').value = snaps.data().jumat.toDate().toISOString().split('T')[0]; 
            document.getElementById('jmtDisplay').textContent = formatTgl(document.getElementById('jmt').value);

        } else{
            // console.error(`Tidak ada documen untuk minggu ${week.value} ini `);
            showError(`Tidak ada data untuk minggu ${week.value} ini `);
            document.getElementById('besar').value = '';
            document.getElementById('tanggal').value = ''; 
            document.getElementById('tanggalDisplay').textContent = '';
            document.getElementById('saldo').value = '';
            document.getElementById('jmt').value = ''; 
            document.getElementById('jmtDisplay').textContent = '';            
        }
    })
    .catch((err) => {
        // console.error(`Ada error pada nasabah ${pilih.value}: `, err);
        showError(`Ada error pada nasabah ${pilih.value}: ${err.message}`);
    });
});

document.getElementById('tanggal').addEventListener('change', function(){
    document.getElementById('tanggalDisplay').textContent = formatTgl(document.getElementById('tanggal').value);
});

document.getElementById('jmt').addEventListener('change', function(){
    document.getElementById('jmtDisplay').textContent = formatTgl(document.getElementById('jmt').value);
});


});

function simpan(){
    const tgl = firebase.firestore.Timestamp.fromDate(new Date(document.getElementById('tanggal').value));
    const saldo = parseInt(document.getElementById('saldo').value.replace(/[^\d]/g, ''));
    const jmt = firebase.firestore.Timestamp.fromDate(new Date(document.getElementById('jmt').value));

    const customerID = document.getElementById('customer').value;
    const mgu = document.getElementById('week').value;
    
    const docref =  db.collection('customer').doc(customerID).collection('minggu').doc(mgu);

    docref.get()
    .then((snap) => {
        if(snap.exists){
            return docref.update({
            jumat:jmt,
            saldo:saldo,
            tanggal:tgl 
            });
        } else{
            return docref.set({
            jumat:jmt,
            saldo:saldo,
            tanggal:tgl 
            }, {merge: true});
        }
    })
    .then(() => {
        showError('Data Berhasil disimpan atau diperbaharui');
    })
    .catch((err) => {
        showError('Gagal menyimpan data: ' + err.message);
    });
    
}

function kembali(){
    localStorage.setItem('nasabahe', document.getElementById('customer').value);
    window.location.href = 'index.html';
}