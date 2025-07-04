=== Pinterest for WooCommerce ===
Contributors: automattic, pinterest, woocommerce
Tags: pinterest, woocommerce, marketing, product catalog feed, pixel
Requires at least: 5.6
Tested up to: 6.8
Requires PHP: 7.3
Stable tag: 1.4.21
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Get your products in front of Pinterest users searching for ideas and things to buy. Connect your WooCommerce store to make your catalog browsable.

== Description ==

Pinterest gives people their next great idea. Part collection, part marketplace, it’s a one-stop shop for millions of pinners to source inspiration, new products and new possibilities. It’s like a visual search engine, guiding people to ideas, inspiration and products that are just right for them.

With the Pinterest for WooCommerce extension, you can put your products in front of Pinterest users who are already looking for ideas and things to buy. Connect your WooCommerce store to your *[Pinterest business account](https://business.pinterest.com/)* directly in the WooCommerce app. Your entire catalog will become browsable on Pinterest in just a few clicks.

= Pinterest Ads =

Get started with Pinterest Ads with **$125 free ad credit\*** from Pinterest when you set up Pinterest for WooCommerce and spend $15 on ads! Pinterest *[terms and conditions](https://business.pinterest.com/en-us/business-terms-of-service/)* apply.

= Open-minded and undecided =

People on Pinterest are eager for new ideas, which means they want to hear from you. In fact, 97% of top Pinterest searches are unbranded. Content from brands doesn’t interrupt on Pinterest—it inspires. Shopping features are built into both the organic Pinner experience, and our ad solutions.

We'll also automatically set up your Pinterest tag, and a shop tab on your Pinterest profile.

*[Learn more about Shopping on Pinterest](https://business.pinterest.com/en/shopping/)*

= Set up your foundation =

*Connect your account*

Install the extension and connect your account to quickly publish Product Pins, automatically update your product catalog every day, and track performance with the Pinterest tag.

*Catalogs*

Turn your entire product catalog into browsable product Pins, all at once.

*Pinterest tag*

Add the tag to your site to measure conversions and to optimize ads for shopping campaigns or retargeting.

Consider longer attribution windows to capture shoppers who take more time to convert.

*Build brand loyalty*

People on Pinterest are nearly 50% more likely to be open to new brands while shopping. And once they find a brand they like, they’re more loyal.

Become their new favorite with merchant solutions like the Shop Tab and the Verified Merchants Program. Shop Tab on profile: Consider this your always-on Pinterest shop. It’s automatically created when you upload your catalog so people can shop right from your profile.

*Verified Merchant Program*

People love to shop from brands they trust. That’s what the Verified Merchant Program is all about. It includes benefits like a “verified” badge on your profile and eligibility for enhanced distribution.

*More about Pinterest*

Pinterest is a visual discovery engine people use to find inspiration for their lives and make it easier to shop for home decor, fashion and style, electronics and more. 450 million people have saved more than 240 billion Pins across a range of interests, which others with similar tastes can discover through search and recommendations.

== Installation ==

= Minimum Requirements =

* WordPress 5.6 or greater
* WooCommerce 5.3 or greater
* PHP version 7.3 or greater (PHP 7.4 or greater is recommended)
* MySQL version 5.6 or greater

Visit the [WooCommerce server requirements documentation](https://woocommerce.com/document/server-requirements/) for a detailed list of server requirements.

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser. To do an automatic install of this plugin, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type “Pinterest for WooCommerce” and click Search Plugins. Once you’ve found this plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

The manual installation method involves downloading the plugin and uploading it to your webserver via your favourite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

= Where can I report bugs or contribute to the project? =

Bugs should be reported in the [Pinterest for WooCommerce repository](https://github.com/woocommerce/pinterest-for-woocommerce/).

= This is awesome! Can I contribute? =

Yes you can! Join in on our [GitHub repository](https://github.com/woocommerce/pinterest-for-woocommerce/) :)

Release and roadmap notes available on the [WooCommerce Developers Blog](https://developer.woocommerce.com/)

== Changelog ==

= 1.4.21 - 2025-06-16 =
* Update WP Consent API to affect all tracking with improved architecture.
* [dev] Pin Github actions to immutable references of commits instead of tags.

= 1.4.20 - 2025-06-03 =
* Tweak - Reenable WP Consent API tracking integration.

= 1.4.19 - 2025-05-29 =
* Add CAPI enablement modal to encourage merchants to enable Conversions API.
* Enable Conversions API in OAuth flow and settings UI.

= 1.4.18 - 2025-05-20 =
* Tweak - WC 9.9 compatibility.
* Tweak - WP 6.8 compatibility.

= 1.4.17 - 2025-03-18 =
* Add - PHP 8.4 compatibility.
* Fix - Add feed status data fallback to empty data sets.
* Fix - Site locale is obtained from settings.
* Tweak - WC 9.8 compatibility.

= 1.4.16 - 2025-02-11 =
* Add - UTM parameters to the products URLs used in the product feed.
* Dev - Updating code styling rules.
* Tweak - WC 9.7 compatibility.

= 1.4.15 - 2025-01-21 =
* Tweak - WC 9.6 compatibility.

= 1.4.14 - 2024-12-18 =
* Tweak - WC 9.5 compatibility.

= 1.4.13 - 2024-12-04 =
* Add - Admin notice of a failed Pinterest account status.
* Update - Do not disconnect on the Action Scheduler action failure.
* Update - Failed actions to log the errors.

= 1.4.12 - 2024-11-07 =
* Tweak - WC 9.4 compatibility.
* Tweak - WP 6.7 compatibility.

= 1.4.11 - 2024-10-23 =
* Add - API method to get commerce integration.
* Add - Commerce Integration `partner_metadata` weekly sync.
* Add - Failed Create Commerce Integration API call retries procedure.
* Add - Weakly heartbeat action.
* Update - Make `integration_data` optional for the extension.

= 1.4.10 - 2024-09-24 =
* Dev - Tests suits update.
* Fix - 403 Pinterest API error response is not the reason to auto-disconnect.
* Fix - Feed Deletion Failure notice duplicates removal.
* Fix - Reuse existing feed, if any.

= 1.4.9 - 2024-09-12 =
* Tweak - WC 9.3 compatibility.

= 1.4.8 - 2024-08-29 =
* Fix - Detect no product error in the page_visit tracking.
* Release/1.4.7.

= 1.4.7 - 2024-08-26 =
* Add - Adding admin notice in case of feed deletion error.
* Add - Call to disconnect from Pinterest on deactivation.
* Dev - Fixing SKU Unit tests.
* Fix - Pagination on Feed Issues table.
* Fix - Pinterest Save button positioning.
* Fix - Reset internal feed status on disconnect.
* Tweak - New .pot file.

= 1.4.6 - 2024-08-13 =
* Dev - Update dependency.
* Tweak - Add the website's domain to the Pinterest feed name.
* Tweak - WC 9.2 compatibility.

= 1.4.5 - 2024-07-19 =
* Tweak - replace locale source function.

= 1.4.4 - 2024-07-10 =
* Add - Billing status info in the Settings UI
* Fix - Token invalid reset procedure
* Fix - Checkbox control UI with WordPress 6.6
* Tweak - WC 9.1 compatibility.
* Tweak - WP 6.6 compatibility.

= 1.4.3 - 2024-06-25 =
* Tweak - Remove `feature_flag` connection info data key.
* Update - Disabling CAPI tracker.

= 1.4.2 - 2024-06-13 =
* Add - Versioning and compatibility checks to implement support policy.
* Fix - Release v1.4.1.
* Fix - Undefined array key "path" warning thrown by DomainVerification.php.
* Tweak - Adds WooCommerce as a dependency to the plugin header.
* Tweak - Revert to WooCommerce.com domain.

= 1.4.1 - 2024-05-01 =
* Add - Heartbeat actions cleanup
* Update - Mandatory condition on tracking
* Update - pinit.js script import to match with Pinterest documentation
* Update - Error cases handling for discounts
* Tweak - Advertiser ID missing exception
* Fix - Missing Order ID into custom_data array for Checkout CAPI event.
* Fix - Fix tooltip UI issue in Settings page
* Fix - Correct coupons information
