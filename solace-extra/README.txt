=== Solace Extra ===
Contributors: solacewp
Donate link: https://solacewp.com/
Tags: solace, import, template, kit
Requires at least: 6.2
Tested up to: 6.8
Stable tag: 1.3.3
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Solace Extra: A WordPress plugin with one-click demo import feature, effortlessly giving users a professional website look in an instant.

== Description ==

The Solace Extra plugin is designed to enhance the user experience for Solace Theme users by facilitating the selection and importation of pre-designed Elementor templates. With this plugin, users can effortlessly browse through a variety of templates and seamlessly import them into their WordPress website, simplifying the process of building stunning pages with Elementor.

This plugin uses external services provided by SolaceWP and Google reCAPTCHA. These services include fetching demo content, retrieving plugin information, and providing security features to prevent spam and abuse. Data will be sent to and retrieved from `https://solacewp.com/api` (which is operated by the plugin author) and `https://www.google.com/recaptcha`.

For more details, please refer to the service terms of use and privacy policies:
- SolaceWP: [Terms of Use](https://solacewp.com/api/terms-of-service/), [Privacy Policy](https://solacewp.com/api/privacy-policy/)
- Google reCAPTCHA: [Terms of Use](https://www.google.com/recaptcha/intro/v3.html), [Privacy Policy](https://policies.google.com/privacy)
- Sendy API: [Terms of Use](https://sendy.co/end-user-license-agreement), [Privacy Policy](https://sendy.co/privacy-policy)

== Installation ==

1. Open WordPress Dashboard:
- Log in to your WordPress dashboard.
2. Access Plugin Menu:
On the left panel, click "Plugins" and select "Add New."
3. Search for "Solace Extra":
-On the Add Plugin page, locate the search box in the top right corner.
-Type "Solace Extra" in the search box.
4.Install the Plugin:
-After finding the "Solace Extra" plugin, click the "Install" button below it.
5. Activate the Plugin:
Once the installation is complete, click the "Activate" button that appears.

== Frequently Asked Questions ==

= Can this plugin run on all WordPress themes? =

No, this plugin is a plugin supporting the Solace Theme, so this plugin will run properly only with the Solace Theme. 

= Does this plugin use any external services? =

Yes, this plugin interacts with external services provided by SolaceWP (operated by the plugin author) to fetch demo content and plugin information, and by Google reCAPTCHA to provide security features.

= What data is sent to these services? =

Data such as demo names, plugin versions, and other necessary information for plugin operation may be sent to `https://solacewp.com`. Additionally, information required for reCAPTCHA verification will be sent to `https://www.google.com/recaptcha`.

= Where can I find the service terms of use and privacy policy? =

You can find the service terms of use and privacy policies at the following links:
- SolaceWP: [Terms of Use](https://solacewp.com/api/terms-of-service/), [Privacy Policy](https://solacewp.com/api/privacy-policy/)
- Google reCAPTCHA: [Terms of Use](https://www.google.com/recaptcha/intro/v3.html), [Privacy Policy](https://policies.google.com/privacy)
- This plugin provides additional functionality for the Solace theme. To view the uncompressed source code of the JavaScript used, please visit the following link: https://github.com/LottieFiles/lottie-player/tree/master/src and 
https://www.google.com/recaptcha/api.js
- This plugin uses the Sendy API for managing newsletter subscriptions. The data sent includes user email addresses and optional names during the subscription process. For more information, please refer to their [Terms of Use](https://sendy.co/end-user-license-agreement), [Privacy Policy](https://sendy.co/privacy-policy)

== Screenshots ==

1. **Dashboard**
2. **Starter Templates**
3. **Change Colors & Fonts**
2. **Import Process**


== Changelog ==

= 1.3.3 - 25 August 2025 =
* Fix: Resolved the Patchstack Server Side Request Forgery (SSRF) vulnerability reported by Que Thanh Tuan - Blue Rock.
* Fix: Resolved the Patchstack Server Side Request Forgery (SSRF) vulnerability reported by stealthcopter.
* Fix: Resolved the Patchstack Arbitrary File Upload issue reported by theviper17.

= 1.3.2 - 3 March 2025 =
* Fix: Resolved the Patchstack trusted domain issue.
* Fix: Fixed the vulnerability in Patchstack's "Get Logo from API" function.
* Fix: Corrected the console error related to the adjustWidth issue.
* Fix: Changed the order to descending (DESC) on the frontend.
* Fix: Fixed the alert issue when one part has the same conditions.
* Fix: Styled the popup.
* Style: Applied automatic styling to the widget navigation menu.

= 1.3.0 - 14 February 2025 =
* Feat: Site Builder
* Fix: Solace Nav Menu widget issues
* Fix: Generate thumbnails for imported images

= 1.2.3 - January 24 2025 =
* Fix: The Border color issue in the template preview
* Fix: The Color palette issue in the template preview
* Fix: The Padding and margin issues in the Solace Form Widget
* Fix: Plugin check issue
* Refactor: File upload message in the Solace Form widget
* Style: Label padding in the Solace Form widget
* Fix: Default file type and file extension validation in the Solace Form widget
* Fix: Issue with allowed file types in the Solace Form widget
* Refactor: File input functionality in the Solace Form widget
* Fix: Single image size issue
* Refactor: Default button value in the Solace Form widget
* Fix: Button stretch position in the Solace Form widget
* Fix: Full-width button style in the Solace Form widget
* Feat: Button position adjustment in the Solace Form widget

= 1.2.2 =
* fix: customizer can't back to solace dashboard when clossing customizer
* fix: customization submenu trigger an error when clicking on it
* fix: loading issue in elementor editor in Elementor 3.26
* fix: plugin check
* fix: submenu dashboard customization link redirect
* feat: activate and deactivate plugin litespeed
* fix: change alert control to raw_html for security
* fix: when menus is null or not array
* feat: add wpfc clear cache to wp config
* fix: page blog import menu
* fix: import menu shop link
* fix: toggle admin banner
* fix: add http_build_query and esc_url_raw for secure starterlink submenu
* fix: error submenu link, change to JS instead wp_redirect
* feat: If the plugin is deactivated, display an admin notice
* refactor: Solace Form Builder Widget placeholder default
* fix: Solace Form Builder Widget Radio button issue
* fix: Solace Form Builder Widget select issue
* refactor: Solace Form Builder Widget email format
* fix: Solace Form Builder Widget form message issue

= 1.0.0 =
* Initial Release.

== Upgrade Notice ==

= 1.0 =
Initial Release.
