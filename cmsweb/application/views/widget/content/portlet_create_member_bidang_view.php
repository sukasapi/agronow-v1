<!-- START PORTLET BIDANG MEMBER -->
<div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title text-primary">
                Bidang Member
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-group">
                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-pill btn-icon-md" aria-describedby="tooltip_61ygcqyd20"><i class="la la-angle-down"></i></a>

            </div>
        </div>
    </div>

    <div class="kt-portlet__body pt-3 pb-0">

        <div class="row">
            <div class="col-12">

                <div class="form-group">
                    <div class="kt-checkbox-list">
                        <?php
                        $arr_value =  set_value('bidang')?set_value('bidang'):array();
                        ?>

                        <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                            <input type="checkbox" id="check-bidang-all" <?php echo in_array('all',$arr_value)==TRUE?'checked':''; ?> value="all" name="bidang[]" onchange="checkAll(this,'check-bidang')"> SEMUA
                            <span></span>
                        </label>

                        <?php foreach($bidang as $k => $v): ?>
                            <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                                <input type="checkbox" class="check-bidang" <?php echo in_array($v['bidang_id'],$arr_value)==TRUE?'checked':''; ?>
                                       value="<?php echo $v['bidang_id']; ?>" name="bidang[]"> <?php echo $v['bidang_name']; ?>
                                <span></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<!-- END PORTLET BIDANG MEMBER -->