jQuery(document).ready(function ($) {
    $('#feedback-form').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let message = $("#response");

        let name = form.find('#name').val();
        let email = form.find('#email').val();
        let feedback = form.find('#feedback').val();

        $.ajax({
            url: feedbackForm.ajax_url,
            type: 'POST',
            data: {
                action: "submit_feedback",
                nonce: feedbackForm.nonce,
                name: name,
                email: email,
                feedback: feedback,
            },
            beforeSend: function () {
                form.find("button").prop("disabled", true);
                message.text("Submitting...").removeClass("success error");
            },
            success: function (response) {
                form.find("button").prop("disabled", false);

                if (response.success) {
                    message.text(response.data).addClass("success");
                    form.trigger("reset"); // clear the form instead of hiding
                } else {
                    message.text(response.data).addClass("error");
                }
            },
            error: function () {
                form.find("button").prop("disabled", false);
                message.text("There was an error. Please try again.").addClass("error");
            }
        });
    });
});
