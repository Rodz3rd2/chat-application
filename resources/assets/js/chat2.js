var Chat2 = {
    ENTER: 13,

    typing_delay: "",
    is_initial_typing: true,

    init: function() {
        Chat2.scrollDown();

        $("#profile-img").click(Chat2.showStatusOptions);
        $(".expand-button").click(Chat2.expandButton);
        $('#contacts').on("click", ".contact", Chat2.selectContact);
        $('#input-message').keyup(Chat2.typing);
        $('button.submit').click(Chat2.send);
        $('.messages').scroll(Chat2.loadMoreMessages);

        Chat2Events.init();
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
        var selected_contact = $('.contact-profile :input[name="selected-user-id"]').val();

        $('#contacts .contact').removeClass('active');
        $(this).addClass('active');
        var sender_id = $('#contacts .contact.active').data('id');

        console.log(selected_contact, sender_id);

        if (selected_contact != sender_id) {
            $('.contact-profile img').attr('src', img);
            $('.contact-profile p').text(name);
            $('.contact-profile :input[name="selected-user-id"]').val(sender_id);

            var data = {
                event: Chat2Events.ON_FETCH_MESSAGES,
                sender_id: sender_id
            };

            Chat2Events.send(data);
        } else {
            console.log(selected_contact, sender_id);
        }
    },

    typing: function(e) {
        var receiver_id = $('#contacts .contact.active').data('id');
        var key_code = e.which;
        var data = {receiver_id: receiver_id};

        // initial typing
        if (Chat2.is_initial_typing) {
            Chat2.is_initial_typing = false;

            Chat2Events.send($.extend({event: Chat2Events.ON_TYPING}, data));
        }

        // when stop typing
        clearTimeout(Chat2.typing_delay);
        Chat2.typing_delay = _.delay(function() {
            Chat2.is_initial_typing = true;

            Chat2Events.send($.extend({event: Chat2Events.ON_STOP_TYPING}, data));
        }, Chat2Events.typing_delay);

        if (key_code === Chat2.ENTER) {
            Chat2.send();
        }
    },

    send: function() {
        var receiver_id = $('#contacts .contact.active').data('id');
        var message = $('#input-message').val().trim();

        if (!_.isEmpty(message)) {
            var data = {
                event: Chat2Events.ON_SEND_MESSAGE,
                // message_date: moment().format("MMMM Do YYYY"),
                // chat_name: chat.first_name,
                receiver_id: receiver_id,
                message: message,
                // message_time: moment().format("hh:mm A"),
                // chat_lastname: chat.last_name
            };

            Chat2Events.send(data);
        }

        $('#input-message').val("");
    },

    loadMoreMessages: function() {
        if ($(this).scrollTop() === 0 && Chat2Events.load_more_increment !== -1) {
            var sender_id = $('#contacts .contact.active').data('id');
            var data = {
                event: Chat2Events.ON_LOAD_MORE_MESSAGES,
                sender_id: sender_id,
                load_more_increment: ++Chat2Events.load_more_increment
            };

            Chat2Events.send(data);
        }
    },

    scrollDown: function() {
        var bottom = $('.messages').prop('scrollHeight');
        $('.messages').animate({scrollTop: bottom});
    }
};

