$(function (){
    $(document).on('click', '[role="tab"]', function (){
        let container = $(this).closest('[role="tab-container"]');
        let id = $(this).data('id');
        container.find('[role="tabpanel"]').removeClass('active');
        container.find(id).addClass('active');
        container.find('[role="tab"]').removeClass('active');
        $(this).addClass('active');
    })
})
