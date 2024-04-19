<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);
?>
<!-- end:: Header -->
<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            <h3 class="kt-subheader__title"><?= $request['group_name'] ?></h3>

            <?php
                $group_id = $this->input->get('group_id', TRUE);
            ?>
            <?php if(has_access('forumgroupcat.create',FALSE)): ?>
            <a href="<?php echo site_url("forum_group_category/create?group_id="). $group_id; ?>" class="btn btn-brand kt-margin-l-10">
                Tambah
            </a>
            <?php endif; ?>
        </div>

    </div>
    <!-- end:: Subheader -->


    <!-- begin:: Content -->
    <div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            ?>

            <div class="col-lg-4">

                <!-- START PORTLET -->
                <div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Group
                            </h3>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <div class="row">
                            <div class="col-12">

                                <div class="list-group">

                                    <?php foreach ($group as $k => $v): ?>

                                    <?php if (is_my_group($v['group_id'])): ?>
                                    <a href="<?php echo site_url('forum_group_category?group_id=').$v['group_id']; ?>" class="list-group-item list-group-item-action <?php echo ($this->input->get('group_id')==$v['group_id'])?'active':NULL; ?>"><?= $v['group_name'] ?></a>
                                    <?php endif; ?>

                                    <?php endforeach; ?>

                                </div>

                            </div>
                        </div>

                    </div>


                </div>
                <!-- END PORTLET -->

            </div>

            <div class="col-lg-8">
                <!--begin::Portlet Struktur-->
                <div class="kt-portlet">

                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                <?= $request['group_name'] ?>
                            </h3>
                        </div>
                    </div>

                    <div class="kt-portlet__body">

                        <form action="<?php echo site_url('forum_group_category/update_tree'); ?>" method="post" accept-charset="utf-8">

                            <?php if(has_access('forumgroupcat.edit',FALSE)): ?>
                            <div class="form-group">
                                <button type="submit" id="saveMenu" class="btn btn-brand btn-sm"><i class="fa fa-network-wired"></i> Simpan Struktur</button>
                            </div>
                            <?php endif; ?>


                            <div id="sideMenu"  class="dd">

                                <?php
                                displayList($list_menu,$actual_link);
                                function displayList($list,$actual_link=NULL) { ?>
                                <ol class="dd-list">
                                    <?php if (!empty($list)): ?>
                                    <?php foreach($list as $item): ?>

                                    <li id="menu-item-<?php echo $item['cat_id']; ?>" class="dd-item" data-id="<?php echo $item["cat_id"]; ?>">

                                        <?php if (!empty($item["child"])): ?>
                                            <button data-action="collapse" type="button" class="fa fa-minus" style="color: #aaa">collapse</button>
                                            <button data-action="expand" type="button" class="fa fa-plus" style="color: #aaa">expand</button>
                                        <?php endif; ?>

                                        <?php
                                            $ids_disable = array(
                                                    165,166,175,167,177,178
                                            );
                                        ?>

                                        <?php if (!in_array($item['cat_id'],$ids_disable)): ?>
                                        <div class="dd-handle bg-light-blue">
                                            <i class="fa fa-ellipsis-v"></i>
                                            <i class="fa fa-ellipsis-v"></i>
                                        </div>
                                        <?php endif; ?>


                                        <p>
                                            <?php echo $item["cat_name"]; ?>
                                            <span class="dd-action">

                                                <?php if ($item['cat_image']): ?>
                                                <img class="mr-4" src="<?php echo URL_MEDIA_IMAGE.$item['cat_image']; ?>" height="24px">
                                                <?php endif; ?>

                                                <?php if ($item['cat_status']=='1'): ?>
                                                <span class="kt-badge kt-badge--success  kt-badge--inline kt-badge--pill mr-5">Active</span>
                                                <?php else: ?>
                                                    <span class="kt-badge kt-badge--dark  kt-badge--inline kt-badge--pill mr-5">Non-Active</span>
                                                <?php endif; ?>

                                                <a class="mr-3 <?= has_access("forumgroupcat.edit",FALSE)?"":"d-none" ?>" href="<?php echo site_url('forum_group_category/edit_picture/').$item['cat_id']; ?>" title="Edit Gambar">
                                                    <i class="la la-picture-o"></i>
                                                </a>
                                                <a class="mr-3 <?= has_access("forumgroupcat.edit",FALSE)?"":"d-none" ?>" href="<?php echo site_url('forum_group_category/edit/').$item['cat_id']; ?>" title="edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a class="text-danger <?= has_access("forumgroupcat.delete",FALSE)?"":"d-none" ?>" href="<?php echo site_url('forum_group_category/delete/').$item['cat_id']; ?>" title="delete">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </span>
                                        </p>
                                        <?php if (!empty($item["child"])): ?>
                                            <?php displayList($item["child"],$actual_link); ?>
                                        <?php endif; ?>
                                    </li>
                                    <?php endforeach; ?>
                                    <?php endif; ?>

                                </ol>
                                <?php } ?>

                            </div>
                            <input type="hidden" name="url_return" value="<?php echo $actual_link; ?>" />
                            <input type="hidden" name="type" value="side-menu" />
                            <textarea hidden name="json_menu" id="tampilJsonSideMenu"></textarea>
                        </form>

                    </div>

                </div>
                <!--end::Portlet Struktur-->
            </div>

        </div>
    </div>
    <!-- end:: Content -->


</div>


<script src="<?php echo base_url('assets'); ?>/vendors/general/jquery.nestable/jquery.nestable.js" type="text/javascript"></script>
<link href="<?php echo base_url('assets'); ?>/vendors/general/jquery.nestable/jquery.nestable.css" rel="stylesheet" type="text/css" />
<script>
    $(function(){
        $('#navMenu').addClass('active');
        $('#sideMenu').nestable({
            maxDepth:3
        });
        $('#tampilJsonSideMenu').html(window.JSON.stringify($('#sideMenu').nestable('serialize')));
        $('#sideMenu').on('change', function() {
            console.log(window.JSON.stringify($('#sideMenu').nestable('serialize')));
            $('#tampilJsonSideMenu').val(window.JSON.stringify($('#sideMenu').nestable('serialize')));
        });
    });
</script>
