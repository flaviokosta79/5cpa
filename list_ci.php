<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Buscar todos os CIs gerados
$stmt = $pdo->prepare('SELECT * FROM cis ORDER BY date DESC');
$stmt->execute();
$cis = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>

<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Exibir os CIs gerados -->
    <h4 class="fw-bold py-3 mb-2">Números de CI</h4>
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>CI</th>
                        <th>Data</th>
                        <th>Descrição</th>
                        <th>Origem</th>
                        <th>Destino</th>
                        <th>CI criada por</th> <!-- Nova coluna para mostrar quem criou o CI -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cis as $ci): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ci['ci_number']); ?></td>
                            <td><?php echo htmlspecialchars(date('d/m/Y', strtotime($ci['date']))); ?></td>
                            <td><?php echo htmlspecialchars($ci['description']); ?></td>
                            <td><?php echo htmlspecialchars($ci['origem']); ?></td>
                            <td><?php echo htmlspecialchars($ci['destination']); ?></td>
                            <td><?php echo htmlspecialchars($ci['username']); ?></td> <!-- Exibir quem criou o CI -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- / Exibir os CIs gerados -->

<?php include 'footer.php'; ?>
