
<?php
// team
    Route::get('team', 'TeamController@index');
    Route::get('get_members/{id}', 'TeamController@get_members');
    Route::post('team/create', 'TeamController@store');
    Route::get('team/edit/{id}', 'TeamController@edit');
    Route::post('team/update', 'TeamController@update');
    Route::get('team/delete/{id}', 'TeamController@destroy');