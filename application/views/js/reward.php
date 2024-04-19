<?php if (isset($reward)): ?>
<div class="modal fade dialogbox" id="rewardModal" data-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-icon text-warning">
                <ion-icon name="star"></ion-icon>
            </div>
            <div class="modal-header">
                <h5 class="modal-title">+ <span id="poin"><?= $reward['poin'] ?></span> poin</h5>
            </div>
            <div class="modal-body">
                Anda mendapatkan poin dari <span id="cause"><?= $reward['cause'] ?></span>.
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn" data-dismiss="modal">CLOSE</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if(isset($show_reward)){ ?>
    <?php if($show_reward){ ?>
        <script type="text/javascript">
            $(function(){
                setTimeout(function(){
                    $('#rewardModal').modal('show');
                },1000)
            })
        </script>
    <?php } ?>
<?php } ?>
<?php endif; ?>
