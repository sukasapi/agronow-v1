<!-- App Header -->
<div class="appHeader text-light" style="background: url('<?=PATH_ASSETS?>icon/login_bg.png'); background-repeat: no-repeat; background-size: 100%; border-top: 0px;">
    <div class="left">
        <a href="javascript:history.back()" class="headerButton">
            <ion-icon name="chevron-back-outline" class="mr-1"></ion-icon>
        </a>
    </div>
    <div class="pageTitle"><?=strtoupper($title)?></div>
</div>
<!-- * App Header -->

<!-- Extra Header -->
<div class="extraHeader">
    <form class="search-form">
        <div class="form-group searchbox">
            <input type="text" class="form-control" placeholder="Search...">
            <i class="input-icon">
                <ion-icon name="search-outline"></ion-icon>
            </i>
        </div>
    </form>
</div>
<!-- * Extra Header -->

<!-- App Capsule -->
<div id="appCapsule" class="extra-header-active full-height">
    <div class="section full">
        <div class="pt-1">
            <ul class="nav nav-tabs style1" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#ebook" role="tab">
                        E-BOOK
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#document" role="tab">
                        DOCUMENT
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#video" role="tab">
                        VIDEO
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#audio" role="tab">
                        AUDIO
                    </a>
                </li>
            </ul>
            <div class="tab-content mt-2">
                <div class="tab-pane fade show active" id="ebook" role="tabpanel">
                    <div class="section full mb-3">
                        <div class="section-title">Latest E-Book</div>
            
                        <div class="carousel-multiple owl-carousel owl-theme">
                            <div class="item">
                                <a href="<?=base_url('learning/book')?>">
                                    <img src="<?=PATH_ASSETS?>img/JPEG_373_Berpikir_dan_Berjiwa_Besar.jpg" alt="alt" class="imaged w-100 p-2">
                                    <p class="text-double text-center" style="color: black;">BERPIKIR DAN BERJIWA BESAR</p>
                                </a>
                            </div>
                            <div class="item">
                                <a href="<?=base_url('learning/book')?>">
                                    <img src="<?=PATH_ASSETS?>img/teknik-budidaya-kelapa-sawit.jpg" alt="alt" class="imaged w-100 p-2">
                                    <p class="text-double text-center" style="color: black;">TEKNOLOGI BUDIDAYA KELAPA SAWIT</p>
                                </a>
                            </div>
                            <div class="item">
                                <a href="<?=base_url('learning/book')?>">
                                    <img src="<?=PATH_ASSETS?>img/cara-cepat-membaca-bahasa-tubuh.jpg" alt="alt" class="imaged w-100 p-2">
                                    <p class="text-double text-center" style="color: black;">CARA CEPAT MEMBACA BAHASA TUBUH</p>
                                </a>
                            </div>
                            <div class="item">
                                <a href="<?=base_url('learning/book')?>">
                                    <img src="<?=PATH_ASSETS?>img/penyerbukan-silang-antar-budaya.jpg" alt="alt" class="imaged w-100 p-2">
                                    <p class="text-double text-center" style="color: black;">PENYERBUKAN SILANG ANTAR BANGSA DAN NEGARA</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="section full mb-3">
                        <div class="wide-block">
                            <div class="section-title">Select Category</div>
                            <div class="d-flex justify-content-center p-1">
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_leadership.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">LEADERSHIP</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_business.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">BUSINESS</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_plantation.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">PLANTATIOON</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_managerial.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">MANAGERIAL</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_processing.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">PROCESSING</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section full mb-3">
                        <div class="section-title">Recommended</div>
            
                        <div class="carousel-multiple owl-carousel owl-theme">
                            <div class="item">
                                <img src="<?=PATH_ASSETS?>img/JPEG_373_Berpikir_dan_Berjiwa_Besar.jpg" alt="alt" class="imaged w-100">
                                <p class="text-double">BERPIKIR DAN BERJIWA BESAR</p>
                            </div>
                            <div class="item">
                                <img src="<?=PATH_ASSETS?>img/teknik-budidaya-kelapa-sawit.jpg" alt="alt" class="imaged w-100">
                                <p class="text-double">TEKNOLOGI BUDIDAYA KELAPA SAWIT</p>
                            </div>
                            <div class="item">
                                <img src="<?=PATH_ASSETS?>img/cara-cepat-membaca-bahasa-tubuh.jpg" alt="alt" class="imaged w-100">
                                <p class="text-double">CARA CEPAT MEMBACA BAHASA TUBUH</p>
                            </div>
                            <div class="item">
                                <img src="<?=PATH_ASSETS?>img/penyerbukan-silang-antar-budaya.jpg" alt="alt" class="imaged w-100">
                                <p class="text-double">PENYERBUKAN SILANG ANTAR BANGSA DAN NEGARA</p>
                            </div>
                        </div>
                    </div>
                    <div class="section full mb-3">
                        <div class="section-title">Last Opened</div>
                        <ul class="listview image-listview media">
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>img/penyerbukan-silang-antar-budaya.jpg" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>PENYERBUKAN SILANG ANTAR BUDAYA</b></p>
                                            <p class="mb-1 text-muted">LEADERSHIP</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>img/cara-cepat-membaca-bahasa-tubuh.jpg" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>CARA CEPAT MENANAM POHON DALAM SATU HARI</b></p>
                                            <p class="mb-1 text-muted">LEADERSHIP</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-pane fade" id="document" role="tabpanel">
                    <div class="section full mb-3">
                        <div class="section-title">Latest Document</div>
            
                        <div class="carousel-multiple owl-carousel owl-theme">
                            <div class="item">
                                <img src="<?=PATH_ASSETS?>icon/dglib_ico_doc.png" alt="alt" class="imaged w-100 p-2">
                                <p class="text-double text-center">BERPIKIR DAN BERJIWA BESAR</p>
                            </div>
                            <div class="item">
                                <img src="<?=PATH_ASSETS?>icon/dglib_ico_doc.png" alt="alt" class="imaged w-100 p-2">
                                <p class="text-double text-center">TEKNOLOGI BUDIDAYA KELAPA SAWIT</p>
                            </div>
                            <div class="item">
                                <img src="<?=PATH_ASSETS?>icon/dglib_ico_doc.png" alt="alt" class="imaged w-100 p-2">
                                <p class="text-double text-center">CARA CEPATY MEMBACA BAHASA TUBUH</p>
                            </div>
                            <div class="item">
                                <img src="<?=PATH_ASSETS?>icon/dglib_ico_doc.png" alt="alt" class="imaged w-100 p-2">
                                <p class="text-double text-center">PENYERBUKAN SILANG ANTAR BANGSA DAN NEGARA</p>
                            </div>
                        </div>
                    </div>
                    <div class="section full mb-3">
                        <div class="wide-block">
                            <div class="section-title">Select Category</div>
                            <div class="d-flex justify-content-center p-1">
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_leadership.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">LEADERSHIP</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_business.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">BUSINESS</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_plantation.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">PLANTATIOON</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_managerial.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">MANAGERIAL</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_processing.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">PROCESSING</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section full mb-3">
                        <div class="section-title">Recommended</div>
            
                        <ul class="listview image-listview media">
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>icon/dglib_ico_doc.png" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>Penyerbukan Silang Antar Budaya</b></p>
                                            <p class="mb-1 text-muted">Plantation</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>icon/dglib_ico_doc.png" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>Teknik Budidaya Karet</b></p>
                                            <p class="mb-1 text-muted">Plantation</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>icon/dglib_ico_doc.png" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>Pemanfaatan Lahan Gambut</b></p>
                                            <p class="mb-1 text-muted">Plantation</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>icon/dglib_ico_doc.png" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>Budidaya Tebu di Daerah Dataran Sangat Tinggi Sekali</b></p>
                                            <p class="mb-1 text-muted">Plantation</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>icon/dglib_ico_doc.png" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>Teknologi Panen Untuk Mempercepat Balik Modal</b></p>
                                            <p class="mb-1 text-muted">Processing</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="section full mb-3">
                        <div class="section-title">Last Opened</div>
                        <ul class="listview image-listview media">
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>icon/dglib_ico_doc.png" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>Teknik Budidaya Sawit</b></p>
                                            <p class="mb-1 text-muted">Plantation</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>icon/dglib_ico_doc.png" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>Teknik Kagebunshin no Jutsu</b></p>
                                            <p class="mb-1 text-muted">Processing</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-pane fade" id="video" role="tabpanel">
                    <div class="section full mb-3">
                        <div class="section-title">Latest Video</div>
            
                        <div class="carousel-single owl-carousel owl-theme">
                            <div class="item">
                                <div class="card">
                                    <video controls class="card-img-top" preload="none">
                                        <source src="movie.mp4" type="video/mp4">
                                        <source src="movie.ogg" type="video/ogg">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="card-body">
                                        <h6 class="card-title mb-0">Teknik Pengolahan Karet RSS</h6>
                                        <p class="card-text">09 Nov 2019 13:00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="card">
                                    <video controls class="card-img-top" preload="none">
                                        <source src="movie.mp4" type="video/mp4">
                                        <source src="movie.ogg" type="video/ogg">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="card-body">
                                        <h6 class="card-title mb-0">Teknik Pengolahan Karet RSS</h6>
                                        <p class="card-text">09 Nov 2019 13:00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="card">
                                    <video controls class="card-img-top" preload="none">
                                        <source src="movie.mp4" type="video/mp4">
                                        <source src="movie.ogg" type="video/ogg">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="card-body">
                                        <h6 class="card-title mb-0">Teknik Pengolahan Karet RSS</h6>
                                        <p class="card-text">09 Nov 2019 13:00</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section full mb-3">
                        <div class="wide-block">
                            <div class="section-title">Select Category</div>
                            <div class="d-flex justify-content-center p-1">
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_leadership.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">LEADERSHIP</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_business.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">BUSINESS</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_plantation.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">PLANTATIOON</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_managerial.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">MANAGERIAL</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_processing.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">PROCESSING</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section full mb-3">
                        <div class="section-title">Recommended</div>
            
                        <div class="carousel-multiple owl-carousel owl-theme">
                            <div class="item">
                                <div class="card">
                                    <video controls class="card-img-top" preload="none">
                                        <source src="movie.mp4" type="video/mp4">
                                        <source src="movie.ogg" type="video/ogg">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="card-body">
                                        <p class="card-text mb-0" style="font-size: medium;"><b>Kesesuaian Lahan Karet</b></p>
                                        <p class="card-text" style="font-size: smaller;">09 Nov 2019 13:00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="card">
                                    <video controls class="card-img-top" preload="none">
                                        <source src="movie.mp4" type="video/mp4">
                                        <source src="movie.ogg" type="video/ogg">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="card-body">
                                        <p class="card-text mb-0" style="font-size: medium;"><b>Kesesuaian Lahan Tebu</b></p>
                                        <p class="card-text" style="font-size: smaller;">09 Nov 2019 13:00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="card">
                                    <video controls class="card-img-top" preload="none">
                                        <source src="movie.mp4" type="video/mp4">
                                        <source src="movie.ogg" type="video/ogg">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="card-body">
                                        <p class="card-text mb-0" style="font-size: medium;"><b>Kesesuaian Lahan Sawit</b></p>
                                        <p class="card-text" style="font-size: smaller;">09 Nov 2019 13:00</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section full mb-3">
                        <div class="section-title">Last Played</div>
                        <div class="carousel-single owl-carousel owl-theme">
                            <div class="item">
                                <div class="card">
                                    <video controls class="card-img-top" preload="none">
                                        <source src="movie.mp4" type="video/mp4">
                                        <source src="movie.ogg" type="video/ogg">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="card-body">
                                        <h6 class="card-title mb-0">Teknik Pengolahan Karet RSS</h6>
                                        <p class="card-text">09 Nov 2019 13:00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="card">
                                    <video controls class="card-img-top" preload="none">
                                        <source src="movie.mp4" type="video/mp4">
                                        <source src="movie.ogg" type="video/ogg">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="card-body">
                                        <h6 class="card-title mb-0">Teknik Pengolahan Karet RSS</h6>
                                        <p class="card-text">09 Nov 2019 13:00</p>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="card">
                                    <video controls class="card-img-top" preload="none">
                                        <source src="movie.mp4" type="video/mp4">
                                        <source src="movie.ogg" type="video/ogg">
                                        Your browser does not support the video tag.
                                    </video>
                                    <div class="card-body">
                                        <h6 class="card-title mb-0">Teknik Pengolahan Karet RSS</h6>
                                        <p class="card-text">09 Nov 2019 13:00</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="audio" role="tabpanel">
                    <div class="section full mb-3">
                        <div class="section-title">Latest Document</div>
            
                        <div class="carousel-multiple owl-carousel owl-theme">
                            <div class="item">
                                <img src="<?=PATH_ASSETS?>icon/dglib_ico_audio.png" alt="alt" class="imaged w-100 p-2">
                                <p class="text-double text-center">BERPIKIR DAN BERJIWA BESAR</p>
                            </div>
                            <div class="item">
                                <img src="<?=PATH_ASSETS?>icon/dglib_ico_audio.png" alt="alt" class="imaged w-100 p-2">
                                <p class="text-double text-center">TEKNOLOGI BUDIDAYA KELAPA SAWIT</p>
                            </div>
                            <div class="item">
                                <img src="<?=PATH_ASSETS?>icon/dglib_ico_audio.png" alt="alt" class="imaged w-100 p-2">
                                <p class="text-double text-center">CARA CEPATY MEMBACA BAHASA TUBUH</p>
                            </div>
                            <div class="item">
                                <img src="<?=PATH_ASSETS?>icon/dglib_ico_audio.png" alt="alt" class="imaged w-100 p-2">
                                <p class="text-double text-center">PENYERBUKAN SILANG ANTAR BANGSA DAN NEGARA</p>
                            </div>
                        </div>
                    </div>
                    <div class="section full mb-3">
                        <div class="wide-block">
                            <div class="section-title">Select Category</div>
                            <div class="d-flex justify-content-center p-1">
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_leadership.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">LEADERSHIP</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_business.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">BUSINESS</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_plantation.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">PLANTATIOON</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_managerial.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">MANAGERIAL</p>
                                </div>
                                <div class="text-center p-1">
                                    <img src="<?=PATH_ASSETS?>icon/dglib_ico_processing.png" alt="image" class="imaged w48 rounded">
                                    <p class="m-0" style="font-size: 10px;">PROCESSING</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="section full mb-3">
                        <div class="section-title">Recommended</div>
            
                        <ul class="listview image-listview media">
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>icon/dglib_ico_audio.png" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>Penyerbukan Silang Antar Budaya</b></p>
                                            <p class="mb-1 text-muted">Plantation</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>icon/dglib_ico_audio.png" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>Teknik Budidaya Karet</b></p>
                                            <p class="mb-1 text-muted">Plantation</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>icon/dglib_ico_audio.png" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>Pemanfaatan Lahan Gambut</b></p>
                                            <p class="mb-1 text-muted">Plantation</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>icon/dglib_ico_audio.png" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>Budidaya Tebu di Daerah Dataran Sangat Tinggi Sekali</b></p>
                                            <p class="mb-1 text-muted">Plantation</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>icon/dglib_ico_audio.png" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>Teknologi Panen Untuk Mempercepat Balik Modal</b></p>
                                            <p class="mb-1 text-muted">Processing</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="section full mb-3">
                        <div class="section-title">Last Opened</div>
                        <ul class="listview image-listview media">
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>icon/dglib_ico_audio.png" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>Teknik Budidaya Sawit</b></p>
                                            <p class="mb-1 text-muted">Plantation</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="item">
                                    <div class="imageWrapper">
                                        <img src="<?=PATH_ASSETS?>icon/dglib_ico_audio.png" alt="image" class="imaged w64">
                                    </div>
                                    <div class="in">
                                        <div>
                                            <p class="mb-1 text-double" style="color: black; font-size: larger;"><b>Teknik Kagebunshin no Jutsu</b></p>
                                            <p class="mb-1 text-muted">Processing</p>
                                            <p class="mb-0" style="color: gray; font-size: small;">23 Oktober 2019</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- * App Capsule -->