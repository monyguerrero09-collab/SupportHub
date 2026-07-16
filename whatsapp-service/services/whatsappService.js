const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');

let whatsappClient = null;
let isReady = false;

const initWhatsAppService = () => {
    whatsappClient = new Client({
        authStrategy: new LocalAuth({ dataPath: './auth' }),
        puppeteer: {
            args: ['--no-sandbox', '--disable-setuid-sandbox'],
        }
    });

    whatsappClient.on('qr', (qr) => {
        console.log('\n--- ESCANEA ESTE CÓDIGO QR CON WHATSAPP ---');
        qrcode.generate(qr, { small: true });
        console.log('--------------------------------------------\n');
    });

    whatsappClient.on('ready', () => {
        console.log('¡Cliente de WhatsApp Web listo y conectado!');
        isReady = true;
    });

    whatsappClient.on('authenticated', () => {
        console.log('Autenticación exitosa.');
    });

    whatsappClient.on('auth_failure', msg => {
        console.error('Fallo en la autenticación:', msg);
    });

    whatsappClient.on('disconnected', (reason) => {
        console.log('Cliente desconectado:', reason);
        isReady = false;
        // Optionally attempt reconnect here
    });

    whatsappClient.initialize();
};

const sendMessage = async (phone, message) => {
    if (!isReady || !whatsappClient) {
        throw new Error("El cliente de WhatsApp no está listo.");
    }
    
    // Format the phone number (assuming it's a Mexican number 52, adjust as needed)
    // Most numbers need the country code and @c.us appended.
    let formattedPhone = phone.replace(/\D/g, ''); // Remove non-digits
    
    if (formattedPhone.length === 10) {
        // Assume Mexico code +52 if it's just 10 digits
        formattedPhone = `521${formattedPhone}`; 
        // Note: For Mexico, sometimes the '1' is required for mobiles in whatsapp-web.js, e.g., 521...
        // If it doesn't work with 521, you can try just 52
    }
    
    const chatId = `${formattedPhone}@c.us`;

    try {
        const response = await whatsappClient.sendMessage(chatId, message);
        return { success: true, messageId: response.id._serialized };
    } catch (error) {
        console.error(`Error al enviar mensaje a ${phone}:`, error);
        throw error;
    }
};

module.exports = {
    initWhatsAppService,
    sendMessage
};
