jQuery(document).ready(function ($) {
    console.log("🔎 Nonce Received from PHP:", reviews_api.nonce); // Debug Nonce

    var reviewForm = $("#reviewForm");
    var alertContainer = $("#alert-container");

    if (reviewForm.length) {
        reviewForm.on("submit", function (e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append("action", "submit_review");

            if (reviews_api.nonce) {
                formData.append("security", reviews_api.nonce); // ✅ Ensure the nonce is added
            } else {
                console.error("❌ Nonce is undefined! Check wp_localize_script()");
            }

            $.ajax({
                url: reviews_api.ajax_url,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    alertContainer.html('<div class="alert alert-info">⏳ Submitting your review...</div>');
                    reviewForm.find("button[type=submit]").prop("disabled", true).text("Submitting...");
                },
                success: function (response) {
                    console.log("✅ AJAX Response:", response); // Debug API response
                    if (response.success) {
                        var message = response.data.message || "🎉 Review submitted successfully!";
                        alertContainer.html('<div class="alert alert-success">' + message + '</div>');
                        reviewForm[0].reset();
                    } else {
                        var errorMessage = response.data.message || "⚠️ An unknown error occurred.";
                        alertContainer.html('<div class="alert alert-danger">' + errorMessage + '</div>');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("❌ AJAX Error:", status, error);
                    console.error("📥 Response Text:", xhr.responseText);
                    alertContainer.html('<div class="alert alert-danger">❌ An unexpected error occurred. Please try again.</div>');
                },
                complete: function () {
                    reviewForm.find("button[type=submit]").prop("disabled", false).text("Submit Review");
                }
            });
        });
    }

    // Swiper Initialization
    new Swiper(".swiper", {
        loop: true,
        pagination: { el: ".swiper-pagination", clickable: true },
        navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
    });

    // Star Rating Interaction
    $(".star-rating input").on("change", function () {
        $(".star-rating label").css("color", "rgba(0, 0, 0, 0.2)");
        var selected = false;
        $(".star-rating input").each(function () {
            if ($(this).is(":checked")) {
                selected = true;
            }
            if (selected) {
                $(this).next("label").css("color", "#0066CC");
            }
        });
    });
});