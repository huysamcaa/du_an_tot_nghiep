<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Http\Requests\CategoryRequest;
class Controller extends BaseController // Đảm bảo dòng này
{
    use AuthorizesRequests, ValidatesRequests;
}
