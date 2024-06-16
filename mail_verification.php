<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    require 'PHPMailer-master/src/Exception.php';
    require 'PHPMailer-master/src/PHPMailer.php';
    require 'PHPMailer-master/src/SMTP.php';
    require 'data/dbAccess.php';
    require 'data/mailCredentials.php';

    $logFilePath = "logs/log" . date("d-m-Y") . ".txt";
    if (!file_exists(dirname($logFilePath))) {
        mkdir(dirname($logFilePath), 0755, true);
    }
    $filePathParts = explode("/", __FILE__);

    $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $pw);
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Verificación de email</title>
        <meta name="description" content="Verifica tu dirección de email para acceder a 'Vota!'">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <link rel="shortcut icon" href="/icons/faviconDark.svg" type="image/svg">
        <link rel="shortcut icon" href="/icons/favicon.svg" type="image/svg" media="(prefers-color-scheme: light)">
        <script src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
        <script src="/componentes/notificationHandler.js"></script>
    </head>

    <body id="mailVerification">
        <?php
        include_once ("common/header.php");
        // if (!isset($_SESSION["usuario"])) {
        //     header("HTTP/1.1 403 Forbidden");
        //     exit();
        // }
    
        if (isset($_GET['token'])) {
            $query = $pdo->prepare("SELECT * FROM User WHERE token = :token");
            $query->execute([':token' => $_GET['token']]);
            $row = $query->fetch();

            if ($row && $row['user_id']) {
                $_SESSION["usuario"] = $row['user_id'];

                $query = $pdo->prepare("UPDATE User SET is_mail_valid = true WHERE user_id = :id");
                $query->execute([':id' => $_SESSION["usuario"]]);
                $logTxt = "\n[" . end($filePathParts) . " ― " . date('H:i:s') . " ― MAIL VAERIFICATED]: Se ha verificado el email.\n";
                file_put_contents($logFilePath, $logTxt, FILE_APPEND);
                if ($row['conditions_accepted']) {
                    header("Location: dashboard.php?succ=1");
                    exit();
                } else {
                    header("Location: terms_conditions.php");
                    echo "successfulNotification('¡Email verificado!')";
                    exit();
                }
            }
        }

        $query = $pdo->prepare("SELECT * FROM User WHERE user_id = :id");
        $query->execute([':id' => $_SESSION["usuario"]]);
        $row = $query->fetch();

        if ($row) {
            if ($row['is_mail_valid']) {
                if ($row['conditions_accepted']) {
                    header("Location: dashboard.php");
                    exit();
                } else {
                    header("Location: terms_conditions.php");
                    exit();
                }
            } else {
                if ($row['token'] != null) {
                    $token = $row['token'];
                } else {
                    $token = bin2hex(random_bytes(50));
                    $query = $pdo->prepare("UPDATE User SET token = :token WHERE user_id = :user_id");
                    $query->execute([':token' => $token, ':user_id' => $_SESSION["usuario"]]);
                }

                $link = "https://eescrichalmagro.ieti.cat/vota/VotaAws24/mail_verification.php?token=$token";
                // $link = "http://0.0.0.0:8081/mail_verification.php?token=$token";
                try {
                    $mail = new PHPMailer();
                    $mail->IsSMTP();
                    $mail->Mailer = "smtp";
                    $mail->IsSMTP();
                    $mail->CharSet = 'UTF-8';
                    $mail->Encoding = 'base64';

                    // $mail->SMTPDebug = 1;
                    $mail->SMTPAuth = TRUE;
                    $mail->SMTPSecure = "tls";
                    $mail->Port = 587;
                    $mail->Host = "smtp.gmail.com";
                    $mail->Username = $emailUsername;
                    $mail->Password = $emailPassword;

                    $mail->IsHTML(true);
                    $mail->AddAddress($row['user_mail']);
                    $mail->SetFrom('vota@aws24.ieti.com', 'VOTA TEAM');
                    $mail->Subject = '¡VERIFICA TU CORREO ELECTRÓNICO!';
                    $name = $row['customer_name'];
                    $content = <<<HTML
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                background-color: #f2f2f2;
                                margin: 0;
                                padding: 0;
                            }
                            .container {
                                max-width: 600px;
                                margin: 50px auto;
                                background-color: #fff;
                                padding: 20px;
                                border-radius: 10px;
                                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                            }
                            h1 {
                                color: #333;
                                text-align: center;
                                font-size: 1.5rem;
                            }
                            p {
                                color: #666;
                                text-align: center;
                                font-size: 1.125rem;
                            }
                            p span {
                                text-align: right;
                            }
                            .container > div {
                                display: flex;
                                justify-content: center;
                            }
                            .container > div > a {
                                display: inline-block;
                                background-color: #4CAF50;
                                color: white;
                                padding: 0.625rem 1.25rem;
                                text-align: center;
                                text-decoration: none;
                                border-radius: 5px;
                                margin: 1.25rem auto;
                                cursor: pointer;
                                font-size: 1.125rem;
                            }
                            a:hover {
                                background-color: #45a049;
                            }
                        </style>
                    </head>
                    <body>
                        <div class="container">
                            <h1>Hola {$name},</h1>
                            <p>Para verificar tu cuenta de correo electrónico, por favor haz clic en el botón de abajo:</p>
                            <div>
                                <a href="{$link}">Verificar cuenta</a>
                            </div>
                            <p>Si no has solicitado verificar tu cuenta, por favor ignora este correo electrónico.</p>
                            <br>
                            <p>Gracias,<br>VOTA TEAM</p>
                        </div>
                    </body>
                    </html>
                    HTML;

                    $mail->MsgHTML($content);
                    $mail->CharSet = 'UTF-8';
                    if (!$mail->Send()) {
                        $logTxt = "\n[" . end($filePathParts) . " ― " . date('H:i:s') . " ― MAIL ERROR]: Ha habido un error al enviarle un email al correo " . $row['user_mail'] . ".\n";
                        file_put_contents($logFilePath, $logTxt, FILE_APPEND);
                        echo "<script>errorNotification('Ha surgido un error al enviar el email de verificacion al email " . $row['user_mail'] . "')</script>";
                    } else {
                        $logTxt = "\n[" . end($filePathParts) . " ― " . date('H:i:s') . " ― MAIL SEND]: Se ha enviado un email de verificacion al correo " . $row['user_mail'] . ".\n";
                        file_put_contents($logFilePath, $logTxt, FILE_APPEND);
                        echo "<script>successfulNotification('Se ha enviado un email de verificacion al correo " . $row['user_mail'] . "')</script>";
                    }
                } catch (Exception $e) {
                    $logTxt = "\n[" . end($filePathParts) . " ― " . date('H:i:s') . " ― MAIL ERROR]: Ha habido un error al enviarle un email al correo " . $row['user_mail'] . ".\n";
                    file_put_contents($logFilePath, $logTxt, FILE_APPEND);
                    echo "<script>errorNotification('Ha surgido un error al enviar el email de verificacion al email " . $row['user_mail'] . "')</script>";
                }
                ?>
                <main>
                    <h1>Verifica tu dirección de email</h1>
                    <p>Te hemos enviado un email a <b>
                            <?php echo $row['user_mail'] ?>
                        </b> con un enlace para verificar tu dirección de email.</p>
                    <p>Si no lo encuentras, revisa la carpeta de spam.</p>
                    <a href="https://mail.google.com/mail" target="_blank">Ir al correo</a>
                </main>

                <ul id="notification__list"></ul>
                <div class="footer">
                    <?php include_once ("common/footer.php") ?>
                </div>
                <script> successfulNotification('¡Registro completado!'); </script>
            </body>

            </html>
            <?php
            }
        } else {
            echo "<script>errorNotification('ERROR al consultar la base de datos.')</script>";
        }
} catch (PDOException $e) {
    $logTxt = "\n[" . end($filePathParts) . " ― " . date('H:i:s') . " ― DB ERROR]: Ha habido un error al conectarse a la base de datos: " . $e->getMessage() . "\n";
    file_put_contents($logFilePath, $logTxt, FILE_APPEND);
    echo "<script>errorNotification('Ha habido un error al conectarse a la base de datos.')</script>";
}