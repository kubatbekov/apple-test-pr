(function() {

    window.Apple = {};

    Apple.showModal = function(appleId) {
        $('#appleModal').modal('show');
        $('#appleId').val(appleId);

        return false;
    };
})();