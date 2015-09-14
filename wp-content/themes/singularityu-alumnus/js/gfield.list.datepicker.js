(function($) {
    $(document).bind('gform_post_render', function () {
        var i = 0;
        $.each(
            $(".datePicker tbody input"), function () {
                i++;
                $(this).datepicker();
            }
        );
        $('.datePicker .gfield_list').on("click", ".add_list_item", function () {
            i++;
            var button_parent = $(this).parent();
            var old_row = $(button_parent).parent();
            var new_row = $(old_row).next();
            var new_input = $(new_row).find("input");
            new_input.attr("id", new_input.attr("id") + i);
            $(new_input).on("focus", function () {
                var $this = $(this);
                if (!$this.data('datepicker')) {
                    $this.removeClass("hasDatepicker");
                    $this.datepicker();
                    $this.datepicker("show");
                }
            });
        });
    });
})(jQuery);