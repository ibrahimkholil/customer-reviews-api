<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<div class="container py-5">
    <div class="text-center mb-4">
        <div class="swiper">
            <div class="swiper-wrapper" id="reviewsContainer">
                <?php if (!empty($reviews)) : ?>
                    <?php foreach ($reviews as $review) : ?>
                        <div class="swiper-slide">
                            <div class="card">
                                <?php if (!empty($review['image_path'])) : ?>
                                    <img src="<?php echo esc_url($review['image_path']); ?>" alt="<?php esc_attr_e('Review Image', 'customer-reviews-api'); ?>" class="review-image">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="review-title"> <?php echo esc_html($review['title']); ?> </h5>
                                    <p class="review-text"> <?php echo esc_html($review['review']); ?> </p>
                                    <div class="reviewer-info">
                                        <p class="reviewer-name"> <?php echo esc_html($review['name']); ?> </p>
                                        <span class="review-rating">⭐<?php echo esc_html($review['rating']); ?></span>
                                    </div>
                                    <p class="review-date"> <?php echo esc_html($review['created_at']); ?> </p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="text-center text-muted"><?php esc_html_e('No reviews available.', 'customer-reviews-api'); ?></p>
                <?php endif; ?>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
        <h1 class="mb-2 text-white"><?php esc_html_e('Apple Pencil', 'customer-reviews-api'); ?></h1>
        <h5 class="text-white"><?php esc_html_e('Share your experience with the most advanced digital pencil', 'customer-reviews-api'); ?></h5>
    </div>

    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h2 class="h4 mb-0"><?php esc_html_e('Write Your Review', 'customer-reviews-api'); ?></h2>
                    <p class="text-muted mt-2 mb-0"><?php esc_html_e('Your feedback helps others make their creative journey better', 'customer-reviews-api'); ?></p>
                </div>
                <div class="card-body p-4">
                    <div id="alert-container"></div>
                    <form id="reviewForm">
                        <div class="mb-3">
                            <label for="name" class="form-label"><?php esc_html_e('Your Name', 'customer-reviews-api'); ?></label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="<?php esc_attr_e('Enter your name', 'customer-reviews-api'); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label"><?php esc_html_e('Email Address', 'customer-reviews-api'); ?></label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="<?php esc_attr_e('Enter your email', 'customer-reviews-api'); ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><?php esc_html_e('Rating', 'customer-reviews-api'); ?></label>
                            <div class="star-rating">
                                <?php for ($i = 5; $i >= 1; $i--) : ?>
                                    <input type="radio" id="star<?php echo esc_attr($i); ?>" name="rating" value="<?php echo esc_attr($i); ?>">
                                    <label for="star<?php echo esc_attr($i); ?>" class="fas fa-star"></label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="title" class="form-label"><?php esc_html_e('Review Title', 'customer-reviews-api'); ?></label>
                            <input type="text" class="form-control" id="title" name="title" required placeholder="<?php esc_attr_e('Sum up your experience in a title', 'customer-reviews-api'); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label"><?php esc_html_e('Add Photos', 'customer-reviews-api'); ?></label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <div class="form-text"><?php esc_html_e('Share your creative work (JPG, PNG - Max 5MB)', 'customer-reviews-api'); ?></div>
                        </div>
                        <div class="mb-3">
                            <label for="review" class="form-label"><?php esc_html_e('Your Review', 'customer-reviews-api'); ?></label>
                            <textarea class="form-control" id="review" name="review" rows="4" required placeholder="<?php esc_attr_e('Tell us about your creative experience with Apple Pencil...', 'customer-reviews-api'); ?>"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="g-recaptcha" data-sitekey="<?php echo esc_attr(get_option('customer_reviews_api_settings')['recaptcha_site_key'] ?? ''); ?>"></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100"><?php esc_html_e('Submit Review', 'customer-reviews-api'); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

