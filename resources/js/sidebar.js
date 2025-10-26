document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.querySelector('.sidebar-toggle');
    
    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
    }

    // Toggle sidebar when button is clicked
    toggleBtn?.addEventListener('click', toggleSidebar);

    // Close sidebar when clicking outside
    document.addEventListener('click', function(event) {
        if (!sidebar?.contains(event.target) && 
            !toggleBtn?.contains(event.target) && 
            !sidebar?.classList.contains('-translate-x-full')) {
            toggleSidebar();
        }
    });

    // Handle responsive behavior
    function handleResize() {
        if (window.innerWidth >= 640) { // sm breakpoint
            sidebar?.classList.remove('-translate-x-full');
        } else {
            sidebar?.classList.add('-translate-x-full');
        }
    }

    // Initial check and listen for resize events
    handleResize();
    window.addEventListener('resize', handleResize);
});