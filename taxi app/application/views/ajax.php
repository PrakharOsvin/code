<script src="<?php echo base_url(); ?>/public/js/jquery-1.8.3.min.js"></script>

<style>
    ul {
        list-style-type: none;
    }
</style>
<div align="center" style="padding-top: 1%" id="div_main">
    <div class="jumbotron">
        <h1>Cashbook Items</h1>

        <p>Add items to to cashbook so they used <br>when adding incomings and out goings later</p>

        <form id="items_form" data-abide>
            <div class="large-3 large-centered columns">
                <label>New Item Name
                    <input type="text" required name="item" id="item_name">
                </label>
                <small class="error">No item added</small>
            </div>

            <button class="button radius" id="btnItem">Submit</button>
        </form>
        <a href="#" data-reveal-id="firstModal" class="radius button" id="btnList">Item List</a>
    </div>

</div>

<div class="row">
    <div id="firstModal" class="reveal-modal" data-reveal align="center">
        <h3>Current Items</h3>

        <p>Reccuring Items in the database</p>
        <ul id="list_item">
        </ul>
    </div>
</div>
</div>
<br>

<script>

    $('#btnList').click(function(e){
    $.ajax({
        url:"<?php echo base_url();?>Dashboard/get_job",
        dataType:'text',
        type:"POST",
        success: function(result){
            var obj = $.parseJSON(result);
            $.each(obj,function(index,object){
                $('ul').append('<li>' +object['driver']+ '</li>');
            })
        }
    })
    $('#div_list').toggle(900)
})

    $('#items_form').submit(function (e) {
        e.preventDefault();
        var data = $('#item_name').val();
        alert(data);
        $.ajax({
            url: "<?php echo base_url();?>/cashbook/new_item",
            type: 'POST',
            data: "item=" + data,
            success: function () {
                alert('THIS WORKED');

            },
            error: function () {
                alert('Nah died');
            }

        });
    })
</script>