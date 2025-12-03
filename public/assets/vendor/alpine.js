// Alpine.js basic implementation
// Only implements basic functionality needed for admin layout

// Simulate x-data
function initAlpine() {
    const elements = document.querySelectorAll('[x-data]');
    
    elements.forEach(element => {
        const dataExpr = element.getAttribute('x-data');
        const data = eval(`(${dataExpr})`);
        
        // Save data to element
        element._alpineData = data;
        
        // Handle x-cloak
        element.style.display = '';
        
        // Handle x-show
        handleXShow(element);
        
        // Handle event listeners
        handleEvents(element);
    });
}

// Handle x-show directive
function handleXShow(element) {
    const xShowElements = element.querySelectorAll('[x-show]');
    
    xShowElements.forEach(el => {
        const expr = el.getAttribute('x-show');
        const value = eval(`with(element._alpineData) { ${expr} }`);
        el.style.display = value ? '' : 'none';
    });
}

// Handle event listeners
function handleEvents(element) {
    // Handle all elements with @click
    const clickElements = element.querySelectorAll('[@click]');
    clickElements.forEach(el => {
        const expr = el.getAttribute('@click');
        el.addEventListener('click', () => {
            with(element._alpineData) {
                eval(expr);
            }
            handleXShow(element);
            updateSidebarClass(element);
        });
    });
    
    // Handle all elements with x-on:click
    const xClickElements = element.querySelectorAll('[x-on\:click]');
    xClickElements.forEach(el => {
        const expr = el.getAttribute('x-on\:click');
        el.addEventListener('click', () => {
            with(element._alpineData) {
                eval(expr);
            }
            handleXShow(element);
            updateSidebarClass(element);
        });
    });
}

// Update sidebar classes
function updateSidebarClass(element) {
    const sidebar = element.querySelector('aside');
    const mainContent = element.querySelector('div.transition-all');
    
    if (sidebar && mainContent) {
        if (element._alpineData.sidebarOpen) {
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');
            mainContent.classList.remove('ml-20');
            mainContent.classList.add('ml-64');
        } else {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');
            mainContent.classList.remove('ml-64');
            mainContent.classList.add('ml-20');
        }
    }
}

// Initialize when page loads
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAlpine);
} else {
    initAlpine();
}