<?php
//bisa menggunakan include atau require
session_start();

include '../funcition.php';
//cek kalo gada session login maka blm login
//kembalikan ke login
//jika tidak ada session[login] maka tendang ke login.php
if (!isset($_SESSION["login"])) {
  echo '<script>window.location="../form-login.php"</script>';
}
$banyakDataPerHal = 3;
$banyakData = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM tb_product"));
$banyalHal = ceil($banyakData / $banyakDataPerHal);

if (isset($_GET['halaman'])) {
  $halamanAktif = $_GET['halaman'];
} else {
  $halamanAktif = 1;
}
$dataAwal = ($halamanAktif * $banyakDataPerHal) - $banyakDataPerHal;
$no = 1;
// konfigurasi cari
if ((isset($_POST['Bcari'])) and $_POST['Pkeyword'] <> "") {
  $keyword = $_POST['Pkeyword'];
  $select = mysqli_query($conn, "SELECT * FROM tb_product WHERE 
  product_name LIKE '%$keyword%' OR
  product_price LIKE '%$keyword%' OR
  product_pcs LIKE '%$keyword%' OR
  product_status LIKE '%$keyword%'
  LIMIT $dataAwal,$banyakDataPerHal");
} else {
  // $select = mysqli_query($conn, "SELECT * FROM tb_product LIMIT $dataAwal,$banyakDataPerHal");
  $select = mysqli_query($conn, "SELECT * FROM tb_product LEFT JOIN tb_category USING (category_id) ORDER BY product_id DESC LIMIT $dataAwal,$banyakDataPerHal");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>DASHBOARD - N2Y STORE</title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="../fontawesome/css/all.min.css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">

</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../user/index.php">
        <div class="sidebar-brand-icon rotate-n-10">
          <i class="fa-solid fa-shop"></i>
        </div>
        <div class="sidebar-brand-text mx-3"><?= $_SESSION['level']; ?> N2Y STORE</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="index.php">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">


      <!-- Nav Item - daftar siswa -->
      <li class="nav-item">
        <a class="nav-link" href="data-kategori.php">
          <i class="fa-solid fa-tag me-3"></i>
          <span>Data Kategori</span></a>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - daftar guru -->
      <li class="nav-item">
        <a class="nav-link" href="data-produk.php">
          <i class="fa-solid fa-cart-shopping me-3"></i>
          <span>Data Produk</span></a>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider my-0">
      <!-- Divider -->
      <?php if ($_SESSION['level'] == 'Admin') : ?>
        <hr class="sidebar-divider my-0">
        <!-- Nav Item - daftar pegawai -->
        <li class="nav-item">
          <a class="nav-link" href="officer.php">
            <i class="fa-solid fa-user-gear me-3"></i>
            <span>Officer</span></a>
        </li>
      <?php endif; ?>
      <hr class="sidebar-divider my-0">
      <!-- Nav Item - daftar pegawai -->
      <li class="nav-item">
        <a class="nav-link" href="setting.php">
          <i class="fas fa-cogs fa-sm fa-fw me-3"></i>
          <span>Setting</span></a>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider my-0">
      <!-- Nav Item - keluar -->
      <li class="nav-item">
        <a class="nav-link" href="keluar.php" data-toggle="modal" data-target="#logoutModal">
          <i class="fa-solid fa-right-from-bracket"></i>
          <span>Log Out</span></a>
      </li>

      <!-- Nav Item - Pages Collapse Menu -->
      <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
          aria-controls="collapseTwo">
          <i class="fas fa-fw fa-cog"></i>
          <span>Components</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Custom Components:</h6>
            <a class="collapse-item" href="buttons.html">Buttons</a>
            <a class="collapse-item" href="cards.html">Cards</a>
          </div>
        </div>
      </li> -->

      <!-- Divider -->
      <hr class=" sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <form method="POST" class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" name="Pkeyword" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button name="Bcari" class="btn btn-primary" type="submit">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search" method="POST">
                  <div class="input-group">
                    <input type="text" name="Pkeyword" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button name="Bcari" class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>


            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?= $_SESSION['nama']; ?></span>
                <?php if ($_SESSION['avatar'] == 1) : ?>
                  <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                <?php endif; ?>
                <?php if ($_SESSION['avatar'] == 2) : ?>
                  <img class="img-profile rounded-circle" src="img/avatar-svgrepo-com.svg">
                <?php endif; ?>
                <?php if ($_SESSION['avatar'] == 3) : ?>
                  <img class="img-profile rounded-circle" src="img/avatar.svg">
                <?php endif; ?>
                <?php if ($_SESSION['avatar'] == 4) : ?>
                  <img class="img-profile rounded-circle" src="img/avatar (1).svg">
                <?php endif; ?>
                <?php if ($_SESSION['avatar'] == 5) : ?>
                  <img class="img-profile rounded-circle" src="img/undraw_profile_3.svg">
                <?php endif; ?>
                <?php if ($_SESSION['avatar'] == 6) : ?>
                  <img class="img-profile rounded-circle" src="img/avatar (2).svg">
                <?php endif; ?>
                <?php if ($_SESSION['avatar'] == 7) : ?>
                  <img class="img-profile rounded-circle" src="img/avatar (3).svg">
                <?php endif; ?>
                <?php if ($_SESSION['avatar'] == 8) : ?>
                  <img class="img-profile rounded-circle" src="img/avatar4.svg">
                <?php endif; ?>
                <?php if ($_SESSION['avatar'] == 9) : ?>
                  <img class="img-profile rounded-circle" src="img/avatar5.svg">
                <?php endif; ?>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a href="#" class="dropdown-item" data-toggle="modal" data-target="#ModalTambah">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="ubah-avatar.php">
                  <i class="fa-solid fa-face-smile fa-sm fa-fw mr-2 text-gray-400"></i>
                  Avatar
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="keluar.php" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <h1 class="h3 mb-3 text-gray-800">Data Produk</h1>
          <!-- DataTales Example -->
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <a href="tambah-produk.php" class="btn btn-primary"><i class="fa-solid fa-cart-plus mr-1"></i>Tambah Data</a>
              <!-- Topbar Search -->
              <form method="POST" class="float-right d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                  <input type="text" name="Pkeyword" class="form-control small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                  <div class="input-group-append">
                    <button name="Bcari" class="btn btn-primary" type="submit">
                      <i class="fas fa-search fa-sm"></i>
                    </button>
                  </div>
                </div>
              </form>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th scope="col">No</th>
                      <th scope="col">Nama Produk</th>
                      <th scope="col">Harga</th>
                      <th scope="col">Stok</th>
                      <th scope="col">Gambar</th>
                      <th scope="col">Status</th>
                      <th colspan="2" scope="col">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = $dataAwal + 1;
                    //untuk kondisi jika tidak ada data yang dicari
                    if (mysqli_num_rows($select) > 0) :
                    ?>

                      <?php foreach ($select as $rows) : ?>
                        <tr>
                          <td><?= $no++; ?></td>
                          <td><?= $rows['product_name']; ?></td>
                          <td>Rp. <?php echo number_format($rows['product_price']) ?></td>
                          <td><?= $rows['product_pcs']; ?></td>
                          <td><a href="image/<?php echo $rows['product_image'] ?>" target="_blank"><img src="image/<?= $rows['product_image']; ?>" height="50px" width="50px"></td>
                          <td><?php echo ($rows['product_status'] == 0) ? 'Tidak Aktif' : 'Aktif'; ?></a></td>


                          <td>
                            <a href=" edit-produk.php?product_id=<?= $rows['product_id'] ?>" class="btn btn-success text-white rounded"><i class="fa-solid fa-pen-to-square"></i></a>
                          </td>
                          <td><a href="hapus-produk.php?product_id=<?= $rows['product_id'] ?>" onclick="return confirm('Yakin Hapus?')" class="btn btn-danger text-white rounded"><i class="fa-solid fa-trash-can"></i></a>
                          </td>
                        </tr>
                      <?php endforeach; ?>
                    <?php else : ?>
                      <!-- tidak ada data yang dicari -->
                      <tr>
                        <td colspan="7">
                          <p>Data Tidak Ada</p>
                        </td>
                      </tr>
                    <?php endif; ?>

                  </tbody>
                </table>
                <nav>

                  <ul class="pagination justify-content-end">
                    <!-- sebelumnya -->
                    <?php if ($halamanAktif <= 1) : ?>
                      <li class="page-item disabled"><a href="?halaman=<?= $halamanAktif - 1 ?>" class="page-link">Back</a></li>
                    <?php else : ?>
                      <li class="page-item"><a href="?halaman=<?= $halamanAktif - 1 ?>" class="page-link">Back</a></li>
                    <?php endif; ?>
                    <!-- akhir sebelumnya -->
                    <?php
                    for ($i = 1; $i <= $banyalHal; $i++) : ?>
                      <?php if ($i == $halamanAktif) : ?>
                        <li class="page-item"><a href="?halaman=<?= $i; ?>" class="page-link bg-primary text-white"><?= $i; ?></a></li>
                      <?php else : ?>
                        <li class="page-item"><a href="?halaman=<?= $i; ?>" class="page-link"><?= $i; ?></a></li>
                      <?php endif; ?>
                    <?php endfor; ?>
                    <!-- selanjutnya -->
                    <?php if ($halamanAktif >= $banyalHal) : ?>
                      <li class="page-item disabled"><a href="?halaman=<?= $halamanAktif + 1 ?>" class="page-link">Next</a></li>
                    <?php else : ?>
                      <li class="page-item"><a href="?halaman=<?= $halamanAktif + 1 ?>" class="page-link">Next</a></li>
                    <?php endif; ?>
                    <!-- akhir selanjutnya -->
                  </ul>
                </nav>
              </div>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; FahmiCode 2022</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Peringatan</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Apakah Anda Yakin Ingin Keluar?</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="keluar.php">Logout</a>
        </div>
      </div>
    </div>
  </div>
  <!-- bagian modal profil -->
  <div class="modal fade" id="ModalTambah" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Profil <?= $_SESSION['level']; ?></h1>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close"></button>
          <span aria-hidden="true"><b>X</b></span>
        </div>
        <div class="modal-body">
          <form action="murid.php" method="POST">
            <div class="mb-3">
              <label class="form-label">Nama <?= $_SESSION['level']; ?></label>
              <input type="text" class="form-control" value="<?= $_SESSION['nama']; ?>" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Email <?= $_SESSION['level']; ?></label>
              <input type="email" value="<?= $_SESSION['email']; ?>" class="form-control" readonly>
            </div>
            <div class="mb-3">
              <label class="form-label">Level <?= $_SESSION['level']; ?></label>
              <input type="text" class="form-control" value="<?= $_SESSION['level']; ?>" readonly>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" type="button" data-dismiss="modal">Oke</button>
        </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

</body>

</html>