<!-- banner start -->

<section class="banner inner-banner">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-6">
                <div class="banner-cont inner-banner-text wow fadeInLeft">
                    <h1>
                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Subscription' ?>
                    </h1>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="inner-banner">
                    <img src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>" onerror="this.onerror=null;this.src='<?= g('images_root') . 'dummy-image.jpg' ?>';" />
                </div>
            </div>

        </div>

    </div>

</section>

<!-- banner end -->
<style>
/* Section styling */
.new-sec-plan {
    padding: 60px 0;
    background: #f9f9f9;
}

/* Card container */
.plan-card {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 30px;
    perspective: 1000px;
}

/* Neumorphism effect */
.plan-card-front {
    width: 100%;
    background: #ffffff;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1), inset 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: box-shadow 0.3s ease, transform 0.3s ease;
    position: relative;
    overflow: hidden;
    border: 1px solid #ddd;
}

/* Card hover effects */
.plan-card:hover .plan-card-front {
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
    transform: translateY(-10px);
}

/* Plan Icon */
.plan-icon {
    margin-bottom: 20px;
}

.plan-icon svg {
    width: 80px;
    height: 80px;
    fill: #8204aa;
    transition: fill 0.3s, transform 0.3s;
}

.plan-card:hover .plan-icon svg {
    fill: #5a007a;
    transform: scale(1.1);
}

/* Plan Title */
.plan-title {
    font-size: 1.75em;
    font-weight: 700;
    color: #8204aa;
    margin-bottom: 10px;
}

/* Plan Price */
.plan-price {
    font-size: 4.5em;
    font-weight: 700;
    color: #8204aa;
    margin: 15px 0;
    line-height: 1;
}

.plan-price span {
    font-size: 0.6em;
    color: #555;
}

/* Plan Features */
.plan-features {
    list-style: none;
    padding: 0;
    margin: 20px 0;
    text-align: left;
}

.plan-features li {
    padding: 12px 0;
    border-bottom: 1px solid #8204aa;
    color: #555;
    font-size: 1em;
    display: flex;
    align-items: center;
    transition: color 0.3s ease;
}

.feature-icon {
    display: inline-block;
    width: 20px;
    height: 20px;
    background-color: #8204aa;
    color: #fff;
    border-radius: 50%;
    text-align: center;
    line-height: 20px;
    margin-right: 10px;
    font-weight: bold;
}

.plan-features li:hover {
    color: #8204aa;
}

.plan-features li:last-child {
    border-bottom: none;
}

/* Plan Button */
.plan-button {
    border-radius: 25px;
    padding: 12px 25px;
    font-size: 1.1em;
    cursor: pointer;
    text-transform: uppercase;
    transition: background-color 0.3s, color 0.3s, transform 0.3s;
    position: relative;
}

.plan-button.btn-primary {
    background-color: #8204aa;
    color: #fff;
    border: 0;
}

.plan-button.btn-primary:hover {
    background-color: #5a007a;
    transform: scale(1.05);
}

.featured .plan-card-front {
    border: 2px solid #8204aa;
    background: linear-gradient(145deg, #ffffff, #f3e6ff);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .plan-card {
        margin-bottom: 20px;
    }

    .plan-card-front {
        padding: 20px;
    }

    .plan-icon svg {
        width: 60px;
        height: 60px;
    }

    .plan-title {
        font-size: 1.5em;
    }

    .plan-price {
        font-size: 2em;
    }

    .plan-button {
        font-size: 1em;
        padding: 10px 20px;
    }
}

