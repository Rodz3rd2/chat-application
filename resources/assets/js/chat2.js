var Chat2 = {
    ENTER: 13,

    init: function() {
        Chat2.scrollDown();

        $("#profile-img").click(Chat2.showStatusOptions);
        $(".expand-button").click(Chat2.expandButton);
        $('#contacts .contact').click(Chat2.selectContact);
        $("#status-options ul li").click(Chat2.selectStatus);
        $('#input-message').keyup(Chat2.typing);
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
            Chat2.enter();
        }
    },

    enter: function() {
        var message = $('#input-message').val().trim();

        if (!_.isEmpty(message)) {
            var data = {
                // message_date: moment().format("MMMM Do YYYY"),
                // chat_name: window.chat.first_name,
                picture: window.chat.picture,
                message: message,
                // message_time: moment().format("hh:mm A"),
                // chat_lastname: window.chat.last_name
            };

            Chat2.message(data);
            // Chat2.conn.send(JSON.stringify(data));
        }

        $('#input-message').val("");
    },

    message: function(data) {
        var template = _.template($('#message-sent-tmpl').html());

        if ($('.messages .no-conversation').length > 0) {
            $('.messages .no-conversation').remove();
        }

        $('.contact.active .preview').html('<span>You: </span>' + data.message);

        $('.messages ul').append(template(data));
        Chat2.scrollDown();
    },

    scrollDown: function() {
        var bottom = $('.messages').prop('scrollHeight');
        $('.messages').animate({scrollTop: bottom});
    }
};

$(document).ready(Chat2.init);

// $(".messages").animate({ scrollTop: $(document).height() }, "fast");

// $("#profile-img").click(function() {
//     $("#status-options").toggleClass("active");
// });

// $(".expand-button").click(function() {
//     $("#profile").toggleClass("expanded");
//     $("#contacts").toggleClass("expanded");
// });

// $("#status-options ul li").click(function() {
//     $("#profile-img").removeClass();
//     $("#status-online").removeClass("active");
//     $("#status-away").removeClass("active");
//     $("#status-busy").removeClass("active");
//     $("#status-offline").removeClass("active");
//     $(this).addClass("active");

//     if ($("#status-online").hasClass("active")) {
//         $("#profile-img").addClass("online");
//     } else if ($("#status-away").hasClass("active")) {
//         $("#profile-img").addClass("away");
//     } else if ($("#status-busy").hasClass("active")) {
//         $("#profile-img").addClass("busy");
//     } else if ($("#status-offline").hasClass("active")) {
//         $("#profile-img").addClass("offline");
//     } else {
//         $("#profile-img").removeClass();
//     };

//     $("#status-options").removeClass("active");
// });

// function newMessage() {
//     message = $(".message-input input").val();
//     if ($.trim(message) == '') {
//         return false;
//     }
//     $('<li class="sent"><img src="http://emilcarlsson.se/assets/mikeross.png" alt="" /><p>' + message + '</p></li>').appendTo($('.messages ul'));
//     $('.message-input input').val(null);
//     $('.contact.active .preview').html('<span>You: </span>' + message);
//     $(".messages").animate({ scrollTop: $(document).height() }, "fast");
// };

// $('.submit').click(function() {
//     newMessage();
// });

// $(window).on('keydown', function(e) {
//     if (e.which == 13) {
//         newMessage();
//         return false;
//     }
// });