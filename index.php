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
        input[type="date"],
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

        .results {
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .result-item {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }

        .trip-summary {
    background-color: #e7f3ff;
    border-left: 5px solid #2196F3;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 5px;
}

.result-item {
    background-color: #ffffff;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-left: 5px solid #4CAF50;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}

.result-item p {
    margin: 5px 0;
    color: #333;
}

.result-item strong {
    color: #000;
}

.no-results {
    text-align: center;
    color: #888;
    font-style: italic;
    margin-top: 20px;
}

    </style>
</head>
<body>
    <h1>Car pooling</h1>

    <h3>Cerca un viaggio:</h3>

    <form action="index.php" method="POST">
        <input name="citta_partenza" type="text" placeholder="Partenza" required></input>
        <input name="citta_arrivo" type="text" placeholder="Arrivo" required></input><br>
        <input name="data_partenza" type="date" placeholder="Data_partenza" required></input><br>
        <br>
        <input type="submit" value="Cerca"></input>
    </form>

    <?php
    
    $connection = mysqli_connect("localhost", "root", "", "car_pooling");

    if (!$connection) {
        die("Connessione al database non riuscita: " . mysqli_connect_error());
    }

//     echo "<pre>";
// var_dump($_POST);
// echo "</pre>";
    
    if (isset($_POST["citta_partenza"]) && isset($_POST["citta_arrivo"]) && isset($_POST["data_partenza"])) {
        $citta_partenza = mysqli_real_escape_string($connection, $_POST["citta_partenza"]);
        $citta_arrivo = mysqli_real_escape_string($connection, $_POST["citta_arrivo"]);
        $data_partenza = mysqli_real_escape_string($connection, $_POST["data_partenza"]);

        
        $sql = "
            SELECT Autisti.nome, Autisti.cognome, Auto.targa, Auto.modello, Viaggi.contributo_passeggero, 
            Auto.posti, Viaggi.id_viaggio, Autisti.id_autista, COUNT(Prenotazioni.id_passeggero) AS n_passeggeri
            FROM Autisti
            INNER JOIN Auto USING (id_autista)
            INNER JOIN Viaggi USING (id_autista)
            LEFT JOIN Prenotazioni USING (id_viaggio)
            WHERE Viaggi.citta_partenza = '$citta_partenza' AND Viaggi.citta_arrivo = '$citta_arrivo' AND Viaggi.data_partenza = '$data_partenza'
            GROUP BY Autisti.id_autista, Auto.posti
            HAVING Auto.posti - 1 - n_passeggeri > 0
            ORDER BY Viaggi.ora_partenza;
        ";


        $result = mysqli_query($connection, $sql);
        
        $n_rows = mysqli_num_rows($result);

        if ($n_rows > 0) {
            echo "<div class='trip-summary'>";
            echo "<p><strong>Partenza:</strong> " . htmlspecialchars($citta_partenza) . "</p>";
            echo "<p><strong>Arrivo:</strong> " . htmlspecialchars($citta_arrivo) . "</p>";
            echo "<p><strong>Data della partenza:</strong> " . htmlspecialchars($data_partenza) . "</p>";
            echo "</div>";

            
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='result-item'>";
                echo "<p><strong>Nome autista:</strong> " . htmlspecialchars($row["nome"]) . "</p>";
                echo "<p><strong>Cognome autista:</strong> " . htmlspecialchars($row["cognome"]) . "</p>";
                echo "<p><strong>Targa auto:</strong> " . htmlspecialchars($row["targa"]) . "</p>";
                echo "<p><strong>Modello auto:</strong> " . htmlspecialchars($row["modello"]) . "</p>";
                echo "<p><strong>Contributo passeggero:</strong> €" . htmlspecialchars($row["contributo_passeggero"]) . "</p>";
                echo "<p><strong>Posti auto:</strong> " . htmlspecialchars($row["posti"]) . "</p>";
                echo "<p><strong>Numero passeggeri:</strong> " . htmlspecialchars($row["n_passeggeri"]) . "</p>";
                echo "<p><strong>Posti disponibili:</strong> " . htmlspecialchars($row["posti"] - 1 - $row["n_passeggeri"]) . "</p>";
                echo "<form action='detail.php' method='POST'>";
                echo "<input type='hidden' name='id_viaggio' value='" . htmlspecialchars($row["id_viaggio"]) . "'>";
                echo "<input type='hidden' name='id_autista' value='" . htmlspecialchars($row["id_autista"]) . "'>";
                echo "<input type='hidden' name='citta_partenza' value='" . htmlspecialchars($citta_partenza) . "'>";
                echo "<input type='hidden' name='citta_arrivo' value='" . htmlspecialchars($citta_arrivo) . "'>";
                echo "<input type='hidden' name='data_partenza' value='" . htmlspecialchars($data_partenza) . "'>";
                echo "<input type='submit' value='Prenota'>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-results'>Appariranno qua i viaggi disponibili se ce ne sono</p>";
        }

       
        mysqli_free_result($result);
    } else {
        echo "<p class='no-results'>Inserisci i parametri di ricerca per trovare i viaggi.</p>";
    }

   
    mysqli_close($connection);
    ?>
</body>
</html>
