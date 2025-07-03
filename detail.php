<html>

<head>
    <meta charset="UTF-8">
    <title>Car Pooling</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        h1, h3 {
            text-align: center;
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            max-width: 400px;
            margin: 0 auto;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        input[type="text"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .trip-summary {
            background-color: #e7f3ff;
            border-left: 5px solid #2196F3;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            max-width: 500px;
            margin: 20px auto;
        }
    </style>
</head>
<body>

<?php

$connection = mysqli_connect("localhost","root","","car_pooling");
if(!$connection){
    die("Connessione al database non riuscita: " . mysqli_connect_error());
}

session_start();

//se i dati del viaggio arrivano da index.php, li salviamo in sessione
if(isset($_POST["id_viaggio"])){
    $_SESSION['id_viaggio'] = $_POST['id_viaggio'];
    $_SESSION['id_autista'] = $_POST['id_autista'];
    $_SESSION['citta_partenza'] = $_POST['citta_partenza'];
    $_SESSION['citta_arrivo'] = $_POST['citta_arrivo'];
    $_SESSION['data_partenza'] = $_POST['data_partenza'];
}

echo "<h2>Riepilogo dati del viaggio</h2>";
echo "<div class='trip-summary'>";
echo "<p><strong>Partenza:</strong> " . htmlspecialchars($_SESSION['citta_partenza']) . "</p>";
echo "<p><strong>Arrivo:</strong> " . htmlspecialchars($_SESSION['citta_arrivo']) . "</p>";
echo "<p><strong>Data della partenza:</strong> " . htmlspecialchars($_SESSION['data_partenza']) . "</p>";
echo "</div>";

echo "<h1>Prenotazione</h1>";
echo "<h3>Inserisci i tuoi dati per prenotare</h3>";
echo "<form action='detail.php' method='POST'>";
echo "Nome: <input type='text' name='nome' required><br>";
echo "Cognome: <input type='text' name='cognome' required><br>";
echo "Telefono: <input type='text' name='telefono' required><br>";
echo "<input type='submit' value='Conferma Prenotazione'>";
echo "</form>";

//se riceviamo i dati del passeggero
if(isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['telefono'])) {
    $nome = $_POST["nome"];
    $cognome = $_POST["cognome"];
    $telefono = $_POST["telefono"];

    $_SESSION['nome'] = $nome;
    $_SESSION['cognome'] = $cognome;
    $_SESSION['telefono'] = $telefono;

    //verifica se il passeggero è già registrato
    $check_if_exist = "SELECT id_passeggero FROM Passeggeri WHERE nome='$nome' AND cognome='$cognome' AND telefono='$telefono' LIMIT 1;";
    $result = mysqli_query($connection, $check_if_exist);

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        $id_passeggero = $row["id_passeggero"];
    } else {
        $sql = "INSERT INTO Passeggeri (nome, cognome, documento, telefono, mail)
                VALUES ('$nome', '$cognome', null, '$telefono', null);";
        mysqli_query($connection, $sql);
        $id_passeggero = mysqli_insert_id($connection);
    }

    $id_viaggio = $_SESSION['id_viaggio'];

    $query_prenotazione = "INSERT INTO Prenotazioni (id_viaggio, id_passeggero)
                           VALUES ('$id_viaggio', '$id_passeggero');";
    mysqli_query($connection, $query_prenotazione);

    //reindirizza alla pagina di conferma
    header("Location: confirm.php");
    exit;
}

?>

</body>
</html>
