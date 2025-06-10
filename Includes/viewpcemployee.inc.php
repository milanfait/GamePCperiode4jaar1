<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$sql = 'SELECT 
            p.paymentstatus, 
            u.firstname, u.infix, u.lastname, u.email, 
            p.price,

            cpu_part.partname AS cpu,
            gpu_part.partname AS gpu,
            mobo_part.partname AS motherboard,
            ram_part.partname AS ram,
            storage_part.partname AS storage,
            psu_part.partname AS psu,
            case_part.partname AS cabinet,
            cooler_part.partname AS cpu_cooler,
            monitor_part.partname AS monitor,
            keyboard_part.partname AS keyboard,
            mouse_part.partname AS mouse

        FROM payment p 
        LEFT JOIN users u ON p.userid = u.id
        LEFT JOIN pc pc ON pc.partid = p.id

        LEFT JOIN pcparts cpu_part ON pc.CPU = cpu_part.id
        LEFT JOIN pcparts gpu_part ON pc.GPU = gpu_part.id
        LEFT JOIN pcparts mobo_part ON pc.Motherboard = mobo_part.id
        LEFT JOIN pcparts ram_part ON pc.RAM = ram_part.id
        LEFT JOIN pcparts storage_part ON pc.SSDHDD = storage_part.id
        LEFT JOIN pcparts psu_part ON pc.PSU = psu_part.id
        LEFT JOIN pcparts case_part ON pc.Cabinet = case_part.id
        LEFT JOIN pcparts cooler_part ON pc.CPU_cooler = cooler_part.id
        LEFT JOIN pcparts monitor_part ON pc.Monitor = monitor_part.id
        LEFT JOIN pcparts keyboard_part ON pc.Keyboard = keyboard_part.id
        LEFT JOIN pcparts mouse_part ON pc.Mouse = mouse_part.id';

$stmt = $conn->prepare($sql);
$stmt->execute();

$payments = $stmt->fetchAll();

echo '<table border="1">';
echo '<tr>
        <th>Payment Status</th>
        <th>Name</th>
        <th>Email</th>
        <th>Total Price</th>
        <th>CPU</th>
        <th>GPU</th>
        <th>Motherboard</th>
        <th>RAM</th>
        <th>Storage</th>
        <th>PSU</th>
        <th>Cabinet</th>
        <th>CPU Cooler</th>
        <th>Monitor</th>
        <th>Keyboard</th>
        <th>Mouse</th>
      </tr>';

foreach ($payments as $pay) {
    $fullName = trim(htmlspecialchars($pay['firstname'] . ' ' . ($pay['infix'] ?? '') . ' ' . $pay['lastname']));

    echo '<tr>';
    echo '<td>' . htmlspecialchars($pay['paymentstatus']) . '</td>';
    echo '<td>' . $fullName . '</td>';
    echo '<td>' . htmlspecialchars($pay['email']) . '</td>';
    echo '<td>' . htmlspecialchars($pay['price']) . '</td>';
    echo '<td>' . htmlspecialchars($pay['cpu']) . '</td>';
    echo '<td>' . htmlspecialchars($pay['gpu']) . '</td>';
    echo '<td>' . htmlspecialchars($pay['motherboard']) . '</td>';
    echo '<td>' . htmlspecialchars($pay['ram']) . '</td>';
    echo '<td>' . htmlspecialchars($pay['storage']) . '</td>';
    echo '<td>' . htmlspecialchars($pay['psu']) . '</td>';
    echo '<td>' . htmlspecialchars($pay['cabinet']) . '</td>';
    echo '<td>' . htmlspecialchars($pay['cpu_cooler']) . '</td>';
    echo '<td>' . htmlspecialchars($pay['monitor']) . '</td>';
    echo '<td>' . htmlspecialchars($pay['keyboard']) . '</td>';
    echo '<td>' . htmlspecialchars($pay['mouse']) . '</td>';
    echo '</tr>';
}
echo '</table>';
?>
