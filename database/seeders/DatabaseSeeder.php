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
        $operadorRole = \App\Models\Role::firstOrCreate(['nombre' => 'Operador']);
        
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
            'nombre_completo' => 'Usuario Operador',
            'email' => 'operador@ejemplo.com',
            'password' => bcrypt('password'),
            'rol_id' => $operadorRole->id,
            'codigo_acceso' => 'OP-4444',
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
    }
}
