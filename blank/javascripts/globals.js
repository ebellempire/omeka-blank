// ITEM TYPE FILTER (on change select)
const item_type_filter = (e) => {
  var current_url = window.location;
  var new_url = null;
  var select = document.querySelector("#item-type-selection select");
  var url = null;
  if (select.value) {
    let params = new URLSearchParams(window.location.search);
    let new_type_id = select.value;
    let current_type_id = params.get("type");
    if (current_type_id) {
      // if type param is already set, replace type param
      new_url = current_url.href.replace(
        "type=" + current_type_id,
        "type=" + new_type_id
      );
    } else {
      // otherwise, add new type param
      if (current_url.href.includes("?")) {
        // add to existing params
        new_url = current_url.href + "&type=" + new_type_id;
      } else {
        // set the sole param
        new_url = current_url.href + "?type=" + new_type_id;
      }
    }
    window.location.assign(new_url);
  }
};
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
    }
  };
  // HEADER SEARCH FUNCTIONALITY
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
  const title = nav.getAttribute("data-title");
  const sliding_option = nav.getAttribute("data-sliding-submenus");
  const slidingSubmenus = sliding_option > 0 ? true : false;
  const menu = new MmenuLight(nav);
  const navigator = menu.navigation({
    theme: theme,
    slidingSubmenus: slidingSubmenus,
    title: title,
  });
  const drawer = menu.offcanvas({ position: "right" });
  document.querySelector("a#menu-button").addEventListener("click", (evnt) => {
    evnt.preventDefault();
    drawer.open();
  });
  // METADATA TOGGLE (items & collections)
  var toggle = document.querySelector(
    "#full-metadata-record.interactive",
    ":before"
  );
  if (typeof toggle != "undefined" && toggle) {
    toggle.addEventListener("click", (e) => {
      if (e.target.children[0]) {
        document
          .querySelector("#full-metadata-record.interactive")
          .classList.toggle("up");
        e.target.children[0].classList.toggle("open");
      }
    });
  }
});
