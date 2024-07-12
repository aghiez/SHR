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

$data = file_get_contents('data/shrkospin.json');
$nasabah = json_decode($data, true);

if(isset($_SESSION['nasabh'])){
    $pilihNasabah = $_SESSION['nasabh'];
} elseif( isset($_POST['customer'])){
    $pilihNasabah = $_POST['customer'];
} else {
    $pilihNasabah='';
}

function tgl_indo($tgl){
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
    return $pecah[2].' '.$bulan[(int)$pecah[1]].' '.$pecah[0];
}


?>
<!DOCTYPE html>
<html>
    <head>
        <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Commissioner&display=swap" rel="stylesheet" />
        <link href="css/main.css" rel="stylesheet" />
        <title>SHR-KospinJasa</title>
    </head>
    <body>
        <div class="konten">
            <span class="sedang">Selamat Datang, <?php echo $_SESSION['nama']; ?></span>
            <span class="customer"><br/>Silahkan pilih Customer : 
                <form action="" method="post">
            <select name="customer" id="customer" onchange="this.form.submit()">
                <option value="">Pilih Nasabah</option>
                <option value="agus" <?php if($pilihNasabah == 'agus') echo 'selected'; ?>>Agus Sutomo</option>
                <option value="devi" <?php if($pilihNasabah == 'devi') echo 'selected'; ?>>Astika Devi Paramitha</option>
                <option value="asli" <?php if($pilihNasabah == 'asli') echo 'selected'; ?>>Asli Khatul Khasanah</option>
            </select>
            </form>
            </span>
            <a href="logout.php" class="tombol"><img src="images/exit.png" alt="Logout"></a>
            <a href="input.php" class="tombol"><img src="images/input.png" alt="Input Data"></a>
            <div class="card" id="card">
                <?php 
                    if($pilihNasabah && isset($nasabah[$pilihNasabah])):
                    foreach($nasabah[$pilihNasabah] as $nilai): 
                ?>
            <div class="box">
                        <span class="teks">Besar : </span>
                        <div class="boxk"><span class="teks1" id="besar">Rp. <?php echo number_format($nilai["besar"],0,",","."); ?>,-</span></div>

                        <span class="teks">Tanggal : </span>
                        <div class="boxk"><span class="teks1" id="tanggal"><?php echo tgl_indo($nilai["tanggal"]); ?></span></div>

                        <span class="teks">Minggu Ke : </span>
                        <div class="boxk"><span class="teks2" id="minggu"><?php echo $nilai["minggu"]; ?></span></div>

                        <span class="teks">Untuk : </span>
                        <div class="boxk1"><span class="teks3" id="jmt">Jum'at, <?php echo tgl_indo($nilai["jmt"]); ?></span></div>

                        <span class="teks">Saldo : </span>
                        <div class="boxk"><span class="teks1" id="saldo">Rp. <?php echo number_format($nilai["saldo"],0,",","."); ?>,-</span></div>
                        <a href="set_session.php?nasabah=<?php echo $pilihNasabah; ?>&minggu=<?php echo $nilai['minggu']-1; ?>" class="yedit">Edit</a>
                    </div>
<?php endforeach;
endif; ?>
            </div>
        </div>
        <script src="js/script.js"></script>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    </body>
</html>