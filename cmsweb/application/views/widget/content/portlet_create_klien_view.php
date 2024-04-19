<!-- START PORTLET BIDANG MEMBER -->
<div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title text-primary">
                Klien
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
                        $arr_value =  set_value('klien')?set_value('klien'):array();
                        ?>

                        <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                            <input type="checkbox" id="check-klien-all" <?php echo in_array('all',$arr_value)==TRUE?'checked':''; ?> value="all" name="klien[]" onchange="checkAll(this,'check-klien')"> SEMUA
                            <span></span>
                        </label>

                        <?php foreach($klien as $k => $v): ?>
                            <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                                <input type="checkbox" class="check-klien" <?php echo in_array($v['id'],$arr_value)==TRUE?'checked':''; ?>
                                       value="<?php echo $v['id']; ?>" name="klien[]"> <?php echo $v['nama']; ?>
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