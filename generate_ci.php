<?php
session_start();
require 'db.php'; // Certifique-se de que o arquivo db.php existe e configura a variável $pdo

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// Definir o fuso horário para garantir que a hora do servidor esteja correto
date_default_timezone_set('America/Sao_Paulo');

// Obter informações do usuário logado
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('SELECT username FROM users WHERE id = ?');
$stmt->execute([$user_id]);
$user = $stmt->fetch();
$username = $user['username']; // Nome do usuário logado

// Obter a data atual no formato brasileiro
$date = date('Y-m-d');

// Função para gerar número de CI único
function generateUniqueCiNumber($pdo) {
    do {
        $stmt = $pdo->prepare('SELECT MAX(ci_number) FROM cis WHERE YEAR(date) = YEAR(CURDATE())');
        $stmt->execute();
        $last_number = $stmt->fetchColumn();
        $new_number = $last_number ? $last_number + 1 : 1;
        if ($new_number > 9999) {
            $new_number = 1;
        }

        // Verificar se o número de CI já existe
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM cis WHERE ci_number = ? AND YEAR(date) = YEAR(CURDATE())');
        $stmt->execute([$new_number]);
        $count = $stmt->fetchColumn();
    } while ($count > 0);

    return $new_number;
}

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = $_POST['description'];
    $origem = $_POST['origem'];
    $destinations = $_POST['destinations'];
    $date = $_POST['date'];
    $ci_number = generateUniqueCiNumber($pdo);

    // Inserir o novo CI no banco de dados
    try {
        $stmt = $pdo->prepare('INSERT INTO cis (ci_number, description, origem, destination, date, user_id, username) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$ci_number, $description, $origem, $destinations, $date, $user_id, $username]);

        $_SESSION['success'] = 'Número de CI gerado com sucesso.';
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Erro ao gerar número de CI: ' . $e->getMessage();
    }

    header('Location: generate_ci.php');
    exit();
}

// Buscar todos os CIs gerados
$stmt = $pdo->prepare('SELECT * FROM cis ORDER BY date DESC');
$stmt->execute();
$cis = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Opções de locais e descrições
$locations = [
    "1º BPM", "2º BPM", "3º BPM", "4º BPM", "5º BPM", "6º BPM", "7º BPM", "8º BPM",
    "9º BPM", "10º BPM", "11º BPM", "12º BPM", "13º BPM", "14º BPM", "15º BPM", 
    "16º BPM", "17º BPM", "18º BPM", "19º BPM", "20º BPM", "21º BPM", "22º BPM", 
    "23º BPM", "24º BPM", "25º BPM", "26º BPM", "27º BPM", "28º BPM", "29º BPM", 
    "30º BPM", "31º BPM", "32º BPM", "33º BPM", "34º BPM", "35º BPM", "36º BPM", 
    "37º BPM", "38º BPM", "39º BPM", "40º BPM", "41º BPM"
];
$descriptions = [
    "Solicitação de apoio", "Envio de relatório", "Convocação para reunião", 
    "Entrega de documentos", "Pedido de informações"
];

include 'header.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Número de CI</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <link rel="stylesheet" href="assets/vendor/css/core.css">
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css">
    <link rel="stylesheet" href="assets/css/demo.css">
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css">
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
    <style>
        .form-group {
            margin-bottom: 0.5rem; /* Diminuir o espaço entre os grupos de formulário */
        }
        .card-body {
            padding: 10px; /* Diminuir o padding do container */
        }
        .form-control {
            height: calc(1.4em + .4rem + 2px); /* Diminuir a altura das caixas */
            padding: .25rem .5rem; /* Ajustar o padding das caixas */
            font-size: 0.875rem; /* Diminuir o tamanho da fonte */
        }
        .btn {
            padding: .25rem .5rem; /* Diminuir o padding do botão */
            font-size: 0.875rem; /* Diminuir o tamanho da fonte */
        }
        .form-group label {
            font-size: 0.875rem; /* Diminuir o tamanho da fonte dos labels */
        }
        .layout-content-navbar .content-wrapper {
            padding-top: 10px; /* Reduzir o espaço entre a barra de navegação e o conteúdo */
        }
        .container-xxl {
            padding-top: 10px; /* Reduzir o espaço entre os containers */
        }
    </style>
