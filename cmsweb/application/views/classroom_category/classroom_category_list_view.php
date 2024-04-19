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

            <?php if(has_access('classroomcat.create',FALSE)): ?>
            <a href="<?php echo site_url("classroom_category/create/"); ?>" class="btn btn-brand kt-margin-l-10">
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

            <div class="col-lg-12">

                <!--begin::Portlet Struktur-->
                <div class="kt-portlet">
                    <div class="kt-portlet__body">

                        <form action="<?php echo site_url('classroom_category/update_tree'); ?>" method="post" accept-charset="utf-8">

                            <?php if(has_access('classroomcat.edit',FALSE)): ?>
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

                                        <div class="dd-handle bg-light-blue">
                                            <i class="fa fa-ellipsis-v"></i>
                                            <i class="fa fa-ellipsis-v"></i>
                                        </div>
                                        <p>
                                            <?php echo $item["cat_name"]; ?>
                                            <span class="dd-action">
                                                <?php if ($item['cat_status']=='1'): ?>
                                                <span class="kt-badge kt-badge--success  kt-badge--inline kt-badge--pill mr-5">Active</span>
                                                <?php else: ?>
                                                    <span class="kt-badge kt-badge--dark  kt-badge--inline kt-badge--pill mr-5">Non-Active</span>
                                                <?php endif; ?>
                                                <!--<a href="<?php /*echo site_url('classroom_category/create').'?parent='.$item['cat_id']; */?>" title="add child" class=" <?/*= has_access('classroomcat.create',FALSE)?'':'d-none' */?>">
                                                    <i class="fa fa-plus"></i>
                                                </a>-->
                                                <a href="<?php echo site_url('classroom_category/edit/').$item['cat_id']; ?>" title="edit" class=" <?= has_access('classroomcat.edit',FALSE)?'':'d-none' ?>">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a class="text-danger  <?= has_access('classroomcat.delete',FALSE)?'':'d-none' ?>" href="<?php echo site_url('classroom_category/delete/').$item['cat_id']; ?>" title="delete">
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
            maxDepth:2
        });
        $('#tampilJsonSideMenu').html(window.JSON.stringify($('#sideMenu').nestable('serialize')));
        $('#sideMenu').on('change', function() {
            console.log(window.JSON.stringify($('#sideMenu').nestable('serialize')));
            $('#tampilJsonSideMenu').val(window.JSON.stringify($('#sideMenu').nestable('serialize')));
        });
    });
</script>
