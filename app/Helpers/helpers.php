<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Write code on Method
 *
 * @return response()
 */


function createlog($id, $page, $message)
{


    DB::table("active_log")
        ->insert([
            'user_id' => $id,
            'page' => $page,
            'message' => $message
        ]);
    return;
}
