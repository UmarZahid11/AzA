 <!-- banner start -->

 <section class="banner inner-banner">

     <div class="container">

         <div class="row justify-content-center">

             <div class="col-lg-6">

                 <div class="banner-cont inner-banner-text wow fadeInLeft">

                     <h1>

                        <?= isset($banner['inner_banner_title']) ? $banner['inner_banner_title'] : 'Privacy' ?>

                     </h1>

                 </div>

             </div>
             <div class="col-lg-6">
                 <div class="inner-banner">
                    <img src="<?= isset($banner['inner_banner_image']) ? get_image($banner['inner_banner_image_path'], $banner['inner_banner_image']) : '' ?>" onerror="this.onerror=null;this.src='<?=g('images_root').'dummy-image.jpg'?>';" />
                 </div>
             </div>

         </div>

     </div>

     <!-- </div> -->

 </section>


    <section class="about-sec">
        <div class="container">
            <div class="row">
                <div class="main">
                    <div class="preview">
                        <?php if(isset($cms['cms_page_content'])): ?>
                            <?= html_entity_decode($cms['cms_page_content']); ?>
                        <?php else: ?>
                            <h2><strong>Privacy Policy</strong></h2>
                            <p>A Privacy Policy is a legal statement found on a website or app that outlines explicit details on how it will use personal data provided by users. Such personal data may include details like names, addresses, phone numbers, date of birth or data related to one's financial information like credit card details.</p>
                            <p>Other than outlining how your website will use the data, a Privacy Policy also describes the legal obligations you have if you fail to meet stipulated responsibilities as the website owner.</p>
                            <p>As a business owner who's considering putting up a website, this article will give you a basic understanding of why you need to have a Privacy Policy and how you should incorporate one.</p>
                            <p>As part of an array of privacy laws available across the globe, if your website will collect user information, you are legally required to have a Privacy Policy in place.</p>
                            <p>If you are a resident member of the EU, having a Privacy Policy shows compliance with the General Data Protection Regulation(GDPR). Failure to comply with this EU regulation can lead to a fine of up to 20 million Euros according to <a href="https://gdpr-info.eu/issues/fines-penalties/">Intersoft Consulting</a>.</p>
                            <p>The U.S., on the other hand, doesn't have a singular governing data protection legislation. Rather, the U.S. uses a combination of related data privacy laws at the federal and state level.</p>
                            <p>For instance, the Federal Trade Commission Act (FTCA) empowers the Federal Trade Commission to enforce privacy and data protection laws in federal jurisdiction. On the other hand, the California Online Privacy Protection Act (CalOPPA) is one such <a href="https://oag.ca.gov/sites/all/files/agweb/pdfs/cybersecurity/making_your_privacy_practices_public.pdf">data privacy law</a> which protects users with residency in California.</p>
                            <p>A Privacy Policy also instils trust into users that their information is safe from unrelated parties. If not, you might be liable to legal repercussions. In general, a Privacy policy further legitimises your business by ensuring all the parties involved are part of a legally binding agreement.</p>
                            <p>Having a strong Privacy Policy also offers a substantial competitive advantage. We don't precisely understand how Google's search algorithm works, but the biggest consensus is that if Google trusts your business, the higher your chances of appearing on its first search results pages.</p>
                            <p>According to <a href="https://www.woorank.com/en/blog/privacy-policy-seo-impact">Woorank</a>, most SEO experts believe that a website's privacy policy has a pivotal role to play in how Google and other search engines ultimately identify you as "trust-worthy."</p>
                            <h2>What to Include in Your Privacy Policy</h2>
                            <p>The content of your Privacy Policy will largely depend on the function of your website, the information gathered and how you intend to use said information. However, to pass legal standards, all Privacy policies should have these basic elements within the text.</p>
                            <h3>Your Business Contact Information</h3>
                            <p>Your Privacy needs to display your organization details like the legal name, contact details and place of business. Best practice recommends that this part should appear as the first or the last part of your Privacy Policy for visibility.</p>
                            <h3>The Type of Data You Will Collect</h3>
                            <p>This ranges from emails, physical and IP addresses, credit card details, phone numbers or tracking locations. CalOPPA goes a step further to mandate that commercial or online websites collecting information on California residents must categorically list the type of personal information collected.</p>
                            <h3>How You Will Collect the Information</h3>
                            <p>In addition to filling out forms, you can also collect data automatically through the use of cookies. Internet cookies are, by far, the easiest way to collect user data since browsers collect and save information from an array of sites users have previously visited. However, you must also obtain consent from users to use their cookies when collecting information.</p>
                            <h3>How You Intend to Use the Data</h3>
                            <p>A vital element of a Privacy Policy is describing how you intend to use the data collected. This clause is particularly important if third-parties like advertising programs or fintech companies are in the loop.</p>
                            <p>Will you use the data for transactional purposes alone or will you also send newsletters to your visitors? Will your company share information with other third-party entities like merchants? If so, the law legally requires you to list down all the relevant parties who will also have access to user information alongside your business.</p>
                            <p>In <a href="https://www.quora.com/about/privacy">Quora's</a> Privacy Policy, they have explained in great detail how they intend to use user information, and even further clarifying that they do not sell to third parties:</p>
                            <p><img class="img-fluid" src="quora-privacy-policy-how-use-information-clause.jpg" alt="Quora Privacy Policy: How We Use Your Information clause"></p>
                            <h3>Security Measures in Place to Protect Data</h3>
                            <p>Perhaps the most crucial clause in a Privacy Policy, website owners should give details of the security safeguards they have in place to keep customers' and visitors' personal information safe.</p>
                            <p>The industry-standard safety measure for protecting private information is the use of a <a href="https://www.ssl.com/faqs/faq-what-is-ssl/">Secure Socket Layers</a> (SSL) system. With SSLs, information fed into a website by users is automatically encrypted and coded, which prevents a breach during transmission.</p>
                            <p>You're free to integrate as many security measures as you want as long as malicious parties or unrestricted personnel can't intercept or have access to user information.</p>
                            <p>Here's how <a href="https://www.bathandbodyworks.com/customer-care/privacy-and-security.html">Bath and Body Works</a> explained its security measures in place. It doesn't go too technical on what they do, but its description manages to assure customers that their details are safe:</p>
                            <p><img class="img-fluid" src="bath-body-works-privacy-policy-how-protect-personal-information-clause.jpg" alt="Bath and Body Works Privacy Policy: How do we protect personal information clause"></p>
                            <h3>Rights of the Users</h3>
                            <p>Under the EU's GDPR laws, you should also inform your users of the rights they have with their data. Under these rights, users should be able to request, update, transfer, view or erase their data (where applicable) upon request.</p>
                            <p>The GDPR outlines explicitly that the user has a right to:</p>
                            <ul>
                            <li>Know details about their information</li>
                            <li>Request access to their information</li>
                            <li>Ask you to rectify their information</li>
                            <li>Ask you to erase their information</li>
                            <li>Request that you refrain from processing their information (where erasure is not possible)</li>
                            <li>Request for copies of their data</li>
                            <li>Object to data processing</li>
                            <li>Object to automated decision-making</li>
                            </ul>
                            <h3>How Long You Will Retain Collected Information</h3>
                            <p>As a business owner, you should also let your users know how long you intend to keep their information in your database.</p>
                            <p>First and foremost, do you have a clause stating when the policy will take effect and how long you will retain personal information? Second, a Privacy Policy must give users the leeway to opt-out, clear instructions on how to do so and what options are available for users who want to opt-out.</p>
                            <p>For example, many website owners also share with marketing entities, whether in-house or as third-party entities. This is not exactly illegal, but at the very least, users should have the option of opting out from a marketer's mailing list in a simple way like sending an email or text message to a toll-free number.</p>
                            <h2>Where to Display Your Privacy Policy</h2>
                            <p>You should place Privacy Policies strategically, so users have easy access.</p>
                            <h3>Link in Footer</h3>
                            <p>Most websites place their Privacy Policy as a link in the footer together with other links to relevant information.</p>
                            <p>Gwyneth Paltrow's <a href="https://goop.com/privacy-cookies-policy/">goop</a> is just one among many websites that prefer this style:</p>
                            <p><img class="img-fluid" src="goop-website-footer-privacy-policy-highlighted.jpg" alt="goop website footer with Privacy Policy highlighted"></p>
                            <h3>Side Menu</h3>
                            <p>The placement also depends on how your website or app operates. If your website prompts users to scroll down endlessly (like <a href="https://developer.twitter.com/en/docs/twitter-for-websites/privacy">Twitter</a>), it would be more practical to have the Privacy Policy in a bar on the left/right-hand side of the screen:</p>
                            <p><img class="img-fluid" src="twitter-sidebar-privacy-policy-highlighted.jpg" alt="Twitter sidebar with Privacy Policy highlighted"></p>
                            <h3>Sign-Up Form</h3>
                            <p>Another common practice is by including a link to your Privacy Policy as part of the sign-up form. This feature is a winner, especially since it plays a double role: It asks for users consent to collect their information, and provides a direct link to the Privacy Policy.</p>
                            <h3>Checkout Pages</h3>
                            <p>Checkout pages can also contain a link to your Privacy Policy. Consumers should always read the Privacy Policy before giving consent, but a significant portion really don't.</p>
                            <p><a href="https://www.pewresearch.org/fact-tank/2014/12/04/half-of-americans-dont-know-what-a-privacy-policy-is/">More than half</a> of your users likely don't understand the significance of a Privacy Policy which translates to the increasing preferences towards checkout boxes. Checkboxes make it clear that users will automatically enter into a legally binding contract once they check the box especially when using strong phrases like "I Agree."</p>
                            <p>The goal here is to make the user's experience easy without incurring any legal liabilities as a business owner. Other than the conveniences a checkbox provides, the GDPR also requires you to have them for consent collection. At the end of the day, you're better off integrating one with your Privacy Policy.</p>
                            <h2>Summary</h2>
                            <p>No matter the services you are selling to users, a Privacy Policy should be a key component of your website. Not only is it a legal requirement under most country laws, but having a Privacy Policy creates a sense of trust among users.</p>
                            <p>As a legal requirement, your Privacy Policy should have, in great detail, information regarding how you will collect a user's information, and how you intend to use said information. Other significant details to include are your contact details, user rights, the security measures in place to protect user information, and how long you intend to retain information.</p>
                            <p>Where you opt to place your Privacy Policy depends on how your website or app functions.</p>
                            <p>Most websites place theirs at the bottom of the page while some opt to have theirs included in a sign-up form or under a checkbox. Regardless of where you choose to place your Privacy Policy, you should also ensure that your Privacy policy is visible and easy to access.</p>
                            <p>You may also want to consider the easiest way to get consent from your users. Since people hardly ever read the Privacy Policy, having an "I Agree" checkbox ensures consent either way.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>