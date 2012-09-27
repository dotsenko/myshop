(function (b) {
   b.fn.placeholder = function (e, h) {
       e = e || "placeholder";
       h = h || e;
       var g = function () {
               b(this).removeClass(h);
               if (this.value == this.getAttribute(e)) {
                   this.value = ""
               }
           };
       this.focus(g);
       this.parents("form").submit(function () {
           b(this).find("input").each(g)
       });
       var d = function () {
               p = this.getAttribute(e);
               if (this.value == p || this.value.match(/^\s*$/)) {
                   b(this).addClass(h);
                   this.value = p
               }
           };
       this.blur(d);
       this.each(d);
       return this
   };

   b("li.link").click(function () {
       window.location = b(this).find("a[href]").attr("href")
   });
   if (b("form").length) {
       if (!b(".show-hints").length) {
           b(".field .hint").hide();
           b(".field :checkbox, .field :radio, .inline-field label").hover(function () {
               b(this).parents(".field").children(".hint").fadeIn("fast")
           }, function () {
               b(this).parents(".field").children(".hint").fadeOut("fast")
           });
           b(".field :input:not(:checkbox)").focus(function () {
               b(this).parents(".field").children(".hint").fadeIn("fast")
           }).blur(function () {
               b(this).parents(".field").children(".hint").fadeOut("fast")
           })
       }
       b("input[placeholder], textarea[placeholder]").placeholder();
       b("input[autofocus='autofocus']").eq(0).focus()
   }
   b("input[type='radio']:checked").parent("label").addClass("checked");
   b("input[type='radio']").closest("label").click(function () {
       b(this).addClass("checked").siblings().removeClass("checked");
       b(this).parent(".radio-field").siblings().children("label").removeClass("checked")
   }).hover(function () {
       b(this).addClass("hover").siblings().removeClass("hover")
   }, function () {
       b(this).removeClass("hover")
   });

   b.fn.labelFader = function () {
       var c = function () {
               var e = b(this);
               var d = e.siblings("label").children("span");
               if (e.val()) {
                   d.hide()
               }
               else {
                   d.fadeIn("fast")
               }
           };
       this.focus(function () {
           c.call(this);
           b(this).siblings("label").addClass("focus")
       });
       this.blur(function () {
           c.call(this);
           b(this).siblings("label").removeClass("focus")
       });
       this.keyup(c);
       this.keydown(c);
       this.change(c);
       this.each(c);
       return this
   }
})(jQuery);

$(function () {
   $(".fade-label input").labelFader();
});

