/**
 * Character Counter for Text Areas and Text Inputs
 * 
 * Provides real-time character counting with visual feedback for form fields
 * with maxlength constraints.
 * 
 * @package   CTLT.iPeer
 * @license   MIT
 */

// Initialize character counters when DOM is ready
(function() {
    // Function to update character counter
    function updateCharCounter(textarea, counter) {
        if (!textarea || !counter) return;
        
        var maxlength = parseInt(textarea.getAttribute('maxlength'));
        var current = textarea.value.length;
        var remaining = maxlength - current;
        
        // Update counter text
        counter.textContent = current + '/' + maxlength + ' characters';
        
        // Apply color coding based on remaining characters
        if (remaining <= 10) {
            // Red alert: 10 or fewer characters remaining
            counter.style.color = 'red';
            counter.style.fontWeight = 'bold';
        } else if (remaining <= 50) {
            // Orange warning: 50 or fewer characters remaining
            counter.style.color = 'orange';
            counter.style.fontWeight = 'normal';
        } else {
            // Normal: plenty of space
            counter.style.color = '#666';
            counter.style.fontWeight = 'normal';
        }
    }
    
    // Function to initialize a single character counter
    function initCharCounter(element) {
        var counterId = element.getAttribute('data-counter-id');
        if (!counterId) return;
        
        var counter = document.getElementById(counterId);
        if (!counter) return;
        
        // Initialize the counter display
        updateCharCounter(element, counter);
        
        // Add event listeners
        element.addEventListener('input', function() {
            updateCharCounter(element, counter);
        });
        
        element.addEventListener('keyup', function() {
            updateCharCounter(element, counter);
        });
        
        element.addEventListener('change', function() {
            updateCharCounter(element, counter);
        });
        
        // Handle paste events (with slight delay to get new value)
        element.addEventListener('paste', function() {
            var that = this;
            var counter = document.getElementById(counterId);
            setTimeout(function() {
                updateCharCounter(that, counter);
                
                // Show warning if text was truncated
                var maxlength = parseInt(that.getAttribute('maxlength'));
                if (that.value.length >= maxlength) {
                    // Optional: Add visual feedback for truncation
                    counter.style.color = 'red';
                    counter.style.fontWeight = 'bold';
                }
            }, 10);
        });
    }
    
    // Function to initialize all character counters on the page
    function initAllCharCounters() {
        // Find all elements with char-limited class
        var elements = document.querySelectorAll('.char-limited');
        
        // For older browsers that don't support querySelectorAll
        if (!elements.length && document.getElementsByClassName) {
            elements = document.getElementsByClassName('char-limited');
        }
        
        // Initialize each element
        for (var i = 0; i < elements.length; i++) {
            initCharCounter(elements[i]);
        }
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAllCharCounters);
    } else {
        // DOM already loaded
        initAllCharCounters();
    }
    
    // Also support jQuery if available (for compatibility with existing iPeer code)
    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function() {
            initAllCharCounters();
        });
    }
    
    // Expose function globally for dynamic content
    window.initCharCounters = initAllCharCounters;
})();

