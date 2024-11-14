let player = document.getElementById('player');
let obstacle = document.getElementById('obstacle');
let scoreDisplay = document.getElementById('score');
let jumpSound = document.getElementById('jumpSound');
let gameOverSound = document.getElementById('gameOverSound');
let isJumping = false;
let score = 0;

let obstacleSpeed = 3; 
updateObstacleSpeed();

player.style.bottom = '0px';

document.addEventListener('click', jump);

function jump() {
  if (isJumping) return;
  jumpSound.play(); // Play sound when jumping
  isJumping = true;
  let jumpHeight = parseInt(player.style.bottom);

  let upInterval = setInterval(() => {
    if (jumpHeight >= 150) {
      clearInterval(upInterval);
      let downInterval = setInterval(() => {
        if (jumpHeight <= 0) {
          clearInterval(downInterval);
          jumpHeight = 0;
          player.style.bottom = jumpHeight + 'px';
          isJumping = false;
        }
        jumpHeight -= 10;
        player.style.bottom = jumpHeight + 'px';
      }, 30);
    }
    jumpHeight += 10;
    player.style.bottom = jumpHeight + 'px';
  }, 30);
}

let timeElapsed = 0;

setInterval(() => {
  score += 10;
  scoreDisplay.textContent = 'Score: ' + score;

  timeElapsed += 1;

  if (timeElapsed % 10 === 0) {
    obstacleSpeed -= 0.2;
    if (obstacleSpeed < 1) obstacleSpeed = 1;
    updateObstacleSpeed();
  }
}, 1000);

function updateObstacleSpeed() {
  obstacle.style.animation = 'none';
  obstacle.offsetHeight;
  obstacle.style.animation = `moveObstacle ${obstacleSpeed}s linear infinite`;
}

setInterval(() => {
  let playerRect = player.getBoundingClientRect();
  let obstacleRect = obstacle.getBoundingClientRect();

  if (
    playerRect.left < obstacleRect.right &&
    playerRect.right > obstacleRect.left &&
    playerRect.bottom > obstacleRect.top
  ) {
    gameOverSound.play(); // Play sound when game over
    alert('Game Over! Your score: ' + score);
    score = 0;
    scoreDisplay.textContent = 'Score: 0';
    location.reload();
  }
}, 10);
