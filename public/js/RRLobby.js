//import
import { getQueryParam } from '../module_js/get_query.js';
import { Encoder } from '../module_js/encrypt.js';
import { fetchWithAuth } from '../module_js/fetch_with_auth.js';

const encoder = new Encoder();

//DOM element
const title = document.getElementById("lobby-title");
const lobbyName = document.getElementById("lobby-name-welcome");
const rulesMobile = document.getElementById("rulesMobile")
const readyBtn = document.getElementById("readyBtn");
const startBtn = document.getElementById("startBtn");
const quitBtn = document.getElementById("quitBtn");

// Modal functionality
const rulesBtn = document.getElementById('rulesBtn');
const rulesModal = document.getElementById('rulesModal');
const modalClose = document.getElementById('modalClose');

// Chat functionality
const chatInput = document.getElementById('chatInput');
const chatSendBtn = document.getElementById('chatSendBtn');
const chatMessages = document.getElementById('chatMessages');

let bet = 0;
let readyPlayers = 0;
let isHost = true;
let isPlayer = false;
let dataHost = {};
let dataPlayer = {};
let lastChatId = 0;
const renderedMessages = new Set();

const decodedLobby = encoder.decode(getQueryParam("lobby")) ?? null;
const id = new URLSearchParams(decodedLobby).get("id")
const name = new URLSearchParams(decodedLobby).get("name")
document.addEventListener('DOMContentLoaded', function() {

    fetchWithAuth("api/getLobbyData", {
        method: "POST",
        headers: {"Content-Type" : "application/json"},
        body: JSON.stringify({id})
    })
    .then(res => res.json())
    .then(data => {
        if(!data.success || (!data.is_host && !data.is_player)){
            alert('keluar sana hush');
            setTimeout(() => window.location.href="RRLobby_list", 1000);
            return;
        }

        //init data
        bet = data.bet;
        readyPlayers = data.ready_players;
        isHost = data.is_host ? true : false;
        isPlayer = data.is_player ? true : false;
        dataHost = data.data_host;
        dataPlayer = data.data_player

        if(isHost){
            startBtn.classList.remove("d-none");
        }

        // Initialize game info
        title.textContent = name;
        document.getElementById('betAmount').textContent = bet;
        lobbyName.textContent = name;
        updateStatus();
        updateAndGetData();
        setInterval(updateAndGetData, 1000)

        //display card player
        updateDisplayPlayer1();

        if(dataPlayer){
            updateDisplayPlayer2();
        }

        //event lstener
        rulesMobile.addEventListener('click', toggleRulesMobile)
        readyBtn.addEventListener('click', playerReady)
        startBtn.addEventListener('click', () => startGame)
        quitBtn.addEventListener('click', () => window.location.href = "/RRLobby_list")

        rulesBtn.addEventListener('click', () => {
            rulesModal.style.display = 'block';
        });

        modalClose.addEventListener('click', () => {
            rulesModal.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === rulesModal) {
                rulesModal.style.display = 'none';
            }
        });

        chatSendBtn.addEventListener('click', sendChatMessage);
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendChatMessage();
            }
        });
    })
    .catch(() => {});
})


