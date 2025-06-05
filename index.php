<?php
session_start();

// Redireciona para contacts.php se já estiver logado
if (isset($_SESSION['user_id'])) {
    header("Location: contacts.php");
    exit;
}

// Processar login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = new mysqli('mysql', 'root', 'root', 'agenda');
    
    $username = $conn->real_escape_string($_POST['username']);
    $password = md5($_POST['password']); // Posteriormente mudaremos para password_hash
    
    $sql = "SELECT id, username, role FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: contacts.php");
        exit;
    }
    
    $error = "Usuário ou senha inválidos";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Agenda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Login</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label>Usuário:</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                
                <div class="mb-3">
                    <label>Senha:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>
        </div>
    </div>
</body>
</html>