function handleClick(userType) {
    let button = userType === 'customer' ? document.querySelector('.customer-btn') : document.querySelector('.admin-btn');
    
    // Add a quick shake animation on click
    button.style.animation = "shake 0.5s ease-in-out";

    setTimeout(() => {
        button.style.animation = "";
        if (userType === 'customer') {
            
            window.location.href = "cuslogin.php"; // Change to actual page
        } else {
            
            window.location.href = "adlogin.php"; // Change to actual page
        }
    }, 500);
}

/* Shake Animation */
const style = document.createElement('style');
style.innerHTML = `
    @keyframes shake {
        0% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        50% { transform: translateX(5px); }
        75% { transform: translateX(-5px); }
        100% { transform: translateX(0); }
    }
`;
document.head.appendChild(style);