function addChatMessage(message, isSystem = false) {
    const messageElement = document.createElement('div');
    messageElement.className = `chat-message ${isSystem ? 'system' : 'player'}`;
    messageElement.textContent = message;
    chatMessages.appendChild(messageElement);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function sendChatMessage() {
    const message = chatInput.value.trim();
    if (message) {
        fetchWithAuth("api/sendMessageLobby", {
            method: "POST",
            headers: {"Content-Type" : "application/json"},
            body: JSON.stringify({id, message})
        })
        .catch(() => {});
        
        chatInput.value = '';
    }
}

function getChatMessage(messages){
    if (messages.length > 0) {
        messages.forEach(msg => {
            if (!renderedMessages.has(msg.id)) {
                addChatMessage(`${msg.sender}: ${msg.message}`);
                renderedMessages.add(msg.id);
            }
        });

        lastChatId = messages[messages.length - 1].id;
    }
}

function updateAndGetData(){
    fetchWithAuth("api/updateAndGetDataLobby", {
        method: "POST",
        headers: {"Content-Type" : "application/json"},
        body: JSON.stringify({id, lastChatId})
    })
    .then(res => res.json())
    .then(data => {
        if(!data.success){
            addChatMessage(data.message, true);
            alert("keluar sana hush");
            window.location.href = "/RRLobby_list";
            return;
        }

        dataHost = data.host;
        updateDisplayPlayer1();
        dataPlayer = data.player;
        updateDisplayPlayer2();

        const messages = data.dataMessages;
        getChatMessage(messages);
        updateStatus();

        const dataGame = data.dataGame;
        if(dataGame.status === "starting"){
            startGame(parseInt(dataGame.countdown));
        }
    })
    .catch(() => {});
}

//update display player 1
function updateDisplayPlayer1() {
    if(dataHost){
        document.getElementById('player1Name').textContent = dataHost.username;
        document.getElementById('player1Avatar').innerHTML = `<img src="${dataHost.photo}" />`;

        if(dataHost.is_ready){
            document.getElementById('player1Status').textContent = 'Siap';
            document.getElementById('player1Status').classList.add('ready');
            document.getElementById('player1Card').classList.add('ready');
            
            if(isHost){
                document.getElementById('readyBtn').disabled = true;
                document.getElementById('readyBtn').textContent = 'Sudah Siap';
            }
        }
    }
}

//update display player 2
function updateDisplayPlayer2() {
    if(dataPlayer){
        document.getElementById('player2Name').textContent = dataPlayer.username;
        document.getElementById('player2Avatar').innerHTML = `<img src="${dataPlayer.photo}" />`;

        if(dataPlayer.is_ready){
            document.getElementById('player2Status').textContent = 'Siap';
            document.getElementById('player2Status').classList.add('ready');
            document.getElementById('player2Card').classList.add('ready');

            if(!isHost){
                document.getElementById('readyBtn').disabled = true;
                document.getElementById('readyBtn').textContent = 'Sudah Siap';
            }
        }
    } else {
        document.getElementById('player2Name').textContent = "-";
        document.getElementById('player2Avatar').innerHTML = `<i class="bi bi-incognito text-white"></i>`;
        document.getElementById('player2Status').textContent = 'Menunggu...';
        document.getElementById('player2Status').classList.remove('ready');
        document.getElementById('player2Card').classList.remove('ready');
    }
}

// Player ready function
function playerReady() {
    fetchWithAuth("api/updateStatusReady", {
        method: "POST",
        headers: {"Content-Type" : "application/json"},
        body: JSON.stringify({id})
    })
    .then(res => res.json())
    .then(data => {
        if(data.success){
            if(isHost){
                dataHost.is_ready = true;
                updateDisplayPlayer1();
            } else {
                dataPlayer.is_ready = true;
                updateDisplayPlayer2();
            }
            readyPlayers++;
            updateStatus();

            addChatMessage(`${isHost ? dataHost.username : dataPlayer.username} siap bermain`, true);
            createParticles(event);
        }
    })
    .catch(() => {});
}

// Update status
function updateStatus() {
    document.getElementById('statusText').textContent = `${readyPlayers}/2 Pemain Siap`;
    
    readyPlayers = dataHost.is_ready 
        ?
        (dataPlayer.is_ready ? 2 : 1)
        :
        0;

    if (readyPlayers === 2) {
        document.getElementById('statusIndicator').style.background = '#4CAF50';
        document.getElementById('startBtn').disabled = false;
    } else {
        document.getElementById('statusIndicator').style.background = '#ff0000';
        document.getElementById('startBtn').disabled = true;
    }
}

// Start game function
function startGame(countdown = 5) {
    if(readyPlayers !== 2){
        addChatMessage("Salah satu pemain belum siap", true);
        return;
    }

    addChatMessage("Memuat data...", true);
    document.getElementById('startBtn').disabled = true;
    fetchWithAuth("../api/beforeStartGame", {
        method: "POST",
        headers: {"Content-Type" : "application/json"},
        body: JSON.stringify({id})
    })
    .then(res => res.json())
    .then(data => {
        if(!data.success){
            addChatMessage("Gagal memuat data...", true);
            console.log(data.error);
            document.getElementById('startBtn').disabled = false;
            return;
        }
    
        if (!data.game || !data.game.bullets) {
            addChatMessage("Terjadi kesalahan saat memuat data game. Kamu akan dikembalikan ke lobby", true);
            setTimeout(() => {window.location.href = "/RRLobby_list";}, 2000);
            return;
        }
        addChatMessage('Permainan akan dimulai dalam 5 detik...', true);
        chatInput.disabled = true;
        
        // Countdown
        const countdownInterval = setInterval(() => {
            addChatMessage(`${countdown}...`, true);
            countdown--;
            
            if (countdown < 0) {
                clearInterval(countdownInterval);
                addChatMessage('Permainan akan segera dimulai!', true);
                
                fetchWithAuth("api/startGamePvp", {
                    method: "POST",
                    headers: {"Content-Type" : "application/json"},
                    body: JSON.stringify({id})
                })
                .then(res => res.json())
                .then(data => {
                    if(!data.success){
                        addChatMessage("ERROR: " + data.message, true);
                        chatInput.disabled = false;
                        if(data.leave) window.location.href = "/RRLobby_list"
                        return;
                    }
                    addChatMessage(data.message, true);
                    setTimeout(() => window.location.href = `games/russian-roulette?lobby=${getQueryParam("lobby")}`, 1500);
                })
            }
        }, 1000);
    })
}

// Create particle effect
function createParticles(event) {
    const colors = ['#ff0000', '#cc0000', '#990000', '#660000'];
    
    for (let i = 0; i < 10; i++) {
        setTimeout(() => {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * window.innerWidth + 'px';
            particle.style.top = window.innerHeight / 2 + 'px';
            particle.style.width = Math.random() * 10 + 5 + 'px';
            particle.style.height = particle.style.width;
            particle.style.background = colors[Math.floor(Math.random() * colors.length)];
            particle.style.borderRadius = '50%';
            
            document.body.appendChild(particle);
            
            setTimeout(() => {
                particle.remove();
            }, 4000);
        }, i * 50);
    }
}

function toggleRulesMobile() {
    const rules = document.querySelector('.mobile-rules');
    const arrow = document.getElementById('arrow-icon');

    if (rules.style.display === "none" || rules.style.display === "") {
        rules.style.display = "block";
        arrow.style.transform = "rotate(180deg)";
    } else {
        rules.style.display = "none";
        arrow.style.transform = "rotate(0deg)";
    }
}