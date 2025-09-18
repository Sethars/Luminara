document.addEventListener("DOMContentLoaded", () => {
  const chatMessages = document.getElementById("chatMessages");
  const chatWith = document.getElementById("chatWith");
  const chatText = document.getElementById("chatText");
  const sendBtn = document.getElementById("sendBtn");
  const searchUser = document.getElementById("searchUser");

  let currentUserId = null;

  // ambil data dari PHP (window.chatData)
  const messages = window.chatData?.messages || {};

  function renderMessages(userId) {
    chatMessages.innerHTML = "";
    if (!messages[userId] || messages[userId].length === 0) {
      chatMessages.innerHTML = `<p class="text-muted">Belum ada pesan</p>`;
      return;
    }

    messages[userId].forEach((msg) => {
      const div = document.createElement("div");
      div.className = `message ${msg.type}`;
      div.textContent = msg.text;
      chatMessages.appendChild(div);
    });

    chatMessages.scrollTop = chatMessages.scrollHeight;
  }

  function openChat(userId, username) {
    currentUserId = userId;
    chatWith.textContent = username;
    renderMessages(userId);
  }

  document.querySelectorAll(".chat-user").forEach((li) => {
    li.addEventListener("click", () => {
      const userId = li.dataset.user;
      const username = li.querySelector("span").textContent;
      openChat(userId, username);
    });
  });

  function sendMessage() {
    if (!currentUserId) return;
    const text = chatText.value.trim();
    if (!text) return;

    const newMsg = { sender: "Me", type: "sent", text };
    if (!messages[currentUserId]) messages[currentUserId] = [];
    messages[currentUserId].push(newMsg);

    renderMessages(currentUserId);
    chatText.value = "";
  }

  sendBtn.addEventListener("click", sendMessage);
  chatText.addEventListener("keypress", (e) => {
    if (e.key === "Enter") sendMessage();
  });
});
