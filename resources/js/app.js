import axios from "axios";
import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();
const auth_id = document.querySelector('meta[name="auth_id"]').getAttribute('content');

/* کاربران انلاین*/

let allUsers = [];
let onlineUsers = {};

async function fetchAllUsers() {
    const response = await fetch("/users");
    allUsers = await response.json();

    updateUserBar();
}

function updateUserBar() {
    const userBar = document.getElementById("user-bar");
    userBar.innerHTML = "";
    allUsers.forEach((user) => {
        const userItem = document.createElement("div");
        userItem.className = "user-item";
        userItem.innerHTML = `
            <span>${user.name}</span>
            <span class="status-indicator ${
                onlineUsers[user.id] ? "online" : "offline"
            }"></span>
        `;
        userBar.appendChild(userItem);
    });
}

window.Echo.join("presence-status")
    .subscribed(() => {
        console.log("Successfully subscribed to presence channel.");
    })
    .here((users) => {
        onlineUsers = users.reduce((acc, user) => {
            acc[user.id] = true;
            return acc;
        }, {});
        updateUserBar();
    })
    .joining((user) => {
        onlineUsers[user.id] = true;
        updateUserBar();
    })
    .leaving((user) => {
        delete onlineUsers[user.id];
        updateUserBar();
    });
fetchAllUsers();


/*پیام ها*/
const messageList = document.getElementById("messages");
const messageInput = document.getElementById("message-input");
const sendBtn = document.getElementById("send-btn");

const groupId = 1;

sendBtn.addEventListener("click", async () => {
    const message = messageInput.value.trim();

    if (message) {
        try {
            axios.post(`/groups/${groupId}/messages`, { message }, {
                headers: { 'Content-Type': 'application/json' }
            });
                        console.log("Message sent to server");

            const messageItem = document.createElement("li");
            messageItem.textContent = `شما: ${message}`;
            messageItem.classList.add("my-message");
            messageList.appendChild(messageItem);

            messageInput.value = "";
        } catch (error) {
            console.error("Error sending message:", error);
        }
    }
});

window.Echo.private('group.' + groupId)
    .subscribed(() => {
        console.log(`Successfully subscribed to group.${groupId} channel.`);
    })
    .listen(".server.created", (e) => {
        console.log("New message received:", e.message);

        if(e.id != auth_id){
            const messageItem = document.createElement("li");
            messageItem.textContent = `${e.user}: ${e.message}`;
            messageItem.classList.add("received-message");
            messageList.appendChild(messageItem);
        }


    })
    .error((error) => {
        console.error("Error subscribing to channel:", error);
    });


