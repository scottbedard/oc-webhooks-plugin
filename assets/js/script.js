+function ($) { "use strict";

    var UrlColumn = function ($el) {
        this.url = $el.find('a').data('url');
        this.lang = $el.closest('.bedard-webhooks.list').data('lang');
        $el.on('click', this.onClicked.bind(this));
    };

    /**
     * Url column click handler
     *
     * @param  {Object} e
     * @return {void}
     */
    UrlColumn.prototype.onClicked = function (e) {
        e.preventDefault();
        e.stopPropagation();
        this.copyToClipboard();
        this.flashSuccessMessage();
    };

    /**
     * Copy the webhook to the user's clipboard
     *
     * @return {void}
     */
    UrlColumn.prototype.copyToClipboard = function () {
        var $input = $("<input>");
        $("body").append($input);
        $input.val(this.url).select();
        document.execCommand("copy");
        $input.remove();
    };

    /**
     * Tell the user we've copied the webhook
     *
     * @return {void}
     */
    UrlColumn.prototype.flashSuccessMessage = function () {
        $.oc.flashMsg({
            'text': this.lang.hooks.copied_to_clipboard,
            'class': 'success',
            'interval': 3
        });
    };

    //
    // Bind to widget container
    //
    $.fn.UrlColumn = function () {
        return new UrlColumn(this);
    }

    $(document).on('render', function() {
        $('td.url-column').UrlColumn();
    });
}(window.jQuery);
