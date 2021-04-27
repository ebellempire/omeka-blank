# Omeka Blank

A boilerplate starter theme with a bunch of useful stuff, including time-saving helper functions, theme options, common plugin templates, and more.

# Features

- [Photoswipe](https://photoswipe.com/) image viewer
- [Mmenu Light](https://mmenujs.com/mmenu-light/) side menu (plus basic dropdown menus)
- Simple homepage image gallery (~4Kb)
- Header search
- Filter Item views by Item Type
- Social media icon links ([Ionicons](https://ionicons.com/))
- Social media meta tags for rich previews
- Auto-embed from YouTube and Vimeo using URL field
- Item templates that highlight core metadata like textual descriptions and byline info
- Toggle full metadata visibility
- Inclusion of common plugin templates like exhibit builder, simple pages, and geolocation
- Customizable labels for Items
- Easy-to-read/-modify templates and functions for developers
- Customizable Call to Action
- Typekit integration
- Lots more...

# Who is this for?

Primarily, this is for me! But I hope it will be useful for any Omeka Classic developer who is looking to start a theme from scratch but would appreciate a little head start. To use this theme, you need to be adept at CSS and at least somewhat acquainted with JavaScript.

# How does it work?

You just design on top of it. All custom functions are prefixed `ob_` and defined/documented in the theme's `custom.php` file. Note that the `default.css` file contains a handful of styles that are important to JavaScript functionality. Where possible, I'll add notes. In general, it's recommended to just add new styles and overwrite existing styles in the `custom.css` file. Remove or comment out any theme options (in `config.ini`) that you don't need for your project. Don't forget to change the info in `theme.ini`.

# What if I find a bug?

I am happy to review pull requests, especially those that improve performance and accessibility. However, I don't plan to offer much in the way of technical support. If something is broken or could be improved, feel free to [open an issue](https://github.com/ebellempire/omeka-blank/issues). If you need help understanding the code or implementing a change, please first try the Omeka Forums, Stack Overflow, etc. Thanks for understanding!
