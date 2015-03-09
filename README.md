### Plugin Name 
Contributors: Asitha, alanapost
Tags: classy,api,shortcodes
Requires at least: 3.0.1
Tested up to: 4.1.1
Stable tag: 4.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A tool used to integrate Classy's API into easily accessible shortcodes

## Description

[Classy](https://classy.org) is the world’s largest fundraising platform for social good organizations. Since 2011, they've enabled 1.3 million people across 300K individual campaigns to raise over $130M for social good organizations. From cutting-edge health programs to educational advancement, their customers are tackling the world’s greatest challenges with the power of the Classy platform.

This plugin integrates the Classy API via shortcodes for easy access on Wordpress.  

Currently supported shortcodes/API calls:

* [classy_campaigns]
* [classy_fundraisers]
* [classy_donations]

You are able to use parameters that are available on the API as well in your shortcodes. For example:

[classy_fundraisers order="firstname"]

This will return a list of fundraisers ordered by first name.

This plugin was created using Tom McFarlin's Boilerplate WordPress Plugin template.

[Classy API](http://go.stayclassy.org/hs-fs/hub/190333/file-1586506388-pdf/StayClassy_API_v1.1_FINAL_%281%29.pdf)

## Installation

Upload the Classy plugin to your blog, activate it, then enter your API Token and CID in the Classy menu.

To get your API Token and CID, you will need to contact [Classy support](https://fundraise.tofightcancer.com/help-center). 

## Changelog

= 1.0 =
* Initial plugin which includes campaigns, fundraisers and donations.