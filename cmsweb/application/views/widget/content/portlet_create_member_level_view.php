<!-- START PORTLET MEMBER LEVEL -->
<div class="kt-portlet kt-portlet--head-sm" data-ktportlet="true">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title text-primary">
                Level Member
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
                        $arr_value =  set_value('member_level')?set_value('member_level'):array();
                        ?>

                        <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                            <input type="checkbox" id="check-mlevel-all" <?php echo in_array('all',$arr_value)==TRUE?'checked':''; ?> value="all" name="member_level[]" onchange="checkAll(this,'check-mlevel')"> SEMUA
                            <span></span>
                        </label>
                        <?php foreach($member_level as $k => $v): ?>
                            <label class="kt-checkbox kt-checkbox--tick kt-checkbox--brand">
                                <input type="checkbox" class="check-mlevel" <?php echo in_array($k,$arr_value)==TRUE?'checked':''; ?>  value="<?php echo $v['mlevel_id']; ?>" name="member_level[]"> <?php echo $v['mlevel_name']; ?>
                                <span></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<!-- END PORTLET MEMBER LEVEL -->