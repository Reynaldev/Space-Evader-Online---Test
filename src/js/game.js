var username = "Player" + Math.floor(Math.random() * 10000);

function user() {
    let input = prompt("Enter your username", username);

    if (input == "" || input == null || input.match(/[!@#$%^&*(){}:"<>?/,.;']/g)) {
        alert("Please enter your username!");
        user();
    } else {
        username = input;
    }
}

function commentPost(val) {
    document.getElementById("inputName").value = username;
    
    if (val) {
        document.getElementById("comment-toggle").style.display = "none";
        document.getElementById("comment-form").style.display = "";

    } else {
        document.getElementById("comment-toggle").style.display = "";
        document.getElementById("comment-form").style.display = "none";
    }
}

function startGame() {
    document.getElementById("game-overlay").style.display = "none";
    
    // Game
    game.start();

    // Player
    player = new Player();
    player.username = username;

    // Player ship
    ship = new Ship(30, 30, "/src/image/player.png", (game.canvas.width - 30) / 2, 380);

    // Meteors
    meteors = [];
    for (let i = 0; i < 3; i++) {
        let meteor = new Meteor(30, 30, "/src/image/meteor.png", Math.random() * (game.canvas.width - 30), 0);
        meteor.speedY = Math.random() * 20;

        meteors.push(meteor);
    }

    // Background
    background = new Background(game.canvas.width, game.canvas.height, "/src/image/background.png", 0, 0);
}

// Game System
var ship, meteors = [], background, player;

var game = {
    canvas : document.createElement("canvas"),
    start : function() {
        this.canvas.width = 240;
        this.canvas.height = 426;

        this.context = this.canvas.getContext("2d");

        this.canvas.style.position = "absolute";
        this.canvas.style.border = "2px solid black";
        this.canvas.style.left = "50%";
        this.canvas.style.transform = "translateX(-50%)";
        this.canvas.style.zIndex = "-1";

        document.getElementById("game-section").appendChild(this.canvas);

        this.interval = setInterval(update, 20);

        window.addEventListener('keydown', function(e) {
            game.key = e.code;
        })

        window.addEventListener('keyup', function(e) {
            game.key = false;
        })
    },
    clear : function() {
        this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
    },
    stop : function() {
        clearInterval(this.interval);
    }
}

class Player {
    constructor() {
        this.username = "";
        this.score = 0;
    }

    incScore() {
        this.score++;
    }

    getName() {
        return this.username;
    }

    getScore() {
        return this.score;
    }
}

class GameObject {
    constructor(width, height, image, x, y) {
        this.width = width;
        this.height = height;

        this.image = new Image();
        this.image.src = image;

        this.x = x;
        this.y = y;
    }
}

class Ship extends GameObject {
    constructor(width, height, image, x, y) {
        super(width, height, image, x, y);

        this.speedX = 0;
    }

    update() {
        this.ctx = game.context;
        this.ctx.drawImage(this.image, this.x, this.y, this.width, this.height);
    }

    move() {
        this.x += this.speedX;
    }

    collideWith(otherObject) {
        let left = this.x;
        let right = this.x + this.width;
        let top = this.y;
        let bottom = this.y + this.height;

        let otherLeft = otherObject.x;
        let otherRight = otherObject.x + otherObject.width;
        let otherTop = otherObject.y;
        let otherBottom = otherObject.y + otherObject.height;

        let crash = true;
        if ((top > otherBottom) ||
            (bottom < otherTop) ||
            (left > otherRight) ||
            (right < otherLeft)) {
                crash = false;
        }

        return crash;
    }
}

class Meteor extends GameObject {
    constructor(width, height, image, x, y) {
        super(width, height, image, x, y);

        this.speedY = 0;
    }

    update() {
        this.ctx = game.context;
        this.ctx.drawImage(this.image, this.x, this.y, this.width, this.height);
    }

    move() {
        this.y += this.speedY;
    }
}

class Background extends GameObject {
    constructor(width, height, image, x, y) {
        super(width, height, image, x, y);
    }

    update() {
        this.ctx = game.context;
        this.ctx.drawImage(this.image, this.x, this.y, this.width, this.height);
    }
}

function update() {
    for (let i = 0; i < meteors.length; i++) {
        if (ship.collideWith(meteors[i])) {
            uploadScore(player.getName(), player.getScore());
            game.stop();

            document.getElementById("game-overlay").style.display = "";
            document.getElementById("scoreboard").innerText = `Your score: ${player.getScore()}`;


            return;
        }
    }

    game.clear();

    player.incScore();

    background.update();

    // Meteors
    for (let i = 0; i < meteors.length; i++) {
        // If a meteor pass the canvas height
        if (meteors[i].y > game.canvas.height) {
            meteors[i].x = Math.random() * (game.canvas.width - 30);
            meteors[i].y = 0;
            meteors[i].speedY = Math.random() * 20;
        }

        meteors[i].move();
        meteors[i].update();
    }
    // Meteors

    // Player
    // Player movement
    ship.speedX = 0;
    if (game.key == "KeyA" || game.key == "ArrowLeft") { ship.speedX = -5; }
    if (game.key == "KeyD" || game.key == "ArrowRight") { ship.speedX = 5; }

    // Player border
    if (ship.x > (game.canvas.width - ship.width)) { ship.x = 0; }
    if (ship.x < 0) { ship.x = (game.canvas.width - ship.width); }

    ship.move();
    ship.update();
    // Player
}

// Game System

// Upload score
function uploadScore(username, score) {
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function() {
        player.username = this.responseText;
        player.score = this.responseText;
    }
    
    xhttp.open("POST", "/web/uploadScore.php?n=" + username + "&s=" + score, true);
    xhttp.send();
    
    console.log(xhttp.status);
}
// Upload score
