<style>
    section.section-padd-rep ul {
        /* list-style: disc; */
        list-style: decimal;
        padding-left: 20px;
        /*font-family: 'Montserrat';*/
        font-size: 20px;
        line-height: 1.5;
        font-weight: 500;
        color: #000;
    }
    .section-padd-rep-sub {
        font-family: 'palatinolinotype' !important;
        font-size: 16px !important; 
    }
</style>

<!-- banner start -->

<section class="banner inner-banner">

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-lg-6">

                <div class="banner-cont inner-banner-text wow fadeInLeft">

                    <h1>

                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'About us' ?>

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


<section class="section-padd-rep">

    <div class="container px-5">

        <div class="row align-items-center">
            <div class="col-lg-12 wow slideInLeft">
                <?php if (isset($cms[0]['cms_page_content'])) : ?>
                    <?= html_entity_decode($cms[0]['cms_page_content']) ?>
                <?php else : ?>
                    <p>AzAveze is a dynamic and all-encompassing platform meticulously developed to cater to the unique needs of start-up entrepreneurs, established businesses, and individuals alike. Whether you are a budding visionary, a veteran businessman, or a solo flyer, chances are, you will need help at one point or another. Azaveze provides a thriving ecosystem to connect and engage with like-minded individuals, foster collaboration, bolster your promotional efforts, facilitate talent acquisition, and aid in the seamless exchange of products and services. </p>
                    <h3>Our Mission</h3>
                    <p>Our mission at AzAveze is to be the go-to destination for emerging entrepreneurs, where they can nurture their entrepreneurial spirit and turn their innovative ideas into successful ventures. With our platform, you can unlock your business potential and embrace a world of opportunities.</p>
                    <h3>Why Choose Azaveze</h3>
                    <div class="icoo-boxx">
                        <h4 class="text-center">Networking and Collaboration</h4>
                        <p>AzAveze is a vibrant hub for entrepreneurs to connect, network, and collaborate with fellow innovators. Share your experiences, gain insights, and build valuable relationships that can drive your business forward.</p>
                        <h4 class="text-center">Promotion and Marketing</h4>
                        <p>We understand the importance of getting your brand out there. AzAveze provides tools and features to enhance your promotional efforts, helping you reach a wider audience and increase brand visibility.</p>
                        <h4 class="text-center">Talent Acquisition</h4>
                        <p>From humble beginnings to hiring the right talent is a key to success for any start-up. Our platform simplifies the recruitment process, making it easy to find and hire skilled professionals who share your passion.</p>
                        <h4 class="text-center">E-commerce Opportunities: </h4>
                        <p>AzAveze facilitates the buying and selling of products and services, creating a thriving marketplace for entrepreneurs to grow their businesses.</p>
                        <h4 class="text-center">Business Applications</h4>
                        <p>Access essential business applications within our platform, including document storage and sharing, project management, as well as accounting and banking tools. Simplify your day-to-day operations and stay organized.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
</section>