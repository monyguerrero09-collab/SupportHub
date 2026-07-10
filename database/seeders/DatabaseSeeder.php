<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \Illuminate\Database\Eloquent\Model::unguard();

        $adminRole = \App\Models\Role::firstOrCreate(['nombre' => 'Admin']);
        $agenteRole = \App\Models\Role::firstOrCreate(['nombre' => 'Agente TI']);
        $usuarioRole = \App\Models\Role::firstOrCreate(['nombre' => 'Usuario']);
        
        User::create([
            'nombre_completo' => 'Admin Support',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'rol_id' => $adminRole->id,
            'codigo_acceso' => 'AD-1111',
            'grupo' => 'Administrativo',
        ]);

        User::create([
            'nombre_completo' => 'Agente Soporte 1',
            'email' => 'agente@admin.com',
            'password' => bcrypt('password'),
            'rol_id' => $agenteRole->id,
            'codigo_acceso' => 'TI-2222',
            'grupo' => 'Administrativo',
        ]);

        User::create([
            'nombre_completo' => 'Usuario Administrativo',
            'email' => 'admin_user@ejemplo.com',
            'password' => bcrypt('password'),
            'rol_id' => $adminRole->id,
            'codigo_acceso' => 'AD-3333',
            'grupo' => 'Administrativo',
        ]);

        User::create([
            'nombre_completo' => 'Usuario General',
            'email' => 'usuario@ejemplo.com',
            'password' => bcrypt('password'),
            'rol_id' => $usuarioRole->id,
            'codigo_acceso' => 'US-4444',
            'grupo' => 'Técnico',
        ]);

        $estados = ['Abierto', 'En Progreso', 'Resuelto', 'Cerrado'];
        foreach ($estados as $est) {
            \App\Models\EstadoTicket::create(['nombre' => $est]);
        }

        $prioridades = ['Baja', 'Media', 'Alta', 'Urgente'];
        foreach ($prioridades as $pri) {
            \App\Models\Prioridad::create(['nombre' => $pri]);
        }

        $tipos = ['Falla de Hardware', 'Problema de Software', 'Acceso a Red', 'Duda General', 'Solicitud de Equipo'];
        foreach ($tipos as $tip) {
            \App\Models\TipoTicket::create(['nombre' => $tip]);
        }

        $departamentos = ['Sistemas', 'Recursos Humanos', 'Ventas', 'Contabilidad', 'Administración'];
        foreach ($departamentos as $dep) {
            \App\Models\Departamento::create(['nombre' => $dep]);
        }

        // Seed Sectors first if needed (already in file above, but making sure)
        $sectores = [
            'Administración',
            'Producción - Compresión',
            'Producción - Tensión',
            'Producción - Torsión',
            'Producción - Plat',
            'Producción - Wire Standing',
            'Producción - Wipers',
            'Producción - Mecatrónicos',
            'Producción - Welding',
            'Producción - Bending'
        ];
        foreach ($sectores as $sec) {
            \App\Models\Sector::firstOrCreate(['nombre' => $sec]);
        }

        $stationsData = [
            ['id' => 'ST-1', 'name' => 'Estación 1 - Recepción', 'description' => 'Área principal de atención', 'pos_x' => 200, 'pos_y' => 200, 'status' => 'active'],
            ['id' => 'ST-2', 'name' => 'Estación 2 - Operaciones', 'description' => 'Centro de operaciones y logística', 'pos_x' => 600, 'pos_y' => 200, 'status' => 'active'],
            ['id' => 'ST-3', 'name' => 'Estación 3 - Administración', 'description' => 'Recursos humanos y finanzas', 'pos_x' => 1000, 'pos_y' => 200, 'status' => 'active'],
            ['id' => 'ST-4', 'name' => 'Estación 4 - Dirección', 'description' => 'Oficina de dirección general', 'pos_x' => 200, 'pos_y' => 600, 'status' => 'active'],
        ];

        $stationMap = [];
        foreach ($stationsData as $index => $s) {
            $machine = \App\Models\Maquina::create([
                'external_id' => $s['id'],
                'nombre' => $s['name'],
                'descripcion' => $s['description'],
                'sector_id' => ($index % 10) + 1,
                'pos_x' => $s['pos_x'],
                'pos_y' => $s['pos_y'],
                'status' => $s['status'],
            ]);
            $stationMap[$s['id']] = $machine->id;
        }

        $inventoryData = [
            ['id' => 'INV-001', 'name' => 'Monitor Dell 24"', 'type' => 'Pantalla', 'barcode' => 'DL24-9982', 'model' => 'UltraSharp U2419H', 'status' => 'deployed', 'stationId' => 'ST-1', 'installedAt' => '2025-10-15'],
            ['id' => 'INV-002', 'name' => 'CPU Lenovo ThinkCentre', 'type' => 'CPU', 'barcode' => 'LN-TC-554', 'model' => 'M720q', 'status' => 'deployed', 'stationId' => 'ST-1', 'installedAt' => '2025-10-15'],
            ['id' => 'INV-003', 'name' => 'Teléfono IP Cisco', 'type' => 'Periférico', 'barcode' => 'CS-IP-112', 'model' => 'CP-7841', 'status' => 'deployed', 'stationId' => 'ST-1', 'installedAt' => '2025-10-16'],
            ['id' => 'INV-004', 'name' => 'Monitor LG 27"', 'type' => 'Pantalla', 'barcode' => 'LG27-3341', 'model' => '27UN850-W', 'status' => 'deployed', 'stationId' => 'ST-2', 'installedAt' => '2025-11-01'],
            ['id' => 'INV-005', 'name' => 'CPU HP EliteDesk', 'type' => 'CPU', 'barcode' => 'HP-ED-992', 'model' => '800 G6', 'status' => 'deployed', 'stationId' => 'ST-2', 'installedAt' => '2025-11-01'],
            ['id' => 'INV-006', 'name' => 'Impresora HP LaserJet', 'type' => 'Impresora', 'barcode' => 'HP-LJ-102', 'model' => 'Pro M404n', 'status' => 'deployed', 'stationId' => 'ST-2', 'installedAt' => '2025-11-20'],
            ['id' => 'INV-007', 'name' => 'Monitor Dell 24"', 'type' => 'Pantalla', 'barcode' => 'DL24-9983', 'model' => 'UltraSharp U2419H', 'status' => 'deployed', 'stationId' => 'ST-3', 'installedAt' => '2025-12-05'],
            ['id' => 'INV-008', 'name' => 'CPU Lenovo ThinkCentre', 'type' => 'CPU', 'barcode' => 'LN-TC-555', 'model' => 'M720q', 'status' => 'deployed', 'stationId' => 'ST-3', 'installedAt' => '2025-12-05'],
            ['id' => 'INV-009', 'name' => 'CPU HP ProDesk (Nueva)', 'type' => 'CPU', 'barcode' => 'HP-PD-001', 'model' => '400 G7', 'status' => 'in-stock', 'stationId' => null, 'installedAt' => null],
            ['id' => 'INV-010', 'name' => 'CPU HP ProDesk (Nueva)', 'type' => 'CPU', 'barcode' => 'HP-PD-002', 'model' => '400 G7', 'status' => 'in-stock', 'stationId' => null, 'installedAt' => null],
            ['id' => 'INV-011', 'name' => 'Monitor Samsung 24"', 'type' => 'Pantalla', 'barcode' => 'SM-24-001', 'model' => 'F24T35', 'status' => 'in-stock', 'stationId' => null, 'installedAt' => null],
            ['id' => 'INV-012', 'name' => 'Impresora Epson EcoTank', 'type' => 'Impresora', 'barcode' => 'EP-EC-055', 'model' => 'L3250', 'status' => 'in-stock', 'stationId' => null, 'installedAt' => null],
            ['id' => 'INV-013', 'name' => 'Laptop Lenovo ThinkPad', 'type' => 'Laptop', 'barcode' => 'LN-TP-088', 'model' => 'L14 Gen 2', 'status' => 'in-stock', 'stationId' => null, 'installedAt' => null],
            ['id' => 'INV-014', 'name' => 'Mouse Óptico HP', 'type' => 'Mouse', 'barcode' => 'HP-MS-441', 'model' => 'M100', 'status' => 'in-stock', 'stationId' => null, 'installedAt' => null],
            ['id' => 'INV-015', 'name' => 'Teclado Dell KB216', 'type' => 'Teclado', 'barcode' => 'DL-KB-901', 'model' => 'KB216', 'status' => 'in-stock', 'stationId' => null, 'installedAt' => null],
            ['id' => 'INV-016', 'name' => 'UPS Koblenz 1410R', 'type' => 'UPS', 'barcode' => 'KB-UP-230', 'model' => '1410 R', 'status' => 'in-stock', 'stationId' => null, 'installedAt' => null],
            ['id' => 'INV-017', 'name' => 'Monitor Dell 27"', 'type' => 'Pantalla', 'barcode' => 'DL-27-044', 'model' => 'S2721HN', 'status' => 'in-stock', 'stationId' => null, 'installedAt' => null],
            ['id' => 'INV-018', 'name' => 'Impresora Zebra ZD220', 'type' => 'Impresora', 'barcode' => 'ZB-TM-552', 'model' => 'ZD220', 'status' => 'in-stock', 'stationId' => null, 'installedAt' => null],
        ];

        foreach ($inventoryData as $i) {
            $minVal = $i['type'] === 'Pantalla' ? 3 : ($i['type'] === 'CPU' ? 2 : 5);
            $maxVal = $i['type'] === 'Pantalla' ? 10 : ($i['type'] === 'CPU' ? 8 : 25);
            \App\Models\Equipment::create([
                'name' => $i['name'],
                'type' => $i['type'],
                'barcode' => $i['barcode'],
                'model' => $i['model'],
                'status' => $i['status'],
                'maquina_id' => $i['stationId'] ? $stationMap[$i['stationId']] : null,
                'installed_at' => $i['installedAt'],
                'min_stock' => $minVal,
                'max_stock' => $maxVal,
            ]);
        }

        // 5. Create Sample Inventory Movements
        \App\Models\InventoryMovement::create([
            'action' => 'Ingreso',
            'details' => 'Ingreso de 12 Computadoras ThinkPad L14 Gen 3 a Bodega',
            'created_at' => '2026-05-10 10:00:00',
        ]);
        \App\Models\InventoryMovement::create([
            'action' => 'Asignación',
            'details' => 'Asignado 1 Computadora ThinkPad L14 Gen 3 a Estación de Carga A',
            'created_at' => '2026-06-01 14:30:00',
        ]);
        \App\Models\InventoryMovement::create([
            'action' => 'Retorno',
            'details' => 'Retornado 1 Computadora ThinkPad L14 Gen 3 de Estación Recepción a Bodega',
            'created_at' => '2026-06-15 09:15:00',
        ]);

        // 5. Create Sample Tickets linked to equipment
        $firstEquipment = \App\Models\Equipment::first();
        if ($firstEquipment) {
            \App\Models\Ticket::create([
                'titulo' => 'Falla en monitor (Imagen fantasma)',
                'descripcion' => 'Se observa una imagen fantasma permanente en el centro de la pantalla. Requiere revisión técnica.',
                'tipo_ticket_id' => 1,
                'estado_id' => 1,
                'prioridad_id' => 3,
                'usuario_creador_id' => 1,
                'departamento_id' => 1,
                'maquina_id' => $firstEquipment->maquina_id,
                'equipment_id' => $firstEquipment->id,
            ]);

            \App\Models\Ticket::create([
                'titulo' => 'Mantenimiento preventivo anual',
                'descripcion' => 'Limpieza interna y actualización de firmware del equipo realizada exitosamente.',
                'tipo_ticket_id' => 2,
                'estado_id' => 3,
                'prioridad_id' => 1,
                'usuario_creador_id' => 1,
                'departamento_id' => 1,
                'maquina_id' => $firstEquipment->maquina_id,
                'equipment_id' => $firstEquipment->id,
            ]);
        }

        // 6. Create directories for uploads
        $generalDocsDir = storage_path('app/public/general_documents');
        $ticketAttachDir = storage_path('app/public/ticket_attachments');
        if (!file_exists($generalDocsDir)) {
            mkdir($generalDocsDir, 0755, true);
        }
        if (!file_exists($ticketAttachDir)) {
            mkdir($ticketAttachDir, 0755, true);
        }

        // Copy / Create file examples
        $adminUser = \App\Models\User::where('email', 'admin@admin.com')->first() ?? \App\Models\User::first();
        $adminId = $adminUser ? $adminUser->id : 1;

        // PDF Example
        $srcPdf = public_path('img/LAYOUT CGR DE MEXICO QRO PLANTA 1-Model 3 (1).pdf');
        $destPdf = 'general_documents/Manual_Usuario_Planta1.pdf';
        if (file_exists($srcPdf)) {
            copy($srcPdf, storage_path('app/public/' . $destPdf));
        } else {
            file_put_contents(storage_path('app/public/' . $destPdf), "%PDF-1.4 ... Dummy PDF Content ...");
        }

        \App\Models\Documento::create([
            'nombre' => 'Manual de Operación de la Planta 1',
            'nombre_archivo' => 'Manual_Usuario_Planta1.pdf',
            'ruta_archivo' => $destPdf,
            'tipo_archivo' => 'application/pdf',
            'tamaño' => file_exists(storage_path('app/public/' . $destPdf)) ? filesize(storage_path('app/public/' . $destPdf)) : 1024,
            'categoria' => 'Manual',
            'usuario_id' => $adminId,
            'descripcion' => 'Manual detallado para el uso de equipos y la operación segura en la Planta 1.',
            'autor' => $adminUser ? $adminUser->nombre_completo : 'Admin',
            'licencia' => 'todos-derechos-reservados',
            'visible_operadores' => true,
        ]);

        // Image Example
        $srcImg = public_path('img/layout_cgr.png');
        $destImg = 'general_documents/Plano_Planta1.png';
        if (file_exists($srcImg)) {
            copy($srcImg, storage_path('app/public/' . $destImg));
        } else {
            file_put_contents(storage_path('app/public/' . $destImg), "");
        }

        \App\Models\Documento::create([
            'nombre' => 'Plano de Distribución de Planta 1',
            'nombre_archivo' => 'Plano_Planta1.png',
            'ruta_archivo' => $destImg,
            'tipo_archivo' => 'image/png',
            'tamaño' => file_exists(storage_path('app/public/' . $destImg)) ? filesize(storage_path('app/public/' . $destImg)) : 2048,
            'categoria' => 'General',
            'usuario_id' => $adminId,
            'descripcion' => 'Plano arquitectónico de la ubicación física de las máquinas y estaciones en Planta 1.',
            'autor' => $adminUser ? $adminUser->nombre_completo : 'Admin',
            'licencia' => 'no-especificada',
            'visible_operadores' => true,
        ]);

        // Text Example
        $destText = 'general_documents/Terminal_Log.txt';
        $logContent = "[2026-07-03 08:12:45] SYSTEM.INFO: Starting database migration checks...\n" .
                      "[2026-07-03 08:12:46] SYSTEM.SUCCESS: Database is up to date.\n" .
                      "[2026-07-03 08:14:02] WEBSERVER.REQUEST: GET /api/v1/tickets/status - 200 OK - 42ms\n" .
                      "[2026-07-03 08:15:10] WEBSERVER.REQUEST: POST /api/v1/tickets/create - 201 Created - 110ms\n" .
                      "[2026-07-03 08:20:00] CRON.INFO: Running background ticket notifications...\n" .
                      "[2026-07-03 08:20:05] CRON.SUCCESS: Sent 3 pending notifications.\n";
        file_put_contents(storage_path('app/public/' . $destText), $logContent);

        \App\Models\Documento::create([
            'nombre' => 'Registro de Consola de Servidor',
            'nombre_archivo' => 'Terminal_Log.txt',
            'ruta_archivo' => $destText,
            'tipo_archivo' => 'text/plain',
            'tamaño' => strlen($logContent),
            'categoria' => 'Guía',
            'usuario_id' => $adminId,
            'descripcion' => 'Logs del servidor principal que muestran las conexiones de la API de tickets.',
            'autor' => 'Sistema Automático',
            'licencia' => 'dominio-publico',
            'visible_operadores' => true,
        ]);

        // DOCX Example (Not supported preview)
        $destDocx = 'general_documents/Politicas_Seguridad.docx';
        file_put_contents(storage_path('app/public/' . $destDocx), "Word Document Mock Contents");

        \App\Models\Documento::create([
            'nombre' => 'Políticas de Seguridad de la Información',
            'nombre_archivo' => 'Politicas_Seguridad.docx',
            'ruta_archivo' => $destDocx,
            'tipo_archivo' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'tamaño' => 25,
            'categoria' => 'Política',
            'usuario_id' => $adminId,
            'descripcion' => 'Directrices oficiales sobre el uso correcto de contraseñas y accesos de TI.',
            'autor' => 'Oficial de Seguridad',
            'licencia' => 'todos-derechos-reservados',
            'visible_operadores' => false,
        ]);

        // Ticket Attachment Example
        $sampleTicket = \App\Models\Ticket::first();
        if ($sampleTicket) {
            $srcAttach = public_path('img/conexion_error.jpg');
            $destAttach = 'ticket_attachments/conexion_error.jpg';
            if (file_exists($srcAttach)) {
                copy($srcAttach, storage_path('app/public/' . $destAttach));
            } else {
                file_put_contents(storage_path('app/public/' . $destAttach), "");
            }

            \App\Models\ArchivoAdjunto::create([
                'ticket_id' => $sampleTicket->id,
                'nombre_archivo' => 'conexion_error.jpg',
                'ruta_archivo' => $destAttach,
                'visible_operadores' => true,
            ]);
        }
    }
}
