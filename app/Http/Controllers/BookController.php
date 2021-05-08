<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

use App\Models\Book;

class BookController extends Controller
{
    public function list()
    {
        $bookData = Book::all();
        return View::make(
            'book-list', 
            [
                'bookData' => $bookData, 
                'mainClass' => 'darker-bg all-space'
            ]
        );
    }
}
