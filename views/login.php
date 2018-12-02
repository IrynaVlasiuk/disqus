<?php
include 'header.php';
?>

<div><a href="index">Back</a></div>

<?php if(isset($_POST['submit'])):?>
    <div class="info-msg-error"><?php LoginController::loginUser($_POST); ?></div>
<?php endif; ?>
<div class='container'>
    <div class='title'>Please enter your data</div><br>
    <form method='post' class='form-login' action="" autocomplete="off">
        <div class='row justify-content-center'>
            <div class='col-md-10'>
                <label for='email' class='col-md-10'>Email:</label>
                <div class='col'>
                    <input id='email' type='email' name='email' class="input-field" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                </div>
            </div>
            <div class='col-md-10'>
                <label for='password' class='col-md-10'>Password:</label>
                <div class='col'>
                    <input id='password' type='password' name='password' class="input-field">
                </div>
            </div>
            <div class='col-md-10'>
                <input type='submit' class='btn btn-success' name='submit' value='Log in'>
            </div>
        </div>
    </form>
</div>
