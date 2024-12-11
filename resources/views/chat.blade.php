<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="auth_id" content="{{ auth()->user()->id }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App</title>
    @vite('resources/css/app.css')
    <style>

    </style>
</head>
<body>
    <div id="app">
        <div id="user-bar">
        </div>
        <div id="chat_box">
            <ul id="messages">


            </ul>
            <div class="send_message">
                <button id="send-btn">ارسال</button>
                <input type="text" id="message-input" placeholder="پیام خود را وارد کنید...">
            </div>
        </div>
    </div>
    @vite('resources/js/app.js')
</body>


</html>