</style>
<!--featured-->
<section class="new-sec-plan">
    <div class="container">
        <div class="row">
            <!-- 6 Months Plan -->
            <div class="col-md-6">
                <div class="plan-card">
                    <div class="plan-card-front">
                        <div class="plan-icon">
                            <!-- Example SVG icon -->
                            <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#8204aa"/>
                            </svg>
                        </div>
                        <h2 class="plan-title">Grower</h2>
                        <p class="plan-price">$50 <span>/6 months</span></p>
                        <ul class="plan-features">
                            <li><span class="feature-icon">✔</span> Monthly Fee</li>
                            <li><span class="feature-icon">✔</span> AzAverze Community</li>
                            <li><span class="feature-icon">✔</span> Networking (Connect/Follow/Like/Endorse/Leave Reviews)</li>
                            <li><span class="feature-icon">✘</span> Video Upload</li>
                            <li><span class="feature-icon">✘</span> Video Conferencing</li>
                            <li><span class="feature-icon">✘</span> Webinar</li>
                            <li><span class="feature-icon">✘</span> Calendar</li>
                            <li><span class="feature-icon">✘</span> Business Applications</li>
                            <li><span class="feature-icon">✘</span> Sell Service</li>
                            <li><span class="feature-icon">✘</span> Purchase Services</li>
                        </ul>
                        <button class="plan-button btn btn-primary">Subscribe</button>
                    </div>
                </div>
            </div>
            <!-- 1 Year Plan -->
            <div class="col-md-6">
                <div class="plan-card featured">
                    <div class="plan-card-front">
                        <div class="plan-icon">
                            <!-- Example SVG icon -->
                            <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#8204aa"/>
                            </svg>
                        </div>
                        <h2 class="plan-title">Standard</h2>
                        <p class="plan-price">$90 <span>/year</span></p>
                        <ul class="plan-features">
                            <li><span class="feature-icon">✔</span> Monthly Fee</li>
                            <li><span class="feature-icon">✔</span> AzAverze Community</li>
                            <li><span class="feature-icon">✔</span> Networking (Connect/Follow/Like/Endorse/Leave Reviews)</li>
                            <li><span class="feature-icon">✘</span> Video Upload</li>
                            <li><span class="feature-icon">✘</span> Video Conferencing</li>
                            <li><span class="feature-icon">✘</span> Webinar</li>
                            <li><span class="feature-icon">✘</span> Calendar</li>
                            <li><span class="feature-icon">✘</span> Business Applications</li>
                            <li><span class="feature-icon">✘</span> Sell Service</li>
                            <li><span class="feature-icon">✘</span> Purchase Services</li>
                        </ul>
                        <button class="plan-button btn btn-primary">Subscribe</button>
                    </div>
                </div>
            </div>
            <!-- 3 Years Plan -->
            <div class="col-md-6">
                <div class="plan-card">
                    <div class="plan-card-front">
                        <div class="plan-icon">
                            <!-- Example SVG icon -->
                            <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#8204aa"/>
                            </svg>
                        </div>
                        <h2 class="plan-title">Extended</h2>
                        <p class="plan-price">$240 <span>/3 years</span></p>
                        <ul class="plan-features">
                            <li><span class="feature-icon">✔</span> Monthly Fee</li>
                            <li><span class="feature-icon">✔</span> AzAverze Community</li>
                            <li><span class="feature-icon">✔</span> Networking (Connect/Follow/Like/Endorse/Leave Reviews)</li>
                            <li><span class="feature-icon">✘</span> Video Upload</li>
                            <li><span class="feature-icon">✘</span> Video Conferencing</li>
                            <li><span class="feature-icon">✘</span> Webinar</li>
                            <li><span class="feature-icon">✘</span> Calendar</li>
                            <li><span class="feature-icon">✘</span> Business Applications</li>
                            <li><span class="feature-icon">✘</span> Sell Service</li>
                            <li><span class="feature-icon">✘</span> Purchase Services</li>
                        </ul>
                        <button class="plan-button btn btn-primary">Subscribe</button>
                    </div>
                </div>
            </div>
            <!-- 5 Years Plan -->
            <div class="col-md-6">
                <div class="plan-card">
                    <div class="plan-card-front">
                        <div class="plan-icon">
                            <!-- Example SVG icon -->
                            <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L2 7v10l10 5 10-5V7L12 2z" fill="#8204aa"/>
                            </svg>
                        </div>
                        <h2 class="plan-title">Ultimate</h2>
                        <p class="plan-price">$400 <span>/5 years</span></p>
                        <ul class="plan-features">
                            <li><span class="feature-icon">✔</span> Monthly Fee</li>
                            <li><span class="feature-icon">✔</span> AzAverze Community</li>
                            <li><span class="feature-icon">✔</span> Networking (Connect/Follow/Like/Endorse/Leave Reviews)</li>
                            <li><span class="feature-icon">✘</span> Video Upload</li>
                            <li><span class="feature-icon">✘</span> Video Conferencing</li>
                            <li><span class="feature-icon">✘</span> Webinar</li>
                            <li><span class="feature-icon">✘</span> Calendar</li>
                            <li><span class="feature-icon">✘</span> Business Applications</li>
                            <li><span class="feature-icon">✘</span> Sell Service</li>
                            <li><span class="feature-icon">✘</span> Purchase Services</li>
                        </ul>
                        <button class="plan-button btn btn-primary">Subscribe</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>