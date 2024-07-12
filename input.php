<?php
session_start();

function check_login() {
    if (!isset($_SESSION['username'])) {
        header('Location: login.php');
        exit();
    }
}

// Panggil fungsi check_login di bagian atas setiap halaman yang ingin dilindungi
check_login();

$file = 'data/shrkospin.json';
$data = file_get_contents($file);
$nasabah = json_decode($data, true);
if(isset($_SESSION['nasabah'])){
    $pilihNasabah = $_SESSION['nasabah'];
}elseif(isset($_POST['customer'])){
    $pilihNasabah = $_POST['customer'];
} else{
    $pilihNasabah = '';
}

if(isset($_SESSION['minggu'])){
    $pilihMinggu = $_SESSION['minggu'];
}elseif(isset($_POST['week'])){
    $pilihMinggu = $_POST['week'];
}else{
    $pilihMinggu = '';
}

// $pilihNasabah = isset($_GET['nasabah']) ? $_GET['nasabah'] :'';
// $pilihMinggu = isset($_GET['minggu']) ? $_GET['minggu'] :'';
// $pilihNasabah = isset($_POST['customer']) ? $_POST['customer'] : '' ;
// $pilihMinggu = isset($_POST['week']) ? $_POST['week'] : '';
function tgl_indo($tgl){
    if(empty($tgl)) return '-';
    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember' 
    );
    $pecah = explode('-', $tgl);
    if(count($pecah) < 3) return '-';
    return $pecah[2].' '.$bulan[(int)$pecah[1]].' '.$pecah[0];
}


