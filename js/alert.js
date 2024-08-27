const styles = `
        .message-container {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            width: 90%;
            max-width: 600px;
            background: #1b1b1b;
            color: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            animation: slideDown 0.5s ease-out;
        }
        .message {
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: bold;
        }
        .message-button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        .message-button:hover {
            background-color: #45a049;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translate(-50%, -100%);
            }
            to {
                opacity: 1;
                transform: translate(-50%, 0%);
            }
        }
    `;

function showMessage(message, type) {
    const messageContainer = document.getElementById('message-container');
    const messageElement = document.createElement('div');
    messageElement.className = `message ${type}`;
    messageElement.innerHTML = `
        ${message}
        <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
    `;

    messageElement.style.opacity = 0;
    messageElement.style.transform = 'translate(-50%, -100%)';
    messageElement.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';

    messageContainer.appendChild(messageElement);

    setTimeout(() => {
        messageElement.style.opacity = 1;
        messageElement.style.transform = 'translate(-50%, 0%)';
    }, 50);

    setTimeout(() => {
        messageElement.style.opacity = 0;
        messageElement.style.transform = 'translate(-50%, -100%)';
        setTimeout(() => {
            messageElement.remove();
        }, 500);
    }, 3000);
}

function continueMessage(message, yesButtonLabel, noButtonLabel, yesCallback, noCallback) {

    const messageContainer = document.createElement('div');
    messageContainer.className = 'message-container';

    const messageElement = document.createElement('div');
    messageElement.className = 'message';

    const messageText = document.createElement('p');
    messageText.textContent = message;

    const yesButton = document.createElement('button');
    yesButton.textContent = yesButtonLabel;
    yesButton.className = 'message-button';

    yesButton.onclick = () => {
        if (yesCallback && typeof yesCallback === 'function') {
            yesCallback();
        }
        document.body.removeChild(messageContainer);
        document.head.removeChild(styleSheet);
    };

    const noButton = document.createElement('button');
    noButton.textContent = noButtonLabel;
    noButton.className = 'message-button';

    noButton.onclick = () => {
        if (noCallback && typeof noCallback === 'function') {
            noCallback();
        }
        document.body.removeChild(messageContainer);
        document.head.removeChild(styleSheet);
    };

    messageElement.appendChild(messageText);
    messageElement.appendChild(yesButton);
    messageElement.appendChild(noButton);

    messageContainer.appendChild(messageElement);
    document.body.appendChild(messageContainer);

    const styleSheet = document.createElement('style');
    styleSheet.type = 'text/css';
    styleSheet.innerText = styles;
    document.head.appendChild(styleSheet);
}