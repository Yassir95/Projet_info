document.addEventListener("DOMContentLoaded", function() {
    const textareas = document.querySelectorAll('.auto-resize-textarea');

    textareas.forEach(textarea => {
        function autoResize() {
            textarea.style.height = 'auto'; 
            textarea.style.height = textarea.scrollHeight + 'px';
        }

        textarea.addEventListener('input', autoResize);

        autoResize();
    });
});