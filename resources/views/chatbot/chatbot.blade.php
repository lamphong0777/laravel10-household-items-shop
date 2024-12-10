{{--<!DOCTYPE html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport" content="width=device-width, initialscale=1.0">--}}
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}

{{--    <title>Chatbot</title>--}}
{{--    <style>--}}
{{--        #chatbox {--}}
{{--            width: 100%;--}}
{{--            height: 300px;--}}
{{--            border: 1px solid #ccc;--}}
{{--            padding: 10px;--}}
{{--            overflow-y: scroll;--}}
{{--        }--}}

{{--        .message {--}}
{{--            margin: 10px 0;--}}
{{--        }--}}

{{--        .message.user {--}}
{{--            text-align: right;--}}
{{--        }--}}
{{--    </style>--}}
{{--</head>--}}
{{--<body>--}}
{{--<div id="chatbox"></div>--}}
{{--<input type="text" id="messageInput" placeholder="Nhập tin nhắn...">--}}
{{--<button id="sendBtn">Gửi</button>--}}
{{--<script--}}
{{--    src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>--}}
{{--<script>--}}
{{--    $(document).ready(function () {--}}
{{--        // Get the CSRF token from the meta tag--}}
{{--        var csrfToken = $('meta[name="csrf-token"]').attr('content');--}}

{{--        $('#sendBtn').on('click', function () {--}}
{{--            let message = $('#messageInput').val();--}}
{{--            if (message.trim() !== '') {--}}
{{--                $('#chatbox').append('<div class="message user">' + message + '</div>');--}}
{{--                $('#messageInput').val('');--}}
{{--                // Gửi tin nhắn tới server với CSRF token--}}
{{--                $.ajax({--}}
{{--                    url: '/chatbot/message',--}}
{{--                    type: 'POST',--}}
{{--                    data: { message: message },--}}
{{--                    headers: {--}}
{{--                        'X-CSRF-TOKEN': csrfToken // Include CSRF token in the header--}}
{{--                    },--}}
{{--                    success: function (data) {--}}
{{--                        data.forEach(function (response) {--}}
{{--                            $('#chatbox').append('<div class="message bot">' + response.text + '</div>');--}}
{{--                        });--}}
{{--                    },--}}
{{--                    error: function (error) {--}}
{{--                        console.log("Error: ", error);--}}
{{--                    }--}}
{{--                });--}}
{{--            }--}}
{{--        });--}}
{{--    });--}}

{{--</script>--}}
{{--</body>--}}
{{--</html>--}}

    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chatbot</title>
    <style>
        /* Cấu hình cửa sổ chatbox ở góc dưới bên phải màn hình */
        #chatboxContainer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            height: 400px;
            border: 1px solid #ccc;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }

        /* Định dạng cửa sổ chat */
        #chatbox {
            width: 100%;
            height: calc(100% - 50px); /* Chiều cao chatbox trừ phần input */
            border-bottom: 1px solid #ccc;
            padding: 10px;
            overflow-y: scroll;
            font-family: Arial, sans-serif;
        }

        /* Định dạng tin nhắn */
        .message {
            margin: 10px 0;
        }

        .message.user {
            text-align: right;
            background-color: #e0f7fa;
            border-radius: 10px;
            padding: 5px 10px;
            display: inline-block;
        }

        .message.bot {
            text-align: left;
            background-color: #f1f1f1;
            border-radius: 10px;
            padding: 5px 10px;
            display: inline-block;
        }

        /* Định dạng input và button */
        #messageInput {
            width: calc(100% - 60px);
            padding: 10px;
            margin: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        #sendBtn {
            width: 40px;
            height: 40px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        #sendBtn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<!-- Chatbot Container -->
<div id="chatboxContainer">
    <div id="chatbox"></div>
    <div style="padding: 10px;">
        <input type="text" id="messageInput" placeholder="Nhập tin nhắn...">
        <button id="sendBtn">Gửi</button>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        // Get the CSRF token from the meta tag
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        $('#sendBtn').on('click', function () {
            let message = $('#messageInput').val();
            if (message.trim() !== '') {
                $('#chatbox').append('<div class="message user">' + message + '</div>');
                $('#messageInput').val('');
                // Gửi tin nhắn tới server với CSRF token
                $.ajax({
                    url: '/chatbot/message',
                    type: 'POST',
                    data: { message: message },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // Include CSRF token in the header
                    },
                    success: function (data) {
                        data.forEach(function (response) {
                            $('#chatbox').append('<div class="message bot">' + response.text + '</div>');
                        });
                    },
                    error: function (error) {
                        console.log("Error: ", error);
                    }
                });
            }
        });
    });
</script>
</body>
</html>
