@extends('front.layouts.app')
@section('content')
    <main>
        <section class="section-5 pt-3 pb-3 mb-3 bg-white">
            <div class="container">
                <div class="light-font">
                    <ol class="breadcrumb primary-color mb-0">
                        <li class="breadcrumb-item text-primary">Tài khoản</li>
                        <li class="breadcrumb-item">Tin nhắn</li>
                    </ol>
                </div>
            </div>
        </section>
        <section class="section-11">
            <div class="container">
                <div class="row">
                    <div class="col-md-9 mx-auto">
                        <div class="card">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div id="chat-box">
                                        <ul id="messages">
                                            @if($messages->isNotEmpty())
                                                @foreach($messages as $message)
                                                    <li class="message-item
                                            {{ $message->is_admin ? 'admin' : 'user' }}">
                                                        <strong>{{ $message->is_admin ? 'Admin' : $message->user->name }}
                                                            :</strong>
                                                        {{ $message->message }}
                                                        <p class="message-time">{{ date_format($message->created_at, 'd/m/Y, H:i') }}</p>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li class="message-item admin">
                                                    Hãy gửi tin nhắn đến cửa hàng để được hỗ trợ nhé.
                                                </li>
                                            @endif
                                        </ul>
                                        <div class="d-flex">
                                            <input type="text" id="message" class="form-control"
                                                   placeholder="Soạn tin nhắn...">
                                            <button class="btn btn-dark" id="chat_button" onclick="sendMessage()">Gửi
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <style>
            #chat-box {
                max-height: 400px;
                overflow-y: auto;
                padding-bottom: 10px;
            }

            #messages {
                list-style-type: none;
                padding: 0;
            }

            .message-item {
                padding: 8px;
                margin-bottom: 10px;
                border-radius: 5px;
                max-width: 80%;
                word-wrap: break-word;
            }

            .user {
                background-color: #f0f8ff; /* Light blue for user */
                text-align: right;
                margin-left: auto; /* Align to the right */
            }

            .admin {
                background-color: #e9ecef; /* Light grey for admin */
                text-align: left;
                margin-right: auto; /* Align to the left */
            }

            #message {
                flex-grow: 1;
                margin-right: 10px;
            }

        </style>

    </main>
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        // Kết nối với Pusher
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });

        // Thay 'userId' bằng ID của user đang đăng nhập
        const userId = '{{ \Illuminate\Support\Facades\Auth::id() }}';
        const userName = '{{ \Illuminate\Support\Facades\Auth::user()->name }}'
        const channel = pusher.subscribe('chat.' + userId);

        //const channel = pusher.subscribe('chat');
        channel.bind('my-event', function (data) {
            const messageElement = document.createElement('li');
            // Gán class cho thẻ <li> dựa trên người gửi (admin hoặc user)
            messageElement.classList.add('message-item');
            messageElement.classList.add(data.message.is_admin ? 'admin' : 'user');

            messageElement.innerHTML = `
              <strong>${data.message.is_admin ? 'Admin' : userName}:</strong>
              ${data.message.message}
              <p class="message-time">
                ${new Date(data.message.created_at).toLocaleString('en-GB', {
                            hour: '2-digit', minute: '2-digit', day: '2-digit', month: '2-digit', year: 'numeric'
                        })}
              </p>`;
            document.getElementById('messages').appendChild(messageElement);
        });

        // Gửi tin nhắn
        function sendMessage() {
            const message = document.getElementById('message').value;

            const sendButton = document.getElementById('chat_button'); // Giả sử nút gửi tin là <button>

            if (!message.trim()) {
                alert('Vui lòng nhập nội dung tin nhắn!');
                return;
            }

            // Vô hiệu hóa nút gửi để ngăn gửi liên tiếp
            sendButton.disabled = true;
            sendButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

            axios.post('/chat/send', {message})
                .then(response => {
                    document.getElementById('message').value = '';
                })
                .catch(error => {
                    console.error(error);
                }).finally(() => {
                sendButton.disabled = false;
                sendButton.textContent = 'Gửi';
            });
        }
    </script>
@endsection


{{--<!DOCTYPE html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--    <meta name="csrf-token" content="{{ csrf_token() }}">--}}
{{--    <title>Chat</title>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>--}}
{{--    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>--}}
{{--</head>--}}
{{--<body>--}}
{{--<div id="chat-box">--}}
{{--    <ul id="messages">--}}
{{--        @foreach($messages as $message)--}}
{{--            <li><strong>{{ $message->is_admin ? 'Admin' : 'User' }}:</strong> {{ $message->message }}</li>--}}
{{--        @endforeach--}}
{{--    </ul>--}}
{{--    <input type="text" id="message" placeholder="Type your message...">--}}
{{--    <button onclick="sendMessage()">Send</button>--}}
{{--</div>--}}

{{--<script>--}}
{{--    // Kết nối với Pusher--}}
{{--    const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {--}}
{{--        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',--}}
{{--        encrypted: true--}}
{{--    });--}}

{{--    const channel = pusher.subscribe('chat');--}}
{{--    channel.bind('my-event', function(data) {--}}
{{--        const messageElement = document.createElement('li');--}}
{{--        messageElement.innerHTML = `<strong>${data.message.is_admin ? 'Admin' : 'User'}:</strong> ${data.message.message}`;--}}
{{--        document.getElementById('messages').appendChild(messageElement);--}}
{{--    });--}}

{{--    // Gửi tin nhắn--}}
{{--    function sendMessage() {--}}
{{--        const message = document.getElementById('message').value;--}}

{{--        axios.post('/chat/send', { message })--}}
{{--            .then(response => {--}}
{{--                document.getElementById('message').value = '';--}}
{{--            })--}}
{{--            .catch(error => {--}}
{{--                console.error(error);--}}
{{--            });--}}
{{--    }--}}
{{--</script>--}}
{{--</body>--}}
{{--</html>--}}
