var Chat = {
    ENTER: 13,

    init: function() {
        Chat.scrollDown();

        $("#addClass").click(Chat.open);
        $("#removeClass").click(Chat.close);
        $('#status_message').keyup(Chat.typing);
    },

    open: function() {
        Chat.scrollDown();
        $('#qnimate').addClass('popup-box-on');
    },

    close: function() {
        $('#qnimate').removeClass('popup-box-on');
    },

    typing: function(e) {
        var key_code = e.which;

        if (key_code === Chat.ENTER) {
            Chat.enter();
        }
    },

    enter: function() {
        var template = _.template($('#message-tmpl').html());

        $('.direct-chat-messages').append(template({
            message_date: "October 8th, 2015",
            chat_name: "Java Man",
            image: "/img/me.jpg",
            message: $('#status_message').val(),
            message_time: "3.36 PM",
            chat_lastname: "Ahoo ahoo"
        }));

        $('#status_message').val("");
        Chat.scrollDown();
    },

    scrollDown: function() {
        $('.popup-box .popup-messages').animate({scrollTop: $('.popup-box .popup-messages').prop('scrollHeight')});
    }
};

$(document).ready(Chat.init);