<?php
$file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$content = file_get_contents($file);

$content = str_replace(
    [
        "@this.set('ticketCategory', this.services.find(s=>s.id===this.service)?.name);",
        "@this.set('ticketSubcategory', this.category);",
        "@this.set('ticketDescription', this.description);",
        "@this.set('ticketAvailableTime', this.availableTime);",
        "@this.createTicket();"
    ],
    [
        "\$wire.set('ticketCategory', this.services.find(s=>s.id===this.service)?.name);",
        "\$wire.set('ticketSubcategory', this.category);",
        "\$wire.set('ticketDescription', this.description);",
        "\$wire.set('ticketAvailableTime', this.availableTime);",
        "\$wire.createTicket();"
    ],
    $content
);

file_put_contents($file, $content);
echo "Fixed to \$wire";
?>
