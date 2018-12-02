<?php
include 'header.php';
?>

<div><a href="index">Back</a></div>

<?php if(isset($_POST['submit'])) : ?>
<div class="info-msg-error"><?php RegistrationController::registerUser($_POST); ?></div>
<?php endif; ?>
<div class='container'>
    <div class="info-msg-error"></div>
    <div class='title'>Please enter your data</div>
    <form method='post' class='form-login' action="" autocomplete="off">
                <div class='row justify-content-center'>
                    <div class='col-md-10'>
                <label for='name' class='col-md-10'>Name:</label>
                <div class='col'>
                    <input id='name' type='text' name='name' class="input-field" value="<?php echo isset($_POST['name']) ? $_POST['name'] : ''; ?>">
                </div>
            </div>
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
                <label for='confirm-password' class='col-md-10'>Confirm password:</label>
                <div class='col'>
                    <input id='confirm-password' type='password' name='confirm-password' class="input-field">
                </div>
            </div>
            <div class='col-md-10'>
                <button type='submit' class='btn btn-success' id="btn-registration" name='submit'>Sign up</button>
            </div>
        </div>
    </form>
</div>

