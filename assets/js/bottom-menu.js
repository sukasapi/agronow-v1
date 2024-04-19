const mAccount = $('#mAccount');
const mPortal = $('#mPortal');
const mHome = $('#mHome');
const mLearning = $('#mLearning');
const mWhatsNew = $('#mWhatsNew');

function clear_active(){
    mPortal.removeClass('active');
    mLearning.removeClass('active');
    mHome.removeClass('active');
    mWhatsNew.removeClass('active');
    mAccount.removeClass('active');
}
$(document).ready(function(){
    mPortal.on('click', function () {
        clear_active();
        mPortal.addClass('active');
    });
    mLearning.on('click', function () {
        clear_active();
        mLearning.addClass('active');
    });
    mHome.on('click', function () {
        clear_active();
        mHome.addClass('active');
    });
    mWhatsNew.on('click', function () {
        clear_active();
        mWhatsNew.addClass('active');
    });
    mAccount.on('click', function () {
        clear_active();
        mAccount.addClass('active');
    });
});