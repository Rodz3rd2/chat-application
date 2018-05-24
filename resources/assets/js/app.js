var Chat = {
    init: function() {
        Chat.scrollDown();

        $("#addClass").click(function() {
            Chat.scrollDown();
            $('#qnimate').addClass('popup-box-on');
        });

        $("#removeClass").click(function() {
            $('#qnimate').removeClass('popup-box-on');
        });
    },

    scrollDown: function() {
        $('.popup-box .popup-messages').animate({scrollTop: $('.popup-box .popup-messages').prop('scrollHeight')});
    }
};

$(document).ready(Chat.init);