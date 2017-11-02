========
Extension "Custom Lightbox" for Contao Open Source CMS
========

V1.0 - 2016-06-05
- Init git repository

V1.1 - 2016-06-14
- Add "Haste" dependancy
- Replace the modules of the lightbox with Contao Hooks

V1.1.1 - 2016-06-16
- Add error message if no content is found

V1.1.2 - 2016-06-17
- Load Javascript sent by the content of the lightbox

v1.2 - 2016-07-02
- External template for the HTML of the lightbox container
-- This template is now loaded when a lightbox is generated, not at the page generation.
- Lightboxes are now nested. It's possible to call a lightbox in a lightbox. Each lightbox has a specific container with attached events.

v1.3 - 2016-12-06
- Redesign of client files (css, js, scss)

v1.4 - 2017-02-15
- Add try-catch blocks to handle and track errors
- Init session values to handle specific events, like forms

v1.5 - 2017-10-03
- Handle some strange JS events
- Add Composer for Packagist

v2.0 - 2017-11-02
- Refactoring and renaming in order to publish the module in Github and Packagist