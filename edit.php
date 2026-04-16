<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Seleção - Copa do Mundo</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<body class="container">
    <main>
        <article>
            <header>
                <h1>📝 Editar Seleção</h1>
            </header>
            
            <form action="index.php?action=atualizar" method="POST">
                
                <input type="hidden" name="id" value="<?= $selecao['id'] ?>">

                <label for="nome">Nome da Seleção</label>
                <input type="text" id="nome" name="nome" value="<?= $selecao['nome'] ?>" required>

                <div class="grid">
                    <div>
                        <label for="grupo">Grupo</label>
                        <input type="text" id="grupo" name="grupo" maxlength="1" value="<?= $selecao['grupo'] ?>" required>
                    </div>
                    <div>
                        <label for="titulos">Títulos</label>
                        <input type="number" id="titulos" name="titulos" value="<?= $selecao['titulos'] ?>">
                    </div>
                </div>

                <footer class="grid">
                    <button type="submit" class="primary">Salvar Alterações</button>
                    <a href="index.php" role="button" class="secondary outline">Cancelar</a>
                </footer>
            </form>
        </article>
    </main>
</body>
</html>