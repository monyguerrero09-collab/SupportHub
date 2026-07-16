require('dotenv').config();
const express = require('express');
const { initWhatsAppService } = require('./services/whatsappService');
const sendRoutes = require('./routes/send');

const app = express();

app.use(express.json());

// Routes
app.use('/api', sendRoutes);

app.get('/', (req, res) => {
    res.send("WhatsApp Service funcionando");
});

const PORT = process.env.PORT || 3000;

app.listen(PORT, () => {
    console.log(`Servidor iniciado en puerto ${PORT}`);
    console.log(`Inicializando servicio de WhatsApp...`);
    initWhatsAppService();
});
