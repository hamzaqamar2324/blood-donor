const WebSocket = require("ws");
const wss = new WebSocket.Server({ port: 8080 });

const clients = {}; // { username: ws }

wss.on("connection", (ws) => {
  console.log("🔗 New connection established");

  ws.on("message", (msg) => {
    let data;
    try {
      data = JSON.parse(msg);
    } catch (e) {
      console.error("❌ Invalid JSON:", msg);
      return;
    }

    // --- User register/authentication ---
    if (data.username) {
      clients[data.username] = ws;
      ws.username = data.username;
      console.log(`✅ User connected: ${data.username}`);
      return;
    }

    // --- Normal chat message ---
    if (data.type === "chat" && data.to && data.message) {
      if (clients[data.to]) {
        clients[data.to].send(
          JSON.stringify({
            type: "chat",
            from: ws.username,
            to: data.to,
            message: data.message,
          })
        );
      } else {
        console.log(`⚠️ User ${data.to} not found`);
      }
      return;
    }

    // --- Call Signaling (audio/video call handling) ---
    if (
      ["call:request", "call:accept", "call:reject", "call:end",
       "rtc:offer", "rtc:answer", "rtc:candidate"].includes(data.type)
    ) {
      if (data.to && clients[data.to]) {
        clients[data.to].send(JSON.stringify({ ...data, from: ws.username }));
      } else {
        console.log(`⚠️ User ${data.to} not available for call`);
      }
      return;
    }

    console.log("⚠️ Unknown message type:", data);
  });

  ws.on("close", () => {
    if (ws.username && clients[ws.username]) {
      delete clients[ws.username];
      console.log(`❌ User disconnected: ${ws.username}`);
    }
  });
});

console.log("🚀 WebSocket server running on ws://localhost:8080");
