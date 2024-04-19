<?php $this->load->view('learning/app_header'); ?>

<!-- App Capsule -->
<div id="appCapsule">
    <div class="section full">
        <div class="m-2">
            <?php 
                // $dummy_desc = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat';

                $menus = [];
                $menus[] = [
                    'name' => 'My Classroom',
                    'url' => base_url('learning/class_room/my_classroom'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_class.png',
                    'desc' => 'Classroom yang anda punya',
                ]; 

                $menus[] = [
                    'name' => 'Available Classroom',
                    'url' => base_url('learning/class_room/buy_classroom'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_class.png',
                    'desc' => 'Classroom yang dapat dibeli',
                ];
				
				$menus[] = [
                    'name' => 'Evaluasi Pelatihan Level 3',
                    'url' => base_url('learning/class_room/evaluasi_lv3'),
                    'icon' => PATH_ASSETS.'icon/learningroom_ico_class.png',
                    'desc' => 'Evaluasi pelatihan level 3',
                ];
				
                if(count((array)$pa) > 0){
                    if($ispk=="ya"){
                        $menus[] = [
                            'name' => 'Project Assignment',
                            'url' => base_url('learning/class_room/project_assignment_pk'),
                            'icon' => PATH_ASSETS.'icon/learningroom_ico_class.png',
                            'desc' => 'Project Assignment',
                        ];
                    }else{
                        $menus[] = [
                            'name' => 'Project Assignment',
                            'url' => base_url('learning/class_room/project_assignment_atasan'),
                            'icon' => PATH_ASSETS.'icon/learningroom_ico_class.png',
                            'desc' => 'Project Assignment',
                        ];
                    }
                   
                }
               
            ?>
            <?php foreach ($menus as $menu) { ?>
                <div class="card mb-4">
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
            <?php } ?>
        </div>
    </div>
</div>
<!-- # App Capsule -->