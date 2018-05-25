var Chat = {
    ENTER: 13,

    init: function() {
        Chat.scrollDown();

        $("#addClass").click(Chat.toggle);
        $("#removeClass").click(Chat.close);
        $('#status_message').keyup(Chat.typing);
    },

    toggle: function() {
        if ($('#qnimate').hasClass('popup-box-on')) {
            Chat.close();
        } else {
            Chat.open();
        }
    },

    open: function() {
        $('#qnimate').addClass('popup-box-on');
        Chat.scrollDown();
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
        var message = $('#status_message').val().trim();

        if (!_.isEmpty(message)) {
            $('.direct-chat-messages').append(template({
                message_date: moment().format("MMMM Do YYYY"),
                chat_name: "Java Man",
                image: "/img/me.jpg",
                message: message,
                message_time: moment().format("hh:mm A"),
                chat_lastname: "Ahoo ahoo"
            }));

            Chat.scrollDown();
        }

        $('#status_message').val("");
    },

    scrollDown: function() {
        $('.popup-box .popup-messages').animate({scrollTop: $('.popup-box .popup-messages').prop('scrollHeight')});
    }
};

$(document).ready(Chat.init);