document.addEventListener("DOMContentLoaded", (e) => {
  // build items array
  const image_links = document.querySelectorAll(".item-file.image a");
  var items = [];
  var no_dimensions = [];
  image_links.forEach((link, i) => {
    // dimensions
    var src = link.getAttribute("data-fullsize");
    var size = [
      link.getAttribute("data-height"),
      link.getAttribute("data-width"),
    ];
    // caption
    var caption = [];
    if ((title = link.getAttribute("data-title"))) {
      caption.push(title);
    }
    if ((description = link.getAttribute("data-description"))) {
      caption.push(description);
    }
    caption.push([
      '<a href="/files/show/' +
        link.getAttribute("data-id") +
        '">' +
        link.getAttribute("data-view-label") +
        "</a>",
    ]);
    // add to items if dimensions are known
    if (size[0] > 0 && size[1] > 0) {
      items.push({
        src: src,
        w: size[0],
        h: size[1],
        title: caption.join(" &middot; "),
      });
    } else {
      no_dimensions.push(i);
    }
  });
  if (no_dimensions.length > 0) {
    console.warn(
      "Omeka/Photoswipe: Failed to compute dimensions for " +
        no_dimensions.length +
        "/" +
        image_links.length +
        " images."
    );
  }
  // gallery UI (see also: custom.php => ob_photoswipe_markup)
  var pswpElement = document.querySelectorAll(".pswp")[0];
  // gallery options
  var options = {};
  // add click event to each
  image_links.forEach((link, i) => {
    link.addEventListener("click", (e) => {
      // has dimensions
      if (!no_dimensions.includes(i)) {
        e.preventDefault();
        options.index = i;
        var gallery = new PhotoSwipe(
          pswpElement,
          PhotoSwipeUI_Default,
          items,
          options
        );
        gallery.init();
      }
      // if no dimensions (or no JS), fallback = direct link to file record page
    });
  });
});
