<?php
include 'header.php';
?>

<div><a href="index">Back</a></div>
<div class='container'>
    <div class='title'>Please enter your comment</div><br>
    <form method='post' name="add-message" id="add-message" action="" autocomplete="off">
        <div class='row justify-content-center'>
            <div class='col-md-10'>
                <textarea name='message' class="textarea-message"></textarea>
            </div>
            <div class='col-md-10'>
                <button type="button" class='btn btn-success' id="add-msg">submit</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function(){
        $('#add-msg').click(function(){
            var message = $('.textarea-message').val();
            var id = <?php echo $_SESSION['user_id']; ?>;
            var data = {message: message, id: id};
            ajaxHandler('POST', 'add-comment', data, successAddComment);
        });

        function successAddComment(response) {
            var result = JSON.parse(response);
            var container =  $('.container');
            if(result.status == "ERROR"){
                $('<div class="info-msg-error">' + result.errors[0] + '</div>').insertBefore('.title');
            }
            else {
                container.empty();
                $('info-msg-error').text('');
                container.append('<div class="info-msg">Your comment was successfully added</div>');
                container.append('<a href="">Add another comment</a>');
            }
        }
    });
</script>