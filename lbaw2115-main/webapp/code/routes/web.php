<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Pages
Route::get('/', 'HomeController@show')->name('home');
Route::get('about', 'AboutController@show');
Route::get('faq', 'FAQController@show');
Route::get('contacts', 'ContactsController@show');
Route::get('browse', 'BrowseController@show')->name('browse.search');

// User Profile
Route::get('user/{user_id}', 'UserController@show')->name('user.show');
Route::get('/user/{user_id}/delete', 'UserController@delete')->name('user.delete');

//User Edit
Route::get('user/{user_id}/edit', 'EditUserController@index')->name('edit.show');
Route::post('user/{user_id}/edit', 'EditUserController@update')->name('user.update');

//User Create Event
Route::get('user/{user_id}/createevent', 'EventController@showCreateForm')->name('create.show');
Route::post('user/{user_id}/createevent', 'EventController@create')->name('event.create');


// User Events
Route::get('user/{user_id}/my_events', 'MyEventsController@index');

// User Participations
Route::get('user/{user_id}/my_participations',/*view my participations controller*/);

// Event
Route::get('event/{event_id}', 'EventController@show')->name('event.show');
Route::get('event/{event_id}/delete', 'EventController@delete')->name('event.delete');

// Event Edit
Route::get('event/{event_id}/edit', 'EditEventController@show')->name('editevent.show');
Route::post('event/{event_id}/edit', 'EditEventController@update')->name('event.update');

// Event Cancel
Route::get('event/{event_id}/cancel', 'EventController@cancel')->name('event.cancel');

//Join Event
Route::get('event/{event_id}/join', 'EventController@join')->name('event.join');

// Leave Event
Route::get('event/{user_id}/leave', 'EventController@leave')->name('event.leave');

//Hosts add participant to event 
Route::get('event/{event_id}/addparticipants', 'EventController@showAdd')->name('event.showaddparticipants');
Route::get('event/{event_id}/addparticipant/{user_id}', 'EventController@add')->name('event.addparticipant');


//Hosts remove participant from event 
Route::get('event/{event_id}/removeparticipants', 'EventController@showRemove')->name('event.removeparticipants');
Route::get('event/{event_id}/removeparticipant/{user_id}', 'EventController@remove')->name('event.remove');

// Report Event
Route::get('event/{event_id}/report', 'ReportEventController@index')->name('event.report');
Route::get('report/{report_id}/delete', 'ReportEventController@delete')->name('report.delete');
Route::post('event/{event_id}/report', 'ReportEventController@report')->name('report');

// Announcement Event
Route::get('event/{event_id}/announcement', 'AnnouncementEventController@index')->name('createannouncement.show');
Route::post('event/{event_id}/announcement', 'AnnouncementEventController@create')->name('createannouncement');
Route::post('event/{event_id}/announcement', 'AnnouncementEventController@announcement')->name('event.announcement');
Route::get('event/{event_id}/announcement/{announcement_id}/edit', 'AnnouncementEventController@edit')->name('announcement.edit');
Route::post('event/{event_id}/announcement/{announcement_id}/edit', 'AnnouncementEventController@update')->name('announcement.update');
Route::get('event/{event_id}/announcement/{announcement_id}/delete', 'AnnouncementEventController@delete')->name('announcement.delete');

// Comment Event
Route::get('event/{event_id}/comment', 'CommentEventController@index')->name('createcomment.show');
Route::post('event/{event_id}/comment', 'CommentEventController@create')->name('createcomment');
Route::post('event/{event_id}/comment', 'CommentEventController@comment')->name('event.comment');
Route::get('event/{event_id}/comment/{comment_id}/edit', 'CommentEventController@edit')->name('comment.edit');
Route::post('event/{event_id}/comment/{comment_id}/edit', 'CommentEventController@update')->name('comment.update');
Route::get('event/{event_id}/comment/{comment_id}/delete', 'CommentEventController@delete')->name('comment.delete');

// Poll Event
Route::get('event/{event_id}/poll', 'PollEventController@index')->name('pollcreate.show');
Route::post('event/{event_id}/poll', 'PollEventController@create')->name('poll.create');
Route::post('event/{event_id}/poll', 'PollEventController@poll')->name('event.poll');
Route::get('event/{event_id}/poll/{pollOption_id}', 'PollOptionController@vote')->name('poll.vote');

// Event Invite
Route::get('event/{event_id}/invite', 'InviteController@showusers')->name('invite.show');
Route::get('event/{event_id}/invite/{user_id}', 'InviteController@invite')->name('event.invite');
Route::get('user/{user_id}/invite/{invite_id}/accept', 'InviteController@accept')->name('invite.accept');
Route::get('user/{user_id}/invite/{invite_id}/delete', 'InviteController@delete')->name('invite.delete');

// Authentication
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');


