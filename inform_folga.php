<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Obter informações do usuário logado
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT username, password FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Definir as datas da próxima semana (segunda a sexta)
$hoje = date('Y-m-d');
$hora_atual = date('H:i');
$segunda_proxima = date('Y-m-d', strtotime('next monday', strtotime($hoje)));
$terca_proxima = date('Y-m-d', strtotime('next tuesday', strtotime($hoje)));
$quarta_proxima = date('Y-m-d', strtotime('next wednesday', strtotime($hoje)));
$quinta_proxima = date('Y-m-d', strtotime('next thursday', strtotime($hoje)));
$sexta_proxima = date('Y-m-d', strtotime('next friday', strtotime($hoje)));

$dias_semana_proxima = [
    'Segunda-feira' => $segunda_proxima,
    'Terça-feira' => $terca_proxima,
    'Quarta-feira' => $quarta_proxima,
    'Quinta-feira' => $quinta_proxima,
    'Sexta-feira' => $sexta_proxima,
];

// Configurar locale para português do Brasil
setlocale(LC_TIME, 'pt_BR.UTF-8', 'Portuguese_Brazil.1252');

$folgas = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $password = $_POST['password'];
    if (password_verify($password, $user['password'])) {
        $folgas = $_POST['folgas'];

        // Remover folgas existentes do usuário
        $stmt = $pdo->prepare('DELETE FROM folgas WHERE user_id = ?');
        $stmt->execute([$user_id]);

        // Inserir as novas folgas
        foreach ($folgas as $dia_folga) {
            $stmt = $pdo->prepare('INSERT INTO folgas (user_id, dia_folga) VALUES (?, ?)');
            $stmt->execute([$user_id, $dia_folga]);
        }

        $_SESSION['success'] = 'Folgas informadas com sucesso.';
        header('Location: inform_folga.php');
        exit();
    } else {
        $_SESSION['error'] = 'Senha incorreta.';
    }
}

// Obter as folgas do usuário
$stmt = $pdo->prepare('SELECT dia_folga FROM folgas WHERE user_id = ?');
$stmt->execute([$user_id]);
$folgas_usuario = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Verificar se é domingo após as 23:59
$domingo = date('Y-m-d', strtotime('sunday this week'));
$prazo_expirado = ($hoje > $domingo) || ($hoje == $domingo && $hora_atual > '23:59');

?>

<?php include 'header.php'; ?>

<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Informar Folga Semanal</h4>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="inform_folga.php">
                <div class="table-responsive mb-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <?php foreach ($dias_semana_proxima as $dia => $data): ?>
                                    <th><?php echo $dia; ?><br><?php echo strftime('%d de %B', strtotime($data)); ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php foreach ($dias_semana_proxima as $dia => $data): ?>
                                    <td>
                                        <input type="checkbox" name="folgas[]" value="<?php echo $dia; ?>" 
                                            <?php echo in_array($dia, $folgas_usuario) ? 'checked' : ''; ?>
                                            onclick="checkFolga(this, '<?php echo $dia; ?>')" <?php echo $prazo_expirado ? 'disabled' : ''; ?>>
                                        <span id="folga-<?php echo $dia; ?>" class=""></span>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php if (!$prazo_expirado): ?>
                    <button type="button" class="btn btn-primary" onclick="confirmFolga()">Informar Folga</button>
                    <?php if (!empty($folgas_usuario)): ?>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#alterarFolgaModal">Alterar Folga</button>
                    <?php endif; ?>
                <?php else: ?>
                    <p>As folgas já foram informadas à P1. Para alterá-las, entre em contato pessoalmente.</p>
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Confirmar Folga -->
<div class="modal fade" id="confirmarFolgaModal" tabindex="-1" role="dialog" aria-labelledby="confirmarFolgaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarFolgaModalLabel">Confirmar Folgas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="inform_folga.php">
                    <div id="mensagem-confirmacao"></div>
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" name="action" value="informar_folga" class="btn btn-primary">Confirmar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Alterar Folga -->
<div class="modal fade" id="alterarFolgaModal" tabindex="-1" role="dialog" aria-labelledby="alterarFolgaModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="alterarFolgaModalLabel">Alterar Folgas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="inform_folga.php">
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" name="action" value="alterar_folga" class="btn btn-primary">Confirmar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function checkFolga(checkbox, dia) {
        let checkboxes = document.querySelectorAll('input[type="checkbox"]');
        let selected = Array.from(checkboxes).filter(cb => cb.checked);

        if (selected.length > 2) {
            checkbox.checked = false;
            alert('Você só pode selecionar até 2 dias de folga.');
            return;
        }

        checkboxes.forEach(cb => {
            let span = document.getElementById('folga-' + cb.value);
            span.textContent = '';
            span.className = '';
        });

        selected.forEach((cb, index) => {
            let span = document.getElementById('folga-' + cb.value);
            if (index === 0) {
                span.textContent = 'Folga oficial';
                span.className = 'text-danger';
            } else if (index === 1) {
                span.textContent = 'Concessão';
                span.className = 'text-purple';
            }
        });
    }

    function confirmFolga() {
        let checkboxes = document.querySelectorAll('input[type="checkbox"]');
        let selected = Array.from(checkboxes).filter(cb => cb.checked).map(cb => cb.value);

        if (selected.length > 0 && selected.length <= 2) {
            let message = selected.length === 1 ? 'Você marcou somente 1 dia de folga semanal, tem certeza disso?' : '';
            document.getElementById('mensagem-confirmacao').innerHTML = message;
            $('#confirmarFolgaModal').modal('show');
        } else {
            alert('Você deve selecionar até 2 dias de folga.');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        let checkboxes = document.querySelectorAll('input[type="checkbox"]');
        let selected = Array.from(checkboxes).filter(cb => cb.checked);

        selected.forEach((cb, index) => {
            let span = document.getElementById('folga-' + cb.value);
            if (index === 0) {
                span.textContent = 'Folga oficial';
                span.className = 'text-danger';
            } else if (index === 1) {
                span.textContent = 'Concessão';
                span.className = 'text-purple';
            }
        });
    });
</script>
<!-- / Content -->
    <style>
    .text-purple {
        color: purple;
    }
    </style>

<?php include 'footer.php'; ?>
