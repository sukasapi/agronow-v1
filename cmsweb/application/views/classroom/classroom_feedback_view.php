<?php
$actual_link = site_url(uri_string());
$actual_link = $_SERVER['QUERY_STRING'] ? $actual_link.'?'.$_SERVER['QUERY_STRING'] : $actual_link;
$actual_link = urlencode($actual_link);
?>

<div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor">


    <!-- begin:: Subheader -->
    <div class="kt-subheader kt-grid__item" id="kt_subheader">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title"><?php echo $page_sub_name; ?></h3>

        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
                <a href="<?php echo site_url("classroom"); ?>" class="btn kt-subheader__btn-primary">
                    <i class="flaticon2-back"></i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
    <!-- end:: Subheader -->


    <div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
        <div class="row">

            <?php
            $this->load->view('flash_notif_view');
            $this->load->view('validation_notif_view');
            ?>

            <!-- Navigation -->
            <?php
                $submenu_data = $classroom;
                $this->load->view(@$submenu,$submenu_data);
            ?>

            <div class="col-lg-9">
                <!-- START PORTLET KONTEN -->
                <?php
                $attributes = array('autocomplete'=>"off");
                echo form_open($form_action, $attributes);
                ?>
                <input autocomplete="false" name="hidden" type="text" style="display:none;">

                <input type="hidden" name="cr_id" value="<?= $classroom['cr_id'] ?>" >

                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title text-primary">
                                Feedback
                            </h3>
                        </div>

                        <div class="kt-portlet__head-toolbar">
                            <div class="kt-portlet__head-actions">
                                <a href="<?= site_url('classroom/feedback_export/'.$classroom['cr_id']) ?>" class="btn btn-outline-brand btn-bold btn-sm">
                                    <i class="fa fa-download"></i> Export
                                </a>
                            </div>
                        </div>

                    </div>

                    <div class="kt-portlet__body">
                        <div class="row">

                            <div class="col-12">
                                <label>Keterangan</label>
                                <textarea id="content" name="Desc" style="min-height: 400px" class="form-control"><?php
                                    if (validation_errors()) {echo set_value('Desc');}else{echo isset($request) ? $request['Desc'] : '';} ?></textarea>

                            </div>


                        </div>
                    </div>

                    <div class="kt-portlet__foot <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                        <div class="kt-form__actions kt-form__actions--solid">
                            <div class="row <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-success pl-5 pr-5">Simpan</button>
                                    <a href="<?php echo site_url('classroom/feedback/').$classroom['cr_id']; ?>" class="btn btn-warning pull-right pl-5 pr-5">Batal</a>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <?php echo form_close(); ?>
                <!-- END PORTLET KONTEN -->


                <!-- START PORTLET QUESTION -->
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Pertanyaan
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                            <div class="kt-portlet__head-actions">
                                <a href="<?php echo site_url('classroom/feedback_add/'.$classroom['cr_id']); ?>" class="btn btn-outline-info btn-sm  btn-icon-md <?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
                                    <i class="flaticon2-plus"></i> Tambah
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="kt-portlet__body">


                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th width="50%">Pertanyaan</th>
                                <th>Type</th>
                                <th width="120px"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($request['Question'])): ?>
                            <?php foreach ($request['Question'] as $k => $v): ?>
                                <tr>
                                    <td><?= $v ?></td>
                                    <td>
                                        <?php
                                        if ($request['Type'][$k]=='pilihan'){echo 'PILIHAN';}
                                        if ($request['Type'][$k]=='text'){echo 'TEXT';}
                                        ?>
                                    </td>
                                    <td>										<div class="<?= is_classroom_editable($classroom['cr_id'])?'':'d-none' ?>">
											<a href="<?php echo site_url('classroom/feedback_edit/'.$classroom['cr_id']).'/'.$k; ?>" class="btn btn-outline-info btn-sm ">
												Edit
											</a>

											<a href="<?php echo site_url('classroom/feedback_delete/'.$classroom['cr_id']).'/'.$k; ?>" class="btn btn-outline-danger btn-sm btn-icon ml-2" onclick="return confirm('Anda yakin menghapus Pertanyaan?')" title="Hapus">
												<i class="fa fa-trash-alt"></i>
											</a>										</div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>

                        </table>


                    </div>
                </div>
                <!-- END PORTLET QUESTION -->


            </div>


        </div>
    </div>

</div>




<script type="text/javascript">
    // Prevent Leave Page
    var formHasChanged = false;
    var submitted = false;

    $(document).on('change', 'input,select,textarea', function(e) {
        formHasChanged = true;
    });

    $(document).ready(function() {
        window.onbeforeunload = function(e) {
            if (formHasChanged && !submitted) {
                var message = "You have not saved your changes.",
                    e = e || window.event;
                if (e) {
                    e.returnValue = message;
                }
                return message;
            }
        }
        $("form").submit(function() {
            submitted = true;

            // submit more than once return false
            $(this).submit(function() {
                return false;
            });

            // submit once return true
            return true;
        });

    });
</script>

