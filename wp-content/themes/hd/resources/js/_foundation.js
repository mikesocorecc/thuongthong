/*jshint esversion: 6 */
const $ = jQuery;
window.$ = jQuery;
window.jQuery = jQuery;

/** */
import { Foundation } from 'foundation-sites/js/foundation.core';

// Foundation plugins
import { Abide } from 'foundation-sites/js/foundation.abide';
import { Dropdown } from 'foundation-sites/js/foundation.dropdown';
import { DropdownMenu } from 'foundation-sites/js/foundation.dropdownMenu';
import { Accordion } from 'foundation-sites/js/foundation.accordion';
import { AccordionMenu } from 'foundation-sites/js/foundation.accordionMenu';
import { ResponsiveMenu } from 'foundation-sites/js/foundation.responsiveMenu';
import { ResponsiveToggle } from 'foundation-sites/js/foundation.responsiveToggle';
import { OffCanvas } from 'foundation-sites/js/foundation.offcanvas';
import { Reveal } from 'foundation-sites/js/foundation.reveal';
import { SmoothScroll } from 'foundation-sites/js/foundation.smoothScroll';
//import { Tabs } from 'foundation-sites/js/foundation.tabs';
import { Tooltip } from 'foundation-sites/js/foundation.tooltip';
import { Sticky } from 'foundation-sites/js/foundation.sticky';

// Foundation core and utils
import { MediaQuery } from 'foundation-sites/js/foundation.util.mediaQuery';
import { Touch } from 'foundation-sites/js/foundation.util.touch';
import { Triggers } from 'foundation-sites/js/foundation.util.triggers';
import { Motion, Move } from 'foundation-sites/js/foundation.util.motion';
import { onImagesLoaded } from 'foundation-sites/js/foundation.util.imageLoader';

Foundation.addToJquery($);

// Require non-modular scripts
require('motion-ui');
//require('what-input');

/** */
Foundation.onImagesLoaded = onImagesLoaded;
Foundation.MediaQuery = MediaQuery;
Foundation.Motion = Motion;
Foundation.Move = Move;
Foundation.Touch = Touch;
Foundation.Triggers = Triggers;

Touch.init(jQuery);
Triggers.init(jQuery, Foundation);

/** */
Foundation.plugin(Abide, 'Abide');
Foundation.plugin(Dropdown, 'Dropdown');
Foundation.plugin(DropdownMenu, 'DropdownMenu');
Foundation.plugin(Accordion, 'Accordion');
Foundation.plugin(AccordionMenu, 'AccordionMenu');
Foundation.plugin(ResponsiveMenu, 'ResponsiveMenu');
Foundation.plugin(ResponsiveToggle, 'ResponsiveToggle');
Foundation.plugin(OffCanvas, 'OffCanvas');
Foundation.plugin(Reveal, 'Reveal');
Foundation.plugin(SmoothScroll, 'SmoothScroll');
//Foundation.plugin(Tabs, 'Tabs');
Foundation.plugin(Tooltip, 'Tooltip');
Foundation.plugin(Sticky, 'Sticky');

$(() => ($(document).foundation()));

export default Foundation;