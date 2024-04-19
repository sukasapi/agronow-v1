<?php $this->load->view('application_header', ['header_title' => 'Learning Room', 'header_type' => 2]); ?>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
		<div class="p-3">
            <?php 
                $dummy_desc = '-';

                $menus = [];
				
				$menus[] = [
                    'name' => 'AgroWallet',
                    'url' => base_url('learning/wallet/beranda'),
                    'icon' => PATH_ASSETS.'icon/lw_.png',
                    'desc' => isset($data['Learning Wallet']) ? $data['Learning Wallet'] : $dummy_desc,
                ];
				
                $menus[] = [
                    'name' => 'Digital Library',
                    'url' => base_url('learning/digital_library'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_digilib.png',
                    'desc' => isset($data['Digital Library']) ? $data['Digital Library'] : $dummy_desc,
                ];

       
                $menus[] = [
                    'name' => 'Corporate Culture',
                    'url' => base_url('learning/digital_library?category=corporate-culture'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_corporatecltr.png',
                    'desc' => '',//isset($data['Corporate Culture']) ? $data['Corporate Culture'] : $dummy_desc,
                ];

                $menus[] = [
                    'name' => 'Class Room',
                    'url' => base_url('learning/class_room'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_class.png',
                    'desc' => isset($data['Class Room']) ? $data['Class Room'] : $dummy_desc,
                ];

                $menus[] = [
                    'name' => 'Kompetensi',
                    'url' => base_url('learning/kompetensi'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_class.png',
                    'desc' => isset($data['Kompetensi']) ? $data['Kompetensi'] : $dummy_desc,
                ];

                $menus[] = [
                    'name' => 'Expert Directory',
                    'url' => base_url('learning/expert_directory'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_exprtdir.png',
                    'desc' => isset($data['Expert Directory']) ? $data['Expert Directory'] : $dummy_desc,
                ];

                $menus[] = [
                    'name' => 'Knowledge Management',
                    'url' => base_url('learning/knowledge_sharing'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_knwldgshr.png',
                    'desc' => isset($data['Knowledge Management']) ? $data['Knowledge Management'] : $dummy_desc,
                ];

                $menus[] = [
                    'name' => 'Forum',
                    'url' => base_url('learning/forum'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_forum.png',
                    'desc' => isset($data['Forum']) ? $data['Forum'] : $dummy_desc,
                ];

                $menus[] = [
                    'name' => 'Attendance',
                    'url' => base_url('learning/attendance'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_attendance.png',
                    'desc' => isset($data['Attendance']) ? $data['Attendance'] : $dummy_desc,
                ];

                $menus[] = [
                    'name' => 'Individual Report',
                    'url' => base_url('learning/individual_report'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_report.png',
                    'desc' => isset($data['Individual Report']) ? $data['Individual Report'] : $dummy_desc,
                ];

                $menus[] = [
                    'name' => 'Glosarium',
                    'url' => base_url('learning/glosarium'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_digilib.png',
                    'desc' => isset($data['Glosarium']) ? $data['Glosarium'] : $dummy_desc,
                ];
            ?>
            <?php foreach ($menus as $menu) { ?>
                <div class="card">
                    <a href="<?=$menu['url']?>">
                        <div class="d-flex p-1">
                            <div class="align-self-center">
                                <img src="<?=$menu['icon']?>" alt="image" class="imaged w86">
                            </div>
                            <div class="align-self-center ml-1">
                                <p class="mb-1" style="color: black; font-size: larger;"><b><?= $menu['name'] ?></b></p>
                                <p class="mb-0 text-triple" style="color: gray; font-size: small;"><b><?= $menu['desc'] ?></b></p>
                            </div>
                        </div>
                    </a>
                </div>
                <br/>
            <?php } ?>
        </div>
    </div>
</div>