require(
    [
        'jquery',
        'Magento_Ui/js/modal/modal'
    ],
    function(
        $,
        modal
    ) {
        var modals = $('.modal');
        $.each(modals, function(index, item) {
            let idModal = $(this).attr('id')
            let modalSelect = $("#"+idModal);
            let modalType = modalSelect.data('modalType');
            let options = {
                type: 'popup',
                responsive: true,
                innerScroll: false,
                modalClass: modalType,
                title: false,
                buttons: false
            };
            let popup = modal(options, $("#"+idModal));
        });
        $(document).on("click", ".open-modal", function(){
            let idModal = $(this).data('modal');
            $("#"+idModal).removeClass('u-hidden').modal("openModal");
        });

        $(document).on("click", ".close-modal", function(){
            let idModal = $(this).data('modal');
            $("#"+idModal).addClass('u-hidden').modal("closeModal");
        });

        $(document).on("click", ".open-close-modal", function(){
            let idModalClose = $(this).data('modal-close');
            $("#"+idModalClose).addClass('u-hidden').modal("closeModal");
            let idModalOpen = $(this).data('modal-open');
            $("#"+idModalOpen).removeClass('u-hidden').modal("openModal");
        });
    }
);