$(document).ready(function () {
    $('#feedbackForm').on('submit', function (e) {
        e.preventDefault(); // Prevent form from submitting traditionally
        
        // Capture form data
        var formData = $(this).serialize();

        // AJAX to submit the form data to the server
        $.ajax({
            type: "POST",
            url: "submit_feedback.php",
            data: formData,
            success: function(response) {
                // On success, display the response (customer's feedback)
                $('body').html(response);  // Replace the current page with the response
            },
            error: function() {
                alert("Error in form submission!");
            }
        });
    });
});