var Chat2Events = {
    ON_CONNECTION_ESTABLISH: "onConnectionEstablish",
    ON_DISCONNECT: "onDisconnect",
    ON_SEND_MESSAGE: "onSendMessage",
    ON_TYPING: "onTyping",
    ON_STOP_TYPING: "onStopTyping",
    ON_READ_MESSAGE: "onReadMessage",
    ON_FETCH_MESSAGES: "onFetchMessages",
    ON_LOAD_MORE_MESSAGES: "onLoadMoreMessages",

    typing_delay: 3000, // 3 seconds
    load_more_increment: 0,

    init: function() {
        Chat2Events.conn = new WebSocket("ws://"+chat.hostname+":"+chat.port+"?auth_id="+chat.auth_id);
        Chat2Events.conn.onopen = Chat2Events.onOpen;
        Chat2Events.conn.onmessage = Chat2Events.onMessage;
    },

    /**
     * Event Handler
     */
    send: function(data, errorCallback) {
        if (Chat2Events.conn.readyState === 1) { // open state
            Chat2Events.conn.send(JSON.stringify(data));
        } else {
            if (typeof(errorCallback) !== "undefined") {
                errorCallback();
            } else {
                console.log("The server is disconnect.");
            }
        }
    },

    /**
     * Event Listener
     */
    onOpen: function(e) {
        console.log("Connection established!");
        Chat2Events.send({event: Chat2Events.ON_CONNECTION_ESTABLISH});
    },

    onMessage: function(e) {
        var parse_data = JSON.parse(e.data);

        var event = parse_data.event;
        delete parse_data.event;

        console.log(event, parse_data);

        Chat2Events[event](parse_data);
    },

    onConnectionEstablish: function(data) {
        if (data.result) {
            var contact_status = $('.contact[data-id="'+data.user_id+'"]');
            var tmpl = $('.contact[data-id="'+data.user_id+'"]').wrap("<div></div>").parent().html();

            // remove div inside of ul
            $('#contacts ul > div').remove();

            // move new online to the last user's online
            $('.contact[data-id="'+data.user_id+'"]').remove();
            if ($('#contacts ul li .contact-status.online').length > 0) {
                $('#contacts ul li .contact-status.online').last().closest('li').after(tmpl);
            } else {
                $('#contacts ul').html(tmpl);
            }

            $('.contact[data-id="'+data.user_id+'"] .contact-status').addClass("online");
        }
    },

    onDisconnect: function(data) {
        if (data.result) {
            var contact_status = $('.contact[data-id="'+data.user_id+'"] .contact-status');

            if (contact_status.hasClass('online')) {
                $('.contact[data-id="'+data.user_id+'"] .contact-status').removeClass("online");
            }
        }
    },

    onSendMessage: function(data) {
        var sent_tmpl = _.template($('#message-sent-tmpl').html());
        var replied_tmpl = _.template($('#message-replied-tmpl').html());
        var message;

        if ($('.messages .no-conversation').length > 0) {
            $('.messages .no-conversation').remove();
        }

        if (typeof(data.sender) !== "undefined") {
            var sender = data.sender;
                message = sender.message;

            $('.messages ul li.typing').remove();
            $('.contact[data-id="'+message.receiver.id+'"] .preview').html('<span>You: </span>' + message.message);
            $('.messages ul').append(sent_tmpl({'message': message.message, 'picture': message.sender.picture}));

            Chat2.scrollDown();
        }

        // if receiver online
        if (typeof(data.receiver) !== "undefined") {
            var receiver = data.receiver;
                message = receiver.message;

            $('.messages ul li.typing').remove();
            $('.contact[data-id="'+message.sender.id+'"] .badge').html(receiver.number_unread);
            $('.contact[data-id="'+message.sender.id+'"] .preview').html(message.message);

            if ($('#contacts .contact.active').data('id') == data.sender_id) {
                $('.messages ul').append(replied_tmpl({'message': message.message, 'picture': message.sender.picture}));
                Chat2.scrollDown();
            }
        }
    },

    onTyping: function(data) {
        var typing_tmpl = _.template($('#typing-tmpl').html());

        if ($('#contacts .contact.active').data('id') == data.sender_id && $('.messages ul li.typing').length === 0) {
            $('.messages ul').append(typing_tmpl());
            Chat2.scrollDown();
        }
    },

    onStopTyping: function() {
        $('.messages ul li.typing').remove();
    },

    onReadMessage: function(data) {
        var sender_id = data.sender_id;
        $('.contact[data-id="'+sender_id+'"] .badge').text("0");
    },

    onFetchMessages: function(data) {
        var conversation = data.conversation;
        console.log(conversation);

        $('.messages ul').html("");

        if (Object.keys(conversation).length > 0) {
            for (var i in conversation) {
                var message = conversation[i],
                    tmpl_func = message.sender.id == chat.auth_id ?
                                 _.template($('#message-sent-tmpl').html()) :
                                 _.template($('#message-replied-tmpl').html());

                $('.messages ul').prepend(tmpl_func({'message': message.message, 'picture': message.sender.picture}));
            }

            Chat2.scrollDown();
            Chat2Events.load_more_increment = 0;
        } else {
            $('.messages ul').html('<li class="no-conversation">No conversation.</li>');
        }
    },

    onLoadMoreMessages: function(data) {
        var conversation = data.conversation;

        if (Object.keys(conversation).length > 0) {
            for (var i in conversation) {
                var message = conversation[i],
                    tmpl_func = message.sender.id == chat.auth_id ?
                                 _.template($('#message-sent-tmpl').html()) :
                                 _.template($('#message-replied-tmpl').html());

                $('.messages ul').prepend(tmpl_func({'message': message.message, 'picture': message.sender.picture}));
            }
        } else {
            Chat2Events.load_more_increment = -1;
        }
    }
};

$(document).ready(Chat2.init);