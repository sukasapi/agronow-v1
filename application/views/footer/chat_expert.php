<!-- chat footer -->
<div class="chatFooter">
    <form id="chatForm" action="<?=base_url('learning/expert_directory/add_chat/'.$data['expert_id'])?>" autocomplete="off" class="needs-validation" novalidate enctype="multipart/form-data" method="post" accept-charset="utf-8">
        <button type="button" id="imgBtn" class="btn btn-icon btn-secondary rounded" data-toggle="modal" data-target="#attachFile">
            <ion-icon name="image"></ion-icon>
        </button>
        <div class="form-group boxed">
            <div class="input-wrapper">
                <input type="hidden" name="em_id" value="<?= $data['expert_member']['em_id']; ?>">
                <input type="text" class="form-control" name="desc" placeholder="Type a message..." onkeyup="stoppedTyping()">
                <i class="clear-input">
                    <ion-icon name="close-circle"></ion-icon>
                </i>
            </div>
        </div>
        <button type="submit" id="sendBtn" class="btn btn-icon btn-warning rounded" disabled>
            <ion-icon name="send"></ion-icon>
        </button>
    </form>
</div>
<!-- * chat footer-->
<!-- Dialog Form-->
<div class="modal fade dialogbox" id="attachFile" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Gambar</h5>
            </div>
                <div class="modal-body text-left mb-2">
                    <div class="custom-file-upload">
                        <input form="chatForm" type="file" id="fileUploadInput" name="chat_image" accept=".png, .jpg, .jpeg">
                        <label id="labelFileUploadInput" for="fileUploadInput">
                            <span>
                                <strong>
                                    <ion-icon name="cloud-upload-outline"></ion-icon>
                                    <i>Tap to Upload</i>
                                </strong>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <button type="button" onclick="cancelImage()" class="btn btn-text-secondary" data-dismiss="modal">CANCEL</button>
                        <button type="button" onclick="confirmImage()" class="btn btn-text-primary" data-dismiss="modal">OK</button>
                    </div>
                </div>
        </div>
    </div>
</div>
<!-- * Dialog Form -->
<script>
    function stoppedTyping() {
        if (document.getElementsByName("desc")[0].value.length > 0){
            document.getElementById("sendBtn").disabled = false;
        } else {
            if (document.getElementById("fileUploadInput").value === ""){
                document.getElementById("sendBtn").disabled = true;
            }
        }
    }

    function confirmImage() {
        if (document.getElementById("fileUploadInput").value !== ""){
            document.getElementById("imgBtn").className = "btn btn-icon btn-success rounded";
            document.getElementById("sendBtn").disabled = false;
        }
    }
    function cancelImage() {
        document.getElementById("fileUploadInput").value = "";
        document.getElementById("labelFileUploadInput").classList.remove("file-uploaded");
        document.getElementById("labelFileUploadInput").style.removeProperty("background-image");
        document.getElementById("labelFileUploadInput").childNodes[1].textContent = 'Tap to Upload';
        document.getElementById("imgBtn").className = "btn btn-icon btn-secondary rounded";
        if (document.getElementsByName("desc")[0].value === ""){
            document.getElementById("sendBtn").disabled = true;
        }
    }
</script>
