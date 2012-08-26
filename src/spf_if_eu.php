<?php

// This is a PLUGIN TEMPLATE.

// Copy this file to a new name like abc_myplugin.php.  Edit the code, then
// run this file at the command line to produce a plugin for distribution:
// $ php abc_myplugin.php > abc_myplugin-0.1.txt

// Plugin name is optional.  If unset, it will be extracted from the current
// file name. Plugin names should start with a three letter prefix which is
// unique and reserved for each plugin author ("abc" is just an example).
// Uncomment and edit this line to override:
$plugin['name'] = 'spf_if_eu';

// Allow raw HTML help, as opposed to Textile.
// 0 = Plugin help is in Textile format, no raw HTML allowed (default).
// 1 = Plugin help is in raw HTML.  Not recommended.
# $plugin['allow_html_help'] = 1;

$plugin['version'] = '0.2';
$plugin['author'] = 'Simon Finch';
$plugin['author_uri'] = 'https://github.com/spiffin/spf_if_eu';
$plugin['description'] = 'Container tag to display content to EU visitors only';

// Plugin load order:
// The default value of 5 would fit most plugins, while for instance comment
// spam evaluators or URL redirectors would probably want to run earlier
// (1...4) to prepare the environment for everything else that follows.
// Values 6...9 should be considered for plugins which would work late.
// This order is user-overrideable.
$plugin['order'] = '2';

// Plugin 'type' defines where the plugin is loaded
// 0 = public       : only on the public side of the website (default)
// 1 = public+admin : on both the public and admin side
// 2 = library      : only when include_plugin() or require_plugin() is called
// 3 = admin        : only on the admin side
$plugin['type'] = '0';

// Plugin "flags" signal the presence of optional capabilities to the core plugin loader.
// Use an appropriately OR-ed combination of these flags.
// The four high-order bits 0xf000 are available for this plugin's private use
if (!defined('PLUGIN_HAS_PREFS')) define('PLUGIN_HAS_PREFS', 0x0001); // This plugin wants to receive "plugin_prefs.{$plugin['name']}" events
if (!defined('PLUGIN_LIFECYCLE_NOTIFY')) define('PLUGIN_LIFECYCLE_NOTIFY', 0x0002); // This plugin wants to receive "plugin_lifecycle.{$plugin['name']}" events

$plugin['flags'] = '0';

if (!defined('txpinterface'))
        @include_once('zem_tpl.php');

# --- BEGIN PLUGIN CODE ---
/**
 * spf_if_eu - display content to EU visitors only
 *
 * © 2012 Simon Finch - https://github.com/spiffin/spf_if_eu
 *
 * Licensed under GNU General Public License version 2
 * http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Version 0.2 -- 26 August 2012
 *
 * Requires geoip.inc and GeoIP.dat to be present in web_root/geoip directory
 */

if ( @txpinterface == 'public' ) {
  include($prefs['path_to_site'].'/geoip/geoip.inc');
}

function spf_if_eu($atts, $thing) {
  global $prefs;

  $gi = geoip_open($prefs['path_to_site'].'/geoip/GeoIP.dat', GEOIP_MEMORY_CACHE);
  $country_code = geoip_country_code_by_addr($gi, remote_addr());

// EU countries array
  $eu = array('AT', 'BE', 'BG', 'CY', 'CZ', 'DK','EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE', 'GB');

return parse(EvalElse($thing, in_array($country_code, $eu)));

}
# --- END PLUGIN CODE ---
if (0) {
?>
<!--
# --- BEGIN PLUGIN HELP ---
<h1>spf_if_eu</h1>

<p>Serve content to EU visitors only (e.g. a cookie-prompt: see notes below) or non-EU visitors (via &lt;txp:else /&gt;) tag.</p>
<p>Latest version: <a href="https://github.com/spiffin/spf_if_eu">spf_if_eu GitHub repository</a>.</p>

<br /><hr /><br />

<h2>Installation:</h2>

<ol>
<li>Upload the 'geoip' directory to your web root.</li>
<li>Install and activate this plugin - spf_if_eu.txt.</li>
</ol>

<br /><hr /><br />

<h2>Usage:</h2>

<ol>
<li>The plugin is intended to be used on page templates (e.g. to serve a cookie prompt such as <a href="https://github.com/michaelw90/cPrompt">cPrompt</a> or my <a href="https://github.com/spiffin/cPrompt">forked version</a> - but can also be used in articles and forms.</li>
<li>Content within <code>&lt;txp:spf_if_eu&gt; ... &lt;/txp:spf_if_eu&gt;</code> tags will only be served to visitors from the EU.</li>
<li>The &lt;txp:else /&gt; tag can also be used to server content only to non-EU visitors.</li>
</ol>

<br /><hr /><br />

<h2>Notes:</h2>

<ol>
<li>Country detection is via <a href="http://www.maxmind.com">MaxMind's</a> GeoIP database - it (very occasionally) makes errors.</li>
<li>Not tested on Texpattern < 4.4.1 and PHP < 5.</li>
</ol>

<br /><hr /><br />

<h2>Version history</h2>

<p>0.2 - 26 August 2012</p>
<ul>
<li>Optimised (thanks Jukka).</li>
</ul>

<p>0.1 - June 2012</p>
<ul>
<li>First release.</li>
</ul>
# --- END PLUGIN HELP ---
-->
<?php
}
?>