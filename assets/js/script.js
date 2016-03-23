+function ($) { "use strict";

    /**
     * Constructor
     *
     * @param  {Object} $el
     * @return {void}
     */
    var UrlColumn = function ($el) {
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
        this.copyToClipboard(e);
        this.flashSuccessMessage();
    };

    /**
     * Copy the webhook to the user's clipboard
     *
     * @param  {Object} e
     * @return {void}
     */
    UrlColumn.prototype.copyToClipboard = function (e) {
        var url = $(e.currentTarget).closest('tr').find('a[data-url]').data('url');
        var $input = $("<input>");
        $("body").append($input);
        $input.val(url).select();
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
