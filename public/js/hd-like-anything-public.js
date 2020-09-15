(function ($) {
  "use strict";

  $(function () {
    //console.log(js_vars);

    $(".hdla_container").click(function (e) {
      //console.log("click");
      var el = $(this);
      sendLikesToAjax(el);
        el.addClass("working");
    });

    function sendLikesToAjax($el) {
      //console.log($el);
      var post_id = $el.data("post_id");

      //console.log(post_id);
      var action = "like";
        if($el.hasClass("liked")) {
            action = "unlike";
        }
      var data = {
        action: "set_post_likes",
        hdla_likes: action,
        hdla_post_id: post_id,
      };
      $.post(
        js_vars.ajax_url, // The AJAX URL
        data, // Send our PHP function
        function (response, data) {
          console.log(response);
          $el.find("b").html(response);
          if(action == "like") {
            $el.removeClass("working notliked").addClass("liked");
          } else {
            $el.removeClass("working liked").addClass("notliked");
          }
        }
      );
    }
  });
})(jQuery);
