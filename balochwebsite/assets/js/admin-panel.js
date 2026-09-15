/* ═══════════════════════════════════════════════════════
   CBCSC Theme — Admin panel JS
   Hooks up color pickers and the sortable homepage builder.
   ═══════════════════════════════════════════════════════ */
(function ($) {
    $(function () {

        // WP color picker on every .bh-color-pick input
        if ($.fn.wpColorPicker) {
            $('.bh-color-pick').wpColorPicker();
        }

        // Sortable homepage sections
        if ($.fn.sortable) {
            $('#bh-section-list').sortable({
                handle: '.bh-sec-grip',
                placeholder: 'bh-sec ui-sortable-placeholder',
                opacity: 0.85,
                tolerance: 'pointer',
            });
        }

        // Auto-dismiss toast
        setTimeout(function () {
            $('.bh-toast').fadeOut(300, function () { $(this).remove(); });
        }, 3500);

        // Confirm before reset / destructive
        $('.bh-btn-danger').on('click', function (e) {
            if (this.dataset.confirmed) return;
            // confirm() inline handler already; this is a fallback
        });

        // Live preview: hex input → CSS variable swatch on appearance tab
        $('.bh-palette-row input.bh-color-pick').on('change', function () {
            var v = $(this).val();
            $(this).closest('.bh-palette-row').find('.wp-color-result').css('background-color', v);
        });
    });
})(jQuery);
