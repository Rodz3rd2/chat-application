var Chat = {
    ENTER: 13,

    init: function() {
        Chat.scrollDown();
        $('#qnimate .popup-messages').slimScroll({height: 305, animate: true});

        $("#addClass").click(Chat.toggle);
        $("#removeClass").click(Chat.close);
        $('#status_message').keyup(Chat.typing);

        Chat.conn = new WebSocket("ws://"+chat.hostname+":"+chat.port);
        Chat.conn.onopen = Chat.onOpen;
        Chat.conn.onmessage = Chat.onMessage;
    },

    onOpen: function(e) {
        console.log("Connection established!");
    },

    onMessage: function(e) {
        var template = _.template($('#message-tmpl').html());
        var parse_data = JSON.parse(e.data);

        Chat.message(parse_data);
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
        var message = $('#status_message').val().trim();

        if (!_.isEmpty(message)) {
            var data = {
                message_date: moment().format("MMMM Do YYYY"),
                chat_name: window.chat.first_name,
                image: window.chat.picture,
                message: message,
                message_time: moment().format("hh:mm A"),
                chat_lastname: window.chat.last_name
            };

            Chat.message(data);
            Chat.conn.send(JSON.stringify(data));
        }

        $('#status_message').val("");
    },

    message: function(data) {
        var template = _.template($('#message-tmpl').html());

        if ($('.direct-chat-messages .no-conversation').length > 0) {
            $('.direct-chat-messages .no-conversation').remove();
        }

        $('.direct-chat-messages').append(template(data));
        Chat.scrollDown();
    },

    scrollDown: function() {
        var bottom = $('.popup-box .popup-messages').prop('scrollHeight');

        $('.popup-box .popup-messages').animate({scrollTop: bottom});
        $('#qnimate .popup-messages').slimScroll({'scrollTo': bottom});
    }
};

$(document).ready(Chat.init);