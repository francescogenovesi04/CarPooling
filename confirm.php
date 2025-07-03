<?php
session_start();
?>

<html>
<head>
    <meta charset="UTF-8">
    <title>Conferma Prenotazione</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e9f6ff;
            padding: 20px;
            text-align: center;
        }
        .box {
            background-color: #ffffff;
            border-left: 5px solid #4CAF50;
            display: inline-block;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .box p {
            font-size: 18px;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>Prenotazione confermata!</h2>
    <p><strong>Nome:</strong> <?= htmlspecialchars($_SESSION['nome']) ?></p>
    <p><strong>Cognome:</strong> <?= htmlspecialchars($_SESSION['cognome']) ?></p>
    <p><strong>Telefono:</strong> <?= htmlspecialchars($_SESSION['telefono']) ?></p>
    <hr>
    <p><strong>Partenza:</strong> <?= htmlspecialchars($_SESSION['citta_partenza']) ?></p>
    <p><strong>Arrivo:</strong> <?= htmlspecialchars($_SESSION['citta_arrivo']) ?></p>
    <p><strong>Data:</strong> <?= htmlspecialchars($_SESSION['data_partenza']) ?></p>

    <a href="index.php">Torna alla Home</a>
</div>

</body>
</html>