</head>
<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
                <h4 class="fw-bold py-3 mb-2">Gerar Número de CI</h4>
                
                <div class="card mb-3">
                    <div class="card-body">
                        <form id="generate-ci-form" method="POST" action="generate_ci.php">
                            <div class="form-group">
                                <label for="origem">Origem</label>
                                <input type="text" id="origem" name="origem" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Descrição</label>
                                <input type="text" id="description" name="description" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="destination">Destino</label>
                                <div class="input-group">
                                    <input type="text" id="destination" class="form-control">
                                    <div class="input-group-append">
                                        <button type="button" id="add-destination" class="btn btn-primary">Adicionar</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Destinos Selecionados:</label>
                                <div id="selected-destinations" class="form-control" style="height:auto;"></div>
                                <input type="hidden" id="selected-destinations-input" name="destinations" required>
                            </div>
                            <div class="form-group">
                                <label for="date">Data</label>
                                <input type="date" id="date" name="date" class="form-control" value="<?php echo $date; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Gerar Número de CI</button>
                        </form>
                    </div>
                </div>
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
                                    <th>CI criada por</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cis as $ci): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($ci['ci_number']); ?></td>
                                        <td><?php echo htmlspecialchars(date('d/m/Y H:i:s', strtotime($ci['date']))); ?></td>
                                        <td><?php echo htmlspecialchars($ci['description']); ?></td>
                                        <td><?php echo htmlspecialchars($ci['origem']); ?></td>
                                        <td><?php echo htmlspecialchars($ci['destination']); ?></td>
                                        <td><?php echo htmlspecialchars($ci['username']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <nav aria-label="Page navigation">
                            <ul class="pagination">
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                                        <a class="page-link" href="generate_ci.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                    </div>
                </div>

            </div>
            <!-- / Content -->
        </div>
    </div>

    <script>
    $(document).ready(function() {
        var defaultLocations = ["1º BPM", "2º BPM", "3º BPM", "4º BPM", "5º BPM", "6º BPM", "7º BPM", "8º BPM", "9º BPM", "10º BPM", "11º BPM", "12º BPM", "13º BPM", "14º BPM", "15º BPM", 
        "16º BPM", "17º BPM", "18º BPM", "19º BPM", "20º BPM", "21º BPM", "22º BPM", "23º BPM", "24º BPM", "25º BPM", "26º BPM", "27º BPM", "28º BPM", "29º BPM", 
        "30º BPM", "31º BPM", "32º BPM", "33º BPM", "34º BPM", "35º BPM", "36º BPM", "37º BPM", "38º BPM", "39º BPM", "40º BPM", "41º BPM"];
        var defaultDescriptions = ["Solicitação de apoio", "Envio de relatório", "Convocação para reunião", "Entrega de documentos", "Pedido de informações"];

        var locations = JSON.parse(localStorage.getItem('customLocations')) || defaultLocations;
        var descriptions = JSON.parse(localStorage.getItem('customDescriptions')) || defaultDescriptions;

        function autocompleteStartsWith(input, array) {
            return array.filter(function(item) {
                return item.toLowerCase().startsWith(input.toLowerCase());
            });
        }
        
        $("#origem").autocomplete({
            source: function(request, response) {
                var results = autocompleteStartsWith(request.term, locations);
                response(results);
            }
        });

        $("#description").autocomplete({
            source: function(request, response) {
                var results = autocompleteStartsWith(request.term, descriptions);
                response(results);
            }
        });

        $("#destination").autocomplete({
            source: function(request, response) {
                var results = autocompleteStartsWith(request.term, locations);
                response(results);
            }
        });

        var selectedDestinations = [];
        $("#add-destination").click(function() {
            var destinationInput = $("#destination").val();
            if (destinationInput && !selectedDestinations.includes(destinationInput)) {
                selectedDestinations.push(destinationInput);
                updateSelectedDestinations();
                $("#destination").val(''); // Limpar a caixa de seleção de destino
            }
        });

        $("#selected-destinations").on("click", ".remove-destination", function() {
            var valueToRemove = $(this).data("value");
            selectedDestinations = selectedDestinations.filter(function(dest) {
                return dest.toString() !== valueToRemove.toString();
            });
            updateSelectedDestinations();
        });

        function updateSelectedDestinations() {
            var selectedList = $("#selected-destinations");
            var selectedInput = $("#selected-destinations-input");
            selectedList.empty();
            selectedDestinations.forEach(function(dest) {
                var span = $("<span></span>").addClass("badge badge-primary mr-2").css("color", "black");
                span.html(dest + ' <span class="remove-destination" data-value="' + dest + '" style="color:red; cursor:pointer;">x</span>');
                selectedList.append(span);
            });
            selectedInput.val(selectedDestinations.join(','));
        }

        $("#generate-ci-form").submit(function(e) {
            if ($("#origem").val() === "" || $("#description").val() === "" || $("#selected-destinations-input").val() === "" || $("#date").val() === "") {
                e.preventDefault();
                alert("Por favor, preencha todos os campos.");
            } else {
                var newOrigin = $("#origem").val();
                if (!locations.includes(newOrigin)) {
                    locations.push(newOrigin);
                    localStorage.setItem('customLocations', JSON.stringify(locations));
                }
                
                var newDescription = $("#description").val();
                if (!descriptions.includes(newDescription)) {
                    descriptions.push(newDescription);
                    localStorage.setItem('customDescriptions', JSON.stringify(descriptions));
                }
                
                selectedDestinations.forEach(function(newDestination) {
                    if (!locations.includes(newDestination)) {
                        locations.push(newDestination);
                    }
                });
                localStorage.setItem('customLocations', JSON.stringify(locations));
            }
        });
    });
    </script>

</body>
</html>
