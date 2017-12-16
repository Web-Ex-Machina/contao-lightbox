========
Lightbox extension for Contao Open Source CMS
========

V0.1.0 - 2016-06-05
- Init git repository

V0.2.0 - 2016-06-14
- Add "Haste" dependancy
- Replace the modules of the lightbox with Contao Hooks

V0.2.1 - 2016-06-16
- Add error message if no content is found

V0.2.2 - 2016-06-17
- Load Javascript sent by the content of the lightbox

v0.3.0 - 2016-07-02
- External template for the HTML of the lightbox container
-- This template is now loaded when a lightbox is generated, not at the page generation.
- Lightboxes are now nested. It's possible to call a lightbox in a lightbox. Each lightbox has a specific container with attached events.

v0.4.0 - 2016-12-06
- Redesign of client files (css, js, scss)

v0.5.0 - 2017-02-15
- Add try-catch blocks to handle and track errors
- Init session values to handle specific events, like forms

v0.6.0 - 2017-10-03
- Handle some strange JS events
- Add Composer for Packagist

v1.0.0 - 2017-11-02
- Refactoring and renaming in order to publish the module in Github and Packagist

v2.0.0 - 2017-12-08 / 2017-12-16
- Backoffice update to make the lightbox easier to handle
- Release in Github