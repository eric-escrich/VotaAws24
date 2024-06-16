<script src="componentes/darkmode.js"></script>
<?php
session_start();
require 'data/dbAccess.php';

try {
    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $pw);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

$saludo = "Bienvenido!";
$dashboard = false;
$createPoll = false;
$listPolls = false;
$myVotes = false;
$validAcount = false;
$logout = false;
$login = true;
$register = true;
$userName = false;

if (isset($_SESSION["usuario"])) {
    $query = $pdo->prepare("SELECT * FROM User WHERE user_id = :id");
    $query->execute([':id' => $_SESSION["usuario"]]);
    $userRow = $query->fetch(PDO::FETCH_ASSOC);

    if ($userRow) {
        if (isset($userRow['customer_name'])) {
            $_SESSION["nombre"] = $userRow['customer_name'];
            $saludo = "Bienvenido " . $userRow['customer_name'] . "!";
            $userName = true;
            $logout = true;
            $login = false;
            $register = false;

            if ($userRow['is_mail_valid'] && $userRow['conditions_accepted']) {
                $dashboard = true;
                $createPoll = true;
                $listPolls = true;
                $myVotes = true;
                $validAcount = true;
            }
        }
    } else {
        echo "<script>console.log('Usuario no encontrado en la base de datos');</script>";
    }
} else {
    echo "<script>console.log('No hay sesión de usuario activa');</script>";
}
?>
<nav class="navbar">
    <ul>
        <li>
            <a href="/index.php">
                <svg class="icono" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 19h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v5.5" />
                    <path d="M19 22v-6" />
                    <path d="M22 19l-3 -3l-3 3" />
                    <path d="M3 7l9 6l9 -6" />
                </svg>
            </a>
        </li>
        <li class="spacer"></li>
        <li class="saludo">
            <p><?php echo $saludo; ?></p>
        </li>
        <li class="toggle-switch">
            <span>Modo oscuro</span>
            <input type="checkbox" id="darkModeSwitch">
            <label for="darkModeSwitch"></label>
        </li>
    </ul>
</nav>

<script>
    const menuItems = {
        dashboard: <?php echo json_encode($dashboard); ?>,
        createPoll: <?php echo json_encode($createPoll); ?>,
        listPolls: <?php echo json_encode($listPolls); ?>,
        myVotes: <?php echo json_encode($myVotes); ?>,
        validAcount: <?php echo json_encode($validAcount); ?>,
        logout: <?php echo json_encode($logout); ?>,
        login: <?php echo json_encode($login); ?>,
        register: <?php echo json_encode($register); ?>
    };
    console.log(menuItems);  // Añadir depuración aquí para verificar que los valores son correctos
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="componentes/handleNav.js"></script>