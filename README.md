# Overview
This is a plugin for Botble CMS so you have to purchase Botble CMS first to use this plugin.

The series plugins, follow plugin `Organize series`.
https://wordpress.org/plugins/organize-series/

![alt text](https://github.com/tvad911/botble-series/blob/master/documents/images/01-a.jpeg?raw=true)
![alt text](https://github.com/tvad911/botble-series/blob/master/documents/images/01-b.jpeg?raw=true)
# Installation

- Rename folder `botble-series-master` to `series`.
- Copy folder `series` into `/platform/plugins`.
- Go to Admin Panel -> Plugins and activate plugin Series.



# Config
- Go to Admin Panel -> Theme options:
+ Series, chose order by and sort by.
![alt text](https://github.com/tvad911/botble-series/blob/master/documents/images/02.jpeg?raw=true)

- Post:
Chose series of post and order position
![alt text](https://github.com/tvad911/botble-series/blob/master/documents/images/03.jpeg?raw=true)
Please input 'order' to make the list of series, get right result

- Wiget
+ Admin widget
+ Front end widget
Copy folder `/documents/widgets/frontends/series` to `themes\[theme_name]\widget`, edit your style follow your themes.
Go to Wiget to use and setting for series.
![alt text](https://github.com/tvad911/botble-series/blob/master/documents/images/04.jpeg?raw=true)

- Menu
![alt text](https://github.com/tvad911/botble-series/blob/master/documents/images/05.jpeg?raw=true)

# Series Page
To custom series page: 
- Copy from plugins `series\resources\views\themes\series.php` to `themes\[theme_name]\views` to edit style.

![alt text](https://github.com/tvad911/botble-series/blob/master/documents/images/06.jpeg?raw=true)
# Shortcode
To show series meta, you should use shortcode: [series-meta postId="12"]
In `views\category.blade.php`, `views\tags.blade.php`

{!! do_shortcode(generate_shortcode('series-meta', ['postId' => $post->id])) !!} before $post->description

![alt text](https://github.com/tvad911/botble-series/blob/master/documents/images/07.jpeg?raw=true)

# Contact us
- Email: facebook.com/anhduongphuong

