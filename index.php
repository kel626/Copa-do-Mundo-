<?php
$host = "localhost";
$db_name = "copa_do_mundo";
$username = "root";
$password = "";

try {
    $db = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) { 
    die("Erro de Conexão: " . $e->getMessage()); 
}

$action = $_GET['action'] ?? 'listar';

// Lógica para Salvar (com sanitização e validação)
if ($action == 'salvar' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $grupo = strtoupper(filter_input(INPUT_POST, 'grupo', FILTER_SANITIZE_SPECIAL_CHARS));
    $titulos = filter_input(INPUT_POST, 'titulos', FILTER_VALIDATE_INT);

    if ($nome && $grupo) {
        $stmt = $db->prepare("INSERT INTO selecoes (nome, grupo, titulos) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $grupo, $titulos]);
        header("Location: index.php"); 
        exit;
    }
}

// Lógica para Excluir
if ($action == 'excluir' && isset($_GET['id'])) {
    $stmt = $db->prepare("DELETE FROM selecoes WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    header("Location: index.php"); 
    exit;
}

// Busca as seleções para exibir
$stmt = $db->prepare("SELECT * FROM selecoes ORDER BY titulos DESC, nome ASC");
$stmt->execute();
$selecoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Copa Dashboard | Kelvin Faria</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <link rel="stylesheet" href="estilo.css">
</head>
<body>

    <nav class="container-fluid nav-fifa">
        <ul>
            <li><strong class="brand-title">🏆 FIFA WORLD CUP 2026</strong></li>
        </ul>
        <ul>
            <li><a href="#" class="tab-link active" onclick="openTab(event, 'dashboard')">Ranking</a></li>
            <li><a href="#" class="tab-link" onclick="openTab(event, 'cadastro')">Nova Seleção</a></li>
            <li><a href="#" class="tab-link" onclick="openTab(event, 'estatisticas')">Estatísticas</a></li>
        </ul>
    </nav>

    <main class="container">
        <section id="dashboard" class="tab-content active-tab">
    <h2 class="title-section">Fase de Grupos</h2>

    <?php if (!$selecoes): ?>
        <article class="empty-state">
            <h3>Nenhuma seleção classificada ainda.</h3>
        </article>
    <?php else: 
        // LÓGICA DE AGRUPAMENTO
        $grupos = [];
        foreach ($selecoes as $s) {
            $grupos[$s['grupo']][] = $s;
        }
        ksort($grupos); // Organiza os grupos de A a Z
    ?>
        <div class="team-info">
    <strong><?= $s['nome'] ?></strong>
    <div class="stars">
        <?php 
        if($s['titulos'] > 0) {
            for($i=0; $i<$s['titulos']; $i++) echo '★';
        } else {
            echo '<small style="color:#cbd5e0">Sem títulos</small>';
        }
        ?>
    </div>
</div>
        <div class="grid-grupos">
            <?php foreach ($grupos as $nomeGrupo => $membros): ?>
                <article class="card-grupo">
                    <header>
                        <strong>GRUPO <?= $nomeGrupo ?></strong>
                    </header>
                    <table role="grid">
                        <tbody>
                            <?php foreach ($membros as $s): ?>
                            <tr>
                                <td>
                                    <div class="team-info">
                                        <strong><?= $s['nome'] ?></strong>
                                        <div class="stars"><?php for($i=0; $i<$s['titulos']; $i++) echo '★'; ?></div>
                                    </div>
                                </td>
                                <td style="text-align: right;">
                                    <a href="index.php?action=excluir&id=<?= $s['id'] ?>" class="btn-mini-excluir" title="Remover">✕</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

        <section id="cadastro" class="tab-content" style="display:none">
            <article>
                <h2 class="title-section">Inscrever Seleção</h2>
                <form action="index.php?action=salvar" method="POST">
                    <div class="grid">
                        <label>
                            Nome da Seleção
                            <input type="text" name="nome" placeholder="Ex: Brasil" required>
                        </label>
                        <label>
                            Grupo
                            <input type="text" name="grupo" maxlength="1" placeholder="Ex: A" required>
                        </label>
                        <label>
                            Total de Títulos
                            <input type="number" name="titulos" value="0" min="0">
                        </label>
                    </div>
                    <button type="submit" class="btn-primary">Registrar na Copa</button>
                </form>
            </article>
        </section>

        <section id="estatisticas" class="tab-content" style="display:none">
            <h2 class="title-section">Dados do Torneio</h2>
            <div class="grid">
                <article class="stat-card">
                    <h5>Equipes Classificadas</h5>
                    <h1><?= count($selecoes) ?></h1>
                </article>
                <article class="stat-card">
                    <h5>Títulos Históricos Disputados</h5>
                    <h1>
                        <?php 
                        $total = 0;
                        foreach($selecoes as $s) $total += $s['titulos'];
                        echo $total;
                        ?>
                    </h1>
                </article>
            </div>
        </section>
    </main>

<footer class="footer-fifa">
    <p>Sistema desenvolvido por <strong>Kelvin Faria</strong></p>
    <small>SENAI | 2026</small>
</footer>

    <script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;

        // Oculta todas as abas
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
            tabcontent[i].classList.remove("fade-in");
        }

        // Remove o estado 'ativo' de todos os links
        tablinks = document.getElementsByClassName("tab-link");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Exibe a aba clicada com animação e seta o link como ativo
        document.getElementById(tabName).style.display = "block";
        document.getElementById(tabName).classList.add("fade-in");
        evt.currentTarget.className += " active";
    }
    </script>
</body>
</html>