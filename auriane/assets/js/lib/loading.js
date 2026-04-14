jQuery(document).ready(function () {
  jQuery(".preloader-cancel-btn").addClass("fade-in");
  jQuery(".preloader-cancel-btn").on("click", function () {
    jQuery("#preloader").addClass("fade-in");
  });
  jQuery(window).on("load", function () {
    if (!jQuery("#preloader").hasClass("fade-in")) {
      jQuery("#preloader").addClass("fade-in");
    }
    if (jQuery(".preloader-cancel-btn").hasClass("fade-in")) {
      jQuery(".preloader-cancel-btn").removeClass("fade-in");
    }
  });
});
