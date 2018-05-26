var Chat2 = {
    ENTER: 13,
    SENDING_MESSAGE_TYPE: "sending-message",
    TYPING_TYPE: "typing",

    init: function() {
        Chat2.scrollDown();

        $("#profile-img").click(Chat2.showStatusOptions);
        $(".expand-button").click(Chat2.expandButton);
        $('#contacts .contact').click(Chat2.selectContact);
        $("#status-options ul li").click(Chat2.selectStatus);
        $('#input-message').keyup(Chat2.typing);
        $('button.submit').click(Chat2.send);

        Chat2.conn = new WebSocket("ws://"+chat.hostname+":"+chat.port+"?auth_id="+chat.auth_id);
        Chat2.conn.onopen = Chat2.onOpen;
        Chat2.conn.onmessage = Chat2.onMessage;
    },

    onOpen: function(e) {
        console.log("Connection established!");
    },

    onMessage: function(e) {
        var parse_data = JSON.parse(e.data);
        var sent_tmpl = _.template($('#message-sent-tmpl').html());
        var replied_tmpl = _.template($('#message-replied-tmpl').html());

        if ($('.messages .no-conversation').length > 0) {
            $('.messages .no-conversation').remove();
        }

        if (parse_data.type === Chat2.SENDING_MESSAGE_TYPE) {
            if (typeof(parse_data.sender) !== "undefined") {
                var sender = parse_data.sender;

                $('.contact[data-id="'+sender.to_user_id+'"] .preview').html('<span>You: </span>' + sender.message);
                $('.messages ul').append(sent_tmpl({'message': sender.message, 'picture': sender.picture}));
            }

            // if receiver online
            if (typeof(parse_data.receiver) !== "undefined") {
                var receiver = parse_data.receiver;

                $('.contact[data-id="'+receiver.from_user_id+'"] .preview').html(receiver.message);
                $('.messages ul').append(replied_tmpl({'message': receiver.message, 'picture': receiver.picture}));
            }
        }

        Chat2.scrollDown();
    },

    showStatusOptions: function() {
        $("#status-options").toggleClass("active");
    },

    expandButton: function() {
        $("#profile").toggleClass("expanded");
        $("#contacts").toggleClass("expanded");
    },

    selectContact: function() {
        var img = $(this).find('.image').attr('src');
        var name = $(this).find('.name').text();

        $('#contacts .contact').removeClass('active');
        $(this).addClass('active');

        $('.contact-profile img').attr('src', img);
        $('.contact-profile p').text(name);
    },

    selectStatus: function() {
        $("#profile-img").removeClass();
        $("#status-online").removeClass("active");
        $("#status-away").removeClass("active");
        $("#status-busy").removeClass("active");
        $("#status-offline").removeClass("active");
        $(this).addClass("active");

        if ($("#status-online").hasClass("active")) {
            $("#profile-img").addClass("online");
        } else if ($("#status-away").hasClass("active")) {
            $("#profile-img").addClass("away");
        } else if ($("#status-busy").hasClass("active")) {
            $("#profile-img").addClass("busy");
        } else if ($("#status-offline").hasClass("active")) {
            $("#profile-img").addClass("offline");
        } else {
            $("#profile-img").removeClass();
        }

        $("#status-options").removeClass("active");
    },

    typing: function(e) {
        var key_code = e.which;

        if (key_code === Chat2.ENTER) {
            Chat2.send();
        }
    },

    send: function() {
        var to_user_id = $('#contacts .contact.active').data('id');
        var message = $('#input-message').val().trim();

        if (!_.isEmpty(message)) {
            var data = {
                type: Chat2.SENDING_MESSAGE_TYPE,
                // message_date: moment().format("MMMM Do YYYY"),
                // chat_name: chat.first_name,
                to_user_id: to_user_id,
                message: message,
                // message_time: moment().format("hh:mm A"),
                // chat_lastname: chat.last_name
            };

            Chat2.conn.send(JSON.stringify(data));
        }

        $('#input-message').val("");
    },

    scrollDown: function() {
        var bottom = $('.messages').prop('scrollHeight');
        $('.messages').animate({scrollTop: bottom});
    }
};

$(document).ready(Chat2.init);