if(isset($_POST['saveButton'])){
    
    $besar = str_replace(['Rp. ', '.', ',-'], '', $_POST['besar']); // Menghapus format Rp.
    $tgl = $_POST['tanggal'];
    $saldo = str_replace(['Rp. ', '.', ',-'], '', $_POST['saldo']); // Menghapus format Rp.

    if(isset($nasabah[$pilihNasabah][$pilihMinggu])) {
        $nasabah[$pilihNasabah][$pilihMinggu]["besar"] = $besar;
        $nasabah[$pilihNasabah][$pilihMinggu]["tanggal"] = $tgl;
        $nasabah[$pilihNasabah][$pilihMinggu]["saldo"] = $saldo;
    

// Meng-encode data menjadi json
$jsonfile = json_encode($nasabah, JSON_PRETTY_PRINT);

// Menyimpan data ke dalam anggota.json
$data = file_put_contents($file, $jsonfile);

if($data){
    echo "<div class='notif' id='notif'>Data berhasil disimpan</div>";
} else{
    echo "<div class='notif' id='notif'>Gagal menyimpan data</div>";
} 
}else{
    echo "<script>alert('Data Nasabah/Minggu tidak ditemukan');</script>";
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Commissioner&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/main.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .form-group {
            margin-bottom: 15px;
            width: 350px;
            border-radius: 20px;
            margin: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group select, .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border-radius: 5px;
        }
        button {
            width: 200px;
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-left: 10%;
            border-radius: 7px;
        }
        .data-form {
            margin-top: 20px;
        }
        h1{
            margin-left:33%;
            top: 10%;
        }

        .kartu{
            background-color: gainsboro;
            width: 400px;
            height: 700px;
            margin: auto;
            border-radius: 10px;
        }
        .tamb{
            height: 30px;
            width: 400px;
        }

        .tgl{
            width: 100%;
            height: 35px;
            background-color:darkblue;
            color: lemonchiffon;
            font-size: 15px;
            border-radius: 7px;
            margin-top: 5px;          
            vertical-align: middle;
            padding-top: 5px;
            padding-left: 10px;  
        }
        .notif{
            position: fixed;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4caf50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.9s ease-in-out;
        }

        .notif.show{
            opacity: 1;
        }

        .homeB{
            height: 50px;
            width: 50px;
            background-color:burlywood;
            color: azure;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px;
            text-decoration: none;
        }

        .homeB img{
            width: 30px;
            height: 30px;
        }

        .homeA{
            width: 50px;
            padding: 10px 15px;
            background-color:brown;
            color: white;
            border: none;
            cursor: pointer;
            margin-left: 5%;
            border-radius: 7px;

        }
    </style>
</head>
<body>
    <div class="konten">
        <div class="tamb">
            <a href="index.php" class="homeB"><img src="images/home.png" alt="Beranda" ></a>
            <a href="logout.php" class="homeB"><img src="images/exit.png" alt="Logout" ></a>
        </div>
        <div class="kartu">
        <h1>Edit Data</h1>
        <form action="" method="post">    
        <div class="form-group">
            <label for="customer">Customer:</label>
            <select name="customer" id="customer" onchange="this.form.submit()">
                <option value="">Pilih Customer</option>
                <option value="agus" <?php if($pilihNasabah == "agus") echo "selected"; ?>>Agus Sutomo</option>
                <option value="devi" <?php if($pilihNasabah == "devi") echo "selected"; ?>>Astika Devy Paramitha</option>
                <option value="asli" <?php if($pilihNasabah == "asli") echo "selected"; ?>>Asli Khatul Khasanah</option>
            </select>
        </div>
        <div class="form-group" id="weekSelectGroup">
            <label for="week">Minggu Ke:</label>
            <select name="week" id="week" onchange="this.form.submit()">
                <option value="">Pilih Minggu</option>
                <?php 
                    if($pilihNasabah && isset($nasabah[$pilihNasabah])):
                        foreach($nasabah[$pilihNasabah] as $index => $v): ?>
                            <option value="<?php echo $index; ?>" <?php if($pilihMinggu == $index) echo "selected"; ?>  >Minggu ke-<?php echo $v["minggu"]; ?></option>
                <?php endforeach;
                    endif; ?>
            </select>
        </div>
            <?php if($pilihNasabah && $pilihMinggu): 
                $nl = $nasabah[$pilihNasabah][$pilihMinggu];
                ?>
        <div class="data-form" id="dataForm">
            <div class="form-group">
                <label for="besar">Besar:</label>
                <input type="text" id="besar" name="besar" value="Rp. <?php echo number_format($nl["besar"],0,",",".") ?>,-">
            </div>
            <div class="form-group">
                <label for="tanggal">Tanggal:</label>
                <input type="date" id="tanggal" name="tanggal" value="<?php echo $nl["tanggal"]; ?>">
                <div class="tgl" id="tanggalDisplay"><?php echo isset($nl["tanggal"]) ? tgl_indo($nl["tanggal"]) : "-"; ?></div>
            </div>
            <div class="form-group">
                <label for="saldo">Saldo:</label>
                <input type="text" id="saldo" name="saldo" value="Rp. <?php echo number_format($nl["saldo"],0,",",".") ?>,-">
            </div>
            <div class="form-group">
                <label for="jmt">Untuk Jum'at:</label>
                <input type="date" id="jmt" name="jmt" value="<?php echo $nl["jmt"]; ?>">
                <div class="tgl" id="jmtDisplay"><?php echo tgl_indo($nl["jmt"]); ?></div>
            </div>
            <button id="saveButton" name="saveButton">Simpan</button>
            <a href="setsession.php?nasabh=<?php echo $pilihNasabah; ?>" class="homeA"><img src="images/back.png" height="13px" alt="Kembali Ke Beranda sesuai Nama"></a>
        </div>
                    <?php  endif; ?>
        </form>
        </div>
    </div>

    <script>
function formatTgl(dateS){
    const date = new Date(dateS);
    if(!isNaN(date)){
        return date.toLocaleDateString('id-ID', {day: '2-digit', month: 'long', year: 'numeric' });
    }else{
        return '-';
    }
}

document.addEventListener('DOMContentLoaded', function(){
document.getElementById('tanggal').addEventListener('change', function(){
    document.getElementById('tanggalDisplay').innerText = formatTgl(this.value);
});

document.getElementById('jmt').addEventListener('change', function(){
    document.getElementById('jmtDisplay').innerText = formatTgl(this.value); 
});

var notif =document.getElementById('notif');
if(notif){
    notif.classList.add('show');
    setTimeout(function(){
        notif.classList.remove('show');
    }, 3000);
    }
});


    </script>

    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script> -->
</body>
</html>