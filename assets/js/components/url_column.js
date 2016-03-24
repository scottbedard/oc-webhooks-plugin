+function ($) { "use strict";
    class UrlColumn {

        /**
         * Constructor
         *
         * @param  {Object} $el
         * @return {void}
         */
        constructor ($el) {
            this.lang = $el.closest('.bedard-webhooks.list').data('lang');
            $el.on('click', this.onClicked.bind(this));
        }

        /**
         * Url column click handler
         *
         * @param  {Object} e
         * @return {void}
         */
        onClicked (e) {
            e.preventDefault();
            e.stopPropagation();

            let url = $(e.currentTarget).closest('tr').find('a[data-url]').data('url');
            this.copyToClipboard(url);
            this.flashSuccessMessage();
        }

        /**
         * Copy the webhook URL to the user's clipboard
         *
         * @param  {String} url
         * @return {void}
         */
        copyToClipboard (url) {
            var $input = $("<input>");
            $("body").append($input);
            $input.val(url).select();
            document.execCommand("copy");
            $input.remove();
        }

        /**
         * Tell the user we've copied the webhook
         *
         * @return {void}
         */
        flashSuccessMessage () {
            $.oc.flashMsg({
                'text': this.lang.hooks.copied_to_clipboard,
                'class': 'success',
                'interval': 3
            });
        }
    };

    //
    // Bind to widget container
    //
    $.fn.UrlColumn = function () {
        return new UrlColumn(this);
    };

    $(document).on('render', () => $('td.url-column').UrlColumn());
}(window.jQuery);
