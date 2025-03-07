# Customer Reviews API - WordPress Plugin

## 📌 Overview
The **Customer Reviews API** plugin allows users to **fetch, display, and submit customer reviews** via an external API. It integrates **Google reCAPTCHA**, supports **AJAX-based form submissions**, and follows **WordPress best practices**.

## 🔹 Features
- ✅ Fetch and display customer reviews dynamically.
- ✅ Secure **AJAX-based** review submission.
- ✅ **Google reCAPTCHA** protection.
- ✅ **Star rating system** with image upload.
- ✅ Shortcode support: `[customer_reviews]`
- ✅ Secure with **nonce verification** and **WordPress API key management**.

## 📦 Installation
1. **Download the plugin ZIP** or clone the repository.
2. Upload it to your WordPress site via `wp-admin > Plugins > Add New`.
3. Activate the plugin from the **Plugins** menu.
4. Go to `Settings > Customer Reviews API` to configure **API Key & reCAPTCHA**.

## 🔧 Configuration
### **1️⃣ Setup API Key & reCAPTCHA**
1. Navigate to **`Settings > Customer Reviews API`**.
2. Enter your **API Key** (provided by your review system).
3. Add your **Google reCAPTCHA Site Key**.
4. Click `Save Changes`.

### **2️⃣ Display Reviews on a Page**
To display reviews, **use the shortcode**:
```html
[customer_reviews]
```
- This will **fetch and display reviews** dynamically using the API.
- Supports **Swiper.js slider** for better UI.

### **3️⃣ Enable Review Submissions**
- Users can **submit reviews** using the built-in form.
- Includes **image uploads** and **rating selection**.
- Uses **AJAX submission** for a smooth experience.

## 🎨 Customization
### **Modify Styles**
- The plugin loads styles from:
    - `assets/css/style.css` → Modify to change the form or review appearance.

### **Modify JavaScript**
- `assets/js/script.js` handles:
    - **AJAX form submission**.
    - **Star rating interactivity**.
    - **Review display enhancements**.

## 🔍 Debugging & Troubleshooting
1. **Issue: Reviews not displaying?**
    - Ensure the API Key is correct in **`Settings > Customer Reviews API`**.
    - Check the console (`F12 > Console`) for errors.

2. **Issue: Form not submitting?**
    - Ensure `wp_localize_script()` is loading the nonce.
    - Check `wp-content/debug.log` for errors.

3. **Issue: reCAPTCHA not working?**
    - Verify the reCAPTCHA **Site Key** in the settings.

## 📜 License
This plugin is open-source and released under the **MIT License**.

## 🤝 Contributing
- Feel free to **submit issues** or **pull requests** on GitHub.
- If you find this plugin useful, give it a ⭐ star!

## 📞 Support
For support or customization, contact: `your-email@example.com`

