document.addEventListener('DOMContentLoaded', () => {
    const board = document.getElementById('board');
    const message = document.getElementById('message');
    const resetButton = document.getElementById('resetButton');
    const scoreXDisplay = document.getElementById('scoreX');
    const scoreODisplay = document.getElementById('scoreO');

    let currentPlayer = 'X';
    let boardState = Array(9).fill(null);
    let gameActive = true;
    let scoreX = 0;
    let scoreO = 0;

    // Create board cells
    for (let i = 0; i < 9; i++) {
        const cell = document.createElement('div');
        cell.classList.add('cell');
        cell.dataset.index = i;
        board.appendChild(cell);
    }

    board.addEventListener('click', (e) => {
        const target = e.target;
        if (target.classList.contains('cell') && !target.classList.contains('taken') && gameActive) {
            const index = target.dataset.index;
            boardState[index] = currentPlayer;
            target.textContent = currentPlayer;
            target.classList.add('taken');
            checkWinner();
            currentPlayer = currentPlayer === 'X' ? 'O' : 'X';
        }
    });

    resetButton.addEventListener('click', resetGame);

    function checkWinner() {
        const winPatterns = [
            [0, 1, 2],
            [3, 4, 5],
            [6, 7, 8],
            [0, 3, 6],
            [1, 4, 7],
            [2, 5, 8],
            [0, 4, 8],
            [2, 4, 6]
        ];

        for (let pattern of winPatterns) {
            const [a, b, c] = pattern;
            if (boardState[a] && boardState[a] === boardState[b] && boardState[a] === boardState[c]) {
                message.textContent = `Player ${boardState[a]} wins!`;
                updateScore(boardState[a]);
                gameActive = false;
                return;
            }
        }

        if (!boardState.includes(null)) {
            message.textContent = "It's a draw!";
            gameActive = false;
        }
    }

    function updateScore(winner) {
        if (winner === 'X') {
            scoreX++;
            scoreXDisplay.textContent = scoreX;
        } else if (winner === 'O') {
            scoreO++;
            scoreODisplay.textContent = scoreO;
        }
    }

    function resetGame() {
        boardState.fill(null);
        currentPlayer = 'X';
        gameActive = true;
        message.textContent = '';
        document.querySelectorAll('.cell').forEach(cell => {
            cell.textContent = '';
            cell.classList.remove('taken');
        });
    }
});
