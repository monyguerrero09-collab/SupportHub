const {
    default: makeWASocket,
    useMultiFileAuthState,
    DisconnectReason,
    fetchLatestBaileysVersion
} = require("@whiskeysockets/baileys");

let sock = null;

async function initWhatsAppService() {
    const { state, saveCreds } = await useMultiFileAuthState("auth");
    const { version, isLatest } = await fetchLatestBaileysVersion();
    console.log(`Usando WhatsApp v${version.join('.')}, isLatest: ${isLatest}`);

    sock = makeWASocket({
        version,
        auth: state,
        printQRInTerminal: false,
        browser: ["Ubuntu", "Chrome", "120.0.0.0"]
    });

    sock.ev.on("connection.update", async (update) => {
        const { connection, lastDisconnect } = update;

        if (connection === "open") {
            console.log("✅ Servidor de WhatsApp Activo y Conectado.");
        }

        if (connection === "close") {
            const statusCode = lastDisconnect?.error?.output?.statusCode;
            const shouldReconnect = statusCode !== DisconnectReason.loggedOut;

            if (shouldReconnect) {
                console.log("🔄 Conexión perdida. Reconectando en 5 segundos...");
                setTimeout(() => initWhatsAppService(), 5000);
            } else {
                console.log("❌ Sesión cerrada (logged out). Elimina la carpeta 'auth' y vuelve a vincular.");
            }
        }
    });

    sock.ev.on("creds.update", saveCreds);
    return sock;
}

async function generarCodigoVinculacion(numeroTelefono) {
    if (!sock) {
        await initWhatsAppService();
    }

    const numeroLimpio = numeroTelefono.replace(/\D/g, "");

    try {
        console.log(`📲 Solicitando código de emparejamiento para: ${numeroLimpio}`);
        // Esperamos un momento para asegurar que el socket esté inicializado antes de pedir el código
        await new Promise(resolve => setTimeout(resolve, 3000));
        const codigo = await sock.requestPairingCode(numeroLimpio);
        return codigo;
    } catch (error) {
        console.error("Error al generar el código en Baileys:", error);
        throw error;
    }
}

function getSockInstance() {
    return sock;
}

module.exports = { initWhatsAppService, generarCodigoVinculacion, getSockInstance };
