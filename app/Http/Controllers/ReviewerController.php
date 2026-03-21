<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Research_title;
use Illuminate\Support\Facades\Auth;

class ReviewerController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Find titles where the user ID is in the assigned_reviewers JSON array
        // We handle both integer and string storage in JSON arrays just in case
        $titles = Research_title::whereJsonContains('assigned_reviewers', (string)$userId)
                    ->orWhereJsonContains('assigned_reviewers', $userId)
                    ->latest()
                    ->get();

        return view('reviewer.dashboard', compact('titles'));
    }
}
