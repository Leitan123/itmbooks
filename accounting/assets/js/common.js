var App = {
    event: null,
    init: function () {
        this.event = (navigator.userAgent.match(/iP/i)) ? "touchstart" : "click";
        this.logout();
        this.deleteAlert();
        this.removeBtn();
        this.cancelAlert();
    },

    logout: function () {

        $('.logout').on(this.event, function () {
            $.ajax({
                url: BaseUrl + "admin/home/logout",
                success: function (json) {
                    location.href = BaseUrl + "admin";
                },
                error: function () {
                    alert("Invalid Request");
                }
            });
        })
    },
    removeBtn: function () {
        $("body").on("click", ".btn-remove", function (e) {
            e.preventDefault();
            $(this).closest($(this).data('remove')).remove();
        })
    },
    deleteAlert: function () {
        $(".delete-btn").click(function (e) {
            e.preventDefault();
            var self = $(this);
            console.log(self.data('title'));
            swal({
                title: self.data('title') || "Are you sure?",
                text: self.data('text') || "Do Your Want To Delete This Records ???",
                type: self.data('type') || "warning",
                showCancelButton: true,
                cancelButtonClass: 'btn-secondary waves-effect',
                confirmButtonClass: self.data('confirmButtonClass') || 'btn-warning',
                confirmButtonText: self.data('confirmButtonText') || "Yes, delete it!",
                closeOnConfirm: false
            }, function () {
                location.href = self.attr('href');
            });
        });
    },
    cancelAlert: function () {
        $(".cancel-btn").click(function (e) {
            e.preventDefault();
            var self = $(this);
            console.log(self.data('title'));
            swal({
                title: self.data('title') || "Are you sure?",
                text: self.data('text') || "Do Your Want To Reverse This Order ???",
                type: self.data('type') || "warning",
                showCancelButton: true,
                cancelButtonClass: 'btn-secondary waves-effect',
                confirmButtonClass: self.data('confirmButtonClass') || 'btn-warning',
                confirmButtonText: self.data('confirmButtonText') || "Yes, reverse it!",
                closeOnConfirm: false
            }, function () {
                location.href = self.attr('href');
            });
        });
    },
    SerialNoChecker: function () {
        $("body").off('blur', '.serial_no_checker').on("blur", ".serial_no_checker", function (e) {
            console.log("Serial No Checker");
            e.preventDefault();
            var $this = $(this);
            $.ajax({
                url: Api + "item/serial_no",
                data: {SerialNo: $this.val()},
                success: function (json) {
                    if (json.hasError) {
                        console.log("Serial No validation error");
                        $this.addClass('form-control-danger').val("").closest('td').addClass('has-danger');
                    } else {
                        $this.removeClass('form-control-danger').closest('td').removeClass('has-danger');
                    }
                }
            });
        })
    }

};

$(function () {
    App.init();
})

var AjaxLoader = AjaxLoader || (function ($) {
        'use strict';
        var $dialog = $(
            '<div class="modal fade" data-backdrop="static" id="AjaxLoader" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
            '<div class="modal-dialog modal-sm">' +
            '<div class="modal-content loader-center">' +
            '<div class="modal-body">' +
            '<i class="fa fa-spinner fa-spin fa-5x" ><i>' +
            '</div>' +
            '</div></div></div>');
        return {
            show: function () {
                if ($('body').find('#AjaxLoader').length) {
                    $('body').find('#AjaxLoader').modal('show');
                } else
                    $dialog.modal();

            },
            hide: function () {
                $dialog.modal('hide');
                //$('body').find('#AjaxLoader,.modal-backdrop').remove();
            }
        };

    })(jQuery);

var ajaxModel = ajaxModel || (function ($) {
        'use strict';

        // Creating modal dialog's DOM
        var $dialog = $(
            '<div class="modal fade" id="ajaxModel" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
            '<div class="modal-dialog modal-m">' +
            '<div class="modal-content">' +
            '<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
            '<div class="modal-body">' +
            '</div>' +
            '</div></div></div>');


        return {
            /**
             * Opens our dialog
             * @param message Custom message
             * @param options Custom options:
             *                  options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
             *                  options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
             */
            show: function (title, message, options) {
                // Assigning defaults
                if (typeof options === 'undefined') {
                    options = {};
                }
                if (typeof title === 'undefined') {
                    title = '';
                }
                if (typeof message === 'undefined') {
                    message = '';
                }
                var settings = $.extend({
                    dialogSize: 'm',
                    onHide: null // This callback runs after the dialog was hidden
                }, options);

                // Configuring dialog
                $dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
                $dialog.find('h3').text(title);
                $dialog.find('.modal-body').html(message);
                // Adding callbacks
                if (typeof settings.onHide === 'function') {
                    $dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
                        settings.onHide.call($dialog);
                    });
                }
                // Opening dialog
                $dialog.modal();
            },
            /**
             * Closes dialog
             */
            hide: function () {
                $dialog.modal('hide');
                $('body').find('#ajaxModel,.modal-backdrop').remove();
            }
        };

    })(jQuery);

$(function () {
    $('body').on('click', '.close_model', function (e) {
        e.preventDefault();
        ajaxModel.hide();
    })
})

