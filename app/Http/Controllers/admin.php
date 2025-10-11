<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class admin extends Controller
{
    public function index($request)
    {
        return view('admin.analytics');
    }
        public function applications()
    {
        $data = [
            ['id' => 1, 'name' => 'John Doe',    'email' => 'john@example.com',  'status' => 'Review',     'date' => '2025-09-10', 'title' => 'Research on AI',           'ReviewType' => 'Full Review'],
            ['id' => 2, 'name' => 'Jane Roe',    'email' => 'jane@example.com',  'status' => 'Revision',   'date' => '2025-09-12', 'title' => 'Nursing Study',            'ReviewType' => 'Exempt',     'RevisionStage' => 'Waiting for Revision'],
            ['id' => 3, 'name' => 'Sam Smith',   'email' => 'sam@example.com',   'status' => 'Complete',   'date' => '2025-08-30', 'title' => 'Grassland Ecology',        'ReviewType' => 'Expedited'],
            ['id' => 4, 'name' => 'Lisa Wong',   'email' => 'lisa@example.com',  'status' => 'Finalization','date' => '2025-09-01', 'title' => 'Behavioral Study',         'ReviewType' => 'Full Review'],
            ['id' => 5, 'name' => 'Tom Brown',   'email' => 'tom@example.com',   'status' => 'Revision',   'date' => '2025-09-15', 'title' => 'Clinical Trial',           'ReviewType' => 'Full Review','RevisionStage' => 'Panel Deliberation'],
            // ['id' => 6, 'name' => 'Anna Green',  'email' => 'anna@example.com',  'status' => 'Review',     'date' => '2025-09-18', 'title' => 'AI Ethics',                'ReviewType' => 'Expedited'],
            // ['id' => 7, 'name' => 'Mark Blue',   'email' => 'mark@example.com',  'status' => 'Revision',   'date' => '2025-09-20', 'title' => 'Environmental Impact',     'ReviewType' => 'Exempt',     'RevisionStage' => 'Submission of Revsion'],
            // ['id' => 8, 'name' => 'Rita Black',  'email' => 'rita@example.com',  'status' => 'Complete',   'date' => '2025-09-05', 'title' => 'Public Health Survey',     'ReviewType' => 'Full Review'],
            // ['id' => 9, 'name' => 'Carlos Diaz', 'email' => 'carlos@example.com','status' => 'Finalization','date' => '2025-09-07', 'title' => 'Soil Microbiology',        'ReviewType' => 'Expedited'],
            // ['id' => 10,'name' => 'Maya Patel',  'email' => 'maya@example.com',  'status' => 'Revision',   'date' => '2025-09-22', 'title' => 'Nutrition Study',          'ReviewType' => 'Full Review','RevisionStage' => 'Checking of Revision'],
        ];
            
        return view('admin.applications')->with('datas', $data);
    }

    public function newSubmissions()
    {
        $submissions = [
            ['id' => 1, 'title' => 'Research on AI', 'status' => 'Pending'],
            ['id' => 1, 'title' => 'Research on nursing', 'status' => 'Pending'],
            ['id' => 1, 'title' => 'Research on grass', 'status' => 'Incomplete'],
            
        ];

        return view('admin.NewSubmissions')->with('submissions', $submissions);
    }
}
