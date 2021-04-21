document.addEventListener("DOMContentLoaded", (event) => {
  // ESCAPE KEY FUNCTIONALITY
  document.onkeydown = function (e) {
    e = e || window.event;
    var isEscape = false;
    if ("key" in e) {
      isEscape = e.key === "Escape" || e.key === "Esc";
    } else {
      isEscape = e.keyCode === 27;
    }
    if (isEscape) {
      // close the side menu
      if (typeof drawer !== "undefined") {
        drawer.close();
      }
      // close the search ui
      var search = document.querySelector(".search-container");
      var search_input = document.querySelector(
        ".search-container input#query"
      );
      if (typeof search !== "undefined" && search.classList.contains("open")) {
        search_input.blur();
        search.classList.toggle("open");
      }
      // close the lightbox @todo
    }
  };
  // SEARCH FUNCTIONALITY
  document.querySelector("#search-button").addEventListener(
    "click",
    (e) => {
      e.preventDefault();
      document.querySelector(".search-container").classList.toggle("open");
      document.querySelector(".search-container input#query").focus();
    },
    false
  );
  // SIDE MENU FUNCTIONALITY
  const nav = document.querySelector("#mmenu-contents");
  const theme = nav.getAttribute("data-theme");
  const menu = new MmenuLight(nav);
  const navigator = menu.navigation({ theme: theme });
  const drawer = menu.offcanvas({ position: "right" });
  document.querySelector("a#menu-button").addEventListener("click", (evnt) => {
    evnt.preventDefault();
    drawer.open();
  });
});
