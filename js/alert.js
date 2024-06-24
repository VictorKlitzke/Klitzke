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