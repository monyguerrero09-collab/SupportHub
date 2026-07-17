const express = require('express');
const router = express.Router();
const sqlite3 = require('sqlite3').verbose();
const path = require('path');

// Conexión a la base de datos SQLite del proyecto Laravel en modo lectura
const dbPath = path.resolve(__dirname, '../../database/database.sqlite');
const db = new sqlite3.Database(dbPath, sqlite3.OPEN_READONLY, (err) => {
    if (err) {
        console.error('Error al conectar a SQLite:', err.message);
    } else {
        console.log('Conectado a la base de datos SQLite para validar números.');
    }
});

// Helper function para hacer consultas como promesas
const queryDb = (query, params) => {
    return new Promise((resolve, reject) => {
        db.get(query, params, (err, row) => {
            if (err) {
                reject(err);
            } else {
                resolve(row);
            }
        });
    });
};

// Endpoint principal para enviar notificaciones validadas por BD
router.post('/send', async (req, res) => {
    const { phone, message, token } = req.body;

    // Validación de seguridad de la API interna
    if (process.env.API_TOKEN && token !== process.env.API_TOKEN) {
        return res.status(401).json({ success: false, error: "Token inválido o no proporcionado." });
    }

    if (!phone || !message) {
        return res.status(400).json({ success: false, error: "Faltan parámetros: 'phone' y 'message' son obligatorios." });
    }

    const numeroLimpio = phone.replace(/\D/g, "");

    try {
        const queryText = 'SELECT * FROM usuarios WHERE telefono = ? LIMIT 1';
        const userRow = await queryDb(queryText, [numeroLimpio]);

        // Si el número no está en la base de datos, bloquea la petición
        if (!userRow) {
            console.log(`🚫 Envío denegado: El número ${numeroLimpio} no existe en la base de datos.`);
            return res.status(403).json({ 
                success: false, 
                error: "El número de destino no está registrado en el sistema." 
            });
        }

        console.log(`✅ Número verificado en la BD. Enviando WhatsApp a: ${numeroLimpio}`);
        
        // Importamos dinámicamente el socket activo del servicio
        const { getSockInstance } = require('../services/whatsappService');
        const sock = getSockInstance();

        if (!sock) {
            return res.status(500).json({ success: false, error: "El servicio de WhatsApp no está emparejado o está desconectado." });
        }

        // Envía el mensaje mediante WhatsApp Web (Baileys)
        let targetJid = numeroLimpio;
        if (targetJid.length === 10) {
             // Agrega el prefijo de México si son 10 dígitos (ajustable)
             targetJid = `521${targetJid}`;
        }
        
        await sock.sendMessage(`${targetJid}@s.whatsapp.net`, { text: message });

        return res.json({ 
            success: true, 
            message: `Notificación enviada con éxito a ${targetJid}.` 
        });

    } catch (error) {
        console.error("❌ Error en el proceso de notificación:", error);
        return res.status(500).json({ success: false, error: "Error interno en base de datos o canal de mensajería." });
    }
});

// Endpoint para solicitar el código de vinculación de WhatsApp
router.post('/pair', async (req, res) => {
    const { phone } = req.body;
    if (!phone) {
        return res.status(400).json({ success: false, error: "Se requiere el número de teléfono (phone)." });
    }
    
    try {
        const { generarCodigoVinculacion } = require('../services/whatsappService');
        const code = await generarCodigoVinculacion(phone);
        return res.json({ success: true, code });
    } catch (error) {
        console.error("Error al generar código:", error);
        return res.status(500).json({ success: false, error: error.message });
    }
});

module.exports = router;
