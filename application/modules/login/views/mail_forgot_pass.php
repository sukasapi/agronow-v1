<style type='text/css'>
    <!--
    .style1 {font:13px/1.5 'Verdana';}
    .style2 {color: #FF6600}
    .style3 {font:20px/1.5 'Verdana'; }
    -->
</style>


<p class='style1'>
    Hai <?= html_entity_decode($member_name); ?>,
</p>
<p class='style1'>
    Kami telah menerima permintaanmu untuk melakukan reset password.<br />
    Silakan klik tautan berikut:</p>

<p><div class='style3'><strong><?= $reset_link; ?></strong></div></p>

<p class='style1'>Tautan di atas berlaku selama 3 jam.</p>

Abaikan e-mail ini jika kamu tidak pernah meminta untuk melakukan reset password.