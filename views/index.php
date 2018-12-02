<?php
include 'header.php';

if(isset($_SESSION['logged-in'])): ?>
    <form method="get" class="form-logout">
        <div class="div-user-name">Hello, <?php echo $_SESSION['user-name']?></div>
        <button name="user-logout" class="logout">Logout</button>
    </form>
    <button class="comments">Review comments</button>
    <button class="new-comment"><a href="new-comment" class="link-new-comment">Add comment</a></button>
<?php else: ?>
    <a href="registration">Sign up</a>
    <a href="login">Log in</a>
<?php endif; ?>
<?php if(isset($_GET['user-logout'])){ $_SESSION = array(); header('Location: index'); }?>

<div class="info-msg-error"></div>
<div class="container"></div>

<script type="text/javascript">
    $(document).ready(function(){

        function successDeleteMsg(elem) {
            elem.parent().remove();
            alert('Your comment has been successfully deleted');
        }

        function successEdit(params) {
            var data = JSON.parse(params);
            if(data.status == 'OK'){
                var id = data.data[0].id;
                $('.span-hidden').each(function(){
                    if($(this).text() == id){
                        $(this).siblings('.input-hidden').hide();
                        $(this).siblings('.msg').show().text(data.data[0].description);
                        $(this).siblings('.msg-edit').text('Edit');
                        $('.info-msg-error').text('');
                    }
                });
            }
            else {
                $('.info-msg-error').text(data.errors);
            }
        }

        function successAddReply(params) {
            var data =  JSON.parse(params);
             if(data.status == "OK") {
                $('.div-reply').remove();
             }
             else{
                 $('.info-msg-error').text(data.errors);
             }
        }

        function renderElements(elements, parentDiv) {
            var user_id = <?php echo isset($_SESSION['user_id']) ? ( $_SESSION['user_id']) : 0; ?>;
            for(var i = 0; i < elements.length; i++) {
                if(user_id > 0 && elements[i].user_id == user_id) {
                    parentDiv.append('<div class="msg-container"><input class="input-hidden"/><div class="msg">'+ elements[i].description +'</div>'
                    +'<div class="msg-data">data: '+ elements[i].date +'</div><div class="div-rating">rating:<span class="span-rating">'+ elements[i].ratingCount +'</span>'
                    +'</div><button class="msg-edit">edit</button><span class="span-hidden" hidden>'+ elements[i].id +'</span><button class="msg-delete">delete</button>'
                    +'<button class="btn-rating">+1</button><button class="btn-show-replies">show replies</button><button class="btn-reply">reply</button><div class="replies"></div></div>');
                }
                else{
                    parentDiv.append('<div class="msg-container"><div>'+ elements[i].description +'</div><div class="msg-data">data: '+ elements[i].date +'</div>'
                    +'<div class="div-rating">rating:<span class="span-rating">'+elements[i].ratingCount +'</span></div><span class="span-hidden" hidden>'+ elements[i].id +'</span>'
                    +'<button class="btn-rating">+1</button><button class="btn-show-replies">show replies</button><button class="btn-reply">reply</button><div class="replies"></div></div>');
                }
            }
        }

        $('.comments').click(function() {
            var parentDiv = $(this).siblings('.container');
                ajaxHandler('GET', 'show-comments', null, function (params) {
                    $('.container').empty();
                    var elements = JSON.parse(params);
                    renderElements(elements, parentDiv);
                });
        });

        $('.container').delegate('.msg-container button[class="msg-delete"]', 'click', function() {
            var comment_id = $(this).siblings('.span-hidden').text();
            if(confirm("Are you sure you want to delete this comment?")) {
                ajaxHandler('POST', 'delete-comment', {id: comment_id}, successDeleteMsg($(this)));
            }
            else {
                return false;
            }
        });

        $('.container').delegate('.msg-container button[class="msg-edit"]', 'click', function() {
            var input = $(this).siblings('.input-hidden');
            var comment_id = parseInt($(this).siblings('.span-hidden').text());
            var new_comment = input.val();
            if($(this).parent().find( 'input:hidden').length > 0){
                var current_comment = $(this).siblings('.msg').text();
                $(this).siblings('.msg').hide();
                input.show();
                input.val(current_comment);
                $(this).text('save');
            }
            else {
                var data = {comment_id: comment_id , message: new_comment};
                ajaxHandler('POST', 'edit-comment', data, successEdit);
            }
        });

        $('.container').delegate('.msg-container button[class="btn-rating"]', 'click', function() {
            var user_id = <?php echo $_SESSION['user_id']; ?>;
            var comment_id = $(this).siblings('.span-hidden').text();
            var data = {comment_id: comment_id, user_id: user_id};
            var context = $(this);
            ajaxHandler('POST', 'add-rating', data, function (params) {
                    var data = JSON.parse(params);
                    if(data.status == 'OK') {
                        $('.info-msg-error').text('');
                        var new_rating = data.data;
                        context.siblings('.div-rating').children('.span-rating').text(new_rating);
                    }
                    else {
                         $('.info-msg-error').text(data.errors);
                    }
            });
        });

        $('.container').delegate('.msg-container button[class="btn-reply"]', 'click', function() {
            $(this).siblings('.div-reply').remove();
            $('<div class="div-reply"><textarea class="reply"></textarea><button class="btn-send-reply">send</button><div>').insertAfter($(this));
        });

        $('.container').delegate('.msg-container button[class="btn-send-reply"]', 'click', function() {
            var user_id = <?php echo $_SESSION['user_id']; ?>;
            var parent_id = $(this).parent().siblings('.span-hidden').text();
            var message = $(this).siblings('.reply').val();
            var data = {message: message, parent_id: parent_id, id: user_id};
            ajaxHandler('POST', 'add-reply', data, successAddReply);
        });

        $('.container').delegate('.msg-container button[class="btn-show-replies"]', 'click', function() {
            var parent_id = $(this).siblings('.span-hidden').text();
            var data = {parent_id: parent_id};
            var parentDiv = $(this).siblings('.replies');
            var context  = $(this);
            if(context.siblings('.replies').children().length == 0) {
                ajaxHandler('POST', 'show-replies', data, function (params) {
                    if (params.length > 2) {
                        parentDiv.empty();
                        context.text('hide replies');
                        var elements = JSON.parse(params);
                        renderElements(elements, parentDiv);
                    }
                    else {
                        context.text('show replies');
                        context.siblings('.replies').remove();
                    }
                });
            }
            else {
               context.text('show replies');
               context.siblings('.replies').empty();
            }
        });
    });
</script>