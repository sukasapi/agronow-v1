<?php $this->load->view('learning/app_header'); ?>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
		<div class="lw_bg_hijau">
			<table class="table table-sm text-light">
				<tr>
					<td style="width:20%">Nama</td>
					<td><?=$this->session->userdata('member_name')?></td>
				</tr>
				<tr>
					<td>NIK</td>
					<td><?=$this->session->userdata('member_nip')?></td>
				</tr>
				<tr>
					<td>Entitas</td>
					<td><?=$this->session->userdata('member_group')?></td>
				</tr>
			</table>
		</div>
	
        <div class="m-2">
            <?php 
                $menus = [];
                
				$menus[] = [
					'menu_class' => '',
                    'icon_class' => 'w64',
					'name' => 'My Classroom',
                    'url' => base_url('learning/class_room/my_classroom'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_class.png',
                    'desc' => 'Classroom yang Anda punya',
                ];
				
				$menus[] = [
					'menu_class' => '',
                    'icon_class' => 'w64',
					'name' => 'Individual Report',
                    'url' => base_url('learning/individual_report'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_report.png',
                    'desc' => 'Laporan pembelajaran Anda',
                ];
				
				$menus[] = [
					'menu_class' => '',
                    'icon_class' => 'w64',
					'name' => 'Edit Profile',
                    'url' => base_url('account/profile'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_attendance.png',
                    'desc' => 'edit profile',
                ];

                $menus[] = [
					'menu_class' => 'pl-2 pr-1',
					'icon_class' => 'w48',
                    'name' => 'Logout',
                    'url' => base_url('logout'),
                    'icon' => PATH_ASSETS.'icon/account_ico_logout.png',
                    'desc' => '&nbsp;',
                ];
            ?>
            <?php foreach ($menus as $menu) { ?>
                <div class="card mb-2">
                    <a href="<?=$menu['url']?>">
                        <div class="d-flex p-1">
                            <div class="align-self-center <?=$menu['menu_class']?>">
                                <img src="<?=$menu['icon']?>" alt="image" class="imaged <?=$menu['icon_class']?>">
                            </div>
                            <div class="align-self-center ml-1">
                                <p class="mb-1" style="color: black; font-size: larger;"><b><?= $menu['name'] ?></b></p>
                                <p class="mb-0 text-triple" style="color: gray; font-size: small;"><b><?= $menu['desc'] ?></b></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- # App Capsule -->