$(document).ready(function () {
    if ($('#messages-container').is(':empty')) {
        $('#select-user').html('<h6>Select a chat or start a new conversation!</h6>');
        $('.chat-box').hide();
    }
});

function getMessages(senderId, receiverId, courseId) {

    let roleType = $('#senderRole').val();
    $('.course-list .course-title').removeClass('active');
    if (roleType == 2 || roleType == 4) {
        $('.course-' + courseId + '-' + receiverId).addClass('active');
        $('#course-title').html($('.course-' + courseId + '-' + receiverId + ' .title').text());
        $('#chat-user-name').html($('.course-' + courseId + '-' + receiverId).closest('li').find('.user-name').text());
    } else if (roleType == 3) {
        $('.course-' + courseId).addClass('active');
        $('#course-title').html($('.course-' + courseId + ' .title').text());
        $('#chat-user-name').html($('.course-' + courseId).closest('li').find('.user-name').text());
    }

    $('#chat-error').empty();
    $('#receiverId').val(receiverId);
    $('#courseId').val(courseId);
    unseenCount(receiverId, courseId);
    let messageContainer = $('#messages-container');
    let fetchMessageUrl = $('#chatMessage').val();

    $.ajax({
        url: fetchMessageUrl,
        data: {
            'receiverId': receiverId,
            'senderId': senderId,
            'courseId': courseId
        },
        type: 'get',
        success: function (res) {
          
            $('.chat-box').show();
            $('#select-user').hide();
            let messagesHtml = '';
            if (res.messages.length == 0) {
                loadMore = false;
                messagesHtml = '<div class="no-message">No messages found.</div>';
            } else {
                res.messages.forEach(message => {
                    const messageClass = message.sender_id == senderId ? 'sender' : 'receiver';
                    messagesHtml += `
                        <div class="chat-message ${messageClass}">
                            <h6 class="message-text">${message.message}</h6>
                        </div>`;
                });
            }
            messageContainer.html(messagesHtml);
            messageContainer.scrollTop(messageContainer[0].scrollHeight);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

$('#chat-send').submit(function (e) {
    e.preventDefault();
    let message = $('#chat-message').val();
    let senderId = $('#senderId').val();
    let receiverId = $('#receiverId').val();
    let courseId = $('#courseId').val();
    let url = $('#chatSend').val();
    
    if (!message) {
        $('#chat-error').html('<p class="text-danger fw-bold text-center">' +
            'message cannot be empty!</p>')
    }
    else {
        $.ajax({
            url: url,
            type: 'post',
            data: {
                sender_id: senderId,
                receiver_id: receiverId,
                course_id: courseId,
                message: message
            },
            success: function (res) {
                $('#chat-message').val('');
                $('#chat-error').empty();
                getMessages(senderId, receiverId, courseId)
            },
            error: function (error) {
                console.log(error)
            }
        })
    }
});

function getPusherMessages(senderId, receiverId, courseId, message) {
    let receiver = $('#receiverId').val();
    let sender = $('#senderId').val();
    let course = $('#courseId').val();
    let messageContainer = $('#messages-container');
    let messagesHtml = '';
    if (senderId == receiver && receiverId == sender && courseId == course) {
        let messageClass = 'receiver';
        messagesHtml = `
        <div class="chat-message ${messageClass}">
            <h6 class="message-text">${message}</h6>
        </div>`;
        messageContainer.prepend(messagesHtml);
        messageContainer.scrollTop(messageContainer[0].scrollHeight);
    } 
}

function unseenCount(userId, courseId) {
    let selectedReceiverId = $('#receiverId').val();
    let selectedCourseId = $('#courseId').val();

    if (selectedCourseId == courseId && selectedReceiverId == userId) {
        let unseenElement = $('#unseen-' + userId + '-' + courseId);
        unseenElement.text('');
        unseenElement.siblings('.notification-icon').addClass('d-none');
    } else {
        let unseenElement = $('#unseen-' + userId + '-' + courseId);
        let unseenCount = parseInt(unseenElement.text() || 0);
        unseenCount++;
        unseenElement.text(unseenCount.toString());
        if (unseenCount > 0) {
            unseenElement.siblings('.notification-icon').removeClass('d-none');
        } else {
            unseenElement.siblings('.notification-icon').addClass('d-none');
        }
    }
}