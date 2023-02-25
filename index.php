<?php include("connection.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Space Evader</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <!-- Styling -->
    <link rel="stylesheet" href="/src/css/style.css">
</head>
<body onload="user()">
    <header>
        <h1 style="text-align: center; font-family: 'BungeeShade';">Space Evader</h1>
    </header>

    <main class="container">
        <section id="game-section">
            <div id="game-overlay">
                <h3 id="scoreboard">Click START to begin</h3>
                <button id="button-start" type="submit" onclick="startGame()">START</button>
            </div>
        </section>

        <section>
            <div class="row">
                <div class="col-6">
                    <h3 style="text-align: center; font-family: 'BungeeShade';">About the game</h3>
                    <hr/>
                    <p>
                        <b>Description</b>
                        <br/>
                        Space Evader is a game where you evade meteors as long as possible.
                        
                        <br/>
                        <br/>
                        
                        <b>Controls</b>
                        <br/>
                        A|D or Left|Right to Move
                    </p>
                </div>
                <div class="col-6">
                    <h3 style="text-align: center; font-family: 'BungeeShade';">Top 10 Hi-Scores</h3>
                    <hr/>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Rank</th>
                                <th scope="col">Username</th>
                                <th scope="col">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $sql = "SELECT * FROM player ORDER BY score DESC LIMIT 10";
                            $result = $conn->query($sql);

                            $rank = 1;

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                        echo "<td>".$rank."</td>";
                                        echo "<td>".$row["username"]."</td>";
                                        echo "<td>".$row["score"]."</td>";
                                    echo "</tr>";

                                    $rank += 1;
                                }
                            }
                            
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <br/><br/><br/>

        <section>
            <h3 style="text-align: center; font-family: 'BungeeShade';">Comments</h3>
            <hr/>
            <button id="comment-toggle" class="btn btn-secondary" onclick="commentPost(true)">+ Post Comment</button>
            <div id="comment-form" style="display: none;">
                <form action="/web/uploadComment.php"  method="POST">
                    <div class="mb-3 row">
                        <label for="inputName" class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input id="inputName" class="form-control" name="username" readonly>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="inputComment" class="col-sm-2 col-form-label">Comment</label>
                        <div class="col-sm-10">
                            <textarea id="inputComment" class="form-control" name="comment" rows="3" autofocus required></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-secondary">Post</button>
                    <button class="btn btn-danger" onclick="commentPost(false)">Cancel</button>
                </form>
            </div>
        </section>

        <section id="comment-section">
            <?php
            
            $sql = "SELECT * FROM comment ORDER BY datepublished DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<hr/>";
                    echo "<div>";
                        echo "<h3>".$row["username"]."</h3>";
                        echo "<p>".$row["datepublished"]."</p>";
                        echo "<p>".$row["commenttext"]."</p>";
                    echo "</div>";
                }
            }

            $conn->close();
            
            ?>
        </section>
    </main>

    <footer>
        <p>Copyright&copy; 2023-present, ReynDev.</p>
    </footer>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM=" crossorigin="anonymous"></script>
    <!-- Scripting -->
    <script src="/src/js/game.js"></script>
</body>
</html>