<?php
Route::set('login', function()
{
  LoginController::renderView('login');
});

Route::set('registration', function()
{
  RegistrationController::renderView('registration');
});

Route::set('index', function()
{
  IndexController::renderView('index');
});

Route::set('logout', function()
{
  IndexController::renderView('logout');
});

Route::set('new-comment', function()
{
  IndexController::renderView('new-comment');
});

Route::set('add-comment', function()
{
  CommentController::addComment($_POST);
});

Route::set('edit-comment', function()
{
  CommentController::editComment($_POST);
});

Route::set('show-comments', function()
{
  CommentController::getAllComments();
});

Route::set('delete-comment', function()
{
  CommentController::deleteComment($_POST['id']);
});

Route::set('add-rating', function()
{
  RatingController::addRating($_POST);
});

Route::set('add-reply', function()
{
  CommentController::addComment($_POST);
});

Route::set('show-replies', function()
{
  CommentController::getReplies($_POST['parent_id']);
});




