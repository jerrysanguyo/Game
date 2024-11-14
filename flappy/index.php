<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Floppy Mario</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body, html {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #70c5ce;
    }

    canvas {
        display: block;
        margin: 0;
        padding: 0; 
        position: absolute;
        top: 0;
        left: 0;
    }
  </style>
</head>
<body>
    <canvas id="canvas"></canvas>
    <button id="startButton" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 10px 20px; font-size: 20px; cursor: pointer;">Click to Play</button>

    <script>
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const startButton = document.getElementById('startButton');

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        const birdImg = new Image();
        birdImg.src = 'mario.png'; 

        const topPipeImg = new Image();
        topPipeImg.src = 'pipe-up.png';

        const bottomPipeImg = new Image();
        bottomPipeImg.src = 'pipe-down.png';

        const backgroundImg = new Image();
        backgroundImg.src = 'bg.jpg';

        let gamePlaying = false;
        const gravity = 1;
        const speed = 5.5;
        const size = [51, 36];
        const jump = -11.5;
        const cTenth = (canvas.width / 100);

        let index = 0,
            bestScore = 0, 
            flight, 
            flyHeight, 
            currentScore, 
            pipes;

        const pipeWidth = 78;
        const pipeGap = 300;
        const pipeLoc = () => (Math.random() * ((canvas.height - (pipeGap + pipeWidth)) - pipeWidth)) + pipeWidth;

        const setup = () => {
            currentScore = 0;
            flight = jump;
            flyHeight = (canvas.height / 2) - (size[1] / 2);
            
            pipes = Array(20).fill().map((_, i) => [
                canvas.width + (i * (pipeGap + pipeWidth)), 
                pipeLoc() 
            ]);
        }

        const render = () => {
            index++;
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            ctx.drawImage(backgroundImg, 0, 0, canvas.width, canvas.height);

            ctx.fillStyle = "#000";
            ctx.textAlign = "center";
            ctx.font = "bold 40px 'Press Start 2P', cursive";
            ctx.fillText("Floppy Mario", canvas.width / 2, 50);

            // Draw score at the top
            ctx.font = "20px 'Press Start 2P', cursive";
            ctx.fillText(`Best: ${bestScore}`, canvas.width / 4, 90);
            ctx.fillText(`Current: ${currentScore}`, (canvas.width / 4) * 3, 90);

            // Pipe display
            if (gamePlaying) {
                pipes.map(pipe => {
                    pipe[0] -= speed;

                    // Draw top pipe
                    ctx.drawImage(topPipeImg, pipe[0], 0, pipeWidth, pipe[1]);

                    // Draw bottom pipe
                    ctx.drawImage(bottomPipeImg, pipe[0], pipe[1] + pipeGap, pipeWidth, canvas.height - pipe[1] - pipeGap);

                    // Check if the pipe has moved off the screen to the left
                    if (pipe[0] + pipeWidth < 0) {
                        currentScore++; // Increment currentScore when a pipe goes off-screen
                        bestScore = Math.max(bestScore, currentScore); // Update bestScore if needed
                        pipes = [...pipes.slice(1), [pipes[pipes.length - 1][0] + pipeGap + pipeWidth, pipeLoc()]];
                    }

                    // Check for collision
                    if ([
                        pipe[0] <= cTenth + size[0],
                        pipe[0] + pipeWidth >= cTenth,
                        pipe[1] > flyHeight || pipe[1] + pipeGap < flyHeight + size[1]
                    ].every(elem => elem)) {
                        gamePlaying = false;
                        startButton.style.display = 'block'; // Show the button when the game stops
                        setup();
                    }
                });
            }

            // Draw bird
            if (gamePlaying) {
                ctx.drawImage(birdImg, cTenth, flyHeight, ...size);
                flight += gravity;
                flyHeight = Math.min(flyHeight + flight, canvas.height - size[1]);
            } else {
                ctx.drawImage(birdImg, ((canvas.width / 2) - size[0] / 2), flyHeight, ...size);
                flyHeight = (canvas.height / 2) - (size[1] / 2);

                ctx.textAlign = "center";
                ctx.textBaseline = "middle";
                ctx.font = "bold 30px courier";

                ctx.fillText(`Best score : ${bestScore}`, canvas.width / 2, canvas.height / 2 - 50);
            }

            window.requestAnimationFrame(render);
        }

        // launch setup
        setup();
        birdImg.onload = render;

        // Handle button click to start or restart the game
        startButton.addEventListener('click', () => {
            gamePlaying = true;
            startButton.style.display = 'none'; // Hide the button when the game starts
            flight = jump; // Reset flight for a new start
        });

        // Handle bird jump
        window.onclick = () => {
            if (gamePlaying) flight = jump;
        };
    </script>
</body>
</html>