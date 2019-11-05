<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/ranking.css">
    <script src="../js/ranking.js"></script>
    <title>Ranking</title>
</head>

<body>
    <table>
        <tr>
            <th>RANK</th>
            <th>NAME</th>
            <th>FAILED<br>ATTEMPTS</th>
            <th>TIME (s)</th>
        </tr>
        <?php
        //Import all the records from the file and append them to an array
        $records = explode("\n", trim(file_get_contents('../resources/rankingData.txt')));

        //Separate the records by failed attempts and add them to an array of arrays,
        //where the index indicates how many attempts are failed
        $rankingByAttempts = [[], [], [], [], [], []];
        foreach ($records as $record) {
            $splitedRecord = explode(';', $record);
            $attempts = $splitedRecord[1];
            $rankingByAttempts[$attempts][count($rankingByAttempts[$attempts])] = $splitedRecord;
        }

        $rankingByTime = [[], [], [], [], [], []];
        foreach ($rankingByAttempts as $i => $subArray) {
            uasort($subArray, function ($a, $b) {
                return intval($a[2]) - intval($b[2]);
            });
            $rankingByTime[$i] = $subArray;
        }
        //Check if have all the needed paramethers in the post 
        $requiredPost = ['name', 'failedAttempts', 'gameTime'];
        $missing = false;
        foreach ($requiredPost as $key) {
            if (!isset($_POST[$key])) {
                $missing = true;
            }
        }
        //prints $rankingByAttempts like a table
        $index = 1;
        foreach ($rankingByTime as $array) {
            foreach ($array as  $att) {
                $time = intval($att[2]) / 1000;
                if (!$missing and $_POST["name"] == $att[0] and $_POST["failedAttempts"] == $att[1] and ($_POST["gameTime"] == $att[2])) {
                    echo "<tr id=highlight><td>" . $index . "</td><td>" . $att[0] . "</td><td>" . $att[1] . "</td><td>" . $time . "</tr>";
                } else {
                    echo "<tr><td>" . $index . "</td><td>" . $att[0] . "</td><td>" . $att[1] . "</td><td>" . $time . "</tr>";
                }
                $index++;
            }
        }
        ?>
    </table>
    <div class="buttons">
        <button onclick="window.location.href='../index.php'" class="button" accesskey="m">Menu</button>
    </div>
    <div id="options">
        <i id="colorBlindness" class="material-icons off"> visibility_off </i>
    </div>

</body>

</html>