Automated system alerts on Primo
=========

When we migrated to Alma/Primo, our senior management challenged us on how we would deal with system outages in the cloud environment. Initially we planned to manually create an alert that could be displayed on Primo using javascript. We later extended this to automatically use system statuses screenscraped from the Ex Libris status page. This meant a note would display even in the middle of the night, reassuring users and providing links to alternative databases to search in.

More recently we've updated the code to use the new status API, and to migrate it to Primo NUI. A manual option remains available for advertising other outages (eg a recent issue with a large database vendor), and notes can be displayed on other webpages.

**NOTE:** We haven't used this for a few years, and particularly haven't tested it above PHP 5.6, or with Primo VE - it is highly likely to break in these environments.

To install
----------
1. Copy all the files to your PHP-enabled webserver. (Tested with PHP v5.6.)
2. Configure your settings in `config.php` and `warningnote.txt`
3. Go to `https://yourdomain.edu/path/to/warningnote/test.php` to check everything's connected as you expect - if not, tweak settings as the page suggests


To display notes on Primo NUI
----------
1. Install as above
2. Edit the code in `PrimoNUI/custom.js` with the correct path for `https://yourdomain.edu/path/to/warningnote/warningnote.js.php` and add this code to `custom.js` in your Primo NUI package.
  * If you're not using any custom.js at the moment, just copy across the whole file.
  * If you are, but nothing that affects prmMainMenuAfter, copy from `/* Begin prmMainMenuAfter */` to `/* End prmMainMenuAfter */`.
  * If you already have code for prmMainMenuAfter, copy from `/* Add in Warning Note script */` to `/* End Warning Note script */`.
3. By default this uses the standard alert-bar styling in Primo, but if you want to style it separately you can create a rule in `custom1.css` for `#WarningNote`.
4. Zip and upload your package per standard Ex Libris instructions.


To display notes on a non-Primo site
----------
1. Install as above
2. You could add an empty `<prm-search-bar>` element to the target site where you want the note to display; OR you could make a copy of `warningnote.js.php` with a modified `function warningNote`.
3. Add `<script type="text/javascript" src="warningnote.js.php"></script>` as close to the bottom of your target site's body as possible.


What displays
----------
Any note required is calculated _when the page first loads_.

1. When there are no known problems, nothing displays
2. If you save any text in `warningnote.txt`, that text will display
3. If `warningnote.txt` is empty, and an issue is showing on the Ex Libris status page, an automated note about that issue will display


Issues, feedback
----------
I'd love to hear from you if you're using this system, especially if it works like a dream and you and your users love it!

If you run into problems or have suggestions for improvements, email me at deborah.fitchett@lincoln.ac.nz - I can't make guarantees but will do what I can.
