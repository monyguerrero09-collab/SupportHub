const { sendMessage } = require('../services/whatsappService');

const sendWhatsAppMessage = async (req, res) => {
    const { token, phone, message } = req.body;

    // Verificar token de seguridad (el mismo que en el .env)
    if (!token || token !== process.env.API_TOKEN) {
        return res.status(401).json({ error: 'Token inválido o no proporcionado' });
    }

    if (!phone || !message) {
        return res.status(400).json({ error: 'Faltan parámetros (phone, message)' });
    }

    try {
        const result = await sendMessage(phone, message);
        return res.status(200).json({ success: true, result });
    } catch (error) {
        return res.status(500).json({ success: false, error: error.message });
    }
};

module.exports = {
    sendWhatsAppMessage
};
